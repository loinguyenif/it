<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

class DocshopViewDashboard extends JViewLegacy
{
    protected $sidebar;

    public function display($tpl = null)
    {
        $this->sidebar = JHtmlSidebar::render();

        // Page title + icon in the admin toolbar bar
        JToolbarHelper::title(JText::_('COM_DOCSHOP_DASHBOARD'), 'home');

        // Load dashboard CSS
        JFactory::getDocument()->addStyleSheet(
            JUri::root(true) . '/media/com_docshop/css/admin/dashboard.css'
        );

        if (count($errors = $this->get('Errors'))) {
            JError::raiseError(500, implode("\n", $errors));
            return false;
        }

        parent::display($tpl);
    }
}
?>