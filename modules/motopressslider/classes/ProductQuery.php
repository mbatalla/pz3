<?php
/**
 * 2014-2016 MotoPress Slider
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the GNU General Public License (GPLv2)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.gnu.org/licenses/gpl2.html
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade MotoPress Slider to newer
 * versions in the future.
 *
 * @author    MotoPress <marketing@getmotopress.com>
 * @copyright 2014-2016 MotoPress
 * @license   http://www.gnu.org/licenses/gpl2.html GNU General Public License (GPLv2)
 */

class MPSLProductQuery
{
    /** @var MotoPressSlider */
    private $module = null;
    /** @var MPSLDb */
    private $db = null;
    private $is_preview = false;

    private $categories = array();
    private $tags = array();
    private $in_stock = false;
    private $on_sale_only = false;
    private $exclude_ids = array();
    private $include_ids = array();
    private $count = 10;
    private $offset = 0;
    private $order_by = 'id';
    private $order = 'DESC';
    private $image_types = array();

    public function __construct($is_preview = false, $db = null)
    {
        $this->module = mpsl_get_module();
        if (!is_null($db)) {
            $this->db = $db;
        } else {
            $this->db = mpsl_get_db();
        }
        $this->is_preview = (bool)$is_preview;
    }

    /** @return MPSLDbQuery */
    public function createQuery()
    {
        $query = $this->db->createQuery();
        $id_shop = mpsl_id_shop();
        $id_lang = mpsl_id_lang();

        // Basic part
        $query->select('p.id_product', 'id');
        $query->select('pl.name');
        $query->select('pl.description_short', 'short_description');
        $query->select('p.date_add', 'date_added');
        $query->select('p.date_upd', 'date_updated');
        $query->from('product', 'p');
        $query->leftJoin('product_lang', 'pl', 'p.id_product = pl.id_product');
        $query->whereIs('pl.id_shop', $id_shop);
        $query->whereIs('pl.id_lang', $id_lang);

        // More basic fields if is not a preview query
        if (!$this->is_preview) {
            $query->select('pl.description');
            $query->select('p.price', 'price_without_tax');
            // Taxes to caculate "price_with_tax"
            $query->select('t.rate', 'tax_rate');
            $query->leftJoin('tax_rule', 'tr', 'p.id_tax_rules_group = tr.id_tax_rules_group');
            $query->leftJoin('tax', 't', 'tr.id_tax = t.id_tax');
            // Quantity
            if (!$this->in_stock) {
                $query->leftJoin('stock_available', 'sa', 'p.id_product = sa.id_product');
                $query->whereIs('sa.id_product_attribute', 0);
            }
            $query->select('sa.quantity', 'quantity');
        }

        // Always group by ps_product.id_product. See the code below:
        //     $query->groupBy('p.id_product');

        // Categories
        if (!empty($this->categories)) {
            $query->leftJoin('category_product', 'cp', 'p.id_product = cp.id_product');
            $query->whereIn('cp.id_category', $this->categories);
        }

        // Tags
        if (!empty($this->tags)) {
            $query->leftJoin('product_tag', 'pt', 'p.id_product = pt.id_product');
            $query->whereIn('pt.id_tag', $this->tags);
        }

        // In stock
        if ($this->in_stock) {
            $query->leftJoin('stock_available', 'sa', 'p.id_product = sa.id_product');
            $query->whereIs('sa.id_product_attribute', 0);
            $query->where('sa.quantity > 0');
        }

        // On sale only
        if ($this->on_sale_only) {
            $query->leftJoin('product_sale', 'ps', 'p.id_product = ps.id_product');
            $query->where('ps.quantity > 0');
        }

        // Exclude IDs
        if (!empty($this->exclude_ids)) {
            $query->whereNotIn('p.id_product', $this->exclude_ids);
        }

        // Include IDs
        // WARNING: this part must be the last with the "WHERE" clause.
        // Otherwise you'll get an unpredictable results!
        if (!empty($this->include_ids)) {
            // Hack: "1) OR (..."! Otherwise we need to rewrite method
            // DbQueryCore::build() because it does not support "OR" statement
            $_include_ids = '(' . implode(',', $this->include_ids) . ')';
            $query->where('1=1) OR (p.id_product IN ' . pSQL($_include_ids));
        }

        // No more where() after this part
        $query->groupBy('p.id_product');

        // Count & Offset
        $query->limit($this->count, $this->offset);

        // Order by & Order
        switch ($this->order_by) {
            case 'id':
                $query->orderBy('p.id_product', $this->order);
                break;
            case 'title':
                $query->orderBy('pl.name', $this->order);
                break;
            case 'date':
                $query->orderBy('p.date_add', $this->order);
                break;
            case 'date_modified':
                $query->orderBy('p.date_upd', $this->order);
                break;
        }

        return $query;
    }

