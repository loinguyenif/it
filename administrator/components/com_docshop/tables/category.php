<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

class DocshopTableCategory extends JTable
{
    public function __construct($db)
    {
        parent::__construct('#__docshop_categories', 'id', $db);
    }

    public function check()
    {
        if (trim($this->title) === '') {
            $this->setError(JText::_('COM_DOCSHOP_ERROR_TITLE_REQUIRED'));
            return false;
        }

        if (empty($this->alias)) {
            $this->alias = $this->title;
        }

        $this->alias = JApplication::stringURLSafe($this->alias);

        if ($this->alias === '') {
            $this->alias = JFactory::getDate()->format('Y-m-d-H-i-s');
        }

        $db = $this->getDbo();
        $query = $db->getQuery(true)
            ->select('COUNT(*)')
            ->from($db->quoteName('#__docshop_categories'))
            ->where($db->quoteName('alias') . ' = ' . $db->quote($this->alias));

        if ($this->id) {
            $query->where($db->quoteName('id') . ' != ' . (int) $this->id);
        }

        $db->setQuery($query);

        if ((int) $db->loadResult() > 0) {
            $this->alias .= '-' . time();
        }

        if (empty($this->created)) {
            $this->created = JFactory::getDate()->toSql();
        }

        return true;
    }
}
