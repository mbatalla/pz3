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

class MPSLDbQuery extends DbQueryCore
{
    /**
     * Adds field to "select" query, but also defines it's alias.
     * @return MPSLDbQuery
     */
    public function select($field, $alias = null)
    {
        if (is_null($alias)) {
            return parent::select($field);
        } else {
            $select = $field . ' AS ' . $alias;
            return parent::select($select);
        }
    }

    /**
     * @return MPSLDbQuery
     */
    public function selectAll()
    {
        return parent::select('*');
    }

    /**
     * @return MPSLDbQuery
     */
    public function selectCount()
    {
        return parent::select('COUNT(*)');
    }

    /**
     * @return MPSLDbQuery
     */
    public function selectMax($field)
    {
        $select = 'MAX(`' . bqSQL($field) . '`)';
        return parent::select($select);
    }

    /**
     * @param int $value
     * @return MPSLDbQuery
     */
    public function whereIs($field, $value)
    {
        $where = bqSQL($field) . ' = ' . (int)$value;
        return $this->where($where);
    }

    /**
     * @return MPSLDbQuery
     */
    public function whereIn($field, $values)
    {
        return $this->whereValues($field, $values, 'IN');
    }

    /**
     * @return MPSLDbQuery
     */
    public function whereNotIn($field, $values)
    {
        return $this->whereValues($field, $values, 'NOT IN');
    }

    /**
     * @return MPSLDbQuery
     */
    public function whereValues($field, $values, $operator)
    {
        if (empty($values) || !is_array($values)) {
            return $this;
        }

        $_values = '(' . implode(',', $values) . ')';
        $where = bqSQL($field) . ' ' . $operator . ' ' . pSQL($_values);
        return $this->where($where);
    }

    /**
     * Just splits orderBy()' single parameter to field name and order key.
     * @return MPSLDbQuery
     */
    public function orderBy($field, $order = 'ASC')
    {
        $order_by = pSQL($field) . ' ' . $order;
        return parent::orderBy($order_by);
    }
}
