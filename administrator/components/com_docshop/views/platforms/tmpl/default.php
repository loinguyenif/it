<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

JHtml::_('behavior.tooltip');
JHtml::_('behavior.multiselect');

$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>

<form action="<?php echo JRoute::_('index.php?option=com_docshop&view=platforms'); ?>" method="post" name="adminForm" id="adminForm">
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
        <div id="filter-bar" class="btn-toolbar">
            <div class="filter-search btn-group pull-left">
                <label for="filter_search" class="element-invisible"><?php echo JText::_('COM_DOCSHOP_FILTER_SEARCH_DESC'); ?></label>
                <input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('COM_DOCSHOP_FILTER_SEARCH_DESC'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" />
            </div>
            <div class="btn-group pull-left">
                <button type="submit" class="btn hasTooltip" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
                <button type="button" class="btn hasTooltip" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();"><i class="icon-remove"></i></button>
            </div>
        </div>

        <table class="table table-striped" id="platformList">
            <thead>
                <tr>
                    <th width="1%" class="hidden-phone">
                        <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                    </th>
                    <th><?php echo JHtml::_('grid.sort', 'COM_DOCSHOP_HEADING_TITLE', 'a.title', $listDirn, $listOrder); ?></th>
                    <th><?php echo JHtml::_('grid.sort', 'JFIELD_ALIAS_LABEL', 'a.alias', $listDirn, $listOrder); ?></th>
                    <th width="8%"><?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?></th>
                    <th width="12%"><?php echo JHtml::_('grid.sort', 'JDATE', 'a.created', $listDirn, $listOrder); ?></th>
                    <th width="5%"><?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->items as $i => $item) : ?>
                    <tr class="row<?php echo $i % 2; ?>">
                        <td class="center hidden-phone">
                            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                        </td>
                        <td>
                            <a href="<?php echo JRoute::_('index.php?option=com_docshop&task=platform.edit&id=' . (int) $item->id); ?>">
                                <?php echo $this->escape($item->title); ?>
                            </a>
                        </td>
                        <td>
                            <?php echo $this->escape($item->alias); ?>
                        </td>
                        <td class="center">
                            <?php echo JHtml::_('jgrid.published', $item->published, $i, 'platforms.', true, 'cb'); ?>
                        </td>
                        <td class="center">
                            <?php echo $item->created && $item->created !== '0000-00-00 00:00:00' ? JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC4')) : '-'; ?>
                        </td>
                        <td class="center">
                            <?php echo (int) $item->id; ?>
                        </td>
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
