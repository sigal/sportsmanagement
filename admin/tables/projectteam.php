<?php
/**
 * SportsManagement ein Programm zur Verwaltung für alle Sportarten
 * @version    1.0.05
 * @package    Sportsmanagement
 * @subpackage tables
 * @file       projectteam.php
 * @author     diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright  Copyright: © 2013-2023 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Restricted access');

/**
 * sportsmanagementTableProjectteam
 *
 * @package
 * @author
 * @copyright diddi
 * @version   2014
 * @access    public
 */
class sportsmanagementTableProjectteam extends JSMTable
{
	/**
	 * Constructor
	 *
	 * @param   object Database connector object
	 *
	 * @since 1.0
	 */
	function __construct(&$db)
	{
		$db = sportsmanagementHelper::getDBConnection();
		parent::__construct('#__sportsmanagement_project_team', 'id', $db);
	}

}
