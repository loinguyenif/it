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
     * JavaScript on the page auto-triggers the actual binary download via stream().
     */
    public function download()
    {
        $app     = JFactory::getApplication();
        $orderId = $app->input->getInt('id', 0);

        if (!$orderId) {
            $session = JFactory::getSession();
            $orderId = (int) $session->get('com_docshop.order_id', 0);
        }

        if (!$orderId) {
            $app->redirect(JRoute::_('index.php?option=com_docshop&view=documents', false), 'Order not found.', 'error');
            return;
        }

        // Verify the order exists and is paid before showing the success page.
        $model = $this->getModel('download', 'DocshopModel');
        $order = $model->getOrder($orderId);

        if (!$order || $order->status !== 'completed') {
            $app->redirect(JRoute::_('index.php?option=com_docshop&view=documents', false), 'Order not completed.', 'error');
            return;
        }

        // Render the success page; JS on that page triggers stream().
        $view = $this->getView('download', 'html');
        $view->setModel($model, true);
        $view->display();
    }

    /**
     * Stream the actual binary file — called directly via task=download.stream.
     * Must bypass the entire Joomla render pipeline and send raw binary output.
     */
    public function stream()
    {
        // Close ALL output buffers immediately — before Joomla can write anything.
        // This must be the very first thing we do so headers are not yet sent.
        while (ob_get_level() > 0) {
            ob_end_clean();
        }
        @ini_set('zlib.output_compression', 'Off');
        if (function_exists('apache_setenv')) {
            apache_setenv('no-gzip', 1);
        }

        $app     = JFactory::getApplication();
        $orderId = $app->input->getInt('id', 0);

        if (!$orderId) {
            $session = JFactory::getSession();
            $orderId = (int) $session->get('com_docshop.order_id', 0);
        }

        if (!$orderId) {
            http_response_code(400);
            exit('Order ID missing.');
        }

        $model = $this->getModel('download', 'DocshopModel');
        $order = $model->getOrder($orderId);

        if (!$order || $order->status !== 'completed') {
            http_response_code(403);
            exit('Order not found or not completed.');
        }

        $document = $model->getDocument($order->document_id);

        if (!$document) {
            http_response_code(404);
            exit('Document not found.');
        }

        $filePath = JPATH_SITE . DIRECTORY_SEPARATOR . 'media' . DIRECTORY_SEPARATOR
                  . 'com_docshop' . DIRECTORY_SEPARATOR . 'files' . DIRECTORY_SEPARATOR
                  . $document->file;

        if (!file_exists($filePath)) {
            http_response_code(404);
            exit('File not found on server.');
        }

        // Clear session fallback and record download time.
        JFactory::getSession()->clear('com_docshop.order_id');
        $model->markDownloaded($orderId);

        // Send binary file directly — no Joomla template involved.
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