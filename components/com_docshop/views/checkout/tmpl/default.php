<?php
defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
?>

<div class="com-docshop-checkout">
    <h1><?php echo Text::_('COM_DOCSHOP_CHECKOUT'); ?></h1>

    <div class="checkout-form">
        <div class="document-summary">
            <h3><?php echo htmlspecialchars($this->document->title); ?></h3>
            <p><?php echo htmlspecialchars($this->document->description); ?></p>
            <div class="price">
                <strong>Price: $<?php echo number_format($this->document->price, 2); ?></strong>
            </div>
        </div>

        <form method="post" action="<?php echo Route::_('index.php?option=com_docshop&task=checkout.processPayment'); ?>">
            <input type="hidden" name="document_id" value="<?php echo (int)$this->document->id; ?>" />
            <?php echo JHtml::_('form.token'); ?>

            <button type="submit" class="btn btn-success btn-lg">
                <?php echo Text::_('COM_DOCSHOP_PROCEED_PAYPAL'); ?>
            </button>

            <a href="<?php echo Route::_('index.php?option=com_docshop&view=documents'); ?>" class="btn btn-secondary">
                <?php echo Text::_('COM_DOCSHOP_BACK'); ?>
            </a>
        </form>
    </div>
</div>
