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
    /**
     * Show the "payment successful" page.
     */
    public function download()
    {
        $app         = JFactory::getApplication();
        $orderNumber = $app->input->getString('order_number', '');

        // Fallback: session stores the order_number after confirm()
        if (empty($orderNumber)) {
            $orderNumber = JFactory::getSession()->get('com_docshop.order_number', '');
        }

        if (empty($orderNumber)) {
            $app->redirect(JRoute::_('index.php?option=com_docshop&view=documents', false), 'Order not found.', 'error');
            return;
        }

        $model = $this->getModel('download', 'DocshopModel');
        $order = $model->getOrderByNumber($orderNumber);

        if (!$order || $order->status !== 'completed') {
            $app->redirect(JRoute::_('index.php?option=com_docshop&view=documents', false), 'Order not completed.', 'error');
            return;
        }

        $view = $this->getView('download', 'html');
        $view->setModel($model, true);
        $view->display();
    }

    /**
     * Stream the actual binary file — called via task=download.stream.
     */
    public function stream()
    {
        // Flush all output buffers before anything else.
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        @ini_set('zlib.output_compression', 'Off');
        if (function_exists('apache_setenv')) {
            apache_setenv('no-gzip', 1);
        }

        $app         = JFactory::getApplication();
        $orderNumber = $app->input->getString('order_number', '');

        if (empty($orderNumber)) {
            $orderNumber = JFactory::getSession()->get('com_docshop.order_number', '');
        }

        if (empty($orderNumber)) {
            $app->redirect(JRoute::_('index.php?option=com_docshop&view=documents', false), 'Order not found.', 'error');
            return;
        }

        $model = $this->getModel('download', 'DocshopModel');
        $order = $model->getOrderByNumber($orderNumber);

        if (!$order || $order->status !== 'completed') {
            $app->redirect(JRoute::_('index.php?option=com_docshop&view=documents', false), 'Order not found or not completed.', 'error');
            return;
        }

        $document = $model->getDocument($order->document_id);

        if (!$document) {
            $app->redirect(JRoute::_('index.php?option=com_docshop&view=documents', false), 'Document not found.', 'error');
            return;
        }

        $filePath = JPATH_SITE . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR
                  . 'com_docshop' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR
                  . $document->file;

        if (!file_exists($filePath)) {
            $app->redirect(JRoute::_('index.php?option=com_docshop&view=documents', false), 'File not found on server.', 'error');
            return;
        }

        // Enforce 3-download limit per order.
        $maxDownloads = 3;
        if ((int) $order->download_count >= $maxDownloads) {
            $app->redirect(JRoute::_('index.php?option=com_docshop&view=documents', false), 'Download limit reached. This order has already been downloaded ' . $maxDownloads . ' times.', 'warning');
            return;
        }

        // Clear session and atomically increment download_count.
        JFactory::getSession()->clear('com_docshop.order_number');
        $model->markDownloaded($order->id);

        $this->streamFile($filePath, $document->title);
    }

    private function streamFile($filePath, $fileName)
    {
        $ext      = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $fileSize = filesize($filePath);

        $contentTypes = array(
            'pdf'  => 'application/pdf',
            'doc'  => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls'  => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'zip'  => 'application/zip',
        );

        $contentType = isset($contentTypes[$ext]) ? $contentTypes[$ext] : 'application/octet-stream';

        // Release session lock so the browser's parallel requests are not blocked.
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }

        // Safe filename: strip characters that break Content-Disposition.
        $safeFileName = preg_replace('/["\\\\\x00-\x1f]/', '', $fileName);
        if ($safeFileName === '') {
            $safeFileName = 'download';
        }

        header_remove(); // wipe any headers Joomla may have already queued
        header('Content-Type: ' . $contentType);
        header('Content-Disposition: attachment; filename="' . $safeFileName . '.' . $ext . '"');
        header('Content-Length: ' . $fileSize);
        header('Content-Transfer-Encoding: binary');
        header('Pragma: no-cache');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Expires: 0');

        $handle = fopen($filePath, 'rb');
        if ($handle === false) {
            http_response_code(500);
            exit('Could not open file for reading.');
        }

        // Stream in 1 MB chunks — safe for large files without memory exhaustion.
        while (!feof($handle)) {
            echo fread($handle, 1048576);
            flush();
        }

        fclose($handle);
        exit;
    }
}
?>