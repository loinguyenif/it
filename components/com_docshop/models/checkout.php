<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

class DocshopModelCheckout extends JModelLegacy
{
    public function getItem($pk = null)
    {
        $app = JFactory::getApplication();
        $pk = $pk ?: $app->input->getInt('id', $app->input->getInt('document_id'));

        if (!$pk) {
            return false;
        }

        $db = $this->getDbo();
        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->quoteName('#__docshop_documents'))
            ->where($db->quoteName('id') . ' = ' . (int) $pk)
            ->where($db->quoteName('published') . ' = 1');

        $db->setQuery($query);

        return $db->loadObject();
    }

    public function createOrder($documentId, $payment, $currency)
    {
        $user = JFactory::getUser();
        $db = $this->getDbo();

        // Get transaction
        $transactions = $payment->getTransactions();
        $transaction = $transactions[0];
        $amount = $transaction->getAmount();
        $total = $amount->getTotal();

        // Get PayPal transaction ID
        $relatedResources = $transaction->getRelatedResources();
        $relatedResource = $relatedResources[0];
        $sale = $relatedResource->getSale();
        $paypalTransactionId = $sale->getId();

        // Create order
        $orderData = array(
            'user_id' => $user->id,
            'document_id' => $documentId,
            'order_number' => uniqid('ORD-'),
            'paypal_transaction_id' => $paypalTransactionId,
            'amount' => $total,
            'currency' => $currency,
            'status' => 'completed',
            'payment_method' => 'paypal',
            'created' => JFactory::getDate()->toSql()
        );

        $query = $db->getQuery(true)
            ->insert($db->quoteName('#__docshop_orders'))
            ->columns(array_keys($orderData))
            ->values(implode(',', array_map(array($db, 'quote'), $orderData)));

        $db->setQuery($query);
        $db->execute();

        $orderId = (int) $db->insertid();
        if ($orderId === 0) {
            $db->setQuery('SELECT LAST_INSERT_ID()');
            $orderId = (int) $db->loadResult();
        }

        $order = new \stdClass();
        $order->id = $orderId;
        $order->order_number = $orderData['order_number'];
        $order->status = 'completed';

        return $order;
    }
}
?>
