<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

class DocshopModelOrder extends JModelAdmin
{
    protected $option = 'com_docshop';

    public function getTable($name = 'Order', $prefix = 'DocshopTable', $options = array())
    {
        return parent::getTable($name, $prefix, $options);
    }

    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm(
            $this->option . '.order',
            'order',
            array('control' => 'jform', 'load_data' => $loadData)
        );

        return empty($form) ? false : $form;
    }

    protected function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState($this->option . '.edit.order.data', array());

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    public function getItem($pk = null)
    {
        $pk = (!empty($pk)) ? (int) $pk : (int) $this->getState($this->getName() . '.id');

        if ($this->_item === null) {
            $this->_item = array();
        }

        if (!isset($this->_item[$pk])) {
            try {
                $db    = $this->getDbo();
                $query = $db->getQuery(true)
                    ->select(array(
                        'a.*',
                        'u.name     AS user_name',
                        'u.email    AS user_email',
                        'u.username AS user_username',
                        'd.title    AS document_title',
                        'd.version  AS document_version',
                        'd.alias    AS document_alias',
                        'd.file     AS document_file',
                        'd.price    AS document_price',
                    ))
                    ->from($db->quoteName('#__docshop_orders', 'a'))
                    ->join('LEFT', $db->quoteName('#__users', 'u')
                        . ' ON ' . $db->quoteName('a.user_id') . ' = ' . $db->quoteName('u.id'))
                    ->join('LEFT', $db->quoteName('#__docshop_documents', 'd')
                        . ' ON ' . $db->quoteName('a.document_id') . ' = ' . $db->quoteName('d.id'))
                    ->where($db->quoteName('a.id') . ' = ' . $pk);

                $db->setQuery($query);
                $data = $db->loadObject();

                if (empty($data)) {
                    throw new Exception(JText::_('COM_DOCSHOP_ORDER_NOT_FOUND'), 404);
                }

                $this->_item[$pk] = $data;

            } catch (Exception $e) {
                $this->setError($e);
                $this->_item[$pk] = false;
            }
        }

        return $this->_item[$pk];
    }

    protected function populateState()
    {
        $app = JFactory::getApplication();
        $id  = $app->input->getInt('id', 0);
        $this->setState($this->getName() . '.id', $id);
        parent::populateState();
    }

    public function delete(&$pks)
    {
        $pks = (array) $pks;
        $db  = $this->getDbo();

        foreach ($pks as $pk) {
            $query = $db->getQuery(true)
                ->delete($db->quoteName('#__docshop_orders'))
                ->where($db->quoteName('id') . ' = ' . (int) $pk);
            $db->setQuery($query);

            try {
                $db->execute();
            } catch (Exception $e) {
                $this->setError($e->getMessage());
                return false;
            }
        }

        return true;
    }
}
