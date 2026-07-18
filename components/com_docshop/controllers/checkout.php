<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

spl_autoload_register(function ($class) {
    if (strpos($class, 'PayPal\\') === 0) {
        $file = JPATH_LIBRARIES . '/vendor/PayPal/' . str_replace('\\', '/', substr($class, 6)) . '.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

class DocshopControllerCheckout extends JControllerLegacy
{
    public function processPayment()
    {
        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));

        $app = JFactory::getApplication();

        $documentId   = $app->input->getInt('document_id');
        $customAmount = $app->input->getFloat('custom_amount', 0);
        $params       = $app->getParams('com_docshop');

        // Get document
        JModelLegacy::addIncludePath(JPATH_COMPONENT_SITE . '/models');
        $docModel = JModelLegacy::getInstance('Documents', 'DocshopModel');
        $document = $docModel->getItem($documentId);

        if (!$document) {
            $app->redirect(JRoute::_('index.php?option=com_docshop&view=documents', false), 'Document not found', 'error');
            return;
        }

        // Determine final amount:
        // - If a custom_amount was submitted (donate page), use it
        // - Otherwise fall back to the document's fixed price
        if ($customAmount > 0) {
            $finalAmount = round($customAmount, 2);
        } else {
            $finalAmount = (float) $document->price;
        }

        if ($finalAmount <= 0) {
            $app->redirect(JRoute::_('index.php?option=com_docshop&view=documents', false), 'Invalid payment amount.', 'error');
            return;
        }

        // Initialize PayPal
        $apiContext = $this->getApiContext($params);

        // Create payment
        $payer = new \PayPal\Api\Payer();
        $payer->setPaymentMethod("paypal");

        $amount = new \PayPal\Api\Amount();
        $amount->setTotal(number_format($finalAmount, 2, '.', ''));
        $amount->setCurrency($params->get('store_currency', 'USD'));

        $transaction = new \PayPal\Api\Transaction();
        $transaction->setAmount($amount);
        $transaction->setDescription("Purchase: " . $document->title);
        $transaction->setInvoiceNumber(uniqid());

        $redirectUrls = new \PayPal\Api\RedirectUrls();
        $redirectUrls->setReturnUrl($this->getReturnUrl($documentId))
                     ->setCancelUrl($this->getCancelUrl());

        $payment = new \PayPal\Api\Payment();
        $payment->setIntent("sale")
                ->setPayer($payer)
                ->setRedirectUrls($redirectUrls)
                ->setTransactions(array($transaction));

        try {
            $payment->create($apiContext);
            $approvalLink = $payment->getApprovalLink();
            $app->redirect($approvalLink);
        } catch (\PayPal\Exception\PPConnectionException $ex) {
            $app->redirect(JRoute::_('index.php?option=com_docshop&view=documents', false), 'Payment error: ' . $ex->getMessage(), 'error');
        }
    }

    public function confirm()
    {
        $app        = JFactory::getApplication();
        $paymentId  = $app->input->getString('paymentId');
        $payerId    = $app->input->getString('PayerID');
        $documentId = $app->input->getInt('document_id');

        $params     = $app->getParams('com_docshop');
        $apiContext = $this->getApiContext($params);

        try {
            // Execute payment
            $payment   = \PayPal\Api\Payment::get($paymentId, $apiContext);
            $execution = new \PayPal\Api\PaymentExecution();
            $execution->setPayerId($payerId);
            $executedPayment = $payment->execute($execution, $apiContext);

            // Load checkout model directly — getModel() on a legacy controller
            // does not search site component model paths automatically
            JModelLegacy::addIncludePath(JPATH_COMPONENT . '/models');
            $orderModel = JModelLegacy::getInstance('Checkout', 'DocshopModel');

            if (!$orderModel) {
                throw new Exception('Could not load checkout model.');
            }

            $order = $orderModel->createOrder(
                $documentId,
                $executedPayment,
                $params->get('store_currency', 'USD')
            );

            if (!$order || !$order->id) {
                throw new Exception('Order could not be saved.');
            }

            // Store order id in session as fallback
            JFactory::getSession()->set('com_docshop.order_id', $order->id);

            // Redirect to success page — success page auto-triggers download
            // and shows a 5-minute signed download link
            $app->redirect(
                JRoute::_('index.php?option=com_docshop&view=download&layout=success&id=' . (int) $order->id, false),
                JText::_('COM_DOCSHOP_PAYMENT_SUCCESS'),
                'success'
            );

        } catch (Exception $ex) {
            // Log full error for debugging
            JLog::addLogger(array('text_file' => 'com_docshop.errors.php'), JLog::ALL, array('com_docshop'));
            JLog::add('checkout.confirm() failed: ' . $ex->getMessage() . ' | Trace: ' . $ex->getTraceAsString(), JLog::ERROR, 'com_docshop');

            $app->redirect(
                JRoute::_('index.php?option=com_docshop&view=documents', false),
                'Payment confirmation failed: ' . $ex->getMessage(),
                'error'
            );
        }
    }

    private function getApiContext($params)
    {
        $mode = $params->get('paypal_mode', 'sandbox');
        $clientId = $params->get('paypal_client_id');
        $clientSecret = $params->get('paypal_client_secret');

        if (empty($clientId) || empty($clientSecret)) {
            throw new Exception('PayPal credentials not configured');
        }

        $apiContext = new \PayPal\Rest\ApiContext(new \PayPal\Auth\OAuthTokenCredential($clientId, $clientSecret));
        $apiContext->setConfig(array(
            'mode'                   => $mode === 'sandbox' ? 'sandbox' : 'live',
            'http.ConnectionTimeOut' => 30,
            'http.Retry'             => 1,
            // Explicit CA bundle — required on Windows/WampServer where the
            // system cert store is not used by PHP curl by default
            'http.CURLOPT_SSLVERSION'        => 'CURL_SSLVERSION_TLSv1_2',
            'http.CURLOPT_SSL_VERIFYPEER'    => true,
            'http.CURLOPT_CAINFO'            => str_replace('\\', '/', ini_get('curl.cainfo'))
                ?: str_replace('\\', '/', JPATH_ROOT . '/libraries/vendor/paypal/rest-api-sdk-php/lib/PayPal/cacert.pem'),
            'log.LogEnabled'  => true,
            'log.FileName'    => JPATH_ROOT . '/logs/paypal.log',
            'log.LogLevel'    => 'INFO',
        ));

        return $apiContext;
    }

    private function getReturnUrl($documentId)
    {
        return JRoute::_('index.php?option=com_docshop&task=checkout.confirm&document_id=' . $documentId, false, -1);
    }

    private function getCancelUrl()
    {
        return JRoute::_('index.php?option=com_docshop&view=documents', false, -1);
    }
}
?>
