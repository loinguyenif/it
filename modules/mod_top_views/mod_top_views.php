<?php
/**
 * @package     mod_top_views
 * @subpackage  Modules
 * @copyright   Copyright (C) 2026
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

require_once __DIR__ . '/helper.php';

$items       = ModTopViewsHelper::getItems($params);
$moduleclass = htmlspecialchars($params->get('moduleclass_sfx', ''));
$suffix      = htmlspecialchars($params->get('views_suffix', 'views'));

require JModuleHelper::getLayoutPath('mod_top_views', $params->get('layout', 'default'));
