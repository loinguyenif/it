<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

use Joomla\CMS\Router\Route;

$order    = $this->order;
$document = $this->document;
$token    = $this->token;
$expireAt = $this->expireAt;  // Unix timestamp

$secureUrl = JRoute::_(
    'index.php?option=com_docshop&task=download.secure&token=' . urlencode($token),
    false
);

$fileSize = !empty($document->file_size)
    ? number_format($document->file_size / 1024, 2) . ' KB'
    : null;

$ttlSeconds = $expireAt - time(); // remaining seconds (≈300)
?>

<div class="fd-success-wrap">

    <!-- Success hero -->
    <div class="fd-success-hero">
        <div class="fd-success-icon" id="fd-success-icon">
            <i class="fas fa-check-circle"></i>
        </div>
        <h1 class="fd-success-title"><?php echo JText::_('COM_DOCSHOP_PAYMENT_SUCCESS'); ?></h1>
        <p class="fd-success-sub"><?php echo JText::_('COM_DOCSHOP_DOWNLOAD_SUCCESS_SUB'); ?></p>
    </div>

    <!-- Main card -->
    <div class="fd-success-card">

        <!-- File info -->
        <div class="fd-success-file">
            <span class="fd-success-file-icon"><i class="fas fa-file-archive"></i></span>
            <div class="fd-success-file-meta">
                <span class="fd-success-file-name"><?php echo htmlspecialchars($document->title); ?></span>
                <span class="fd-success-file-detail">
                    <?php if (!empty($document->version)) : ?>
                        v<?php echo htmlspecialchars($document->version); ?> &nbsp;·&nbsp;
                    <?php endif; ?>
                    <?php echo $fileSize ?: ''; ?>
                </span>
            </div>
        </div>

        <!-- Countdown -->
        <div class="fd-success-countdown" id="fd-countdown-wrap">
            <i class="fas fa-clock"></i>
            <span><?php echo JText::_('COM_DOCSHOP_DOWNLOAD_LINK_EXPIRES_IN'); ?></span>
            <strong id="fd-countdown">05:00</strong>
        </div>

        <!-- Download button -->
        <a href="<?php echo $secureUrl; ?>"
           id="fd-download-btn"
           class="fd-success-btn">
            <i class="fas fa-download"></i>
            <?php echo JText::_('COM_DOCSHOP_DOWNLOAD_NOW'); ?>
        </a>

        <!-- Expired message (hidden initially) -->
        <div class="fd-success-expired" id="fd-expired-msg" style="display:none;">
            <i class="fas fa-exclamation-circle"></i>
            <?php echo JText::_('COM_DOCSHOP_DOWNLOAD_LINK_EXPIRED'); ?>
            <a href="<?php echo JRoute::_('index.php?option=com_docshop&view=documents', false); ?>">
                <?php echo JText::_('COM_DOCSHOP_BACK'); ?>
            </a>
        </div>

        <!-- Order summary -->
        <div class="fd-success-summary">
            <div class="fd-success-row">
                <span><?php echo JText::_('COM_DOCSHOP_ORDER_NUMBER'); ?></span>
                <span class="fd-success-mono"><?php echo htmlspecialchars($order->order_number); ?></span>
            </div>
            <div class="fd-success-row">
                <span><?php echo JText::_('COM_DOCSHOP_AMOUNT'); ?></span>
                <span><strong><?php echo htmlspecialchars($order->currency ?: 'USD') . ' ' . number_format((float) $order->amount, 2); ?></strong></span>
            </div>
            <div class="fd-success-row">
                <span><?php echo JText::_('COM_DOCSHOP_FIELD_ORDER_DATE_LABEL'); ?></span>
                <span><?php echo JHtml::_('date', $order->created, 'd M Y H:i'); ?></span>
            </div>
        </div>

        <div class="fd-success-back">
            <a href="<?php echo JRoute::_('index.php?option=com_docshop&view=documents', false); ?>">
                <i class="fas fa-arrow-left"></i> <?php echo JText::_('COM_DOCSHOP_BACK_TO_DOCUMENTS'); ?>
            </a>
        </div>

    </div><!-- /.fd-success-card -->

</div><!-- /.fd-success-wrap -->

<script>
(function () {
    var expireAt  = <?php echo (int) $expireAt; ?>;
    var btnEl     = document.getElementById('fd-download-btn');
    var countEl   = document.getElementById('fd-countdown');
    var expiredEl = document.getElementById('fd-expired-msg');
    var wrapEl    = document.getElementById('fd-countdown-wrap');

    // Auto-trigger download immediately on page load
    var autoLink  = document.createElement('a');
    autoLink.href = <?php echo json_encode($secureUrl); ?>;
    autoLink.style.display = 'none';
    document.body.appendChild(autoLink);
    setTimeout(function () { autoLink.click(); }, 800);

    function pad(n) { return n < 10 ? '0' + n : '' + n; }

    function tick() {
        var remaining = expireAt - Math.floor(Date.now() / 1000);

        if (remaining <= 0) {
            // Expired — disable button, show message
            btnEl.classList.add('fd-success-btn-disabled');
            btnEl.removeAttribute('href');
            btnEl.onclick = function (e) { e.preventDefault(); };
            wrapEl.style.display = 'none';
            expiredEl.style.display = 'flex';
            return;
        }

        var mins = Math.floor(remaining / 60);
        var secs = remaining % 60;
        countEl.textContent = pad(mins) + ':' + pad(secs);

        // Turn countdown red in last 60 seconds
        if (remaining <= 60) {
            countEl.style.color = '#ef4444';
        }

        setTimeout(tick, 1000);
    }

    tick();
}());
</script>
