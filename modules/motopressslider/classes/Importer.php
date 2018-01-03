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

class MPSLImporter
{
    private static $max_image_size = array();

    /** @var MotoPressSlider */
    private $module = null;
    private $logs = array();

    public function __construct($module = null)
    {
        if (!is_null($module)) {
            $this->module = $module;
        } else {
            $this->module = mpsl_get_module();
        }

        if (empty(self::$max_image_size)) {
            self::$max_image_size['MiB'] = Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE');
            self::$max_image_size['KiB'] = self::$max_image_size['MiB']*1024;
            self::$max_image_size['B'] = self::$max_image_size['KiB']*1024;
        }
    }

    public function import($path, $auth_login = null, $auth_pass = null)
    {
        if (is_readable($path)) {
            $auth = null;
            if (!empty($auth_login) && !empty($auth_pass)) {
                $auth = array(
                    'login' => $auth_login,
                    'pass' => $auth_pass
                );
            }

            $data = Tools::file_get_contents($path);
            if ($data !== false) {
                return $this->importData($data, $auth);
            } else {
                $this->log($this->module->l('Failed to load data from import file.'));
                return false;
            }
        } else {
            $this->log($this->module->l('Import file is not readable.'));
            return false;
        }
    }

    private function importData($data, $auth = null)
    {
        if (empty($data)) {
            return true;
        }

        // Decode data
        $data = mpsl_json_decode_assoc($data);
        if (!is_array($data)) {
            $this->log($this->module->l('Import data is not valid.'));
            return false;
        } else {
            $this->log($this->module->l('Import data is valid.'));
            if (!empty($auth)) {
                $this->log($this->module->l('HTTP Auth login: %s.'), $auth['login']);
                $this->log($this->module->l('HTTP Auth password: %s.'), $auth['pass']);
            }
        }

        $this->log($this->module->l('Import data.'));

        $internal_resources = array(); // Images/uploads
        // No need to save slides and layers: they would be removed with sliders
        $sliders = array();

        // 1/5. Import images
        if (isset($data['uploads']) && !empty($data['uploads'])) {
            $this->log($this->module->l('%d uploads found in import file. Downloading...'), count($data['uploads']));
            $internal_resources = $this->importImages($data, $auth);
            if (count($internal_resources) < count($data['uploads'])) {
                $this->log($this->module->l('Failed to load images.'));
                $this->rollback($internal_resources);
                return false;
            }
            if (!empty($internal_resources)) {
                $this->log($this->module->l('Uploads imported. Files count: %d.'), count($internal_resources));
            }
        } else {
            $this->log($this->module->l('No uploads found (or uploads data is empty).'));
        }

        // 2/5. Import presets
        // Get presets count
        $input_presets_count = 0;
        $output_presets_count = 0;
        if (isset($data['presets']) && is_array($data['presets'])) {
            $input_presets_count = count($data['presets']);
        }
        // Add presets
        if ($input_presets_count > 0) {
            $presets_obj = MPSLPresets::getInstance();
            $new_preset_classes = $presets_obj->addPresets($data['presets']);
            $output_presets_count = count($new_preset_classes);
            if ($output_presets_count > 0) {
                $presets_obj->update();
            }
        }
        $this->log($this->module->l('%1$d presets loaded of %2$d.'), $output_presets_count, $input_presets_count);

        // 3-5/5. Import sliders, slides and layers
        if (isset($data['sliders'])) {
            // 3/5. Import sliders
            // See slides and layers import in method importSliders()
            $imported = $this->importSliders($data['sliders'], $internal_resources, $sliders);
            if ($imported) {
                $this->log($this->module->l('%d slider(s) added to database.'), count($sliders));
            } else {
                $this->rollback($internal_resources, $sliders);
                return false;
            }
        }

        // Data successfully imported
        $this->log($this->module->l('Import successfully finished.'));

        // Update private styles for each new layer
        if (!isset($presets_obj)) {
            $presets_obj = MPSLPresets::getInstance();
        }
        $presets_obj->updatePrivateStyles();
        $preview_presets_obj = MPSLPresets::getInstance(true);
        $preview_presets_obj->updatePrivateStyles();

        return true;
    }

