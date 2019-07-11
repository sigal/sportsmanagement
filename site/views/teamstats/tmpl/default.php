<?php 
/** SportsManagement ein Programm zur Verwaltung f�r alle Sportarten
 * @version   1.0.05
 * @file      default.php
 * @author    diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright Copyright: � 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license   GNU General Public License version 2 or later; see LICENSE.txt
 * @package   sportsmanagement
 * @subpackage teamstats
 */

defined( '_JEXEC' ) or die( 'Restricted access' ); 

// Set display style dependent on what stat parts should be shown
$show_general = 'none';
if ( $this->config['show_general_stats'] == 1 )
{
	$show_general = 'inline';
}

$show_goals = 'none';
if ( $this->config['show_goals_stats'] == 1 )
{
	$show_goals = 'inline';
}

$show_attendance = 'none';
if ( $this->config['show_attendance_stats'] == 1 )
{
	$show_attendance = 'inline';
}

$show_flash = '';
if ( $this->config['show_goals_stats_flash'] == 0 )
{
	$show_flash = 'display:none;';
}

/**
 * Make sure that in case extensions are written for mentioned (common) views,
 * that they are loaded i.s.o. of the template of this view
 */
$templatesToLoad = array('globalviews');
sportsmanagementHelper::addTemplatePaths($templatesToLoad, $this);
?>
<div class="<?php echo $this->divclasscontainer;?>" id="defaultteamstats">
<?php 
echo $this->loadTemplate('projectheading'); 

if ($this->config['show_sectionheader'])
{
echo $this->loadTemplate('sectionheader'); 
}

if ($this->config['show_general_stats'])
{
echo $this->loadTemplate('stats'); 
}

if ($this->config['show_attendance_stats'])
{
echo $this->loadTemplate('attendance_stats'); 
}	

if ( $this->config['show_goals_stats_flash'] )
{
echo $this->loadTemplate('flashchart'); 
}

echo $this->loadTemplate('jsminfo');
?>
</div>
