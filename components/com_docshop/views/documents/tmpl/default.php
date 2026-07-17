<?php
defined('_JEXEC') or die;

use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

JHtml::_('bootstrap.framework');
JHtml::_('bootstrap.modal');
?>
<div class="fd-listing-card">
    <div class="fd-listing-head">
        <h2>Latest Files</h2>
        <form method="get" action="<?php echo Route::_('index.php?option=com_docshop&view=documents'); ?>" class="fd-filters" id="fd-docshop-filters">
            <input type="hidden" name="option" value="com_docshop" />
            <input type="hidden" name="view" value="documents" />
            <input type="hidden" name="filter_order" value="a.created" />
            <select name="filter_category_id" onchange="this.form.submit()">
                <option value="0" <?php echo (int) $this->state->get('filter.category_id') === 0 ? 'selected' : ''; ?>>All Categories</option>
                <?php foreach ($this->categories as $category) : ?>
                    <option value="<?php echo (int) $category->value; ?>" <?php echo ((int) $this->state->get('filter.category_id') === (int) $category->value) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($category->text); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <select name="filter_platform_id" onchange="this.form.submit()">
                <option value="0" <?php echo (int) $this->state->get('filter.platform_id') === 0 ? 'selected' : ''; ?>>All Platforms</option>
                <?php foreach ($this->platforms as $platform) : ?>
                    <option value="<?php echo (int) $platform->value; ?>" <?php echo ((int) $this->state->get('filter.platform_id') === (int) $platform->value) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($platform->text); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <select name="filter_order_Dir" onchange="this.form.submit()">
                <option value="DESC" <?php echo $this->state->get('list.direction') === 'DESC' ? 'selected' : ''; ?>>Latest</option>
                <option value="ASC" <?php echo $this->state->get('list.direction') === 'ASC' ? 'selected' : ''; ?>>Oldest</option>
            </select>
        </form>
    </div>

    <table class="fd-table">
        <thead>
            <tr>
                <th>#</th><th>File Name</th><th>Category</th><th>Size</th><th>Downloads</th><th>Price</th><th>Action</th>
            </tr>
        </thead>
        <tbody>
        <?php $rowIndex = 1; ?>
        <?php foreach ($this->items as $item) : ?>
            <tr>
              <td><?php echo (int) $rowIndex++; ?></td>
              <td>
                <div class="fd-file-name">
                  <span class="fd-file-icon"><i class="fas fa-file-archive"></i></span>
                  <?php echo htmlspecialchars($item->title); ?>
                </div>
              </td>
              <td><?php echo htmlspecialchars($item->category_title ?: 'Uncategorized'); ?></td>
              <td><?php echo htmlspecialchars(!empty($item->file_size) ? number_format($item->file_size / 1024, 2) . ' KB' : '0 KB'); ?></td>
              <td><?php echo (int) $item->download_count; ?></td>
              <td><?php echo '$' . number_format($item->price ?? 0, 2); ?></td>
              <td>
                    <form method="post" action="<?php echo Route::_('index.php?option=com_docshop&task=checkout.processPayment'); ?>" style="display:inline-block;margin:0;">
                        <input type="hidden" name="document_id" value="<?php echo (int) $item->id; ?>" />
                        <?php echo JHtml::_('form.token'); ?>
                        <button type="submit" class="btn fd-btn-download"><i class="fas fa-download"></i>Download</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    </table>

    <div class="fd-listing-footer">
        <span>
            <?php echo $this->pagination->getPagesCounter(); ?> 
        </span>
        <div class="fd-pagination">
            <?php echo $this->pagination->getPagesLinks(); ?>
        </div>
    </div>
</div>
