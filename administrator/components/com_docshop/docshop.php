<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

JLoader::register('DocshopHelper', JPATH_COMPONENT_ADMINISTRATOR . '/helpers/docshop.php');
JLoader::register('DocshopController', JPATH_COMPONENT_ADMINISTRATOR . '/controller.php');

DocshopHelper::addSubmenu(JFactory::getApplication()->input->getCmd('view', 'dashboard'));

$controller = JControllerLegacy::getInstance('Docshop');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
