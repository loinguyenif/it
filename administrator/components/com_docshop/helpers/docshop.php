<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

class DocshopHelper
{
    public static function addSubmenu($vName = 'dashboard')
    {
        JHtmlSidebar::addEntry(
            JText::_('COM_DOCSHOP_DASHBOARD'),
            'index.php?option=com_docshop&view=dashboard',
            $vName === 'dashboard'
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_DOCSHOP_DOCUMENTS'),
            'index.php?option=com_docshop&view=documents',
            $vName === 'documents'
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_DOCSHOP_CATEGORIES'),
            'index.php?option=com_docshop&view=categories',
            $vName === 'categories'
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_DOCSHOP_PLATFORMS'),
            'index.php?option=com_docshop&view=platforms',
            $vName === 'platforms'
        );

        JHtmlSidebar::addEntry(
            JText::_('COM_DOCSHOP_ORDERS'),
            'index.php?option=com_docshop&view=orders',
            $vName === 'orders'
        );
    }
}
