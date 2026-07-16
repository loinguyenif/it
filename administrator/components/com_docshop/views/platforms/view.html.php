<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

class DocshopViewPlatforms extends JViewLegacy
{
    protected $items;
    protected $pagination;
    protected $state;
    protected $sidebar;

    public function display($tpl = null)
    {
        $this->sidebar = JHtmlSidebar::render();
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
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
        JToolbarHelper::title(JText::_('COM_DOCSHOP_PLATFORMS'), 'folder');

        if (JFactory::getUser()->authorise('core.create', 'com_docshop')) {
            JToolbarHelper::addNew('platform.add');
        }

        if (JFactory::getUser()->authorise('core.edit', 'com_docshop')) {
            JToolbarHelper::editList('platform.edit');
        }

        if (JFactory::getUser()->authorise('core.delete', 'com_docshop')) {
            JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'platforms.delete');
        }
    }
}
