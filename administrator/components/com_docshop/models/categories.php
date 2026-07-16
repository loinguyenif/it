<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

class DocshopModelCategories extends JModelList
{
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'id',
                'title',
                'alias',
                'published',
                'created'
            );
        }

        parent::__construct($config);
    }

    protected function getListQuery()
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $query->select('a.*')
            ->from($db->quoteName('#__docshop_categories', 'a'));

        $published = $this->getState('filter.published');

        if ($published !== '') {
            $query->where('a.published = ' . (int) $published);
        }

        $search = $this->getState('filter.search');

        if ($search !== '') {
            $search = $db->quote('%' . $db->escape($search, true) . '%');
            $query->where('(a.title LIKE ' . $search . ' OR a.alias LIKE ' . $search . ')');
        }

        $orderCol = $this->state->get('list.ordering', 'a.title');
        $orderDirn = $this->state->get('list.direction', 'ASC');
        $query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));

        return $query;
    }

    protected function populateState($ordering = null, $direction = null)
    {
        $app = JFactory::getApplication();

        $this->setState('filter.search', $app->getUserStateFromRequest($this->context . '.filter.search', 'filter_search', '', 'string'));
        $this->setState('filter.published', $app->getUserStateFromRequest($this->context . '.filter.published', 'filter_published', '', 'string'));

        parent::populateState('a.title', 'ASC');
    }
}
