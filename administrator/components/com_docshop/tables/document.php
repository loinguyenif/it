<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

class DocshopTableDocument extends JTable
{
    public function __construct($db)
    {
        parent::__construct('#__docshop_documents', 'id', $db);
    }

    public function check()
    {
        // Check for valid title
        if (trim($this->title) == '') {
            $this->setError(JText::_('COM_DOCSHOP_ERROR_TITLE_REQUIRED'));
            return false;
        }

        // Check for valid price
        if ($this->price <= 0) {
            $this->setError(JText::_('COM_DOCSHOP_ERROR_PRICE_INVALID'));
            return false;
        }

        // Generate alias if empty
        if (empty($this->alias)) {
            $this->alias = $this->title;
        }
        $this->alias = JApplication::stringURLSafe($this->alias);

        // Check for unique alias
        $db = $this->getDbo();
        $query = $db->getQuery(true)
            ->select('COUNT(*)')
            ->from($db->quoteName('#__docshop_documents'))
            ->where($db->quoteName('alias') . ' = ' . $db->quote($this->alias));

        if ($this->id) {
            $query->where($db->quoteName('id') . ' != ' . (int)$this->id);
        }

        $db->setQuery($query);
        $count = $db->loadResult();

        if ($count > 0) {
            $this->alias = $this->alias . '-' . time();
        }

        return true;
    }

    public function store($updateNulls = false)
    {
        $date = JFactory::getDate();
        $user = JFactory::getUser();

        if ($this->id) {
            // Existing item
            $this->modified = $date->toSql();
        } else {
            // New item
            if (empty($this->created)) {
                $this->created = $date->toSql();
            }
            if (empty($this->created_by)) {
                $this->created_by = $user->get('id');
            }
        }

        return parent::store($updateNulls);
    }
}
?>