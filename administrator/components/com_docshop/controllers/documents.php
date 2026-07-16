<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

class DocshopControllerDocuments extends JControllerAdmin
{
    protected $text_prefix = 'COM_DOCSHOP';
    protected $default_view = 'documents';

    public function __construct()
    {
        parent::__construct();
        $this->registerTask('apply', 'save');
    }

    public function getModel($name = 'Document', $prefix = 'DocshopModel', $config = array('ignore_request' => true))
    {
        $model = parent::getModel($name, $prefix, $config);
        return $model;
    }

    public function save()
    {
        $app = JFactory::getApplication();
        $input = $app->input;

        // Get the model
        $model = $this->getModel('document');

        // Get form data
        $data = $input->post->getArray();

        // Test if document is already in database
        $id = (int)$input->getInt('id');

        if ($model->save($data)) {
            $this->setMessage('COM_DOCSHOP_DOCUMENT_SAVED');
            
            if ($this->getTask() == 'apply') {
                $this->setRedirect('index.php?option=com_docshop&view=document&layout=edit&id=' . $model->getItem()->id);
            } else {
                $this->setRedirect('index.php?option=com_docshop&view=documents');
            }
        } else {
            $this->setMessage($model->getError(), 'error');
        }

        return true;
    }

    public function delete()
    {
        $app = JFactory::getApplication();
        $cid = $app->input->get('cid', array(), 'array');

        if ($this->getModel('document')->delete($cid)) {
            $this->setMessage('COM_DOCSHOP_DOCUMENTS_DELETED');
        } else {
            $this->setMessage('COM_DOCSHOP_DELETE_ERROR', 'error');
        }

        $this->setRedirect('index.php?option=com_docshop&view=documents');
    }
}
?>