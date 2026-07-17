<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

class DocshopViewDocument extends JViewLegacy
{
    protected $item;

    public function display($tpl = null)
    {
        $app   = JFactory::getApplication();
        $input = $app->input;

        $id = $input->getInt('id', 0);

        // getModel() on a view without a matching model registration returns null
        // — instantiate directly instead
        JModelLegacy::addIncludePath(JPATH_COMPONENT . '/models');
        /** @var DocshopModelDocuments $model */
        $model = JModelLegacy::getInstance('Documents', 'DocshopModel');
        $this->item = $model->getItem($id);

        if (!$this->item) {
            $app->redirect(
                JRoute::_('index.php?option=com_docshop&view=documents', false),
                JText::_('COM_DOCSHOP_DOCUMENT_NOT_FOUND'),
                'error'
            );
            return;
        }

        // Increment view_count — raw UPDATE, no model save (avoids modifying modified/modified_by)
        $db = JFactory::getDbo();
        $db->setQuery(
            'UPDATE ' . $db->quoteName('#__docshop_documents') .
            ' SET ' . $db->quoteName('view_count') . ' = ' . $db->quoteName('view_count') . ' + 1' .
            ' WHERE ' . $db->quoteName('id') . ' = ' . (int) $id
        );
        $db->execute();

        // Re-read updated view_count into item so the template shows the fresh value
        $this->item->view_count = (int) $this->item->view_count + 1;

        // Load category & platform titles if not already joined
        $db = JFactory::getDbo();

        if (!isset($this->item->category_title) && !empty($this->item->category_id)) {
            $q = $db->getQuery(true)
                ->select($db->quoteName('title'))
                ->from($db->quoteName('#__docshop_categories'))
                ->where($db->quoteName('id') . ' = ' . (int) $this->item->category_id);
            $db->setQuery($q);
            $this->item->category_title = (string) $db->loadResult();
        }

        if (!isset($this->item->platform_title) && !empty($this->item->platform_id)) {
            $q = $db->getQuery(true)
                ->select($db->quoteName('title'))
                ->from($db->quoteName('#__docshop_platforms'))
                ->where($db->quoteName('id') . ' = ' . (int) $this->item->platform_id);
            $db->setQuery($q);
            $this->item->platform_title = (string) $db->loadResult();
        }

        // Set page title & meta
        $document = JFactory::getDocument();
        $document->setTitle(htmlspecialchars($this->item->title));

        parent::display($tpl);
    }
}
