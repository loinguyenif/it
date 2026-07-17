<?php
/**
 * @package     mod_top_views
 * @subpackage  Modules
 * @copyright   Copyright (C) 2026
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\Router\Route;
use Joomla\Utilities\ArrayHelper;

class ModTopViewsHelper
{
    public static function getItems($params)
    {
        $db    = Factory::getDbo();
        $query = $db->getQuery(true);

        $count      = (int) $params->get('count', 5);
        $categories = $params->get('catid', array());

        $query->select(array(
                $db->quoteName('a.id'),
                $db->quoteName('a.title'),
                $db->quoteName('a.category_id'),
                $db->quoteName('a.view_count')
            ))
            ->from($db->quoteName('#__docshop_documents', 'a'))
            ->where($db->quoteName('a.published') . ' = 1');

        if (!empty($categories) && is_array($categories)) {
            $categories = ArrayHelper::toInteger($categories);
            $query->whereIn($db->quoteName('a.category_id'), $categories);
        }

        // Order by page views
        $query->order($db->quoteName('a.view_count') . ' DESC')
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
                'title'          => $row->title,
                'hits'           => (int) $row->view_count,
                'hits_fmt'       => number_format((int) $row->view_count),
                'link'           => Route::_('index.php?option=com_docshop&view=document&id=' . (int) $row->id),
            );
        }

        return $items;
    }
}
