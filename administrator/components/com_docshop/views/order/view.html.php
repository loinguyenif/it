<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

class DocshopViewOrder extends JViewLegacy
{
    protected $item;

    public function display($tpl = null)
    {
        $this->item = $this->get('Item');

        if ($this->item === false) {
            JFactory::getApplication()->redirect(
                JRoute::_('index.php?option=com_docshop&view=orders', false),
                JText::_('COM_DOCSHOP_ORDER_NOT_FOUND'),
                'error'
            );
            return;
        }

        $this->addToolbar();

        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        JToolbarHelper::title(
            JText::sprintf('COM_DOCSHOP_ORDER_DETAIL_TITLE', $this->item->order_number),
            'cart'
        );

        // Print Invoice button — opens PDF download in new tab
        $invoiceUrl = JRoute::_(
            'index.php?option=com_docshop&task=orders.invoice&id=' . (int) $this->item->id . '&format=raw',
            false
        );
        JFactory::getDocument()->addScriptDeclaration(
            "function docshopPrintInvoice(){ window.open('" . $invoiceUrl . "', '_blank'); }"
        );
        $bar = JToolbar::getInstance('toolbar');
        $bar->appendButton('Custom',
            '<button type="button" class="btn btn-small" onclick="docshopPrintInvoice();">'
            . '<span class="icon-print"></span> ' . JText::_('COM_DOCSHOP_PRINT_INVOICE')
            . '</button>'
        );

        JToolbarHelper::back('JTOOLBAR_BACK', 'index.php?option=com_docshop&view=orders');
    }
}
