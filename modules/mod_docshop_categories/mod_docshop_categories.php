<?php
/**
 * @package     mod_docshop_categories
 * @subpackage  Modules
 * @copyright   Copyright (C) 2026
 * @license     GNU General Public License version 2 or later
 */

// Không cho phép truy cập trực tiếp
defined('_JEXEC') or die;

require_once __DIR__ . '/helper.php';

$items       = ModDocshopCategoriesHelper::getItems($params);
$moduleclass = htmlspecialchars($params->get('moduleclass_sfx', ''));
$suffix      = htmlspecialchars($params->get('downloads_suffix', 'downloads'));

require JModuleHelper::getLayoutPath('mod_docshop_categories', $params->get('layout', 'default'));
