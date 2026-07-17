<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

use Joomla\CMS\Router\Route;

JHtml::_('bootstrap.framework');
JHtml::_('bootstrap.modal');

$item      = $this->item;
$isFree    = (float) $item->price == 0;
$hasVideo  = !empty($item->youtube_url);
$fileSize  = !empty($item->file_size) ? number_format($item->file_size / 1024, 2) . ' KB' : null;

// Extract YouTube embed ID
$youtubeId = '';
if ($hasVideo) {
    preg_match('/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|shorts\/))([A-Za-z0-9_-]{11})/', $item->youtube_url, $m);
    $youtubeId = isset($m[1]) ? $m[1] : '';
}

$backUrl = JRoute::_('index.php?option=com_docshop&view=documents');
?>

<div class="fd-detail-wrap">

    <!-- Breadcrumb -->
    <nav class="fd-breadcrumbs" aria-label="breadcrumb">
        <a href="<?php echo $backUrl; ?>"><i class="fas fa-home"></i> Documents</a>
        <span class="fd-breadcrumb-sep">/</span>
        <?php if (!empty($item->category_title)) : ?>
            <a href="<?php echo JRoute::_('index.php?option=com_docshop&view=documents&filter_category_id=' . (int) $item->category_id); ?>">
                <?php echo htmlspecialchars($item->category_title); ?>
            </a>
            <span class="fd-breadcrumb-sep">/</span>
        <?php endif; ?>
        <span class="fd-breadcrumb-current"><?php echo htmlspecialchars($item->title); ?></span>
    </nav>

    <div class="fd-detail-layout">

        <!-- ===== LEFT COLUMN: main content ===== -->
        <div class="fd-detail-main">

            <!-- Hero card -->
            <div class="fd-detail-card fd-detail-hero">
                <div class="fd-detail-hero-icon">
                    <i class="fas fa-file-archive"></i>
                </div>
                <div class="fd-detail-hero-body">
                    <h1 class="fd-detail-title"><?php echo htmlspecialchars($item->title); ?></h1>

                    <!-- Meta badges -->
                    <div class="fd-detail-badges">
                        <?php if (!empty($item->category_title)) : ?>
                            <span class="fd-badge fd-badge-category">
                                <i class="fas fa-folder"></i>
                                <?php echo htmlspecialchars($item->category_title); ?>
                            </span>
                        <?php endif; ?>
                        <?php if (!empty($item->platform_title)) : ?>
                            <span class="fd-badge fd-badge-platform">
                                <i class="fas fa-desktop"></i>
                                <?php echo htmlspecialchars($item->platform_title); ?>
                            </span>
                        <?php endif; ?>
                        <?php if (!empty($item->version)) : ?>
                            <span class="fd-badge fd-badge-version">
                                <i class="fas fa-code-branch"></i>
                                v<?php echo htmlspecialchars($item->version); ?>
                            </span>
                        <?php endif; ?>
                        <span class="fd-badge fd-badge-downloads">
                            <i class="fas fa-eye"></i>
                            <?php echo number_format((int) $item->view_count); ?> views
                        </span>
                    </div>

                    <!-- Stats row -->
                    <div class="fd-detail-stats">
                        <?php if ($fileSize) : ?>
                            <div class="fd-stat">
                                <span class="fd-stat-icon"><i class="fas fa-hdd"></i></span>
                                <div>
                                    <span class="fd-stat-label">File Size</span>
                                    <span class="fd-stat-value"><?php echo $fileSize; ?></span>
                                </div>
                            </div>
                        <?php endif; ?>
                        <div class="fd-stat">
                            <span class="fd-stat-icon"><i class="fas fa-tag"></i></span>
                            <div>
                                <span class="fd-stat-label">Price</span>
                                <span class="fd-stat-value fd-stat-price">
                                    <?php echo $isFree ? '<span class="fd-free-tag">FREE</span>' : '$' . number_format((float) $item->price, 2); ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <?php if (!empty($item->description)) : ?>
                <div class="fd-detail-card">
                    <h3 class="fd-detail-section-title">
                        <i class="fas fa-align-left"></i> Description
                    </h3>
                    <div class="fd-detail-description">
                        <?php echo nl2br(htmlspecialchars($item->description)); ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- YouTube guide video -->
            <?php if ($hasVideo && $youtubeId) : ?>
                <div class="fd-detail-card">
                    <h3 class="fd-detail-section-title">
                        <i class="fab fa-youtube"></i> Guide Video
                    </h3>
                    <div class="fd-detail-video">
                        <iframe
                            src="https://www.youtube.com/embed/<?php echo htmlspecialchars($youtubeId); ?>"
                            title="Guide video"
                            frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen
                        ></iframe>
                    </div>
                </div>
            <?php endif; ?>

        </div><!-- /.fd-detail-main -->

        <!-- ===== RIGHT COLUMN: action panel ===== -->
        <div class="fd-detail-aside">

            <div class="fd-detail-card fd-action-card">

                <!-- Price display -->
                <div class="fd-action-price">
                    <?php if ($isFree) : ?>
                        <span class="fd-action-price-free">FREE</span>
                    <?php else : ?>
                        <span class="fd-action-price-amount">$<?php echo number_format((float) $item->price, 2); ?></span>
                    <?php endif; ?>
                </div>

                <!-- Download / Buy button -->
                <form method="post"
                      action="<?php echo JRoute::_('index.php?option=com_docshop&task=checkout.processPayment'); ?>"
                      class="fd-action-form">
                    <input type="hidden" name="document_id" value="<?php echo (int) $item->id; ?>" />
                    <?php echo JHtml::_('form.token'); ?>
                    <button type="submit" class="fd-btn-action">
                        <i class="fas fa-download"></i>
                        <?php echo $isFree ? 'Download Free' : 'Buy &amp; Download'; ?>
                    </button>
                </form>

                <a href="<?php echo $backUrl; ?>" class="fd-btn-back">
                    <i class="fas fa-arrow-left"></i> Back to list
                </a>

                <!-- File details list -->
                <ul class="fd-action-meta">
                    <?php if (!empty($item->version)) : ?>
                        <li>
                            <span class="fd-action-meta-label"><i class="fas fa-code-branch"></i> Version</span>
                            <span class="fd-action-meta-value"><?php echo htmlspecialchars($item->version); ?></span>
                        </li>
                    <?php endif; ?>
                    <?php if ($fileSize) : ?>
                        <li>
                            <span class="fd-action-meta-label"><i class="fas fa-hdd"></i> File Size</span>
                            <span class="fd-action-meta-value"><?php echo $fileSize; ?></span>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($item->category_title)) : ?>
                        <li>
                            <span class="fd-action-meta-label"><i class="fas fa-folder"></i> Category</span>
                            <span class="fd-action-meta-value"><?php echo htmlspecialchars($item->category_title); ?></span>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($item->platform_title)) : ?>
                        <li>
                            <span class="fd-action-meta-label"><i class="fas fa-desktop"></i> Platform</span>
                            <span class="fd-action-meta-value"><?php echo htmlspecialchars($item->platform_title); ?></span>
                        </li>
                    <?php endif; ?>
                    <li>
                        <span class="fd-action-meta-label"><i class="fas fa-eye"></i> Views</span>
                        <span class="fd-action-meta-value"><?php echo number_format((int) $item->view_count); ?></span>
                    </li>
                    <li>
                        <span class="fd-action-meta-label"><i class="fas fa-calendar-alt"></i> Added</span>
                        <span class="fd-action-meta-value"><?php echo JHtml::_('date', $item->created, 'M d, Y'); ?></span>
                    </li>
                </ul>
            </div>

        </div><!-- /.fd-detail-aside -->

    </div><!-- /.fd-detail-layout -->

</div><!-- /.fd-detail-wrap -->