    /**
     * Preview only fields: "id", "name", "short_description", "date_added",
     * "date_updated", "date_added_mdy", "product_url", "cover_id" and
     * "cover_url_%image type%" for each image type ("mpsl_fullsize" and
     * "medium_default" by default).
     *
     * Additional non-preview fields: "description", "price_without_tax",
     * "tax_rate", "quantity", "in_stock", "cart_url", "price_with_tax", "tags",
     * "categories", "price_without_tax2" and "price_with_tax2".
     */
    public function query()
    {
        $query = $this->createQuery();
        $results = $this->db->queryResults($query, false);

        $products = array(); // array(%ID% => %data%)
        $ids = array();

        $preview_type = mpsl_get_preview_type(); // Presumably - "medium_default"
        $image_types = $this->image_types;
        $image_types[] = MotoPressSlider::FULLSIZE; // Always add fullsize variant for text macroses
                                                    // "%mpsl-product-image%" and "%mpsl-product-image-url%"
        $image_types[] = $preview_type;
        $image_types = array_unique($image_types);

        $base_url = $this->module->base_url;

        // 1) Prepare some other parameters
        foreach ($results as $result) {
            $id = (int)$result['id'];
            $cover_id = mpsl_get_cover_id($id);
            $covers = mpsl_get_cover_urls($cover_id, $image_types);
            // Remove tags from short description
            $result['short_description'] = strip_tags($result['short_description']);
            // Add additional parameters
            $result['date_added_mdy'] = mpsl_mysql2date($result['date_added'], 'F j, Y'); // "September 6, 2015"
            $result['product_url'] = $base_url . 'index.php?controller=product&id_product=' . $id;
            $result['cover_id'] = $cover_id;
            // Add cover URLs (for each type)
            foreach ($covers as $type => $url) {
                $cover_key = 'cover_url_' . $type;
                $result[$cover_key] = $url;
            }
            $result['cover_preview'] = $covers[$preview_type]; // Duplicate preview URL with a static name
            // Add additional parameters only for non-preview query
            if (!$this->is_preview) {
                $result['description'] = strip_tags($result['description']);
                $result['in_stock'] = ($this->in_stock ? true : ($result['quantity'] > 0));
                $result['cart_url'] = $base_url . 'index.php?controller=cart&id_product=' . $id . '&add=' . $id;
                // Price with tax
                $tax_ratio = 1; // For rate 20% $tax_ratio = 120% or 1.2
                if ($result['tax_rate'] != null) {
                    $tax_ratio += $result['tax_rate']/100;
                } // else "price_with_tax" = "price_without_tax" (with $tax_ratio = 1)
                $result['price_with_tax'] = $result['price_without_tax']*$tax_ratio;
                $result['price_without_tax2'] = number_format($result['price_without_tax'], 2, '.', '');
                $result['price_with_tax2'] = number_format($result['price_with_tax'], 2, '.', '');
            }
            // Add new product
            $ids[] = $id;
            $products[$id] = $result;
        }

        // 2) Add tags and categories
        if (!$this->is_preview) {
            $tags = $this->queryTags($ids);
            $categories = $this->queryCategories($ids);
            $product_ids = array_keys($products);
            foreach ($product_ids as $id) {
                if (array_key_exists($id, $tags)) {
                    $products[$id]['tags'] = implode(', ', $tags[$id]);
                } else {
                    $products[$id]['tags'] = array();
                }
                if (array_key_exists($id, $categories)) {
                    $products[$id]['categories'] = implode(', ', $categories[$id]);
                } else {
                    $products[$id]['categories'] = array();
                }
            }
        }

        return $products;
    }

    private function queryTags($ids)
    {
        $id_lang = mpsl_id_lang();
        $query = $this->db->createQuery();
        $query->select('pt.id_product', 'id')
              ->select('t.name', 'tag')
              ->from('product_tag', 'pt')
              ->innerJoin('tag', 't', 'pt.id_tag = t.id_tag')
              ->whereIn('pt.id_product', $ids)
              ->whereIs('t.id_lang', $id_lang)
              ->orderBy('pt.id_product', 'ASC');
        $tags = $this->db->query1n($query, 'id');
        return $tags;
    }

    private function queryCategories($ids)
    {
        $id_shop = mpsl_id_shop();
        $id_lang = mpsl_id_lang();
        $query = $this->db->createQuery();
        $query->select('cp.id_product', 'id')
              ->select('cl.name', 'category')
              ->from('category_product', 'cp')
              ->innerJoin('category_lang', 'cl', 'cp.id_category = cl.id_category')
              ->whereIs('cl.id_shop', $id_shop)
              ->whereIs('cl.id_lang', $id_lang)
              ->whereIn('cp.id_product', $ids)
              ->orderBy('cp.id_product')
              ->orderBy('cl.name');
        $categories = $this->db->query1n($query, 'id');
        return $categories;
    }

