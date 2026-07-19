<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

class DocshopModelDownload extends JModelLegacy
{
    /**
     * Get an order by its internal numeric ID.
     */
    public function getOrder($orderId)
    {
        $db    = $this->getDbo();
        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->quoteName('#__docshop_orders'))
            ->where($db->quoteName('id') . ' = ' . (int) $orderId);

        $db->setQuery($query);
        return $db->loadObject();
    }

    /**
     * Get an order by its public order_number (e.g. ORD-A1B2C3D4E5).
     */
    public function getOrderByNumber($orderNumber)
    {
        $db    = $this->getDbo();
        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->quoteName('#__docshop_orders'))
            ->where($db->quoteName('order_number') . ' = ' . $db->quote((string) $orderNumber));

        $db->setQuery($query);
        return $db->loadObject();
    }

    /**
     * Get a published document by ID.
     */
    public function getDocument($documentId)
    {
        $db    = $this->getDbo();
        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->quoteName('#__docshop_documents'))
            ->where($db->quoteName('id') . ' = ' . (int) $documentId)
            ->where($db->quoteName('published') . ' = 1');

        $db->setQuery($query);
        return $db->loadObject();
    }

    /**
     * Increment download_count and update last_download timestamp atomically.
     * Returns the new download_count so the caller can enforce limits.
     */
    public function markDownloaded($orderId)
    {
        $db = $this->getDbo();

        // Single atomic UPDATE — avoids a read-then-write race condition.
        $query = $db->getQuery(true)
            ->update($db->quoteName('#__docshop_orders'))
            ->set($db->quoteName('last_download')  . ' = ' . $db->quote(JFactory::getDate()->toSql()))
            ->set($db->quoteName('download_count') . ' = ' . $db->quoteName('download_count') . ' + 1')
            ->where($db->quoteName('id') . ' = ' . (int) $orderId);

        $db->setQuery($query);
        $db->execute();

        // Return the updated count.
        $query = $db->getQuery(true)
            ->select($db->quoteName('download_count'))
            ->from($db->quoteName('#__docshop_orders'))
            ->where($db->quoteName('id') . ' = ' . (int) $orderId);

        $db->setQuery($query);
        return (int) $db->loadResult();
    }
}
?>
