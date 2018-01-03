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

class MPSLAttachment
{
    const JPEG_QUALITY = 100;
    const PREVIEW_SIZE = 150; // See also "Preview size" in /views/less/mpsl.less

    private static $max_size = array();

    /**
     * @var MotoPressSlider Module instance.
     */
    private $module = null;
    /**
     * @var MPSLDb Main module database instance.
     */
    private $db = null;
    private $id = 0;

    public $file = '';
    public $file_name = '';
    public $file_size = 0;
    public $mime = '';

    public function __construct($id = null, $module = null)
    {
        $this->module = (is_null($module) || !is_a($module, 'MotoPressSlider') ? mpsl_get_module() : $module);
        $this->db = $this->module->db;

        self::refreshMaxSize();

        if (!is_null($id)) {
            // Get it's data from DB
            $data = $this->db->select(MPSLDb::ATTACHMENT_TABLE, $id);
            if ($data) {
                $this->id = $id;
                $this->file = $data['file'];
                $this->file_name = $data['file_name'];
                $this->file_size = (int)$data['file_size'];
                $this->mime = $data['mime'];
            }
        }
    }

    public function add()
    {
        $inserted = $this->db->insert(MPSLDb::ATTACHMENT_TABLE, array(
            'file' => $this->file,
            'file_name' => $this->file_name,
            'file_size' => $this->file_size,
            'mime' => $this->mime
        ));

        if ($inserted) {
            $this->id = $this->db->lastId(MPSLDb::ATTACHMENT_TABLE);
            return $this->id;
        } else {
            return false;
        }
    }

    /**
     * Add new attachment data to database.
     * @param string $file New file name.
     * @param string $file_name Original file name.
     * @return int|boolean new Attachment ID or FALSE.
     */
    public function addNew($file, $file_name, $file_size, $mime, &$log = array())
    {
        if (empty($mime) || Tools::strlen($mime) > 128) {
            $log[] = sprintf($this->module->l('Invalid file extension: *%s*'), $mime);
            return false;
        }

        if (!Validate::isGenericName($file_name)) {
            $log[] = $this->module->l('Invalid file name.');
            return false;
        }

        if (Tools::strlen($file_name) > 128) {
            $log[] = $this->module->l('The file name is too long');
            return false;
        }

        $this->file = $file;
        $this->file_name = $file_name;
        $this->file_size = $file_size;
        $this->mime = $mime;

        $added = $this->add();
        if ($added) {
            return $this->id;
        } else {
            $log[] = $this->module->l('This attachment was unable to load into the database.');
            return false;
        }
    }

    public function update()
    {
        if ($this->id > 0) {
            $updated = $this->db->update(MPSLDb::ATTACHMENT_TABLE, $this->id, array(
                'file' => $this->file,
                'file_name' => $this->file_name,
                'file_size' => $this->file_size,
                'mime' => $this->mime
            ));
            return $updated;
        } else {
            return false;
        }
    }

    public function delete()
    {
        if ($this->id > 0) {
            $this->db->delete(MPSLDb::ATTACHMENT_TABLE, $this->id);

            // Delete image file
            self::deleteAttachmentFile($this->file);

            // Reset attachment fields
            $this->id = 0;
            $this->file = '';
            $this->file_name = '';
            $this->file_size = 0;
            $this->mime = '';

            return true;
        } else {
            return false;
        }
    }

    /**
     * Create attachment record in database using uploaded file. It does not
     * download the file like mpsl_download_file(), and works only with uploaded
     * files.
     */
    public function load($file, &$log = array())
    {
        // extract($file) - no need to generate 12 errors in PrestaShop Validator
        $name = $file['name'];
        $size = $file['size'];
        $type = $file['type'];
        $tmp_name = $file['tmp_name'];
        $error = $file['error'];

        // Check the error code
        if ($error != 0) {
            $log[] = mpsl_translate_php_upload_error($error);
            return false;
        }

        // If file exists
        if (is_uploaded_file($tmp_name)) {
            // File size must be less than maximum upload size in PrestaShop
            if ($size > self::$max_size['B']) {
                // Too big
                $size_kib = number_format(($size/1024), 2, '.', '');
                $log[] = sprintf('The file is too large. The file you are trying to upload is %s KiB.', $size_kib);
                $log[] = sprintf('Maximum size allowed is: %d KiB.', self::$max_size['KiB']);
            } else {

                // The size is good
                $images_dir = $this->module->uploads_dir;

                // In PrestaShop's AttachmentCore - $file = sha1(microtime());
                $file = mpsl_uniqname($name, $images_dir);
                $file = preg_replace('/\s+/', '-', $file); // Replace all spaces by "-" string
                $path = $images_dir . $file;

                // Try to copy the file to "uploads" directory
                if (Tools::copy($tmp_name, $path)) {
                    // Copied. Create new attachment in database
                    $added = $this->addNew($file, $name, $size, $type);
                    if ($added) {
                        // Added. Try to create the image preview
                        self::createPreview($file, $images_dir);
                        return true;
                    } else {
                        // Failed to add new attachment to database. Delete the
                        // file from "uploads" directory
                        @unlink($path);
                    }
                } else {
                    // Failed to copy the file
                    $log[] = $this->module->l('File copy failed.');
                }

                // Delete temporary file
                @unlink($tmp_name);

            }
        } else {
            $log[] = $this->module->l('The file is missing.');
        }

        // Failure
        return false;
    }