    private function importSliders($data, &$internal_resources = array(), &$sliders = array())
    {
        // 3/5. Import sliders
        $this->log($this->module->l('Importing sliders...'));
        foreach ($data as $id => &$slider_data) {
            $this->log($this->module->l('New slider found: ID - %d.'), $id);

            // Get data
            $options = mpsl_get($slider_data, 'options', array());
            $title = mpsl_get($options, 'title', $this->module->l('New Slider'));
            $alias = mpsl_get($options, 'alias', 'slider');

            // Get unique alias
            $suffix = '';
            $unique_alias = MPSLSlider::uniqueAlias($alias, $suffix);
            if ($suffix != '') {
                $this->log(
                    $this->module->l('We need to find new alias for slider *%1$s*. Found new alias - *%2$s*.'),
                    $alias,
                    $unique_alias
                );
                $title .= $suffix;
            } elseif ($unique_alias != $alias) {
                $this->log(
                    $this->module->l('The alias *%1$s* is not valid. New alias is *%2$s*.'),
                    $alias,
                    $unique_alias
                );
            }
            $alias = $unique_alias;

            // Prepare options
            $options['title'] = $title;
            $options['alias'] = $alias;
            $options['shortcode'] = MPSLSlider::aliasToShortcode($alias);

            // Try to add new slider to database
            $this->log($this->module->l('Add new slider to database.'));
            $slider = new MPSLSlider(0, null);
            $slider->settings->updateOptions($options);
            $created = $slider->add();

            // Log result
            if ($created) {
                $this->log($this->module->l('New slider added to database with ID = %d.'), $slider->id);
                $this->log($this->module->l('Slider imported.'));
                $sliders[$id] = $slider->id;
                // 4-5/5. Import slides and layers
                if (isset($slider_data['slides'])) {
                    // 4/5. Import slides
                    $imported = $this->importSlides($slider_data['slides'], $slider->id, $internal_resources);
                    if (!$imported) {
                        // Failed to import slides/layers
                        return false;
                    }
                } else {
                    $this->log($this->module->l('No slides found for this slider.'));
                }
            } else {
                $this->log($this->module->l('Failed to insert new slider into database.'));
                $this->log($this->module->l('Slider with ID = %d is not imported.'), $id);
                return false;
            }
        } // For each slider
        return true;
    }

    private function importSlides($data, $parent_id, &$internal_resources = array())
    {
        // 4/5. Import slides
        $this->log($this->module->l('Importing slides...'));
        $order = 1;
        foreach ($data as &$slide_data) {
            $this->log($this->module->l('New slide found.'));

            $options = mpsl_get($slide_data, 'options', array());

            if (isset($options['bg_image_id'])) {
                $bg_image_id = $options['bg_image_id'];
                $old_bg_image_id = $bg_image_id;
                if (empty($bg_image_id)) {
                    $bg_image_id = '';
                } elseif (!is_array($bg_image_id)) {
                    $bg_image_id = (int)$bg_image_id;
                    $bg_image_id = mpsl_get($internal_resources, $bg_image_id, '');
                } elseif (isset($bg_image_id['old_value'])) {
                    $bg_image_id = (int)$bg_image_id['old_value'];
                    $bg_image_id = mpsl_get($internal_resources, $bg_image_id, '');
                } else {
                    $bg_image_id = '';
                }
                // Update value in options
                $options['bg_image_id'] = $bg_image_id;
                // Log results
                if ($bg_image_id !== $old_bg_image_id) {
                    if (!empty($bg_image_id) || !empty($old_bg_image_id)) {
                        $this->log(
                            $this->module->l('Slides bg_image_id option was changed from *%1$s* to *%2$s*.'),
                            print_r($old_bg_image_id, true),
                            $bg_image_id
                        );
                    } else {
                        $this->log($this->module->l('No image specified for the slide.'));
                    }
                }
            }

            // Try to add new slide to database
            $this->log($this->module->l('Add new slide to database.'));
            $slide = new MPSLSlide(0, null, $parent_id, $order);
            $slide->settings->updateOptions($options);
            $created = $slide->add();

            // Log result
            if ($created) {
                $this->log($this->module->l('New slide added to database with ID = %d.'), $slide->id);
                if (isset($slide_data['layers'])) {
                    // 5/5. Import layers
                    $imported = $this->importLayers($slide_data['layers'], $slide->id, $internal_resources);
                    if (!$imported) {
                        // Failed to import slides/layers
                        return false;
                    }
                } else {
                    $this->log($this->module->l('Layers not found for the slide (or layers data is empty).'));
                }
            } else {
                $this->log($this->module->l('Failed to insert new slide into database.'));
                $this->log($this->module->l('Slide not imported.'));
                return false;
            }

            $order += 1;
        } // For each slide

        $this->log($this->module->l('%1$d slide(s) added to slider with ID = %2$d.'), count($data), $parent_id);
        $this->log($this->module->l('Back to sliders import...'));

        return true;
    }

    private function importLayers($data, $parent_id, &$internal_resources = array())
    {
        // 5/5. Import layers
        $this->log($this->module->l('Importing layers...'));
        foreach ($data as $no => &$layer_data) {
            $this->log($this->module->l('New layer found.'));
            $order = $no + 1;

            $options = $layer_data;

            // Remove deprecated options
            if (isset($options['html_style'])) {
                unset($options['html_style']);
            }
            if (isset($options['button_style'])) {
                unset($options['button_style']);
            }

            // Get real image ID
            if (isset($options['image_id'])) {
                $old_image_id = $image_id = $options['image_id'];
                if (empty($image_id)) {
                    $image_id = '';
                } elseif (!is_array($image_id)) {
                    $image_id = (int)$image_id;
                    $image_id = mpsl_get($internal_resources, $image_id, '');
                } elseif (isset($image_id['old_value'])) {
                    $image_id = (int)$image_id['old_value'];
                    $image_id = mpsl_get($internal_resources, $image_id, '');
                } else {
                    $image_id = '';
                }
                // Update value in options
                $options['image_id'] = $image_id;
                // Log results
                if ($image_id !== $old_image_id) {
                    if (!empty($image_id) || !empty($old_image_id)) {
                        $this->log(
                            $this->module->l('Layers image_id option was changed from *%1$s* to *%2$d*.'),
                            print_r($old_image_id, true),
                            $image_id
                        );
                    } else {
                        $this->log($this->module->l('No image specified for the layer.'));
                    }
                }
            }

            // Try to add new layer to database
            $this->log($this->module->l('Add new layer to database.'));
            $layer = new MPSLLayer(0, null, $parent_id, $order);
            $layer->settings->updateOptions($options);
            $created = $layer->add();

            // Log result
            if ($created) {
                $this->log($this->module->l('New layer added to database with ID = %d.'), $layer->id);
            } else {
                $this->log($this->module->l('Failed to insert new layer into database.'));
                $this->log($this->module->l('Layers not updated.'));
                return false;
            }
        } // For each layer
        return true;
    }

