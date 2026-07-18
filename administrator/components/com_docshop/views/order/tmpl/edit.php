<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
?>
<form action="<?php echo JRoute::_('index.php?option=com_docshop&task=order.save&id=' . (int) $this->item->id); ?>"
      method="post" name="adminForm" id="adminForm" class="form-validate">

    <div class="row-fluid">
        <div class="span9">

            <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', JText::_('COM_DOCSHOP_ORDER_DETAILS')); ?>
            <div class="row-fluid">
                <div class="span6">
                    <?php echo $this->form->renderField('status'); ?>
                    <?php echo $this->form->renderField('amount'); ?>
                    <?php echo $this->form->renderField('currency'); ?>
                    <?php echo $this->form->renderField('download_count'); ?>
                </div>
                <div class="span6">
                    <?php echo $this->form->renderField('paypal_transaction_id'); ?>
                    <?php echo $this->form->renderField('order_number'); ?>
                    <?php echo $this->form->renderField('created'); ?>
                </div>
            </div>
            <?php echo JHtml::_('bootstrap.endTab'); ?>

            <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'refs', JText::_('COM_DOCSHOP_ORDER_REFS')); ?>
            <div class="row-fluid">
                <div class="span6">
                    <?php echo $this->form->renderField('user_id'); ?>
                </div>
                <div class="span6">
                    <?php echo $this->form->renderField('document_id'); ?>
                </div>
            </div>
            <?php echo JHtml::_('bootstrap.endTab'); ?>

            <?php echo JHtml::_('bootstrap.endTabSet'); ?>
        </div>

        <div class="span3">
            <div class="card">
                <div class="card-body">
                    <h4><?php echo JText::_('JDETAILS'); ?></h4>
                    <div class="control-group">
                        <div class="control-label"><?php echo $this->form->getLabel('id'); ?></div>
                        <div class="controls"><?php echo $this->form->getInput('id'); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
</form>
