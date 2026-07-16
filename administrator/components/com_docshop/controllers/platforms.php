<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

class DocshopControllerPlatforms extends JControllerAdmin
{
    protected $text_prefix = 'COM_DOCSHOP';
    protected $default_view = 'platforms';

    public function getModel($name = 'Platform', $prefix = 'DocshopModel', $config = array('ignore_request' => true))
    {
        return parent::getModel($name, $prefix, $config);
    }
}
