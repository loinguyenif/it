<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

class DocshopControllerDocument extends JControllerForm
{
    protected $view_list = 'documents';

    public function __construct($config = array())
    {
        parent::__construct($config);
    }

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
        } else {
            return $user->authorise('core.create', 'com_docshop');
        }
    }

    protected function postSaveHook(JModelLegacy $model, $validData = array())
    {
        return;
    }
}
?>
