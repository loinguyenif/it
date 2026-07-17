<?php
/**
 * @package     mod_top_downloads
 * @subpackage  Modules
 * @copyright   Copyright (C) 2026
 * @license     GNU General Public License version 2 or later
 */

// Không cho phép truy cập trực tiếp
defined('_JEXEC') or die;
?>
<div class="fd_topdownloads<?php echo $moduleclass ? ' ' . $moduleclass : ''; ?>">
	<?php if (!empty($items)) : ?>
	<ul>
		<?php 
		$i = 1;
		foreach ($items as $item) : ?>
		<li>
			<span class="fd-rank"><?php echo $i; ?></span>
			<span class="fd-file-icon">
				<i class="fas fa-file-archive"></i>
			</span>
			<span class="fd-meta">
				<span class="fd-name">
					<a href="<?php echo $item->link; ?>"><?php echo htmlspecialchars($item->title); ?></a>
				</span>
				<span class="fd-downloads"><?php echo $item->hits_fmt; ?> <?php echo $suffix; ?></span>
			</span>
		</li>
		<?php $i++;
	endforeach; ?>
	</ul>
	<?php else : ?>
	<p class="fd-empty"><?php echo JText::_('MOD_TOP_DOWNLOADS_NO_ITEMS'); ?></p>
	<?php endif; ?>
</div>
