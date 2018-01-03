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

/**
 * Stores key-value pairs in two formats:
 *     1) key => value array (direct):
 *         array(
 *             %key 1% => array(%value 1%, %value 2%, ...),
 *             %key 2% => array(%value 1%, %value 2%, ...),
 *             ...
 *         )
 *     2) value => key array (reverse):
 *         array(
 *             %value 1% => array(%key 1%, %key 2%, ...),
 *             %value 2% => array(%key 1%, %key 2%, ...),
 *             ...
 *         )
 */
class MPSLDoubleArray
{
    private $direct = array();
    private $reverse = array();

    public function add($key, $value)
    {
        if (!array_key_exists($key, $this->direct)) {
            $this->direct[$key] = array();
        }
        if (!array_key_exists($value, $this->reverse)) {
            $this->reverse[$value] = array();
        }
        $this->direct[$key][$value] = $value;
        $this->reverse[$value][$key] = $key;
    }

    public function removeDirect($key)
    {
        if (array_key_exists($key, $this->direct)) {
            // Get values before removing
            $values = $this->direct[$key];
            // Remove the key
            unset($this->direct[$key]);
            // Remove values
            foreach ($values as $value) {
                unset($this->reverse[$value][$key]);
                if (empty($this->reverse[$value])) {
                    unset($this->reverse[$value]);
                }
            }
        }
    }

    public function removeReverse($value)
    {
        if (array_key_exists($value, $this->reverse)) {
            // Get keys before removing
            $keys = $this->reverse[$value];
            // Remove the value
            unset($this->reverse[$value]);
            // Remove keys
            foreach ($keys as $key) {
                unset($this->direct[$key][$value]);
                if (empty($this->direct[$key])) {
                    unset($this->direct[$key]);
                }
            }
        }
    }

    public function hasItems()
    {
        return !empty($this->direct);
    }

    public function getDirect($key)
    {
        return mpsl_get($this->direct, $key, array());
    }

    public function getReverse($value)
    {
        return mpsl_get($this->reverse, $value, array());
    }

    public function getDirectArray()
    {
        return $this->direct;
    }

    public function getReverseArray()
    {
        return $this->reverse;
    }

    public function mergeDirect($direct)
    {
        $this->direct = array_merge($this->direct, $direct);
    }

    public function mergeReverse($reverse)
    {
        $this->reverse = array_merge($this->reverse, $reverse);
    }

    public function clear()
    {
        $this->direct = array();
        $this->reverse = array();
    }
}
