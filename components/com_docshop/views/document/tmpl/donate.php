<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

use Joomla\CMS\Router\Route;

JHtml::_('bootstrap.framework');
JHtml::_('bootstrap.modal');

$item = $this->item;
?>

<div class="fd-donate-wrap">

    <!-- Hero banner -->
    <div class="fd-donate-hero">
        <div class="fd-donate-hero-icon">
            <i class="fas fa-seedling"></i>
        </div>
        <h1 class="fd-donate-hero-title">Save the Planet</h1>
        <p class="fd-donate-hero-sub">Every dollar you donate helps us plant a tree and build a better future.</p>
        <div class="fd-donate-hero-badges">
            <span><i class="fas fa-shield-alt"></i> Secure Payment</span>
            <span><i class="fas fa-heart"></i> 100% to the cause</span>
            <span><i class="fas fa-tree"></i> Plant a tree</span>
        </div>
    </div>

    <!-- Main card -->
    <div class="fd-donate-card">

        <!-- Preset amounts -->
        <p class="fd-donate-label">Choose an amount</p>
        <div class="fd-donate-presets" id="fd-presets">
            <button type="button" class="fd-preset-btn" data-amount="5">$5</button>
            <button type="button" class="fd-preset-btn" data-amount="10">$10</button>
            <button type="button" class="fd-preset-btn fd-preset-active" data-amount="25">$25</button>
            <button type="button" class="fd-preset-btn" data-amount="50">$50</button>
            <button type="button" class="fd-preset-btn" data-amount="100">$100</button>
        </div>

        <!-- Custom amount input -->
        <p class="fd-donate-label">Or enter a custom amount</p>
        <div class="fd-donate-input-wrap">
            <span class="fd-donate-currency">$</span>
            <input
                type="number"
                id="fd-donate-amount"
                name="amount"
                class="fd-donate-input"
                value="25"
                min="1"
                step="1"
                placeholder="Enter amount"
            />
            <span class="fd-donate-unit">USD</span>
        </div>

        <!-- Impact meter -->
        <div class="fd-donate-impact" id="fd-impact">
            <i class="fas fa-tree"></i>
            <span id="fd-impact-text">Your $25 will plant approximately <strong>3 trees</strong></span>
        </div>

        <!-- Donate form -->
        <form
            method="post"
            action="<?php echo JRoute::_('index.php?option=com_docshop&task=checkout.processPayment'); ?>"
            id="fd-donate-form"
        >
            <input type="hidden" name="document_id" value="<?php echo (int) $item->id; ?>" />
            <input type="hidden" name="custom_amount" id="fd-hidden-amount" value="25" />
            <?php echo JHtml::_('form.token'); ?>

            <div id="fd-amount-error" class="fd-donate-error" style="display:none;">
                <i class="fas fa-exclamation-circle"></i> Please enter a valid amount (minimum $1).
            </div>

            <button type="submit" class="fd-donate-btn" id="fd-donate-btn">
                <i class="fas fa-heart"></i>
                Donate $<span id="fd-btn-amount">25</span> Now
            </button>
        </form>

        <!-- Trust row -->
        <div class="fd-donate-trust">
            <span><i class="fas fa-lock"></i> SSL Encrypted</span>
            <span><i class="fab fa-paypal"></i> PayPal Secure</span>
            <span><i class="fas fa-undo"></i> Cancel anytime</span>
        </div>

        <!-- Description -->
        <div class="fd-donate-desc">
            <p>
                All proceeds go directly to the
                <a href="https://www.canopyproject.org" target="_blank" rel="noopener">Canopy Project</a>,
                organized by the
                <a href="https://www.earthday.org" target="_blank" rel="noopener">Earth Day Network</a> —
                a 501(c)(3) non-profit focused on broadening the environmental movement worldwide.
            </p>
        </div>

    </div><!-- /.fd-donate-card -->

</div><!-- /.fd-donate-wrap -->

<script>
(function () {
    var presets    = document.querySelectorAll('.fd-preset-btn');
    var amountInput = document.getElementById('fd-donate-amount');
    var hiddenInput = document.getElementById('fd-hidden-amount');
    var btnAmount   = document.getElementById('fd-btn-amount');
    var impactText  = document.getElementById('fd-impact-text');

    function treesFor(amount) {
        return Math.max(1, Math.floor(amount / 8));
    }

    function syncAmount(val) {
        var n = parseFloat(val) || 0;
        hiddenInput.value  = n;
        btnAmount.textContent = n > 0 ? n : '?';
        if (n > 0) {
            var trees = treesFor(n);
            impactText.innerHTML =
                'Your $' + n + ' will plant approximately <strong>' +
                trees + (trees === 1 ? ' tree' : ' trees') + '</strong>';
        } else {
            impactText.innerHTML = 'Enter an amount above to see your impact';
        }
    }

    // Preset buttons
    presets.forEach(function (btn) {
        btn.addEventListener('click', function () {
            presets.forEach(function (b) { b.classList.remove('fd-preset-active'); });
            btn.classList.add('fd-preset-active');
            var amount = btn.dataset.amount;
            amountInput.value = amount;
            syncAmount(amount);
        });
    });

    // Manual input
    amountInput.addEventListener('input', function () {
        presets.forEach(function (b) { b.classList.remove('fd-preset-active'); });
        // Re-activate preset if value matches
        presets.forEach(function (b) {
            if (b.dataset.amount == amountInput.value) {
                b.classList.add('fd-preset-active');
            }
        });
        syncAmount(amountInput.value);
    });

    // Sync hidden field before submit
    document.getElementById('fd-donate-form').addEventListener('submit', function (e) {
        var val = parseFloat(amountInput.value) || 0;
        var errorEl = document.getElementById('fd-amount-error');
        if (val < 1) {
            e.preventDefault();
            errorEl.style.display = 'flex';
            amountInput.focus();
            return;
        }
        errorEl.style.display = 'none';
        hiddenInput.value = val.toFixed(2);
    });

    // Init
    syncAmount(amountInput.value);
}());
</script>
