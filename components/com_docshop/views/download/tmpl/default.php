<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;
?>
<div class="docshop-download-success" style="text-align:center; padding: 40px 20px;">
    <h2><?php echo JText::_('Payment Successful!'); ?></h2>
    <p><?php echo JText::_('Click the button below to download your file.'); ?></p>

    <p>
        <a href="<?php echo htmlspecialchars($this->downloadUrl, ENT_QUOTES, 'UTF-8'); ?>"
           class="btn btn-primary btn-large"
           style="display:inline-block; padding:12px 30px; background:#0070ba; color:#fff; text-decoration:none; border-radius:4px; font-size:16px;">
            &#8595; Download File
        </a>
    </p>

    <p style="color:#666; font-size:13px;">
        You can only download this file a maximum of <strong>3 times</strong> per order.
    </p>
</div>
