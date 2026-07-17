<?php
/**
 * @package     mod_docshop_categories
 * @subpackage  Modules
 * @copyright   Copyright (C) 2026
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;
?>
<?php if (!empty($items)) : ?>
<ul class="<?php echo $moduleclass ? ' ' . $moduleclass : ''; ?>">
    <?php foreach ($items as $item) : ?>
    <li>
        <span>
            <i class="fas fa-desktop"></i>
            <a href="<?php echo $item->link; ?>">
                <?php echo htmlspecialchars($item->title, ENT_QUOTES, 'UTF-8'); ?>
                </a>
        </span>
        <span class="fd-count"><?php echo number_format($item->doc_count); ?></span>
       
    </li>
    <?php endforeach; ?>
</ul>
<?php endif; ?>