    /** @return MPSLProductQuery */
    public function setCategories($categories)
    {
        $categories = $this->toArray($categories);
        $categories = $this->toNaturalArray($categories);
        $this->categories = $categories;
        return $this;
    }

    /** @return MPSLProductQuery */
    public function setTags($tags)
    {
        $tags = $this->toArray($tags);
        $tags = $this->toNaturalArray($tags);
        $this->tags = $tags;
        return $this;
    }

    /** @return MPSLProductQuery */
    public function searchInStock($in_stock)
    {
        $this->in_stock = (bool)$in_stock;
        return $this;
    }

    /** @return MPSLProductQuery */
    public function searchOnSaleOnly($on_sale_only)
    {
        $this->on_sale_only = (bool)$on_sale_only;
        return $this;
    }

    /** @return MPSLProductQuery */
    public function includeIds($include)
    {
        if (is_string($include)) {
            $include = preg_split('/[,\s]+/', $include);
        }
        $this->include_ids = $this->toNaturalArray($include);
        $this->fixConflicts();
        return $this;
    }

    /** @return MPSLProductQuery */
    public function excludeIds($exclude)
    {
        if (is_string($exclude)) {
            $exclude = preg_split('/[,\s]+/', $exclude);
        }
        $this->exclude_ids = $this->toNaturalArray($exclude);
        $this->fixConflicts();
        return $this;
    }

    /** @return MPSLProductQuery */
    public function setCount($count)
    {
        $this->count = (int)$count;
        return $this;
    }

    /** @return MPSLProductQuery */
    public function setOffset($offset)
    {
        $this->offset = (int)$offset;
        return $this;
    }

    /** @return MPSLProductQuery */
    public function orderBy($order_by, $direction = 'DESC')
    {
        $this->order_by = $order_by;
        $this->order = Tools::strtoupper($direction);
        return $this;
    }

    /** @return MPSLProductQuery */
    public function setImageTypes($image_types)
    {
        if (!empty($image_types)) {
            $this->image_types = array_merge($this->image_types, (array)$image_types);
        }
        return $this;
    }

    /** @return MPSLProductQuery */
    public function setOptions($options)
    {
        foreach ($options as $option => $value) {
            switch ($option) {
                case 'categories':
                    $this->setCategories($value);
                    break;
                case 'tags':
                    $this->setTags($value);
                    break;
                case 'in_stock':
                    $this->searchInStock($value);
                    break;
                case 'on_sale_only':
                    $this->searchOnSaleOnly($value);
                    break;
                case 'include_ids':
                    $this->includeIds($value);
                    break;
                case 'exclude_ids':
                    $this->excludeIds($value);
                    break;
                case 'count':
                    $this->setCount($value);
                    break;
                case 'offset':
                    $this->setOffset($value);
                    break;
                case 'order_by':
                    // Also set order direction ("order" field) with the "order_by"
                    $order = (isset($options['order']) ? $options['order'] : 'DESC');
                    $this->orderBy($value, $order);
                    break;
                case 'order':
                    // If no "order_by" field, then order by ID
                    if (!isset($options['order_by'])) {
                        $this->orderBy('id', $order);
                    }
                    break;
                case 'image_types':
                    $this->setImageTypes($value);
                    break;
            }
        }
        return $this;
    }

    private function toArray($data)
    {
        if (!empty($data)) {
            return (array)$data;
        } else {
            return array();
        }
    }

    private function toNaturalArray($raw)
    {
        $natural = array();
        foreach ($raw as $item) {
            $item = (int)$item;
            if ($item > 0) {
                $natural[] = $item;
            }
        }
        return $natural;
    }

    private function fixConflicts()
    {
        // Fix "Include IDs"-"Exclude IDs" conflict
        $conflict_ids = array_intersect($this->include_ids, $this->exclude_ids);
        $this->include_ids = array_diff($this->include_ids, $conflict_ids);
        $this->exclude_ids = array_diff($this->exclude_ids, $conflict_ids);
    }

    /** @return MPSLProductQuery */
    public static function createProductQuery($options, $is_preview = false, $db = null)
    {
        $product_query = new self($is_preview, $db);
        $product_query->setOptions($options);
        return $product_query;
    }

    /** @return array */
    public static function queryProducts($options, $is_preview = false, $db = null)
    {
        $product_query = self::createProductQuery($options, $is_preview, $db);
        return $product_query->query();
    }
}
