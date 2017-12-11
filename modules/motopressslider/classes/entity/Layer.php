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

class MPSLLayer extends MPSLChildEntity
{
    public function __construct($id = 0, $settings = null, $parent_id = 0, $order = null)
    {
        $this->name = 'layer';
        parent::__construct($id, $settings, $parent_id, $order);
    }

    public function loadItems()
    {
        $this->items_loaded = true;
    }

    public function getType()
    {
        return $this->settings->getOption('type', 'html');
    }

    protected function optionsToSanitize()
    {
        return array('text', 'button_text');
    }
}
