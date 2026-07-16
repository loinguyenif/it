<?php /* D:\wamp64\www\ducloi\it\administrator\components\com_admintools\tmpl\ControlPanel\graphs.blade.php */ ?>
<?php
/**
 * @package   admintools
 * @copyright Copyright (c)2010-2022 Nicholas K. Dionysopoulos / Akeeba Ltd
 * @license   GNU General Public License version 3, or later
 */

/** @var  Akeeba\AdminTools\Admin\View\ControlPanel\Html $this */

// Protect from unauthorized access
defined('_JEXEC') || die;

$template = $this->container->template;
$graphDayFrom = gmdate('Y-m-d', time() - 30 * 24 * 3600);
?>

<div class="akeeba-panel--default">
    <header class="akeeba-block-header">
        <h3><?php echo \Joomla\CMS\Language\Text::_('COM_ADMINTOOLS_CONTROLPANEL_DASHBOARD_EXCEPTIONS'); ?></h3>
    </header>

    <div class="akeeba-form--inline">
        <div class="akeeba-form-group">
            <label><?php echo \Joomla\CMS\Language\Text::_('COM_ADMINTOOLS_CONTROLPANEL_DASHBOARD_FROMDATE'); ?></label>

            <?php if(version_compare(JVERSION, '3.999.999', 'le')): ?>
                <?php echo \Joomla\CMS\HTML\HTMLHelper::_('calendar', $graphDayFrom, 'admintools_graph_datepicker', 'admintools_graph_datepicker'); ?>
            <?php else: ?>
                <input
                        type="date"
                        pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}"
                        name="admintools_graph_datepicker"
                        id="admintools_graph_datepicker"
                        value="<?php echo $this->escape($graphDayFrom); ?>"
                        placeholder="<?php echo \Joomla\CMS\Language\Text::_('COM_CONTACTUS_ITEMS_FIELD_CREATED_ON'); ?>"
                >
            <?php endif; ?>
        </div>

        <div class="akeeba-form-group--actions">
            <button class="akeeba-btn--dark" id="admintools_graph_reload">
                <?php echo \Joomla\CMS\Language\Text::_('COM_ADMINTOOLS_CONTROLPANEL_DASHBOARD_RELOADGRAPHS'); ?>
            </button>
        </div>
    </div>

    <div class="akeeba-graph">
        <span id="akthrobber" class="akion-load-a"></span>
        <canvas id="admintoolsExceptionsLineChart" width="400" height="200"></canvas>

        <div id="admintoolsExceptionsLineChartNoData" style="display:none" class="akeeba-block--success small">
            <p><?php echo \Joomla\CMS\Language\Text::_('COM_ADMINTOOLS_CONTROLPANEL_DASHBOARD_STATS_NODATA'); ?></p>
        </div>
    </div>

    <div class="clearfix"></div>

    <h3><?php echo \Joomla\CMS\Language\Text::_('COM_ADMINTOOLS_CONTROLPANEL_DASHBOARD_EXCEPTSTATS'); ?></h3>
    <div class="akeeba-graph">
        <span id="akthrobber2" class="akion-load-a"></span>
        <canvas id="admintoolsExceptionsPieChart" width="400" height="200"></canvas>

        <div id="admintoolsExceptionsPieChartNoData" style="display:none" class="akeeba-block--success small">
            <p><?php echo \Joomla\CMS\Language\Text::_('COM_ADMINTOOLS_CONTROLPANEL_DASHBOARD_STATS_NODATA'); ?></p>
        </div>
    </div>
</div>
