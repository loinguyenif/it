<?php
/**
 * FileDownload Joomla 3 Template
 * Built with Bootstrap 4
 */
defined('_JEXEC') or die;

$app  = JFactory::getApplication();
$doc  = JFactory::getDocument();
$lang = JFactory::getLanguage();
$user = JFactory::getUser();

// Params
$templateColor = $this->params->get('templateColor', '#1e3a8a');
$darkColor     = $this->params->get('darkColor', '#0b1739');
$siteName      = $this->params->get('siteName', 'FileDownload');
$showHero      = $this->params->get('showHero', 1);

// Direction / language
$doc->setMetaData('viewport', 'width=device-width, initial-scale=1.0');

// Bootstrap 4 (CDN - swap for local /js /css copies if you need offline builds)
$doc->addStyleSheet('templates/' . $this->template . '/css/bootstrap.min.css', ['version' => 'auto'], ['integrity' => '', 'crossorigin' => 'anonymous']);
$doc->addStyleSheet('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css');
$doc->addStyleSheet('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

// Template CSS
$doc->addStyleSheet($this->baseurl . '/templates/' . $this->template . '/css/template.css', ['version' => 'auto']);

// JS (jQuery first - Joomla 3 core already ships jQuery via Bootstrap framework, but we load explicitly for standalone use)
JHtml::_('jquery.framework');
$doc->addScript('https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js', ['version' => 'auto'], ['defer' => true]);
$doc->addScript($this->baseurl . '/templates/' . $this->template . '/js/template.js', ['version' => 'auto'], ['defer' => true]);

// Inline CSS vars driven by backend template params
$inlineCss = ":root{--fd-primary:{$templateColor};--fd-dark:{$darkColor};}";
$doc->addStyleDeclaration($inlineCss);
?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
    <jdoc:include type="head" />
</head>
<body class="fd-body">

<!-- ============ HEADER ============ -->
<header class="fd-header">
    <div class="container">
        <div class="d-flex align-items-center justify-content-between fd-header-row">

            <!-- Logo -->
            <a class="fd-logo d-flex align-items-center" href="<?php echo $this->baseurl; ?>">
                <i class="fas fa-cloud-download-alt fd-logo-icon"></i>
                <span class="fd-logo-text">File<strong><?php echo htmlspecialchars($siteName === 'FileDownload' ? 'Download' : $siteName); ?></strong></span>
            </a>

            <!-- Main nav -->
            <nav class="fd-nav d-none d-lg-flex">
                <jdoc:include type="modules" name="menu" style="none" />
            </nav>

            <!-- Right side actions -->
            <div class="fd-header-actions d-flex align-items-center">
                <button class="fd-theme-toggle d-none d-md-inline-flex" type="button" id="fdThemeToggle" aria-label="Toggle dark mode">
                    <i class="fas fa-moon"></i>
                </button>

                <jdoc:include type="modules" name="topbar" style="none" />

                <?php /* if ($user->guest) : ?>
                    <a class="fd-login-link d-none d-md-inline-block" href="<?php echo JRoute::_('index.php?option=com_users&view=login'); ?>">Login</a>
                    <a class="btn fd-btn-register d-none d-md-inline-block" href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>">Register</a>
                <?php else : ?>
                    <a class="fd-login-link d-none d-md-inline-block" href="<?php echo JRoute::_('index.php?option=com_users&view=profile'); ?>">
                        <?php echo htmlspecialchars($user->name); ?>
                    </a>
                    <a class="btn fd-btn-register d-none d-md-inline-block" href="<?php echo JRoute::_('index.php?option=com_users&task=user.logout&' . JSession::getFormToken() . '=1'); ?>">Logout</a>
                <?php endif; */ ?>
                <a class="btn fd-btn-register d-none d-md-inline-block" href="<?php echo JRoute::_('index.php?option=com_docshop&view=document&layout=donate&id=1'); ?>">Donate</a>

                <button class="fd-mobile-toggle d-lg-none" type="button" data-toggle="collapse" data-target="#fdMobileNav" aria-label="Menu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>

        <!-- Mobile nav -->
        <div class="collapse d-lg-none" id="fdMobileNav">
            <nav class="fd-nav-mobile">
                <jdoc:include type="modules" name="menu" style="none" />
            </nav>
        </div>
    </div>
</header>

<div class="fd-main container">
    <div class="row fd-main-row">

        <!-- ============ SIDEBAR ============ -->
        <aside class="col-lg-3 fd-sidebar">

            <div class="fd-card fd-categories-card">
                <div class="fd-card-title fd-categories-title">
                    <i class="fas fa-th-large"></i> All Categories
                </div>
                <jdoc:include type="modules" name="sidebar-categories" style="fd_list" />
            </div>

            <div class="fd-card">
                <h3 class="fd-card-heading">Top Views</h3>
                <jdoc:include type="modules" name="sidebar-top" style="fd_topdownloads" />
            </div>

            <div class="fd-card fd-promo-card">
                <jdoc:include type="modules" name="sidebar-promo" style="fd_promo" />
            </div>

        </aside>

        <!-- ============ CONTENT ============ -->
        <div class="col-lg-9 fd-content">

            <?php if ($showHero && $app->getMenu()->getActive() && $app->getMenu()->getActive()->home) : ?>
            <div class="fd-hero">
                <div class="fd-hero-text">
                    <h1>Download Files Fast &amp; Secure</h1>
                    <p>We provide legacy Joomla extensions and WordPress plugins to help maintain older websites. Whether you need discontinued code, compatibility fixes, or long-term support, we keep your legacy systems running reliably.</p>                    
                </div>
                <div class="fd-hero-art" aria-hidden="true">
                    <div class="fd-hero-window">
                        <div class="fd-hero-window-bar">
                            <span></span><span></span><span></span>
                        </div>
                        <div class="fd-hero-window-body">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <jdoc:include type="modules" name="banner" style="none" />

            <div class="fd-breadcrumbs">
                <jdoc:include type="modules" name="breadcrumbs" style="none" />
            </div>

            <jdoc:include type="modules" name="content-top" style="fd_plain" />

            <main role="main">
                <jdoc:include type="component" />
            </main>

            <jdoc:include type="modules" name="content-bottom" style="fd_plain" />

        </div>
    </div>
</div>

<!-- ============ FOOTER ============ -->
<footer class="fd-footer">
    <div class="container">
        <div class="row fd-footer-row">
            <div class="col-lg-3 col-md-6 fd-footer-col">
                <a class="fd-logo d-flex align-items-center mb-3" href="<?php echo $this->baseurl; ?>">
                    <i class="fas fa-cloud-download-alt fd-logo-icon"></i>
                    <span class="fd-logo-text">File<strong>Download</strong></span>
                </a>
                <p class="fd-footer-about">
                    <jdoc:include type="modules" name="footer-about" style="fd_raw" />
                </p>
            </div>

            <div class="col-lg-3 col-md-6 fd-footer-col">
                <h4>Quick Links</h4>
                <jdoc:include type="modules" name="footer-links" />
            </div>

            <div class="col-lg-3 col-md-6 fd-footer-col">
                <h4>Categories</h4>
                <jdoc:include type="modules" name="footer-categories" style="fd_footerlist" />
            </div>

            <div class="col-lg-3 col-md-6 fd-footer-col">
                <h4>Support</h4>
                <jdoc:include type="modules" name="footer-support" />
            </div>
        </div>

        <!--div class="fd-newsletter-row">
            <div class="fd-newsletter-text">
                <h4>Subscribe to Newsletter</h4>
                <p>Get the latest updates and new files in your inbox.</p>
            </div>
            <jdoc:include type="modules" name="footer-newsletter" style="fd_newsletter" />
        </div-->
        <hr class="fd-footer-separator" />
        <div class="fd-footer-bottom d-flex align-items-center justify-content-between flex-wrap">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> FileDownload. All rights reserved.</p>
            <div class="fd-lang-switch">
                <i class="fas fa-globe"></i> English
            </div>
        </div>
    </div>
</footer>

<jdoc:include type="modules" name="debug" style="none" />

</body>
</html>
