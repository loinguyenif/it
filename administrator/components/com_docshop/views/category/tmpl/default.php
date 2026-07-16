<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

JHtml::_('behavior.formvalidator');
JHtml::_('behavior.keepalive');
?>

<form action="<?php echo JRoute::_('index.php?option=com_docshop&view=category&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" class="form-validate">
    <div class="form-horizontal">
        <div class="row-fluid">
            <div class="span9">
                <?php echo $this->form->renderField('title'); ?>
                <?php echo $this->form->renderField('alias'); ?>
                <?php echo $this->form->renderField('description'); ?>
            </div>
            <div class="span3">
                <?php echo $this->form->renderField('published'); ?>
                <?php echo $this->form->renderField('access'); ?>
                <?php echo $this->form->renderField('created'); ?>
            </div>
        </div>
    </div>

    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
</form>
