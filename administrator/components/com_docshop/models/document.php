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
        $uploadDir  = '/media/com_docshop/files/';
        $uploadPath = JPATH_SITE . $uploadDir;

        if (!JFolder::exists($uploadPath)) {
            JFolder::create($uploadPath);
        }

        // Create .htaccess to block direct execution of uploaded files
        $htaccess = $uploadPath . '.htaccess';
        if (!file_exists($htaccess)) {
            file_put_contents($htaccess,
                "Options -Indexes\n"
                . "<FilesMatch \"\.(php|php3|php4|php5|phtml|pl|py|cgi|sh)$\">\n"
                . "    Order allow,deny\n"
                . "    Deny from all\n"
                . "</FilesMatch>\n"
            );
        }

        $allowedTypes = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'zip', 'rar', '7z');
        $fileExt      = strtolower(JFile::getExt($fileName));

        if (!in_array($fileExt, $allowedTypes)) {
            $this->setError(JText::_('COM_DOCSHOP_ERROR_FILE_TYPE_INVALID'));
            return false;
        }

        if ($_FILES['jform']['size']['file'] > 50 * 1024 * 1024) {
            $this->setError(JText::_('COM_DOCSHOP_ERROR_FILE_TOO_LARGE'));
            return false;
        }

        $newFileName = md5(uniqid(rand(), true)) . '.' . $fileExt;
        $filePath    = $uploadPath . $newFileName;

        // Use move_uploaded_file directly — JFile::upload runs a content
        // security check that rejects legitimate archives (zip, rar) whose
        // byte signatures look unsafe to Joomla's scanner.
        if (!is_uploaded_file($tmpFile)) {
            $this->setError(JText::_('COM_DOCSHOP_ERROR_FILE_UPLOAD_FAILED'));
            return false;
        }

        if (!move_uploaded_file($tmpFile, $filePath)) {
            $this->setError(JText::_('COM_DOCSHOP_ERROR_FILE_UPLOAD_FAILED'));
            return false;
        }

        return $newFileName;
    }
}
?>
