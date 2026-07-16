<?php
/**
 * @package     Joomla.Site
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

    public function display($tpl = null)
    {
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
            throw new \Exception(implode("\n", $errors), 500);
        }

        parent::display($tpl);
    }
}
?>