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

/*
 * Functions:
 *     mpsl_dir
 *     mpsl_download_image
 *     mpsl_fetch_smarty
 *     mpsl_fileinfo
 *     mpsl_fileinfom
 *     mpsl_get_image_src
 *     mpsl_get_mime_types
 *     mpsl_read
 *     mpsl_uniqname
 */

/**
 * Generates a full path to local module's file or folder.
 */
function mpsl_dir($subpath)
{
    $path = _PS_MODULE_DIR_ . 'motopressslider/' . mpsl_unslash_left($subpath);
    if (is_dir($path)) {
        $path = mpsl_slash($path);
    }
    return $path;
}

function mpsl_download_image($url, $dest, $mime, $auth = null)
{
    $module = mpsl_get_module();
    $useragent = 'PrestaShop/' . _PS_VERSION_ . '; ' . $module->base_url;

    $downloaded = false;
    $messages = array();

    if (!empty($auth)) {
        $messages[] = $module->l('Download image using HTTP Auth data...');
        // Get image from auth connection
        if (function_exists('curl_init')) {
            set_time_limit(0);
            $file_handle = fopen($dest, 'w+'); // Write image data to the file
            if ($file_handle) {
                $curl = curl_init($url);
                curl_setopt_array($curl, array(
                    // Write cURL response to file
                    CURLOPT_FILE => $file_handle,
                    CURLOPT_TIMEOUT => 300,
                    CURLOPT_USERPWD => $auth['login'] . ':' . $auth['pass'],
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_USERAGENT => $useragent
                ));
                curl_exec($curl);
                curl_close($curl);
                fclose($file_handle);
                $downloaded = true;
            } else {
                $messages[] = sprintf(
                    Tools::stripslashes(
                        $module->l('Failed to create/rewrite the file "%s".')
                    ),
                    $dest
                );
            }
        } else {
            $messages[] = $module->l('PHP/cURL extention is not supported. The image was not downloaded.');
        }

    } elseif ($mime == 'image/jpeg' && gd_info()) {
        $messages[] = $module->l('Download JPEG image using GD library...');
        // Get JPEG image from non-auth connection
        $stream_handle = @fopen($url, 'r');
        if ($stream_handle) {
            $resource = @imagecreatefromstring(stream_get_contents($stream_handle));
            if (is_resource($resource) && imagejpeg($resource, $dest, MPSLAttachment::JPEG_QUALITY)) {
                @imagedestroy($resource);
                $downloaded = true;
            } else {
                $messages[] = $module->l('Failed to create the image from stream content.');
            }
        } else {
            $messages[] = $module->l('Failed to open the stream.');
        }
        @fclose($stream_handle);

    } else {
        $messages[] = $module->l('Download image using function fule_get_contents...');
        // Get non-JPEG image from non-auth connection
        $image_content = Tools::file_get_contents($url);
        $put_result = ($image_content ? file_put_contents($dest, $image_content) : false);
        if ($put_result) {
            $downloaded = true;
        } else {
            $messages[] = $module->l('Failed to download the image.');
        }
        unset($image_content);
    }

    if ($downloaded) {
        $messages[] = $module->l('Image successfully downloaded.');
    } else {
        $messages[] = $module->l('The image was not downloaded.');
    }

    return array('result' => $downloaded, 'messages' => $messages);
}

/**
 * @param string $template Template file name with subdir name, like: "admin",
 * "hook" etc. So the full name must be like: "admin/sliders.tpl".
 */
function mpsl_fetch_smarty($template, $vars = array())
{
    // Prepare the full path to template
    $template = mpsl_unslash_left($template);
    $module = mpsl_get_module();
    $template_path = $module->templates_dir . $template;

    // Fetch template's data
    $smarty = mpsl_get_smarty();
    if (!empty($vars)) {
        $smarty->assign($vars);
    }
    $output = $smarty->fetch($template_path);

    return $output;
}

/**
 * Generates only file's name and extention and returns either all of them
 * or any specified item. To get the item set the second parameter to
 * <b>"name"</b> or <b>"ext"</b>.
 * @see mpsl_fileinfom()
 */
function mpsl_fileinfo($file, $part = 'all')
{
    $name = $file;
    $ext = '';

    // $matches[1] - %ext%
    // $matches[0] - .%ext%
    $matched = preg_match('/\.([^\s\.]*)$/', $file, $matches);
    if ($matched) {
        $name = str_replace($matches[0], '', $file);
        $ext = $matches[1];
    }

    $fileinfo = array('name' => $name, 'ext' => $ext);

    if (array_key_exists($part, $fileinfo)) {
        return $fileinfo[$part];
    } else {
        return $fileinfo;
    }
}

/**
 * Generates file's name, extention and MIME type and returns either all of them
 * or any specified item. To get the item set the second parameter to
 * <b>"name"</b>, <b>"ext"</b> or <b>"mime"</b>.
 * @see mpsl_fileinfo()
 */
function mpsl_fileinfom($file, $part = 'all')
{
    $mimes = mpsl_get_mime_types();

    $name = false;
    $ext = false;
    $mime = false;

    foreach ($mimes as $ext_preg => $mime_match) {
        $ext_preg = '!\.(' . $ext_preg . ')$!i';
        $matched = preg_match($ext_preg, $file, $ext_matches);
        if ($matched) {
            $mime = $mime_match;
            $ext = $ext_matches[1];
            break;
        }
    }

    if ($ext) {
        $name = str_replace(".{$ext}", '', $file);
    }

    $fileinfo = array('name' => $name, 'ext' => $ext, 'mime' => $mime);

    if (array_key_exists($part, $fileinfo)) {
        return $fileinfo[$part];
    } else {
        return $fileinfo;
    }
}

function mpsl_get_image_src($attachment_id)
{
    $module = mpsl_get_module();
    $db = $module->db;

    $query = $db->createQuery();
    $query->select('file')
          ->from(MPSLDb::ATTACHMENT_TABLE)
          ->whereIs(MPSLDb::ATTACHMENT_ID, $attachment_id);

    $file = $db->queryValue($query);
    $src = $module->uploads_url . $file;

    return $src;
}

function mpsl_get_mime_types()
{
    $mime_types = mpsl_read('includes/defaults/mime-types.php');
    return $mime_types;
}

/**
 * Reads file that located in the module.
 */
function mpsl_read($module_file, $default = array())
{
    $path = mpsl_dir($module_file);
    if (file_exists($path)) {
        return include($path);
    } else {
        return $default;
    }
}

/**
 * Finds unique file name for a new file.
 * @param string $name Original file name.
 */
function mpsl_uniqname($name, $dir)
{
    $dir = mpsl_slash($dir);
    $info = mpsl_fileinfo($name);
    $name = $info['name'];
    $ext = '.' . $info['ext'];

    for ($suffix = 2; file_exists($dir . $name . $ext); $suffix++) {
        $name = $info['name'] . '-' . $suffix;
    }

    $name .= $ext;
    return $name;
}
