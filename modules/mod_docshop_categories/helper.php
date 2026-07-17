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

class ModDocshopCategoriesHelper
{
    public static function getItems($params)
    {
        $db    = Factory::getDbo();
        $query = $db->getQuery(true);

        // Count documents per category using a subquery
        $subQuery = $db->getQuery(true)
            ->select(array(
                $db->quoteName('d.category_id'),
                'COUNT(' . $db->quoteName('d.id') . ') AS ' . $db->quoteName('doc_count'),
            ))
            ->from($db->quoteName('#__docshop_documents', 'd'))
            ->where($db->quoteName('d.published') . ' = 1')
            ->group($db->quoteName('d.category_id'));

        $query->select(array(
                $db->quoteName('c.id'),
                $db->quoteName('c.title'),
                $db->quoteName('c.alias'),
                'COALESCE(' . $db->quoteName('sub.doc_count') . ', 0) AS ' . $db->quoteName('doc_count'),
            ))
            ->from($db->quoteName('#__docshop_categories', 'c'))
            ->join(
                'LEFT',
                '(' . $subQuery . ') AS ' . $db->quoteName('sub') .
                ' ON ' . $db->quoteName('sub.category_id') . ' = ' . $db->quoteName('c.id')
            )
            ->where($db->quoteName('c.published') . ' = 1')
            ->order($db->quoteName('c.title') . ' ASC');

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
                'id'        => (int) $row->id,
                'title'     => $row->title,
                'doc_count' => (int) $row->doc_count,
                'link'      => Route::_('index.php?option=com_docshop&view=documents&filter_category_id=' . (int) $row->id),
            );
        }

        return $items;
    }
}