    public function getInfo()
    {
        // See /views/js/media-library.js/MPSL.MediaLibrary.buildItem()
        $info = array(
            'id' => $this->id,
            'file' => $this->file,
            'name' => $this->file_name,
            'preview_url' => $this->module->uploads_url . 'previews/no-preview.png',
            'width' => 0,
            'height' => 0
        );

        if ($this->id > 0) {
            // Set preview URL information
            $preview_path = $this->module->uploads_dir . 'previews/' . $this->file;
            if (file_exists($preview_path)) {
                $info['preview_url'] = $this->module->uploads_url . 'previews/' . $this->file;
            }
            // Set size information
            $path = $this->module->uploads_dir . $this->file;
            $image_size = getimagesize($path);
            $info['width'] = $image_size[0];
            $info['height'] = $image_size[1];
            // Build item HTML
            $info['html'] = mpsl_fetch_smarty('admin/media-item.tpl', array('data' => $info));
        }

        return $info;
    }

    /**
     * @param array $product Product data, for example from MPSLProductQuery.
     * @return array Detailed cover data for media library.
     */
    public static function getCoverInfo($product)
    {
        $cover_id = $product['cover_id'];
        $cover = mpsl_get_cover($cover_id);
        $data = array(
            'id' => $cover_id,
            'file' => $cover['file'],
            'format' => $cover['format'],
            'name' => $product['name'],
            'preview_url' => $product['cover_preview'],
            'width' => $cover['width'],
            'height' => $cover['height']
        );
        // Build item HTML
        $data['html'] = mpsl_fetch_smarty('admin/media-item.tpl', array(
            'can_delete' => false,
            'is_product' => true,
            'data' => $data
        ));
        return $data;
    }

    public function __get($name)
    {
        return $this->$name;
    }

    /**
     * Tries to create 150x150 image preview not breaking the original
     * proportions.
     */
    public static function createPreview($file, $dir)
    {
        $dir = mpsl_slash($dir);
        $src = $dir . $file;
        $previews_dir = $dir . 'previews/';
        $dest = $previews_dir . $file;
        if (file_exists($src) && file_exists($previews_dir)) {
            $resizer = new MPSLImageResizer();
            $resizer->load($src);
            $resizer->limit(self::PREVIEW_SIZE, self::PREVIEW_SIZE);
            $resizer->save($dest);
        }
    }

    public static function deleteAttachmentFile($file)
    {
        $images_dir = mpsl_get_module()->uploads_dir;
        $attachment_file = $images_dir . $file;
        $preview_file = $images_dir . 'previews/' . $file;
        if (file_exists($attachment_file)) {
            @unlink($attachment_file);
        }
        if (file_exists($preview_file)) {
            @unlink($preview_file);
        }
    }

    /**
     * Create "uploads" and "previews" directories in PrestaShop's "img"
     * directory.
     */
    public static function createCustomStorage()
    {
        $custom_img_dir = _PS_IMG_DIR_ . 'motopressslider/';
        $custom_img_previews_dir = $custom_img_dir . 'previews/';

        if (!file_exists($custom_img_dir)) {
            @mkdir($custom_img_dir, 0755);
        }

        if (file_exists($custom_img_dir) && !file_exists($custom_img_previews_dir)) {
            @mkdir($custom_img_previews_dir, 0755);
        }

        if (file_exists($custom_img_previews_dir)) {
            // Copy no-preview.png from module folder to previews folder
            $source = mpsl_get_module()->img_dir . 'no-preview.png';
            $destination = $custom_img_previews_dir . 'no-preview.png';
            Tools::copy($source, $destination);
        }
    }

    /**
     * Delete custom "uploads" and "previews" directories.
     */
    public static function deleteCustomStorage()
    {
        $custom_img_dir = _PS_IMG_DIR_ . 'motopressslider/';
        Tools::deleteDirectory($custom_img_dir);
    }

    public static function refreshMaxSize()
    {
        if (empty(self::$max_size)) {
            self::$max_size['MiB'] = Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE');
            self::$max_size['KiB'] = self::$max_size['MiB']*1024;
            self::$max_size['B'] = self::$max_size['KiB']*1024;
        }
    }

    public static function getMaxSize($unit = 'MiB')
    {
        self::refreshMaxSize();
        return self::$max_size[$unit];
    }
}
