<?php
/**
 * SportsManagement ein Programm zur Verwaltung für alle Sportarten
 * @version    1.0.05
 * @package    Sportsmanagement
 * @subpackage rosteralltime
 * @file       default.php
 * @author     diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright  Copyright: © 2013-2023 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Component\ComponentHelper;

/** welche joomla version ? */
if (version_compare(substr(JVERSION, 0, 3), '4.0', 'ge'))
{
	HTMLHelper::_('behavior.keepalive');
}
elseif (version_compare(substr(JVERSION, 0, 3), '3.0', 'ge'))
{
HTMLHelper::_('behavior.tooltip');
HTMLHelper::_('behavior.framework');
HTMLHelper::_('behavior.modal');
}

$templatesToLoad = array('globalviews');
sportsmanagementHelper::addTemplatePaths($templatesToLoad, $this);

echo $this->loadTemplate('jsm_warnings');
echo $this->loadTemplate('jsm_tips');
echo $this->loadTemplate('jsm_notes');

?>
<script language="javascript" type="text/javascript">
    function tableOrdering(order, dir, task) {
        var form = document.adminForm;

        form.filter_order.value = order;
        form.filter_order_Dir.value = dir;
        document.adminForm.submit(task);
    }

    function searchPerson(val) {
        var s = document.getElementById("filter_search");
        s.value = val;
        Joomla.submitform('', this.form)
    }
</script>
<div class="<?php echo $this->divclasscontainer; ?>" id="rosteralltime">
<form name="adminForm" id="adminForm" action="<?php echo htmlspecialchars($this->uri->toString()); ?>"
          method="post">
        <fieldset class="filters">
            <legend class="hidelabeltxt">
				<?php echo Text::_('JGLOBAL_FILTER_LABEL'); ?>
            </legend>
            <div class="filter-search">
                <input type="text" name="filter_search" id="filter_search"
                       value="<?php echo $this->escape($this->filter); ?>" class="inputbox"
                       onchange="document.getElementById('adminForm').submit();"/>
                <button type="submit" class="btn" title=""><i
                            class="icon-search"><?php echo Text::_('JGLOBAL_FILTER_BUTTON'); ?></i></button>
                <button type="button" class="btn" title=""
                        onclick="document.id('filter_search').value = '';this.form.submit();"><i
                            class="icon-remove"><?php echo Text::_('JSEARCH_FILTER_CLEAR'); ?></i></button>

				<?php
				$startRange = ComponentHelper::getParams($this->jinput->getCmd('option'))->get('character_filter_start_hex', '0');
				$endRange   = ComponentHelper::getParams($this->jinput->getCmd('option'))->get('character_filter_end_hex', '0');

				for ($i = $startRange; $i <= $endRange; $i++)
				{
					printf("<a href=\"javascript:searchPerson('%s')\">%s</a>&nbsp;&nbsp;&nbsp;&nbsp;", '&#' . $i . ';', '&#' . $i . ';');
				}
				?>
            </div>

            <input type="hidden" name="filter_order" value="<?php echo $this->sortColumn; ?>"/>
            <input type="hidden" name="filter_order_Dir" value="<?php echo $this->sortDirection; ?>"/>
            <input type="hidden" name="limitstart" value=""/>

            <div class="display-limit">
				<?php echo Text::_('JGLOBAL_DISPLAY_NUM'); ?>&#160;
				<?php echo $this->pagination->getLimitBox(); ?>
            </div>

        </fieldset>
	<?PHP
	echo $this->loadTemplate($this->config['show_rosteralllayout']);
	echo $this->loadTemplate('jsminfo');
	?>
    </form>
</div>
