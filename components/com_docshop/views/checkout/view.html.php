<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

class DocshopViewCheckout extends JViewLegacy
{
    protected $document;

    public function display($tpl = null)
    {
        $model = $this->getModel();
        $this->document = $model->getItem();

        if (count($errors = $this->get('Errors'))) {
            throw new \Exception(implode("\n", $errors), 500);
        }

        parent::display($tpl);
    }
}
?>