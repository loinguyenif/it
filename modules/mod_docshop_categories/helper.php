<?php
/**
 * @package     mod_docshop_categories
 * @subpackage  Modules
 * @copyright   Copyright (C) 2026
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\Utilities\ArrayHelper;

class ModDocshopCategoriesHelper
{
    public static function getItems($params)
    {
        $db    = Factory::getDbo();
        $query = $db->getQuery(true);

        $count      = (int) $params->get('count', 5);
        $categories = $params->get('catid', array());

        $query->select(
            array(
                $db->quoteName('a.id'),
                $db->quoteName('a.title'),
                $db->quoteName('a.category_id'),
                $db->quoteName('a.download_count')
            )
        )
            ->from($db->quoteName('#__docshop_documents', 'a'))
            ->where($db->quoteName('a.published') . ' = 1')
            ->group($db->quoteName(array('a.id', 'a.title', 'a.category_id')));

        if (!empty($categories) && is_array($categories)) {
            $categories = ArrayHelper::toInteger($categories);
            $query->whereIn($db->quoteName('a.category_id'), $categories);
        }

        $query->order('download_count DESC')
            ->setLimit($count);

        $db->setQuery($query);

        try {
            $rows = $db->loadObjectList();
        } catch (Exception $e) {
            return array();
        }

        if (empty($rows)) {
            return array();
        }

        $items = array();
        foreach ($rows as $row) {
            $items[] = (object) array(
                'title'      => $row->title,
                'hits'       => (int) $row->download_count,
                'hits_fmt'   => number_format((int) $row->download_count),
                'link'       => Route::_('index.php?option=com_docshop&view=documents&id=' . (int) $row->id),
            );
        }

        return $items;
    }
}
