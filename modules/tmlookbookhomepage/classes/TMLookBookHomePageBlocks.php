<?php

if (!defined('_PS_VERSION_')) {
    exit;
}

class TMLookBookHomePageBlocks extends ObjectModel
{
    public $id;
    public $active;
    public $sort_order;
    public $type;
    public $id_page;
    public $hook_name;

    public static $definition = array(
        'table' => 'tmlookbook_homepage',
        'primary' => 'id',
        'multilang' => false,
        'fields' => array(
            'sort_order' => array('type' => self::TYPE_INT, 'validate' => 'isunsignedInt'),
            'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'type' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
            'id_page' => array('type' => self::TYPE_STRING),
            'hook_name' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
        ),
    );

    public static function getAllBlocks($hook, $id_shop, $only_active = false)
    {
        $sql = 'SELECT tlhp.id, tlhp.active, tlhp.sort_order, tlhp.type, tlhp.id_page, tlhp.hook_name, tlkl.name, tlkl.description, tlk.image
                FROM ' . _DB_PREFIX_ . 'tmlookbook_homepage tlhp
                INNER JOIN ' . _DB_PREFIX_ . 'tmlookbook tlk
                ON tlhp.id_page = tlk.id_page
                AND tlhp.hook_name = \''.$hook.'\'
                JOIN ' . _DB_PREFIX_ . 'tmlookbook_lang tlkl
                ON tlk.id_page = tlkl.id_page
                AND tlkl.id_lang = '. Context::getContext()->language->id.'
                AND tlk.id_shop = '. (int) $id_shop;

        if ($only_active) {
            $sql .= ' AND tlhp.`active` = 1';
        }
        $sql .= ' ORDER BY tlhp.`sort_order`';

        if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql)) {
            return array();
        }

        return $result;
    }

    public function getMaxSortOrder($hook_name)
    {
        $sql = 'SELECT MAX(sort_order)
                AS sort_order
                FROM `'._DB_PREFIX_.'tmlookbook_homepage`
                WHERE `hook_name` = \''. $hook_name .'\';';

        if (!$result = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($sql)) {
            return 0;
        }

        return $result;
    }
}
