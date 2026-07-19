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

    /**
     * Look up an existing completed order by PayPal payment ID.
     * Returns a stdClass order object or null if not found.
     */
    public function getOrderByPaymentId($paymentId)
    {
        if (empty($paymentId)) {
            return null;
        }

        $db    = $this->getDbo();
        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->quoteName('#__docshop_orders'))
            ->where($db->quoteName('paypal_payment_id') . ' = ' . $db->quote((string) $paymentId))
            ->where($db->quoteName('status') . ' = ' . $db->quote('completed'));

        $db->setQuery($query);

        return $db->loadObject() ?: null;
    }

    public function createOrder($documentId, $payment, $currency)
    {
        $user = JFactory::getUser();
        $db   = $this->getDbo();

        // ---- Extract PayPal transaction data ----
        $transactions = $payment->getTransactions();
        if (empty($transactions)) {
            throw new Exception('PayPal payment has no transactions.');
        }

        $transaction = $transactions[0];
        $amountObj   = $transaction->getAmount();
        $total       = $amountObj->getTotal();

        // getRelatedResources() may be null/empty on first execute response;
        // fall back to an empty string rather than fatal-erroring
        $paypalTransactionId = '';
        $relatedResources    = $transaction->getRelatedResources();
        if (!empty($relatedResources)) {
            $sale = $relatedResources[0]->getSale();
            if ($sale) {
                $paypalTransactionId = (string) $sale->getId();
            }
        }

        $paypalPaymentId = (string) $payment->getId();

        // ---- Idempotency: return existing order if this payment was already processed ----
        $existing = $this->getOrderByPaymentId($paypalPaymentId);
        if ($existing) {
            return $existing;
        }

        // ---- Build INSERT using individual bindings for correct typing ----
        $orderNumber = 'ORD-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 10));

        // user_id = NULL for guest, actual id for logged-in users
        $userId = JFactory::getUser()->id ?: null;

        $query = $db->getQuery(true)
            ->insert($db->quoteName('#__docshop_orders'))
            ->columns($db->quoteName(array(
                'user_id', 'document_id', 'order_number',
                'paypal_payment_id', 'paypal_transaction_id', 'amount', 'currency',
                'status', 'payment_method', 'created',
            )))
            ->values(implode(',', array(
                $userId === null ? 'NULL' : (int) $userId,
                (int)    $documentId,
                $db->quote($orderNumber),
                $db->quote($paypalPaymentId),
                $db->quote($paypalTransactionId),
                $db->quote((string) $total),
                $db->quote((string) $currency),
                $db->quote('completed'),
                $db->quote('paypal'),
                $db->quote(JFactory::getDate()->toSql()),
            )));

        $db->setQuery($query);

        try {
            $db->execute();
        } catch (Exception $e) {
            throw new Exception('Failed to save order: ' . $e->getMessage());
        }

        $orderId = (int) $db->insertid();

        if ($orderId === 0) {
            throw new Exception('Order INSERT succeeded but returned no ID.');
        }

        $order                       = new \stdClass();
        $order->id                   = $orderId;
        $order->order_number         = $orderNumber;
        $order->status               = 'completed';
        $order->amount               = $total;
        $order->currency             = $currency;
        $order->paypal_payment_id    = $paypalPaymentId;

        return $order;
    }
}
?>
