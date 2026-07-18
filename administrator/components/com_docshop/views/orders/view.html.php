<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

class DocshopViewOrders extends JViewLegacy
{
    protected $items;
    protected $pagination;
    protected $state;
    protected $sidebar;

    public function display($tpl = null)
    {
        $this->sidebar    = JHtmlSidebar::render();
        $this->items      = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state      = $this->get('State');

        JToolbarHelper::title(JText::_('COM_DOCSHOP_ORDERS'), 'cart');
        JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'orders.delete', 'JTOOLBAR_DELETE');

        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        parent::display($tpl);
    }
}
?>