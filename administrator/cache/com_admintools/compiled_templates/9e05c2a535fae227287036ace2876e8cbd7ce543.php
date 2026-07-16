<?php /* D:\wamp64\www\ducloi\it\administrator\components\com_admintools\tmpl\ControlPanel\plugin_warning.blade.php */ ?>
<?php
/**
 * @package   admintools
 * @copyright Copyright (c)2010-2022 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

use Akeeba\AdminTools\Admin\View\WebApplicationFirewall\Html;
use Joomla\CMS\Language\Text;

defined('_JEXEC') || die;

/** @var  Html $this */

$returnUrl = base64_encode('index.php?option=com_admintools&view=' . $this->getName());

?>
<?php if(!$this->pluginExists): ?>
	<p class="akeeba-block--failure small">
		<?php echo \Joomla\CMS\Language\Text::_('COM_ADMINTOOLS_ERR_CONFIGUREWAF_NOPLUGINEXISTS'); ?>
	</p>
<?php elseif(!$this->pluginActive): ?>
	<p class="akeeba-block--failure small">
		<?php echo \Joomla\CMS\Language\Text::_('COM_ADMINTOOLS_ERR_CONFIGUREWAF_NOPLUGINACTIVE'); ?>
		<br />
		<a href="index.php?option=com_plugins&client=site&filter_type=system&search=admin%20tools">
			<?php echo \Joomla\CMS\Language\Text::_('COM_ADMINTOOLS_ERR_CONFIGUREWAF_NOPLUGINACTIVE_DOIT'); ?>
		</a>
	</p>
<?php elseif($this->isMainPhpDisabled && !empty($this->mainPhpRenamedTo)): ?>
	<p class="akeeba-block--failure small">
		<?php echo \Joomla\CMS\Language\Text::sprintf('COM_ADMINTOOLS_ERR_CONFIGUREWAF_MAINPHPRENAMED_KNOWN', $this->mainPhpRenamedTo); ?>
		<br />
		<a href="index.php?option=com_admintools&view=ControlPanel&task=renameMainPhp&<?php echo $this->container->platform->getToken(true); ?>=1&returnurl=<?php echo urlencode($returnUrl); ?>">
			<?php echo \Joomla\CMS\Language\Text::_('COM_ADMINTOOLS_ERR_CONFIGUREWAF_MAINPHPRENAMED_DOIT'); ?>
		</a>
	</p>
<?php elseif($this->isMainPhpDisabled): ?>
	<p class="akeeba-block--failure small">
		<?php echo \Joomla\CMS\Language\Text::_('COM_ADMINTOOLS_ERR_CONFIGUREWAF_MAINPHPRENAMED_UNKNOWN'); ?>
	</p>
<?php elseif(!$this->pluginLoaded && !$this->isRescueMode): ?>
	<p class="akeeba-block--failure small">
		<?php echo \Joomla\CMS\Language\Text::_('COM_ADMINTOOLS_ERR_CONFIGUREWAF_PLUGINNOTLOADED'); ?>
	</p>
<?php endif; ?>
