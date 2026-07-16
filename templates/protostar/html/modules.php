<?php
/**
 * Custom module chrome for the FileDownload template.
 * These wrap module output so it drops straight into the markup
 * the template CSS expects (see css/template.css).
 */
defined('_JEXEC') or die;

/**
 * Category / sidebar list module chrome.
 * Expected module content: a plain <ul><li>...</li></ul> list of category links.
 */
function modChrome_fd_list($module, &$params, &$attribs)
{
    if (!empty($module->content)) {
        echo '<div class="fd_list">';
        echo $module->content;
        echo '</div>';
    }
}

/**
 * Top downloads module chrome.
 */
function modChrome_fd_topdownloads($module, &$params, &$attribs)
{
    if (!empty($module->content)) {
        echo '<div class="fd_topdownloads">';
        echo $module->content;
        echo '</div>';
    }
}

/**
 * Sidebar promo card module chrome (upload CTA, ads, etc.)
 */
function modChrome_fd_promo($module, &$params, &$attribs)
{
    echo '<div class="fd_promo">';
    if ($module->showtitle) {
        echo '<h4>' . $module->title . '</h4>';
    }
    echo $module->content;
    echo '</div>';
}

/**
 * Plain wrapper - no extra title, just content in a spacer div. Used for
 * content-top / content-bottom module positions.
 */
function modChrome_fd_plain($module, &$params, &$attribs)
{
    if (!empty($module->content)) {
        echo '<div class="fd-plain-module mb-3">';
        echo $module->content;
        echo '</div>';
    }
}

/**
 * Raw passthrough, no wrapper div at all (footer "about" text).
 */
function modChrome_fd_raw($module, &$params, &$attribs)
{
    echo $module->content;
}

/**
 * Footer social icon row.
 */
function modChrome_fd_social($module, &$params, &$attribs)
{
    echo '<div class="fd-social">';
    echo $module->content;
    echo '</div>';
}

/**
 * Footer link column list (Quick Links / Categories / Support).
 */
function modChrome_fd_footerlist($module, &$params, &$attribs)
{
    echo '<ul class="fd_footerlist">';
    echo $module->content;
    echo '</ul>';
}

/**
 * Newsletter signup form wrapper.
 */
function modChrome_fd_newsletter($module, &$params, &$attribs)
{
    echo '<div class="fd_newsletter">';
    echo $module->content;
    echo '</div>';
}
