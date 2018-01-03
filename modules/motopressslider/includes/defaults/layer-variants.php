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

$module = mpsl_get_module();

/*
 * Return format:
return array(
    %Layer Type% => array(
        'label' => %Main Button Text%,
        ['separate' => true/false,] // Separate groups with line or not
        ['variants' => array(
            %Group Name% => array(
                array(
                    'label' => %Variant Label%,
                    ['macros' => %Macros%]
                ),
                array(...),
                ...
            ),
            ...
        )]
    ),
    ...
);
 */

return array(
    'html' => array(
        'label' => $module->l('Add Text'),
        'separate' => false,
        'variants' => array(
            'custom' => array(
                array(
                    'label' => $module->l('Custom Text')
                )
            ),
            'product' => array(
                array(
                    'label' => $module->l('Product Name'),
                    'macros' => 'mpsl-name'
                ),
                array(
                    'label' => $module->l('Product Short Description'),
                    'macros' => 'mpsl-short-description'
                ),
                array(
                    'label' => $module->l('Product Description'),
                    'macros' => 'mpsl-description'
                ),
                array(
                    'label' => $module->l('Product Tags'),
                    'macros' => 'mpsl-tags'
                ),
                array(
                    'label' => $module->l('Product Price Without Tax'),
                    'macros' => 'mpsl-price-no-tax'
                ),
                array(
                    'label' => $module->l('Product Price With Tax'),
                    'macros' => 'mpsl-price'
                ),
                array(
                    'label' => $module->l('Product Category'),
                    'macros' => 'mpsl-categories'
                ),
                array(
                    'label' => $module->l('Product Quantity'),
                    'macros' => 'mpsl-quantity'
                ),
                array(
                    'label' => $module->l('Product In Stock'),
                    'macros' => 'mpsl-in-stock'
                ),
                array(
                    'label' => $module->l('Product URL'),
                    'macros' => 'mpsl-product-url'
                ),
                // array(
                //     'label' => $module->l('Product Image'),
                //     'macros' => 'mpsl-product-image'
                // ),
                array(
                    'label' => $module->l('Product Image URL'),
                    'macros' => 'mpsl-product-image-url'
                )
            )
        )
    ),

    'image' => array(
        'label' => $module->l('Add Image'),
        'separate' => false,
        'variants' => array(
            'custom' => array(
                array(
                    'label' => $module->l('From Library'),
                )
            ),
            'product' => array(
                array(
                    'label' => $module->l('Product Image'),
                    'macros' => 'mpsl-product-image-url'
                )
            )
        )
    ),

    'button' => array(
        'label' => $module->l('Add Button'),
        'separate' => false,
        'variants' => array(
            'custom' => array(
                array(
                    'label' => $module->l('Custom Button'),
                )
            ),
            'product' => array(
                array(
                    'label' => $module->l('"More" Button'),
                    'macros' => 'mpsl-product-url'
                ),
                array(
                    'label' => $module->l('"Add to cart" Button'),
                    'macros' => 'mpsl-add-to-cart-url'
                )
            )
        )
    ),

    'video' => array(
        'label' => $module->l('Add Video')
    )
);
