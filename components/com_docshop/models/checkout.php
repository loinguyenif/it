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

        // ---- Build INSERT using individual bindings for correct typing ----
        $orderNumber = 'ORD-' . strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 10));

        $query = $db->getQuery(true)
            ->insert($db->quoteName('#__docshop_orders'))
            ->columns($db->quoteName(array(
                'user_id', 'document_id', 'order_number',
                'paypal_transaction_id', 'amount', 'currency',
                'status', 'payment_method', 'created',
            )))
            ->values(implode(',', array(
                (int)    $user->id,
                (int)    $documentId,
                $db->quote($orderNumber),
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

        $order                = new \stdClass();
        $order->id            = $orderId;
        $order->order_number  = $orderNumber;
        $order->status        = 'completed';
        $order->amount        = $total;
        $order->currency      = $currency;

        return $order;
    }
}
?>
