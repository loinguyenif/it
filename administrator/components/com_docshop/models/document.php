<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class DocshopModelDocument extends JModelAdmin
{
    protected $option = 'com_docshop';

    public function getTable($name = 'Document', $prefix = 'DocshopTable', $options = array())
    {
        return parent::getTable($name, $prefix, $options);
    }

    public function getForm($data = array(), $loadData = true)
    {
        $form = $this->loadForm($this->option . '.document', 'document', array('control' => 'jform', 'load_data' => $loadData));

        if (empty($form)) {
            return false;
        }

        return $form;
    }

    protected function loadFormData()
    {
        $data = JFactory::getApplication()->getUserState($this->option . '.edit.document.data', array());

        if (empty($data)) {
            $data = $this->getItem();
        }

        return $data;
    }

    public function getItem($pk = null)
    {
        if ($item = parent::getItem($pk)) {
            // Convert price to proper format
            $item->price = number_format($item->price, 2, '.', '');
        }

        return $item;
    }

    public function save($data)
    {
        // Handle file upload
        if (!empty($_FILES['jform']['name']['file'])) {
            $file = $this->handleFileUpload($_FILES['jform']['tmp_name']['file'], $_FILES['jform']['name']['file']);

            if ($file === false) {
                return false;
            }

            if ($file) {
                $data['file'] = $file;
                $data['file_size'] = filesize(JPATH_SITE . '/media/com_docshop/files/' . $file);
            }
        }

        return parent::save($data);
    }

    private function handleFileUpload($tmpFile, $fileName)
    {
        $uploadDir = '/media/com_docshop/files/';
        $uploadPath = JPATH_SITE . $uploadDir;

        if (!JFolder::exists($uploadPath)) {
            JFolder::create($uploadPath);
        }

        $allowedTypes = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip');
        $fileExt = JFile::getExt($fileName);

        if (!in_array($fileExt, $allowedTypes)) {
            $this->setError(JText::_('COM_DOCSHOP_ERROR_FILE_TYPE_INVALID'));
            return false;
        }

        if ($_FILES['jform']['size']['file'] > 50 * 1024 * 1024) { // 50MB limit
            $this->setError(JText::_('COM_DOCSHOP_ERROR_FILE_TOO_LARGE'));
            return false;
        }

        $fileName = md5(uniqid(rand(), true)) . '.' . $fileExt;
        $filePath = $uploadPath . $fileName;

        if (JFile::upload($tmpFile, $filePath)) {
            return $fileName;
        }

        $this->setError(JText::_('COM_DOCSHOP_ERROR_FILE_UPLOAD_FAILED'));
        return false;
    }
}
?>
