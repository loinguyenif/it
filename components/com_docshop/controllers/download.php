<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

class DocshopControllerDownload extends JControllerLegacy
{
    public function download()
    {
        $app = JFactory::getApplication();
        $user = JFactory::getUser();
        $orderId = $app->input->getInt('id');

        if ($user->guest) {
            $app->redirect(JRoute::_('index.php?option=com_users&view=login', false), 'Log in to download', 'warning');
            return;
        }

        if (!$orderId) {
            $session = JFactory::getSession();
            $orderId = (int) $session->get('com_docshop.order_id', 0);
        }

        $model = $this->getModel('download', 'DocshopModel');
        $order = $model->getOrder($orderId);

        // Verify ownership and payment status
        if (!$order || $order->user_id != $user->id || $order->status !== 'completed') {
            $app->redirect(JRoute::_('index.php?option=com_docshop&view=documents', false), 'Order not found or not authorized', 'error');
            return;
        }

        $document = $model->getDocument($order->document_id);

        if (!$document) {
            throw new \Exception('Document not found', 404);
        }

        // Get file
        $filePath = JPATH_SITE . '/media/com_docshop/files/' . $document->file;

        if (!file_exists($filePath)) {
            throw new \Exception('File not found', 404);
        }

        // Update download count
        $model->updateDownload($orderId);

        // Clear fallback session value after successful download initiation
        $session = JFactory::getSession();
        $session->clear('com_docshop.order_id');

        // Stream file
        $this->streamFile($filePath, $document->title);
    }

    private function streamFile($filePath, $fileName)
    {
        $file = basename($filePath);
        $fileSize = filesize($filePath);

        // Determine content type
        $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $contentTypes = array(
            'pdf' => 'application/pdf',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'zip' => 'application/zip',
        );

        $contentType = $contentTypes[$ext] ?? 'application/octet-stream';

        header('Content-Type: ' . $contentType);
        header('Content-Disposition: attachment; filename="' . $fileName . '.' . $ext . '"');
        header('Content-Length: ' . $fileSize);
        header('Pragma: no-cache');
        header('Cache-Control: no-cache, no-store, must-revalidate');

        readfile($filePath);
        exit;
    }
}
?>