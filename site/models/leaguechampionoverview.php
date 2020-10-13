<?php
/** SportsManagement ein Programm zur Verwaltung für alle Sportarten
 * @version    1.0.05
 * @package    Sportsmanagement
 * @subpackage leaguechampionoverview
 * @file       leaguechampionoverview.php
 * @author     diddipoeler, stony, svdoldie und donclumsy (diddipoeler@arcor.de)
 * @copyright  Copyright: © 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Factory;
use Joomla\Utilities\ArrayHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\MVC\Model\BaseDatabaseModel;
use Joomla\Registry\Registry;
use Joomla\CMS\Log\Log;

//jimport('joomla.utilities.array');
//jimport('joomla.utilities.arrayhelper');

/**
 * sportsmanagementModelleaguechampionoverview
 * 
 * @package 
 * @author Dieter Plöger
 * @copyright 2020
 * @version $Id$
 * @access public
 */
class sportsmanagementModelleaguechampionoverview extends BaseDatabaseModel
{

function _sortRanking(&$ranking,$order='',$order_dir='DESC')
	{
		// Reference global application object
		$app       = Factory::getApplication();
		$jinput    = $app->input;
		//$order     = $jinput->request->get('order', '', 'STR');
		//$order_dir = $jinput->request->get('dir', 'DESC', 'STR');

		$arr2 = array();

		foreach ($ranking as $row)
		{
			$arr2[$row->_teamid] = ArrayHelper::fromObject($row);
		}

		if (!$order)
		{
			$order_dir = 'DESC';
			$sortarray = array();

			foreach ($this->_getRankingCriteria() as $c)
			{
				switch ($c)
				{
					case '_cmpPoints':
						$sortarray['sum_points'] = SORT_DESC;
						break;
					case '_cmpPLAYED':
						$sortarray['cnt_matches'] = SORT_DESC;
						break;
					case '_cmpDiff':
						$sortarray['diff_team_results'] = SORT_DESC;
						break;
					case '_cmpFor':
						$sortarray['sum_team1_result'] = SORT_DESC;
						break;
					case '_cmpPlayedasc':
						$sortarray['cnt_matches'] = SORT_ASC;
						break;
				}
			}

			$arr2 = $this->array_msort($arr2, $sortarray);

			unset($ranking);

			foreach ($arr2 as $key => $row)
			{
				$ranking[$key] = ArrayHelper::toObject($row, 'JSMRankingTeamClass');
			}
		}
		else //     if ( !$order_dir)
		{
			switch ($order)
			{
				case 'played':
					// Uasort($ranking, array(self::$classname, "playedCmp"));
					$sortarray['cnt_matches'] = ($order_dir == 'DESC') ? SORT_DESC : SORT_ASC;
					break;
				case 'name':
					// Uasort($ranking, array(self::$classname, "teamNameCmp"));
					$sortarray['_name'] = ($order_dir == 'DESC') ? SORT_DESC : SORT_ASC;
					break;
				case 'rank':
					break;
				case 'won':
					// Uasort($ranking, array(self::$classname, "wonCmp"));
					$sortarray['cnt_won'] = ($order_dir == 'DESC') ? SORT_DESC : SORT_ASC;
					break;
				case 'draw':
					// Uasort($ranking, array(self::$classname, "drawCmp"));
					$sortarray['cnt_draw'] = ($order_dir == 'DESC') ? SORT_DESC : SORT_ASC;
					break;
				case 'loss':
					// Uasort($ranking, array(self::$classname, "lossCmp"));
					$sortarray['cnt_lost'] = ($order_dir == 'DESC') ? SORT_DESC : SORT_ASC;
					break;
				case 'winpct':
					uasort($ranking, array(self::$classname, "_winpctCmp"));

					// $sortarray['_name'] = ( $order_dir == 'DESC' ) ? SORT_DESC : SORT_ASC;
					break;
				case 'quot':
					uasort($ranking, array(self::$classname, "_quotCmp"));

					// $sortarray['_name'] = ( $order_dir == 'DESC' ) ? SORT_DESC : SORT_ASC;
					break;
				case 'goalsp':
					// Uasort($ranking, array(self::$classname, "goalspCmp"));
					$sortarray['sum_team1_result'] = ($order_dir == 'DESC') ? SORT_DESC : SORT_ASC;
					break;
				case 'goalsfor':
					// Uasort($ranking, array(self::$classname, "goalsforCmp"));
					$sortarray['sum_team1_result'] = ($order_dir == 'DESC') ? SORT_DESC : SORT_ASC;
					break;
				case 'goalsagainst':
					// Uasort($ranking, array(self::$classname, "goalsagainstCmp"));
					$sortarray['sum_team2_result'] = ($order_dir == 'DESC') ? SORT_DESC : SORT_ASC;
					break;
				case 'legsdiff':
					// Uasort($ranking, array(self::$classname, "legsdiffCmp"));
					$sortarray['diff_team_legs'] = ($order_dir == 'DESC') ? SORT_DESC : SORT_ASC;
					break;
				case 'legsratio':
					// Uasort($ranking, array(self::$classname, "legsratioCmp"));
					$sortarray['legsRatio'] = ($order_dir == 'DESC') ? SORT_DESC : SORT_ASC;
					break;
				case 'diff':
					// Uasort($ranking, array(self::$classname, "diffCmp"));
					$sortarray['diff_team_legs'] = ($order_dir == 'DESC') ? SORT_DESC : SORT_ASC;
					break;
				case 'points':
					// Uasort($ranking, array(self::$classname, "_pointsCmp"));
					$sortarray['sum_points'] = ($order_dir == 'DESC') ? SORT_DESC : SORT_ASC;

					// $sortarray['_name'] = ( $order_dir == 'DESC' ) ? SORT_DESC : SORT_ASC;
					break;
				case 'start':
					// Uasort($ranking, array(self::$classname, "startCmp"));
					$sortarray['start_points'] = ($order_dir == 'DESC') ? SORT_DESC : SORT_ASC;
					break;
				case 'bonus':
					// Uasort($ranking, array(self::$classname, "bonusCmp"));
					$sortarray['bonus_points'] = ($order_dir == 'DESC') ? SORT_DESC : SORT_ASC;
					break;
				case 'negpoints':
					// Uasort($ranking, array(self::$classname, "negpointsCmp"));
					$sortarray['neg_points'] = ($order_dir == 'DESC') ? SORT_DESC : SORT_ASC;
					break;
				case 'pointsratio':
					// Uasort($ranking, array(self::$classname, "pointsratioCmp"));
					$sortarray['pointsRatio'] = ($order_dir == 'DESC') ? SORT_DESC : SORT_ASC;
					break;

				default:
					if (method_exists($this, $order . 'Cmp'))
					{
						uasort($ranking, array($this, $order . 'Cmp'));
					}
					break;
			}

			$arr2 = $this->array_msort($arr2, $sortarray);

			unset($ranking);

			foreach ($arr2 as $key => $row)
			{
				$ranking[$key] = ArrayHelper::toObject($row, 'JSMRankingTeamClass');
			}
		}

		return $ranking;

	}




}

?>
