<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

class DocshopControllerCategory extends JControllerForm
{
    protected $view_list = 'categories';

    protected function allowAdd($data = array())
    {
        $user = JFactory::getUser();

        return $user->authorise('core.create', 'com_docshop');
    }

    protected function allowEdit($data = array(), $key = 'id')
    {
        $recordId = isset($data[$key]) ? (int) $data[$key] : 0;
        $user = JFactory::getUser();

        if ($recordId) {
            return $user->authorise('core.edit', 'com_docshop');
        }

        return $user->authorise('core.create', 'com_docshop');
    }
}
