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
    /** Expiry window in seconds (5 minutes) */
    const TOKEN_TTL = 300;

    /**
     * Generate a signed download token.
     * Format: base64( orderId '|' expireAt '|' hmac )
     */
    public static function generateToken($orderId)
    {
        $secret   = JFactory::getConfig()->get('secret');
        $expireAt = time() + self::TOKEN_TTL;
        $payload  = (int) $orderId . '|' . $expireAt;
        $hmac     = hash_hmac('sha256', $payload, $secret);
        return base64_encode($payload . '|' . $hmac);
    }

    /**
     * Verify token — returns orderId on success, false on failure/expiry.
     */
    private function verifyToken($token)
    {
        $decoded = base64_decode($token, true);
        if ($decoded === false) {
            return false;
        }

        $parts = explode('|', $decoded);
        if (count($parts) !== 3) {
            return false;
        }

        list($orderId, $expireAt, $hmac) = $parts;

        if (time() > (int) $expireAt) {
            return false;
        }

        $secret   = JFactory::getConfig()->get('secret');
        $payload  = (int) $orderId . '|' . (int) $expireAt;
        $expected = hash_hmac('sha256', $payload, $secret);

        if (!hash_equals($expected, $hmac)) {
            return false;
        }

        return (int) $orderId;
    }

    /**
     * secure() — download via signed token (no login required).
     * URL: index.php?option=com_docshop&task=download.secure&token=XXX
     */
    public function secure()
    {
        $app   = JFactory::getApplication();
        $token = $app->input->getString('token', '');

        $orderId = $this->verifyToken($token);

        if ($orderId === false) {
            $app->redirect(
                JRoute::_('index.php?option=com_docshop&view=documents', false),
                JText::_('COM_DOCSHOP_DOWNLOAD_LINK_EXPIRED'),
                'error'
            );
            return;
        }

        JModelLegacy::addIncludePath(JPATH_COMPONENT . '/models');
        $model = JModelLegacy::getInstance('Download', 'DocshopModel');

        $order = $model->getOrder($orderId);

        if (!$order || $order->status !== 'completed') {
            $app->redirect(
                JRoute::_('index.php?option=com_docshop&view=documents', false),
                JText::_('COM_DOCSHOP_DOWNLOAD_NOT_AUTHORIZED'),
                'error'
            );
            return;
        }

        $document = $model->getDocument($order->document_id);

        if (!$document) {
            throw new \Exception('Document not found.', 404);
        }

        $filePath = JPATH_SITE . '/media/com_docshop/files/' . $document->file;

        if (!file_exists($filePath)) {
            throw new \Exception('File not found on server.', 404);
        }

        $this->streamFile($filePath, $document->title);
    }

    /**
     * download() — legacy direct download via order id (no login required).
     * URL: index.php?option=com_docshop&task=download.download&id=X
     */
    public function download()
    {
        $app     = JFactory::getApplication();
        $orderId = $app->input->getInt('id', 0);

        if (!$orderId) {
            $orderId = (int) JFactory::getSession()->get('com_docshop.order_id', 0);
        }

        JModelLegacy::addIncludePath(JPATH_COMPONENT . '/models');
        $model = JModelLegacy::getInstance('Download', 'DocshopModel');

        if (!$model) {
            throw new \Exception('Could not load download model.', 500);
        }

        $order = $model->getOrder($orderId);

        if (!$order || $order->status !== 'completed') {
            $app->redirect(
                JRoute::_('index.php?option=com_docshop&view=documents', false),
                JText::_('COM_DOCSHOP_DOWNLOAD_NOT_AUTHORIZED'),
                'error'
            );
            return;
        }

        $document = $model->getDocument($order->document_id);

        if (!$document) {
            throw new \Exception('Document not found.', 404);
        }

        $filePath = JPATH_SITE . '/media/com_docshop/files/' . $document->file;

        if (!file_exists($filePath)) {
            throw new \Exception('File not found on server.', 404);
        }

        JFactory::getSession()->clear('com_docshop.order_id');

        $this->streamFile($filePath, $document->title);
    }

    private function streamFile($filePath, $fileName)
    {
        $ext   = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $types = array(
            'pdf'  => 'application/pdf',
            'doc'  => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls'  => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'ppt'  => 'application/vnd.ms-powerpoint',
            'pptx' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
            'zip'  => 'application/zip',
            'rar'  => 'application/x-rar-compressed',
            '7z'   => 'application/x-7z-compressed',
        );

        $contentType = isset($types[$ext]) ? $types[$ext] : 'application/octet-stream';

        while (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Type: ' . $contentType);
        header('Content-Disposition: attachment; filename="' . addslashes($fileName) . '.' . $ext . '"');
        header('Content-Length: ' . filesize($filePath));
        header('Pragma: no-cache');
        header('Cache-Control: no-cache, no-store, must-revalidate');
        header('Expires: 0');

        readfile($filePath);
        jexit();
    }
}
