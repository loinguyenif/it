<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

class DocshopModelOrders extends JModelList
{
    public function __construct($config = array())
    {
        if (empty($config['filter_fields'])) {
            $config['filter_fields'] = array(
                'a.id',
                'a.order_number',
                'a.user_id',
                'a.document_id',
                'a.amount',
                'a.status',
                'a.created'
            );
        }

        parent::__construct($config);
    }

    protected function getListQuery()
    {
        $db = $this->getDbo();
        $query = $db->getQuery(true);

        $query->select('a.*, u.name as user_name, d.title as document_title')
            ->from($db->quoteName('#__docshop_orders', 'a'))
            ->join('LEFT', $db->quoteName('#__users', 'u') . ' ON a.user_id = u.id')
            ->join('LEFT', $db->quoteName('#__docshop_documents', 'd') . ' ON a.document_id = d.id');

        // Filter by status
        $status = $this->getState('filter.status');
        if (!empty($status)) {
            $query->where('a.status = ' . $db->quote($status));
        }

        // Add ordering
        $orderCol = $this->state->get('list.ordering', 'a.created');
        $orderDirn = $this->state->get('list.direction', 'DESC');
        $query->order($db->escape($orderCol) . ' ' . $db->escape($orderDirn));

        return $query;
    }

    protected function populateState($ordering = null, $direction = null)
    {
        $app = JFactory::getApplication();

        $this->setState('filter.status', $app->getUserStateFromRequest($this->context . '.filter.status', 'filter_status', '', 'string'));

        parent::populateState('a.created', 'DESC');
    }
}
?>