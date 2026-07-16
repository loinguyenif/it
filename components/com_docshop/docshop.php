<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

JLoader::register('DocshopController', JPATH_COMPONENT . '/controller.php');

$controller = JControllerLegacy::getInstance('Docshop');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
