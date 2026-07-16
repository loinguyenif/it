<?php /* D:\wamp64\www\ducloi\it\administrator\components\com_admintools\tmpl\ControlPanel\security.blade.php */ ?>
<?php
/**
 * @package   admintools
 * @copyright Copyright (c)2010-2022 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

defined('_JEXEC') || die;

/** @var  Akeeba\AdminTools\Admin\View\ControlPanel\Html $this For type hinting in the IDE */
?>

<div class="akeeba-panel--primary">
	<header class="akeeba-block-header">
		<h3><?php echo \Joomla\CMS\Language\Text::_('COM_ADMINTOOLS_LBL_CONTROLPANEL_SECURITY'); ?></h3>
	</header>

	<div class="akeeba-grid">
		<?php if(ADMINTOOLS_PRO && $this->needsQuickSetup): ?>
			<a href="index.php?option=com_admintools&view=QuickStart" class="akeeba-action--orange">
				<span class="akion-flash"></span>
				<?php echo \Joomla\CMS\Language\Text::_('COM_ADMINTOOLS_TITLE_QUICKSTART'); ?>
			</a>
		<?php endif; ?>

		<?php if($this->htMakerSupported): ?>
			<a href="index.php?option=com_admintools&view=EmergencyOffline" class="akeeba-action--red">
				<span class="akion-power"></span>
				<?php echo \Joomla\CMS\Language\Text::_('COM_ADMINTOOLS_TITLE_EOM'); ?><br />
			</a>
		<?php endif; ?>

		<a href="index.php?option=com_admintools&view=MasterPassword" class="akeeba-action--orange">
			<span class="akion-key"></span>
			<?php echo \Joomla\CMS\Language\Text::_('COM_ADMINTOOLS_TITLE_MASTERPW'); ?><br />
		</a>

		<?php if ($this->htMakerSupported): ?>
			<a href="index.php?option=com_admintools&view=AdminPassword" class="akeeba-action--orange">
				<span class="akion-<?php echo $this->adminLocked ? 'locked' : 'unlocked'; ?>"></span>
				<?php echo \Joomla\CMS\Language\Text::_('COM_ADMINTOOLS_TITLE_ADMINPW'); ?><br />
			</a>
		<?php endif; ?>

		<?php if($this->isPro): ?>
			<?php if($this->htMakerSupported): ?>
				<a href="index.php?option=com_admintools&view=HtaccessMaker" class="akeeba-action--teal">
					<span class="akion-document-text"></span>
					<?php echo \Joomla\CMS\Language\Text::_('COM_ADMINTOOLS_TITLE_HTMAKER'); ?><br />
				</a>
			<?php endif; ?>

			<?php if($this->nginxMakerSupported): ?>
				<a href="index.php?option=com_admintools&view=NginXConfMaker" class="akeeba-action--teal">
					<span class="akion-document-text"></span>
					<?php echo \Joomla\CMS\Language\Text::_('COM_ADMINTOOLS_TITLE_NGINXMAKER'); ?><br />
				</a>
			<?php endif; ?>

			<?php if($this->webConfMakerSupported): ?>
				<a href="index.php?option=com_admintools&view=WebConfigMaker" class="akeeba-action--teal">
					<span class="akion-document-text"></span>
					<?php echo \Joomla\CMS\Language\Text::_('COM_ADMINTOOLS_TITLE_WCMAKER'); ?><br />
				</a>
			<?php endif; ?>

			<a href="index.php?option=com_admintools&view=WebApplicationFirewall" class="akeeba-action--grey">
				<span class="akion-close-circled"></span>
				<?php echo \Joomla\CMS\Language\Text::_('COM_ADMINTOOLS_TITLE_WAF'); ?><br />
			</a>

			<a href="index.php?option=com_admintools&view=Scans" class="akeeba-action--grey">
				<span class="akion-search"></span>
				<?php echo \Joomla\CMS\Language\Text::_('COM_ADMINTOOLS_TITLE_SCANS'); ?><br />
			</a>

			<a href="index.php?option=com_admintools&view=SchedulingInformation" class="akeeba-action--grey">
				<span class="akion-calendar"></span>
				<?php echo \Joomla\CMS\Language\Text::_('COM_ADMINTOOLS_TITLE_SCHEDULINGINFORMATION'); ?><br />
			</a>
		<?php endif; ?>
	</div>
</div>
