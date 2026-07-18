<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

$item = $this->item;

$statusColour = array(
    'pending'   => '#f59e0b',
    'completed' => '#10b981',
    'failed'    => '#ef4444',
    'refunded'  => '#6b7280',
);
$colour = isset($statusColour[$item->status]) ? $statusColour[$item->status] : '#6b7280';
?>
<style>
.ds-order-wrap { max-width: 860px; margin: 0 auto; padding: 16px 0 32px; }
.ds-order-grid { display: grid; grid-template-columns: 1fr 300px; gap: 20px; align-items: start; }
.ds-card {
    background: #fff;
    border: 1px solid #e0e0e0;
    border-radius: 6px;
    margin-bottom: 20px;
    overflow: hidden;
}
.ds-card:last-child { margin-bottom: 0; }
.ds-card-head {
    background: #f5f5f5;
    border-bottom: 1px solid #e0e0e0;
    padding: 10px 16px;
    font-weight: 700;
    font-size: 13px;
    text-transform: uppercase;
    letter-spacing: .4px;
    color: #555;
    display: flex;
    align-items: center;
    gap: 8px;
}
.ds-card-head i { color: #2d6ca2; }
.ds-card-body { padding: 0; }
.ds-row {
    display: flex;
    border-bottom: 1px solid #f0f0f0;
    font-size: 13.5px;
    min-height: 38px;
}
.ds-row:last-child { border-bottom: 0; }
.ds-label {
    width: 160px;
    flex-shrink: 0;
    padding: 9px 14px;
    color: #777;
    font-weight: 600;
    background: #fafafa;
    border-right: 1px solid #f0f0f0;
    display: flex;
    align-items: center;
}
.ds-value {
    flex: 1;
    padding: 9px 14px;
    color: #222;
    display: flex;
    align-items: center;
    word-break: break-word;
}
.ds-status-badge {
    display: inline-block;
    padding: 3px 10px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 700;
    color: #fff;
    letter-spacing: .3px;
}
.ds-amount { font-size: 20px; font-weight: 800; color: #1a1a1a; }
.ds-order-number { font-family: monospace; font-size: 14px; }
.ds-txn-id { font-family: monospace; font-size: 12px; color: #555; word-break: break-all; }
@media (max-width: 767px) {
    .ds-order-grid { grid-template-columns: 1fr; }
}
</style>

<div class="ds-order-wrap">

<!-- Inline Print Invoice button (fallback below toolbar) -->
<div style="text-align:right; margin-bottom:14px;">
    <?php
    $invoiceUrl = JRoute::_(
        'index.php?option=com_docshop&task=orders.invoice&id=' . (int) $item->id . '&format=raw',
        false
    );
    ?>
    <a href="<?php echo $invoiceUrl; ?>" target="_blank" class="btn btn-small">
        <span class="icon-print"></span> <?php echo JText::_('COM_DOCSHOP_PRINT_INVOICE'); ?>
    </a>
</div>

<div class="ds-order-grid">

    <!-- LEFT: order + payment info -->
    <div>

        <!-- Order Info -->
        <div class="ds-card">
            <div class="ds-card-head"><i class="icon-info-circle"></i> <?php echo JText::_('COM_DOCSHOP_ORDER_DETAILS'); ?></div>
            <div class="ds-card-body">
                <div class="ds-row">
                    <span class="ds-label"><?php echo JText::_('COM_DOCSHOP_ORDER_NUMBER'); ?></span>
                    <span class="ds-value ds-order-number"><?php echo $this->escape($item->order_number); ?></span>
                </div>
                <div class="ds-row">
                    <span class="ds-label"><?php echo JText::_('COM_DOCSHOP_STATUS'); ?></span>
                    <span class="ds-value">
                        <span class="ds-status-badge" style="background:<?php echo $colour; ?>">
                            <?php echo JText::_('COM_DOCSHOP_STATUS_' . strtoupper($item->status)); ?>
                        </span>
                    </span>
                </div>
                <div class="ds-row">
                    <span class="ds-label"><?php echo JText::_('COM_DOCSHOP_AMOUNT'); ?></span>
                    <span class="ds-value">
                        <span class="ds-amount">
                            <?php echo htmlspecialchars($item->currency ?: 'USD'); ?>
                            <?php echo number_format((float) $item->amount, 2); ?>
                        </span>
                    </span>
                </div>
                <div class="ds-row">
                    <span class="ds-label"><?php echo JText::_('COM_DOCSHOP_FIELD_ORDER_DATE_LABEL'); ?></span>
                    <span class="ds-value"><?php echo JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC4')); ?></span>
                </div>
                <?php if (!empty($item->modified)) : ?>
                <div class="ds-row">
                    <span class="ds-label"><?php echo JText::_('JGLOBAL_FIELD_MODIFIED_LABEL'); ?></span>
                    <span class="ds-value"><?php echo JHtml::_('date', $item->modified, JText::_('DATE_FORMAT_LC4')); ?></span>
                </div>
                <?php endif; ?>
                <div class="ds-row">
                    <span class="ds-label"><?php echo JText::_('COM_DOCSHOP_PAYMENT_METHOD'); ?></span>
                    <span class="ds-value"><?php echo htmlspecialchars(ucfirst($item->payment_method ?: 'paypal')); ?></span>
                </div>
                <div class="ds-row">
                    <span class="ds-label"><?php echo JText::_('COM_DOCSHOP_PAYPAL_TXN_ID'); ?></span>
                    <span class="ds-value">
                        <span class="ds-txn-id">
                            <?php echo !empty($item->paypal_transaction_id)
                                ? $this->escape($item->paypal_transaction_id)
                                : '<em style="color:#aaa;">—</em>'; ?>
                        </span>
                    </span>
                </div>
                <div class="ds-row">
                    <span class="ds-label"><?php echo JText::_('COM_DOCSHOP_DOWNLOAD_COUNT_LABEL'); ?></span>
                    <span class="ds-value"><?php echo (int) $item->download_count; ?></span>
                </div>
                <?php if (!empty($item->last_download)) : ?>
                <div class="ds-row">
                    <span class="ds-label"><?php echo JText::_('COM_DOCSHOP_LAST_DOWNLOAD'); ?></span>
                    <span class="ds-value"><?php echo JHtml::_('date', $item->last_download, JText::_('DATE_FORMAT_LC4')); ?></span>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Document Info -->
        <div class="ds-card">
            <div class="ds-card-head"><i class="icon-file"></i> <?php echo JText::_('COM_DOCSHOP_DOCUMENT'); ?></div>
            <div class="ds-card-body">
                <div class="ds-row">
                    <span class="ds-label"><?php echo JText::_('COM_DOCSHOP_FIELD_TITLE_LABEL'); ?></span>
                    <span class="ds-value">
                        <a href="<?php echo JRoute::_('index.php?option=com_docshop&task=document.edit&id=' . (int) $item->document_id); ?>">
                            <?php echo $this->escape($item->document_title); ?>
                        </a>
                    </span>
                </div>
                <?php if (!empty($item->document_version)) : ?>
                <div class="ds-row">
                    <span class="ds-label"><?php echo JText::_('COM_DOCSHOP_FIELD_VERSION_LABEL'); ?></span>
                    <span class="ds-value"><?php echo $this->escape($item->document_version); ?></span>
                </div>
                <?php endif; ?>
                <div class="ds-row">
                    <span class="ds-label"><?php echo JText::_('COM_DOCSHOP_FIELD_PRICE_LABEL'); ?></span>
                    <span class="ds-value">$<?php echo number_format((float) $item->document_price, 2); ?></span>
                </div>
                <div class="ds-row">
                    <span class="ds-label"><?php echo JText::_('COM_DOCSHOP_FIELD_FILE_LABEL'); ?></span>
                    <span class="ds-value ds-txn-id"><?php echo $this->escape($item->document_file); ?></span>
                </div>
            </div>
        </div>

    </div><!-- /left -->

    <!-- RIGHT: customer -->
    <div>
        <div class="ds-card">
            <div class="ds-card-head"><i class="icon-user"></i> <?php echo JText::_('COM_DOCSHOP_CUSTOMER'); ?></div>
            <div class="ds-card-body">
                <div class="ds-row">
                    <span class="ds-label"><?php echo JText::_('JGLOBAL_USERNAME'); ?></span>
                    <span class="ds-value">
                        <a href="<?php echo JRoute::_('index.php?option=com_users&task=user.edit&id=' . (int) $item->user_id); ?>">
                            <?php echo $this->escape($item->user_username); ?>
                        </a>
                    </span>
                </div>
                <div class="ds-row">
                    <span class="ds-label"><?php echo JText::_('COM_DOCSHOP_CUSTOMER_NAME'); ?></span>
                    <span class="ds-value"><?php echo $this->escape($item->user_name); ?></span>
                </div>
                <div class="ds-row">
                    <span class="ds-label"><?php echo JText::_('JGLOBAL_EMAIL'); ?></span>
                    <span class="ds-value">
                        <a href="mailto:<?php echo $this->escape($item->user_email); ?>">
                            <?php echo $this->escape($item->user_email); ?>
                        </a>
                    </span>
                </div>
                <div class="ds-row">
                    <span class="ds-label"><?php echo JText::_('COM_DOCSHOP_ORDER_ID'); ?></span>
                    <span class="ds-value">#<?php echo (int) $item->id; ?></span>
                </div>
            </div>
        </div>
    </div><!-- /right -->

</div><!-- /grid -->
</div><!-- /wrap -->
