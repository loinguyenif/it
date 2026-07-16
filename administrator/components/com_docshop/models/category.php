<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

class DocshopModelCategory extends JModelAdmin
{
    protected $text_prefix = 'COM_DOCSHOP';

    public function getTable($name = 'Category', $prefix = 'DocshopTable', $options = array())
    {
        return parent::getTable($name, $prefix, $options);
    }

    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm('com_docshop.category', 'category', array('control' => 'jform', 'load_data' => $loadData));

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    protected function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState('com_docshop.edit.category.data', array());

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }
}
