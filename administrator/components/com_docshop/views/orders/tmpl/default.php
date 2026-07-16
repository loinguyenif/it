<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>

<form action="<?php echo JRoute::_('index.php?option=com_docshop&view=orders'); ?>" method="post" name="adminForm" id="adminForm">
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
        <table class="table table-striped" id="orderList">
            <thead>
                <tr>
                    <th><?php echo JHtml::_('grid.sort', 'COM_DOCSHOP_ORDER_NUMBER', 'a.order_number', $listDirn, $listOrder); ?></th>
                    <th><?php echo JHtml::_('grid.sort', 'COM_DOCSHOP_USER', 'a.user_id', $listDirn, $listOrder); ?></th>
                    <th><?php echo JHtml::_('grid.sort', 'COM_DOCSHOP_DOCUMENT', 'a.document_id', $listDirn, $listOrder); ?></th>
                    <th><?php echo JHtml::_('grid.sort', 'COM_DOCSHOP_AMOUNT', 'a.amount', $listDirn, $listOrder); ?></th>
                    <th><?php echo JHtml::_('grid.sort', 'COM_DOCSHOP_STATUS', 'a.status', $listDirn, $listOrder); ?></th>
                    <th><?php echo JHtml::_('grid.sort', 'JDATE', 'a.created', $listDirn, $listOrder); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->items as $i => $item) : ?>
                    <tr class="row<?php echo $i % 2; ?>">
                        <td><?php echo $this->escape($item->order_number); ?></td>
                        <td><?php echo $this->escape($item->user_name); ?></td>
                        <td><?php echo $this->escape($item->document_title); ?></td>
                        <td>$<?php echo number_format($item->amount, 2); ?></td>
                        <td><?php echo JText::_('COM_DOCSHOP_STATUS_' . strtoupper($item->status)); ?></td>
                        <td><?php echo JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC4')); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php echo $this->pagination->getListFooter(); ?>

        <input type="hidden" name="task" value="" />
        <input type="hidden" name="boxchecked" value="0" />
        <input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
        <input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>