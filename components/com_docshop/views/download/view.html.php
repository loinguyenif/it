<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

// Load the download controller so we can call generateToken()
require_once JPATH_COMPONENT . '/controllers/download.php';

class DocshopViewDownload extends JViewLegacy
{
    protected $order;
    protected $document;
    protected $token;
    protected $expireAt;

    public function display($tpl = null)
    {
        $app  = JFactory::getApplication();
        $user = JFactory::getUser();

        if ($user->guest) {
            $app->redirect(
                JRoute::_('index.php?option=com_users&view=login', false),
                JText::_('COM_DOCSHOP_PLEASE_LOGIN'),
                'warning'
            );
            return;
        }

        $orderId = $app->input->getInt('id', 0);

        if (!$orderId) {
            $orderId = (int) JFactory::getSession()->get('com_docshop.order_id', 0);
        }

        if (!$orderId) {
            $app->redirect(
                JRoute::_('index.php?option=com_docshop&view=documents', false),
                JText::_('COM_DOCSHOP_DOWNLOAD_NOT_AUTHORIZED'),
                'error'
            );
            return;
        }

        JModelLegacy::addIncludePath(JPATH_COMPONENT . '/models');
        $model = JModelLegacy::getInstance('Download', 'DocshopModel');

        $order = $model->getOrder($orderId);

        if (!$order || (int) $order->user_id !== (int) $user->id || $order->status !== 'completed') {
            $app->redirect(
                JRoute::_('index.php?option=com_docshop&view=documents', false),
                JText::_('COM_DOCSHOP_DOWNLOAD_NOT_AUTHORIZED'),
                'error'
            );
            return;
        }

        $document = $model->getDocument($order->document_id);

        if (!$document) {
            $app->redirect(
                JRoute::_('index.php?option=com_docshop&view=documents', false),
                JText::_('COM_DOCSHOP_DOCUMENT_NOT_FOUND'),
                'error'
            );
            return;
        }

        // Generate a 5-minute signed token for the download link
        $this->token    = DocshopControllerDownload::generateToken($orderId);
        $this->expireAt = time() + DocshopControllerDownload::TOKEN_TTL;
        $this->order    = $order;
        $this->document = $document;

        // Set page title
        JFactory::getDocument()->setTitle(JText::_('COM_DOCSHOP_DOWNLOAD_SUCCESS_TITLE'));

        parent::display($tpl);
    }
}
