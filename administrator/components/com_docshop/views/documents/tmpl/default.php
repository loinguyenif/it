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

$user = JFactory::getUser();
$userId = $user->get('id');
$listOrder = $this->escape($this->state->get('list.ordering'));
$listDirn = $this->escape($this->state->get('list.direction'));
?>

<form action="<?php echo JRoute::_('index.php?option=com_docshop&view=documents'); ?>" method="post" name="adminForm" id="adminForm">
    <div id="j-sidebar-container" class="span2">
        <?php echo $this->sidebar; ?>
    </div>
    <div id="j-main-container" class="span10">
        <div id="filter-bar" class="btn-toolbar">
            <div class="filter-search btn-group pull-left">
                <label for="filter_search" class="element-invisible"><?php echo JText::_('COM_DOCSHOP_FILTER_SEARCH_DESC'); ?></label>
                <input type="text" name="filter_search" id="filter_search" placeholder="<?php echo JText::_('COM_DOCSHOP_FILTER_SEARCH_DESC'); ?>" value="<?php echo $this->escape($this->state->get('filter.search')); ?>" title="<?php echo JText::_('COM_DOCSHOP_FILTER_SEARCH_DESC'); ?>" />
            </div>
            <div class="btn-group pull-left">
                <select name="filter_category_id" class="input-medium" onchange="this.form.submit();">
                    <option value="0">All Categories</option>
                    <?php foreach ($this->categories as $category) : ?>
                        <option value="<?php echo (int) $category->value; ?>" <?php echo ((int) $this->state->get('filter.category_id') === (int) $category->value) ? 'selected' : ''; ?>>
                            <?php echo $this->escape($category->text); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="btn-group pull-left">
                <select name="filter_platform_id" class="input-medium" onchange="this.form.submit();">
                    <option value="0">All Platforms</option>
                    <?php foreach ($this->platforms as $platform) : ?>
                        <option value="<?php echo (int) $platform->value; ?>" <?php echo ((int) $this->state->get('filter.platform_id') === (int) $platform->value) ? 'selected' : ''; ?>>
                            <?php echo $this->escape($platform->text); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="btn-group pull-left">
                <button type="submit" class="btn hasTooltip" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
                <button type="button" class="btn hasTooltip" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';document.id('filter_category_id').value='0';document.id('filter_platform_id').value='0';this.form.submit();"><i class="icon-remove"></i></button>
            </div>
        </div>

        <table class="table table-striped" id="documentList">
            <thead>
                <tr>
                    <th width="1%" class="hidden-phone">
                        <input type="checkbox" name="checkall-toggle" value="" title="<?php echo JText::_('JGLOBAL_CHECK_ALL'); ?>" onclick="Joomla.checkAll(this)" />
                    </th>
                    <th><?php echo JHtml::_('grid.sort', 'COM_DOCSHOP_HEADING_TITLE', 'a.title', $listDirn, $listOrder); ?></th>
                    <th width="10%"><?php echo JHtml::_('grid.sort', 'COM_DOCSHOP_HEADING_PRICE', 'a.price', $listDirn, $listOrder); ?></th>
                    <th width="5%"><?php echo JHtml::_('grid.sort', 'JSTATUS', 'a.published', $listDirn, $listOrder); ?></th>
                    <th width="10%"><?php echo JHtml::_('grid.sort', 'JDATE', 'a.created', $listDirn, $listOrder); ?></th>
                    <th width="1%" class="nowrap"><?php echo JHtml::_('grid.sort', 'JGRID_HEADING_ID', 'a.id', $listDirn, $listOrder); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($this->items as $i => $item) : ?>
                    <tr class="row<?php echo $i % 2; ?>">
                        <td class="center hidden-phone">
                            <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                        </td>
                        <td class="has-context">
                            <a href="<?php echo JRoute::_('index.php?option=com_docshop&task=document.edit&id=' . (int) $item->id); ?>">
                                <?php echo $this->escape($item->title); ?>
                            </a>
                        </td>
                        <td class="center">
                            $<?php echo number_format($item->price, 2); ?>
                        </td>
                        <td class="center">
                            <?php echo JHtml::_('jgrid.published', $item->published, $i, 'documents.', true, 'cb'); ?>
                        </td>
                        <td class="center">
                            <?php echo JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC4')); ?>
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