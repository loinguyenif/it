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

    /** @var object|null */
    protected $order;

    /** @var object|null */
    protected $document;

    public function display($tpl = null)
    {
        $app = JFactory::getApplication();

        $this->orderId = $app->input->getInt('id', 0);

        $model          = $this->getModel();
        $this->order    = $model->getOrder($this->orderId);
        $this->document = $this->order ? $model->getDocument($this->order->document_id) : null;

        $this->downloadUrl = JRoute::_(
            'index.php?option=com_docshop&task=download.stream&id=' . $this->orderId,
            false
        );

        parent::display($tpl);
    }
}
?>
