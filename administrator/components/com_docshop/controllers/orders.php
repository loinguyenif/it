<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

class DocshopControllerOrders extends JControllerAdmin
{
    protected $text_prefix = 'COM_DOCSHOP';
    protected $default_view = 'orders';

    public function getModel($name = '', $prefix = '', $config = array())
    {
        $model = parent::getModel($name ?: 'order', $prefix, $config);
        return $model;
    }
}
?>