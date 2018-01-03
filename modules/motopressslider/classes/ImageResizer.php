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

class MPSLImageResizer
{
    const DEFAULT_JPEG_QUALITY = 100;

    const WRITE_TO_FILE_ERROR = 1;
    const CHMOD_ERROR = 2; // Failed to update the file's permissions
    const FUNCTION_NOT_FOUND_ERROR = 4;

    /**
     * @var type image resource
     */
    protected $image = null;
    /**
     * @var type int
     */
    protected $image_type = '';

    public function load($file)
    {
        $this->image_type = $this->recognizeType($file);
        $image_create_func = 'imagecreatefrom' . $this->image_type;
        if (gd_info() && function_exists($image_create_func)) {
            $this->image = $image_create_func($file);
            return true;
        } else {
            $this->image = null;
            return false;
        }
    }

    /**
     * @return int|boolean True for success or int as an error code.
     */
    public function save($to_file, $compression = self::DEFAULT_JPEG_QUALITY, $permissions = null)
    {
        $file_type = $this->recognizeType($to_file);
        if (!$file_type) {
            $file_type = $this->image_type;
        }
        $image_save_func = 'image' . $file_type;

        $created = false;
        $modded = true;

        if (function_exists($image_save_func)) {
            switch ($file_type) {
                case 'jpg':
                case 'jpeg':
                    $created = $image_save_func(
                        $this->image,
                        $to_file,
                        $compression
                    );
                    break;
                default:
                    $created = $image_save_func($this->image, $to_file);
                    break;
            }
            if ($permissions != null) {
                $modded = @chmod($to_file, $permissions);
            }

            if ($created && $modded) {
                return true;
            } elseif (!$created) {
                return self::WRITE_TO_FILE_ERROR;
            } elseif (!$modded) {
                return self::CHMOD_ERROR;
            } else {
                return (self::WRITE_TO_FILE_ERROR & self::CHMOD_ERROR);
            }
        } else {
            return self::FUNCTION_NOT_FOUND_ERROR;
        }
    }

    public function getWidth()
    {
        return ($this->image ? imagesx($this->image) : 0);
    }

    public function getHeight()
    {
        return ($this->image ? imagesy($this->image) : 0);
    }

    public function resizeWidth($new_width)
    {
        if ($this->image) {
            $ratio = $new_width/$this->getWidth();
            $new_height = $this->getheight()*$ratio;
            return $this->resize($new_width, $new_height);
        } else {
            return false;
        }
    }

    public function resizeHeight($new_height)
    {
        if ($this->image) {
            $ratio = $new_height/$this->getHeight();
            $new_width = $this->getWidth()*$ratio;
            return $this->resize($new_width, $new_height);
        } else {
            return false;
        }
    }

    public function resize($new_width, $new_height)
    {
        if ($this->image) {
            $new_image = imagecreatetruecolor($new_width, $new_height);
            $copied = imagecopyresampled(
                $new_image,
                $this->image,
                0,
                0,
                0,
                0,
                $new_width,
                $new_height,
                $this->getWidth(),
                $this->getHeight()
            );
            if ($copied) {
                $this->image = $new_image;
                return true;
            }
        }
        // Otherwise...
        return false;
    }

    public function limit($width_limit, $height_limit)
    {
        $width = $this->getWidth();
        $height = $this->getHeight();
        if ($width > $width_limit || $height > $height_limit) {
            $width_ratio = $width_limit/$width;
            $height_ratio = $height_limit/$height;
            if ($width_ratio <= $height_ratio) {
                return $this->resizeWidth($width_limit);
            } else {
                return $this->resizeHeight($height_limit);
            }
        }
        // Resizing is not required
        return true;
    }

    public function recognizeType($file)
    {
        if (!gd_info() || !file_exists($file)) {
            return '';
        }
        $image_info = getimagesize($file);
        $mime = (isset($image_info['mime']) ? $image_info['mime'] : '');
        $matched = preg_match('/image\/(\w+)/i', $mime, $parts);
        if ($matched) {
            return Tools::strtolower($parts[1]);
        } else {
            return pathinfo($file, PATHINFO_EXTENSION);
        }
    }
}
