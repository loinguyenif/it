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
    protected $form;

    public function display($tpl = null)
    {
        $layout = JFactory::getApplication()->input->getCmd('layout', 'default');

        if ($layout === 'edit') {
            $this->form = $this->get('Form');
            $this->item = $this->get('Item');
            $this->addToolbarEdit();
        } else {
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
        }

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

        // Edit button
        $bar = JToolbar::getInstance('toolbar');
        $editUrl = JRoute::_('index.php?option=com_docshop&view=order&layout=edit&id=' . (int) $this->item->id, false);
        $bar->appendButton('Custom',
            '<a href="' . $editUrl . '" class="btn btn-small">'
            . '<span class="icon-edit"></span> ' . JText::_('JTOOLBAR_EDIT')
            . '</a>'
        );

        // Print Invoice button
        $invoiceUrl = JRoute::_(
            'index.php?option=com_docshop&task=invoice.generate&id=' . (int) $this->item->id,
            false
        );
        JFactory::getDocument()->addScriptDeclaration(
            "function docshopPrintInvoice(){ window.open('" . $invoiceUrl . "', '_blank'); }"
        );
        $bar->appendButton('Custom',
            '<button type="button" class="btn btn-small" onclick="docshopPrintInvoice();">'
            . '<span class="icon-print"></span> ' . JText::_('COM_DOCSHOP_PRINT_INVOICE')
            . '</button>'
        );

        JToolbarHelper::back('JTOOLBAR_BACK', 'index.php?option=com_docshop&view=orders');
    }

    protected function addToolbarEdit()
    {
        JToolbarHelper::title(
            JText::sprintf('COM_DOCSHOP_ORDER_EDIT_TITLE', $this->item->order_number),
            'cart'
        );
        JToolbarHelper::apply('order.apply');
        JToolbarHelper::save('order.save');
        JToolbarHelper::cancel('order.cancel', 'JTOOLBAR_CLOSE');
    }
}
