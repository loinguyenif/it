<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

class DocshopViewDocuments extends JViewLegacy
{
    protected $items;
    protected $pagination;
    protected $state;
    protected $categories;
    protected $platforms;
    protected $sidebar;

    public function display($tpl = null)
    {
        $this->sidebar = JHtmlSidebar::render();
        $this->items = $this->get('Items');
        $this->pagination = $this->get('Pagination');
        $this->state = $this->get('State');

        $db = JFactory::getDbo();

        $categoryQuery = $db->getQuery(true)
            ->select(array($db->quoteName('id', 'value'), $db->quoteName('title', 'text')))
            ->from($db->quoteName('#__docshop_categories'))
            ->where($db->quoteName('published') . ' = 1')
            ->order($db->quoteName('title') . ' ASC');
        $db->setQuery($categoryQuery);
        $this->categories = $db->loadObjectList();

        $platformQuery = $db->getQuery(true)
            ->select(array($db->quoteName('id', 'value'), $db->quoteName('title', 'text')))
            ->from($db->quoteName('#__docshop_platforms'))
            ->where($db->quoteName('published') . ' = 1')
            ->order($db->quoteName('title') . ' ASC');
        $db->setQuery($platformQuery);
        $this->platforms = $db->loadObjectList();

        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        $this->addToolbar();
        parent::display($tpl);
    }

    protected function addToolbar()
    {
        JToolbarHelper::title(JText::_('COM_DOCSHOP_DOCUMENTS'), 'folder');

        if (JFactory::getUser()->authorise('core.create', 'com_docshop')) {
            JToolbarHelper::addNew('document.add');
        }

        if (JFactory::getUser()->authorise('core.edit', 'com_docshop')) {
            JToolbarHelper::editList('document.edit');
        }

        if (JFactory::getUser()->authorise('core.delete', 'com_docshop')) {
            JToolbarHelper::deleteList('JGLOBAL_CONFIRM_DELETE', 'documents.delete');
        }

        JToolbarHelper::preferences('com_docshop');
    }
}
?>