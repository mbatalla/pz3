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

$module = Module::getInstanceByName('motopressslider');

return array(
    'MPSLSliders'  => array(
        'label'      => $module->l('MotoPress Slider'),
        'parent_id'  => 0 // Create new top-level menu tab "MotoPress Slider"
    ),
    'MPSLMedia'    => array(
        'label'   => $module->l('Media Library'),
        'parent'  => 'MPSLSliders'
    ),
    'MPSLSlider'   => $module->l('MotoPress Settings'),
    'MPSLSlides'   => $module->l('MotoPress Slides'),
    'MPSLProducts' => $module->l('MotoPress Product Settings'),
    'MPSLSlide'    => $module->l('MotoPress Slide'),
    'MPSLImpex'    => $module->l('MotoPress Import/Export'),
    'MPSLImporter' => $module->l('MotoPress Importer'),
    'MPSLExporter' => $module->l('MotoPress Exporter'),
    'MPSLPreview'  => $module->l('MotoPress Preview')
);
