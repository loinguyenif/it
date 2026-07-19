<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

class DocshopRouter extends JComponentRouterBase
{
    public function build(&$query)
    {
        $segments = array();

        if (isset($query['view'])) {
            $segments[] = $query['view'];
            unset($query['view']);
        }

        if (isset($query['id'])) {
            $segments[] = $query['id'];
            unset($query['id']);
        }

        // Keep layout and other params in the query string — do not consume them
        // so Joomla appends them as ?layout=success etc.

        return $segments;
    }

    public function parse(&$segments)
    {
        $vars = array();

        if (count($segments) > 0) {
            $vars['view'] = array_shift($segments);
        }

        if (count($segments) > 0) {
            $vars['id'] = array_shift($segments);
        }

        return $vars;
    }
}
