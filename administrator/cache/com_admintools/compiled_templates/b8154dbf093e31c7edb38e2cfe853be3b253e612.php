<?php /* D:\wamp64\www\ducloi\it\administrator\components\com_admintools\tmpl\ControlPanel\quicksetup.blade.php */ ?>
<?php
/**
 * @package   admintools
 * @copyright Copyright (c)2010-2022 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

defined('_JEXEC') || die;

/** @var  Akeeba\AdminTools\Admin\View\ControlPanel\Html $this For type hinting in the IDE */

?>

<div class="akeeba-panel--default">
	<header class="akeeba-block-header">
		<h3><?php echo \Joomla\CMS\Language\Text::_('COM_ADMINTOOLS_CONTROLPANEL_HEADER_QUICKSETUP'); ?></h3>
	</header>

	<p class="akeeba-block--warning small">
		<?php echo \Joomla\CMS\Language\Text::_('COM_ADMINTOOLS_CONTROLPANEL_HEADER_QUICKSETUP_HELP'); ?>
	</p>

	<div class="akeeba-grid">
		<div>
			<a href="index.php?option=com_admintools&view=QuickStart" class="akeeba-action--orange">
				<span class="akion-flash"></span>
				<?php echo \Joomla\CMS\Language\Text::_('COM_ADMINTOOLS_TITLE_QUICKSTART'); ?>
			</a>
		</div>
	</div>
</div>
