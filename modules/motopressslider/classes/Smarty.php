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

class MPSLSmarty
{
    private $smarty = null;
    private $template = '';

    public function __construct($template, $smarty = null)
    {
        $this->smarty = (is_null($smarty) ? mpsl_get_smarty() : $smarty);
        $this->template = _PS_MODULE_DIR_ . 'motopressslider/views/templates/' . $template;
    }

    public function assign($name, $value)
    {
        $this->smarty->assign($name, $value);
        return $this;
    }

    public function fetch()
    {
        return $this->smarty->fetch($this->template);
    }
}
