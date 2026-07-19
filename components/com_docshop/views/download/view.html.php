<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

class DocshopViewDownload extends JViewLegacy
{
    /** @var int */
    protected $orderId;

    /** @var string */
    protected $downloadUrl;

    public function display($tpl = null)
    {
        $app = JFactory::getApplication();

        $orderNumber       = $app->input->getString('order_number', '');
        $this->downloadUrl = JRoute::_(
            'index.php?option=com_docshop&task=download.stream&order_number=' . urlencode($orderNumber),
            false
        );

        parent::display($tpl);
    }
}
?>
