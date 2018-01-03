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
 *     mpsl_preg_check
 *     mpsl_preg_match
 *     mpsl_preg_match_all
 */

function mpsl_preg_check($pattern, $subject)
{
    $matched = preg_match($pattern, $subject);
    return (bool)$matched;
}

function mpsl_preg_match($pattern, $subject, $default = '', $mask_no = 0)
{
    $matched = preg_match($pattern, $subject, $matches);
    if ($matched && array_key_exists($mask_no, $matches)) {
        return $matches[$mask_no];
    } else {
        return $default;
    }
}

function mpsl_preg_match_all($pattern, $subject, $default = array(), $mask_no = 0)
{
    $matched = preg_match_all($pattern, $subject, $matches);
    if ($matched && array_key_exists($mask_no, $matches)) {
        return $matches[$mask_no];
    } else {
        return $default;
    }
}
