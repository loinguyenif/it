<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

class DocshopViewCategory extends JViewLegacy
{
    protected $form;
    protected $item;
    protected $state;

    public function display($tpl = null)
    {
        $this->form = $this->get('Form');
        $this->item = $this->get('Item');
        $this->state = $this->get('State');

        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        $this->addToolbar();

        parent::display($tpl);
    }

    protected function addToolbar()
    {
        JFactory::getApplication()->input->set('hidemainmenu', true);

        $isNew = ((int) $this->item->id === 0);

        JToolbarHelper::title($isNew ? JText::_('COM_DOCSHOP_CATEGORY_NEW') : JText::_('COM_DOCSHOP_CATEGORY_EDIT'), 'pencil-2');
        JToolbarHelper::apply('category.apply');
        JToolbarHelper::save('category.save');
        JToolbarHelper::save2new('category.save2new');

        if (!$isNew) {
            JToolbarHelper::save2copy('category.save2copy');
        }

        JToolbarHelper::cancel('category.cancel');
    }
}
