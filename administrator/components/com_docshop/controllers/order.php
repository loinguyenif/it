<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

class DocshopControllerOrder extends JControllerForm
{
    protected $default_view = 'order';

    public function getModel($name = 'Order', $prefix = 'DocshopModel', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }

    public function cancel($key = null)
    {
        parent::cancel($key);
        $id = JFactory::getApplication()->input->getInt('id', 0);
        $this->setRedirect(JRoute::_(
            'index.php?option=com_docshop&view=order&id=' . $id, false
        ));
        return true;
    }
}
