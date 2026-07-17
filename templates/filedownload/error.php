<?php
/**
 * FileDownload template - error page (404 / 500 etc.)
 */
defined('_JEXEC') or die;

$doc = JFactory::getDocument();
$doc->addStyleSheet('https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css');
$doc->addStyleSheet('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
$doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/template.css', ['version' => 'auto']);

$this->error->getCode();
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
    <jdoc:include type="head" />
</head>
<body class="fd-body">

<header class="fd-header">
    <div class="container">
        <div class="d-flex align-items-center fd-header-row">
            <a class="fd-logo d-flex align-items-center" href="<?php echo $this->baseurl; ?>">
                <i class="fas fa-cloud-download-alt fd-logo-icon"></i>
                <span class="fd-logo-text">File<strong>Download</strong></span>
            </a>
        </div>
    </div>
</header>

<div class="container fd-main">
    <div class="fd-listing-card text-center py-5 my-5">
        <div class="display-1 font-weight-bold mb-3" style="color:var(--fd-primary-light);">
            <?php echo (int) $this->error->getCode(); ?>
        </div>
        <h1 class="h3 mb-3">
            <?php echo $this->error->getCode() == 404 ? 'Page Not Found' : 'Something Went Wrong'; ?>
        </h1>
        <p class="text-muted mb-4">
            <?php echo htmlspecialchars($this->error->getMessage()); ?>
        </p>
        <a href="<?php echo $this->baseurl; ?>" class="btn fd-btn-primary" style="border-radius:8px;padding:10px 26px;">
            <i class="fas fa-home mr-2"></i>Back to Home
        </a>
    </div>
</div>

<footer class="fd-footer py-4">
    <div class="container text-center">
        <p class="mb-0">&copy; <?php echo date('Y'); ?> FileDownload. All rights reserved.</p>
    </div>
</footer>

</body>
</html>
