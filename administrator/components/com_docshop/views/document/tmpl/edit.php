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
JHtml::_('formbehavior.chosen', 'select');

$app = JFactory::getApplication();
$input = $app->input;
?>

<form action="<?php echo JRoute::_('index.php?option=com_docshop&view=document&layout=edit&id=' . (int) $this->item->id); ?>" method="post" name="adminForm" id="adminForm" enctype="multipart/form-data" class="form-validate">

    <div class="form-horizontal">
        <div class="row-fluid">
            <div class="span9">
                <?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

                <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', JText::_('COM_DOCSHOP_TAB_DETAILS')); ?>
                <div class="row-fluid">
                    <div class="span6">
                        <?php echo $this->form->renderField('title'); ?>
                        <?php echo $this->form->renderField('alias'); ?>
                        <?php echo $this->form->renderField('description'); ?>
                        <?php echo $this->form->renderField('youtube_url'); ?>
                        <?php echo $this->form->renderField('price'); ?>
                        <?php echo $this->form->renderField('category_id'); ?>
                    </div>
                    <div class="span6">
                        <?php echo $this->form->renderField('file'); ?>
                        <?php echo $this->form->renderField('published'); ?>
                        <?php echo $this->form->renderField('access'); ?>
                        <?php echo $this->form->renderField('ordering'); ?>
                    </div>
                </div>
                <?php echo JHtml::_('bootstrap.endTab'); ?>

                <?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('JGLOBAL_FIELDSET_PUBLISHING')); ?>
                <div class="row-fluid">
                    <div class="span6">
                        <?php echo $this->form->renderField('created'); ?>
                        <?php echo $this->form->renderField('created_by'); ?>
                    </div>
                    <div class="span6">
                        <?php echo $this->form->renderField('modified'); ?>
                        <?php echo $this->form->renderField('modified_by'); ?>
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
                            <div class="control-label">
                                <?php echo $this->form->getLabel('id'); ?>
                            </div>
                            <div class="controls">
                                <?php echo $this->form->getInput('id'); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="task" value="" />
    <?php echo JHtml::_('form.token'); ?>
</form>