    private function importImages($data, $auth = null)
    {
        if (array_key_exists('uploads', $data)) {
            $data = $data['uploads'];
        }

        $internal_resources = array();
        $attachment = new MPSLAttachment();

        foreach ($data as $id => $source) {
            if (!isset($source['value'])) {
                $this->log($this->module->l('Value attribute not found for upload with ID = %d.'), $id);
                // Import failed; no need to download other images
                break;
            }

            $url = $source['value'];
            $this->log($this->module->l('New upload found: ID - %d, URL - %s.'), $id, $url);
            $file = $this->importImage($url, $auth);

            if (!$file) {
                // Import failed; no need to download other images
                break;
            }

            // Attachemnt imported. Add new attachment to database
            $log = array();
            $added = $attachment->addNew($file['file'], $file['file_name'], $file['file_size'], $file['mime'], $log);
            $this->merge($log);

            if ($added) {
                // Attachment added
                // Try to create the image preview
                MPSLAttachment::createPreview($file['file'], $this->module->uploads_dir);
                // Add new "internal resource"
                $internal_resources[$id] = $attachment->id;
                $this->log(
                    $this->module->l('New database record successfully added for attachment file (new ID = %d).'),
                    $attachment->id
                );
            } else {
                // Failed to add the attachemnt. Delete image file
                $this->log($this->module->l('Failed to add new attachment.'));
                $this->log($this->module->l('Delete the image *%s*.'), $file['file']);
                MPSLAttachment::deleteAttachmentFile($file['file']);
                // Import failed; no need to download other images
                break;
            }
        } // foreach $data

        return $internal_resources;
    }

    private function importImage($url, $auth = null)
    {
        $dir = $this->module->uploads_dir;
        $file_name = basename($url);
        $mime = mpsl_fileinfom($file_name, 'mime');
        $file = mpsl_uniqname($file_name, $dir);
        $path = $dir . $file;

        $this->log($this->module->l('Source file name: %s'), $file_name);

        $load_status = mpsl_download_image($url, $path, $mime, $auth);
        // Save massages from downloader to log
        $this->merge($load_status['messages']);
        // Check the status
        if ($load_status['result']) {
            // Check the file size
            $file_size = filesize($path);
            $file_size_kib = $file_size/1024;
            $max_size_kib = Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE')*1024;
            $max_size = $max_size_kib*1024;
            if ($file_size > $max_size) {
                // Too big
                $this->log(
                    $this->module->l('The file is too large. The file you are trying to upload is %d KiB.'),
                    number_format($file_size_kib, 2, '.', '')
                );
                $this->log($this->module->l('Maximum size allowed is: %d KiB.'), $max_size_kib);
                @unlink($path);
            } else {
                // The size is good
                $this->log($this->module->l('New file name: %s'), $file);
                // All is good
                return array(
                    'file' => $file,
                    'file_name' => $file_name,
                    'file_size' => $file_size,
                    'mime' => $mime
                );
            }
        }

        // Otherwise
        return false;
    }

    private function rollback($internal_resources, $sliders = array())
    {
        $this->log($this->module->l('Cannot appropriately import the data. Restoring the previous state...'));

        if (!empty($internal_resources)) {
            $this->log($this->module->l('Delete all new media files.'));
            foreach ($internal_resources as $id) {
                $this->log($this->module->l('Delete media with ID = %d.'), $id);
                $attachment = new MPSLAttachment($id);
                $attachment->delete();
            }
        }

        if (!empty($sliders)) {
            $this->log($this->module->l('Delete all new sliders (and their slides).'));
            foreach ($sliders as $id) {
                $this->log($this->module->l('Delete slider with ID = %d.'), $id);
                $slider = new MPSLSlider($id);
                $slider->delete();
            }
        }
    }

    private function merge($messages)
    {
        foreach ($messages as $message) {
            $this->log($message);
        }
    }

    private function log($text, $args = null)
    {
        if (is_null($args)) {
            $this->logs[] = $text;
        } else {
            $args = func_get_args();
            $args[0] = $text;
            $result_text = call_user_func_array('sprintf', $args);
            $this->logs[] = $result_text;
        }
    }

    public function getLog()
    {
        return $this->logs;
    }
}
