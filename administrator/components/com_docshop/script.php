<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

jimport('joomla.filesystem.file');

class com_docshopInstallerScript
{
    public function install($parent)
    {
        $this->runSchema();
    }

    public function update($parent)
    {
        $this->runSchema();
    }

    public function postflight($type, $parent)
    {
        if (in_array($type, array('install', 'update', 'discover_install'), true)) {
            $this->runSchema();
        }
    }

    private function runSchema()
    {
        $db = JFactory::getDbo();
        $sqlFile = JPATH_ADMINISTRATOR . '/components/com_docshop/sql/mysql/install.sql';

        if (!JFile::exists($sqlFile)) {
            return;
        }

        $buffer = JFile::read($sqlFile);

        if ($buffer === false) {
            return;
        }

        $queries = $db->splitSql($buffer);

        foreach ($queries as $query) {
            $query = trim($query);

            if ($query === '') {
                continue;
            }

            $db->setQuery($query);
            $db->execute();
        }

        $this->ensureDocumentColumns($db);
    }

    private function ensureDocumentColumns($db)
    {
        $columns = $db->getTableColumns('#__docshop_documents', false);

        if (!isset($columns['youtube_url'])) {
            $db->setQuery(
                'ALTER TABLE ' . $db->quoteName('#__docshop_documents') .
                ' ADD COLUMN ' . $db->quoteName('youtube_url') . ' VARCHAR(500) NULL AFTER ' . $db->quoteName('description')
            );
            $db->execute();
        }

        if (!isset($columns['platform_id'])) {
            $db->setQuery(
                'ALTER TABLE ' . $db->quoteName('#__docshop_documents') .
                ' ADD COLUMN ' . $db->quoteName('platform_id') . ' INT(11) NULL AFTER ' . $db->quoteName('category_id')
            );
            $db->execute();
        }
    }
}
