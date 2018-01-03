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
 *     mpsl_ajax_error
 *     mpsl_ajax_exit
 *     mpsl_ajax_fatal_message
 *     mpsl_ajax_success
 *     mpsl_ajax_warning
 *     mpsl_is_ajax
 *     mpsl_verify_ajax_nonce
 */

/**
 * FALSE result and "error" status.
 */
function mpsl_ajax_error($message, $data = array())
{
    mpsl_ajax_exit(false, 'error', $message, $data);
}

function mpsl_ajax_exit($result, $status, $message, $data)
{
    $response = array_merge(array(
        'result' => $result,
        'status' => $status,
        'message' => $message,
        'is_debug' => MotoPressSlider::IS_DEBUG
    ), $data);
    $response = Tools::jsonEncode($response);
    die($response);
}

/**
 * FALSE result, "error" status with server error.
 */
function mpsl_ajax_fatal_message($message)
{
    header('HTTP/1.1 500 Internal Server Error');
    die($message);
}

/**
 * TRUE result and "success" status.
 */
function mpsl_ajax_success($message, $data = array())
{
    mpsl_ajax_exit(true, 'success', $message, $data);
}

/**
 * TRUE result, but with "warning" status.
 */
function mpsl_ajax_warning($message, $data = array())
{
    mpsl_ajax_exit(true, 'warning', $message, $data);
}

function mpsl_is_ajax()
{
    $context = mpsl_get_context();
    return $context->controller->ajax;
}

function mpsl_verify_ajax_nonce()
{
    $nonce = Tools::getValue('nonce', '');
    $key = 'ajax_' . Tools::getValue('action', '');
    if (empty($nonce) || !mpsl_verify_nonce($nonce, $key)) {
        $module = mpsl_get_module();
        mpsl_ajax_error($module->l('Invalid security token.'));
    }
}
