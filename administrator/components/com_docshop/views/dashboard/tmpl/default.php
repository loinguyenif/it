<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

JHtml::_('bootstrap.tooltip');
?>

<div id="j-sidebar-container" class="span2">
    <?php echo $this->sidebar; ?>
</div>

<div id="j-main-container" class="span10">
    <div id="dashboard-left">
        <div class="dashboard-container">
            <div class="dashboard-info dashboard-button">
                <a href="<?php echo JRoute::_('index.php?option=com_docshop&view=documents'); ?>">
                    <i class="icon-file"></i>
                    <span class="dashboard-title"><?php echo JText::_('COM_DOCSHOP_DOCUMENTS'); ?></span>
                </a>
            </div>
            <div class="dashboard-info dashboard-button">
                <a href="<?php echo JRoute::_('index.php?option=com_docshop&view=categories'); ?>">
                    <i class="icon-folder-open"></i>
                    <span class="dashboard-title"><?php echo JText::_('COM_DOCSHOP_CATEGORIES'); ?></span>
                </a>
            </div>
            <div class="dashboard-info dashboard-button">
                <a href="<?php echo JRoute::_('index.php?option=com_docshop&view=platforms'); ?>">
                    <i class="icon-screen"></i>
                    <span class="dashboard-title"><?php echo JText::_('COM_DOCSHOP_PLATFORMS'); ?></span>
                </a>
            </div>
            <div class="dashboard-info dashboard-button">
                <a href="<?php echo JRoute::_('index.php?option=com_docshop&view=orders'); ?>">
                    <i class="icon-cart"></i>
                    <span class="dashboard-title"><?php echo JText::_('COM_DOCSHOP_ORDERS'); ?></span>
                </a>
            </div>
        </div>
    </div>
</div>
