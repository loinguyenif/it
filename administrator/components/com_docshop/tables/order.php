<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

class DocshopTableOrder extends JTable
{
    public function __construct($db)
    {
        parent::__construct('#__docshop_orders', 'id', $db);
    }

    public function check()
    {
        if (empty($this->status)) {
            $this->setError(JText::_('COM_DOCSHOP_ERROR_STATUS_REQUIRED'));
            return false;
        }

        return true;
    }
}
