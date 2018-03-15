<?php
/** SportsManagement ein Programm zur Verwaltung f�r Sportarten
 * @version   1.0.05
 * @file      sumevents.php
 * @author    diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright Copyright: � 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license   This file is part of SportsManagement.
 * @package   sportsmanagement
 * @subpackage statistics
 */

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'statistics'.DS.'base.php');


/**
 * SMStatisticSumevents
 * 
 * @package 
 * @author diddi
 * @copyright 2014
 * @version $Id$
 * @access public
 */
class SMStatisticSumevents extends SMStatistic 
{
//also the name of the associated xml file	
	var $_name = 'sumevents';
	
	var $_calculated = 1;
	
	var $_showinsinglematchreports = 1;
	var $_ids = 'stat_ids';
    
	/**
	 * SMStatisticSumevents::__construct()
	 * 
	 * @return void
	 */
	function __construct()
	{
		parent::__construct();
	}
	

	/**
	 * SMStatisticSumevents::getMatchPlayerStat()
	 * 
	 * @param mixed $gamemodel
	 * @param mixed $teamplayer_id
	 * @return
	 */
	function getMatchPlayerStat(&$gamemodel, $teamplayer_id)
	{
		$gamestats = $gamemodel->getPlayersEvents();
		$stat_ids = SMStatistic::getSids($this->_ids);
		
		$res = 0;
		foreach ($stat_ids as $id) 
		{
			if (isset($gamestats[$teamplayer_id][$id])) {
				$res += $gamestats[$teamplayer_id][$id];
			}
		}
		return self::formatValue($res, SMStatistic::getPrecision());
	}

	/**
	 * SMStatisticSumevents::getPlayerStatsByGame()
	 * 
	 * @param mixed $teamplayer_ids
	 * @param mixed $project_id
	 * @return
	 */
	function getPlayerStatsByGame($teamplayer_ids, $project_id)
	{
		$app = JFactory::getApplication();
        $option = JFactory::getApplication()->input->getCmd('option');
        $sids = SMStatistic::getQuotedSids($this->_ids);
		$db = sportsmanagementHelper::getDBConnection();
        $query = $db->getQuery(true);
        
		$quoted_tpids = array();
		foreach ($teamplayer_ids as $tpid) 
        {
			$quoted_tpids[] = $db->Quote($tpid);
		}
        
        $query->select('SUM(ms.event_sum) AS value, ms.match_id');       
        $query->from('#__'.COM_SPORTSMANAGEMENT_TABLE.'_match_event AS ms ');
        $query->where('ms.teamplayer_id IN ('. implode(',', $quoted_tpids) .')');
        $query->where('ms.event_type_id IN ('. implode(',', $sids) .')');
        $query->group('ms.match_id');
               
		$db->setQuery($query);
		$res = $db->loadObjectList('match_id');
        
        if ( COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO )
        {
        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' res<br><pre>'.print_r($res,true).'</pre>'),'');
}

		// Determine total for the whole project
		$totals = new stdclass;
		$totals->statistic_id = $this->id;
		$totals->value = 0;
		if (!empty($res))
		{
			$precision = SMStatistic::getPrecision();
			foreach ($res as $k => $match)
			{
				$totals->value += $match->value;
				$res[$k]->value = self::formatValue($res[$k]->value, $precision);
			}
			$totals->value = self::formatValue($totals->value, $precision);
		}
		$res['totals'] = $totals;
		return $res;
	}

	/**
	 * SMStatisticSumevents::getPlayerStatsByProject()
	 * 
	 * @param mixed $person_id
	 * @param integer $projectteam_id
	 * @param integer $project_id
	 * @param integer $sports_type_id
	 * @return
	 */
	function getPlayerStatsByProject($person_id, $projectteam_id = 0, $project_id = 0, $sports_type_id = 0)
	{
	   $app = JFactory::getApplication();
        $option = JFactory::getApplication()->input->getCmd('option');
        
		$sids = SMStatistic::getSids($this->_ids);
		$res = SMStatistic::getPlayerStatsByProjectForEvents($person_id, $projectteam_id, $project_id, $sports_type_id, $sids);
        
        if ( COM_SPORTSMANAGEMENT_SHOW_DEBUG_INFO )
        {
        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' person_id<br><pre>'.print_r($person_id,true).'</pre>'),'Notice');
        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' projectteam_id<br><pre>'.print_r($projectteam_id,true).'</pre>'),'Notice');
        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' project_id<br><pre>'.print_r($project_id,true).'</pre>'),'Notice');
        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' sports_type_id<br><pre>'.print_r($sports_type_id,true).'</pre>'),'Notice');
        
        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' sids<br><pre>'.print_r($sids,true).'</pre>'),'Notice');
        }
        
		return self::formatValue($res, SMStatistic::getPrecision());
	}
	
	/**
	 * Get players stats
	 * @param $team_id
	 * @param $project_id
	 * @return array
	 */
	function getRosterStats($team_id, $project_id, $position_id)
	{
		$sids = SMStatistic::getSids($this->_ids);
		$res = SMStatistic::getRosterStatsForEvents($team_id, $project_id, $position_id, $sids);
		if (!empty($res))
		{
			$precision = SMStatistic::getPrecision();
			foreach ($res as $k => $player)
			{
				$res[$k]->value = self::formatValue($res[$k]->value, $precision);
			}
		}
		return $res;
	}

	/**
	 * SMStatisticSumevents::getPlayersRanking()
	 * 
	 * @param mixed $project_id
	 * @param mixed $division_id
	 * @param mixed $team_id
	 * @param integer $limit
	 * @param integer $limitstart
	 * @param mixed $order
	 * @return
	 */
	function getPlayersRanking($project_id, $division_id, $team_id, $limit = 20, $limitstart = 0, $order=null)
	{		
		$sids = SMStatistic::getQuotedSids($this->_ids);
		$app = JFactory::getApplication();
		$db = sportsmanagementHelper::getDBConnection();
		$query_core = JFactory::getDbo()->getQuery(true);
        
		$query_select_count = 'COUNT(DISTINCT tp.id) as count';
		
        $query_select_details = 'SUM(ms.event_sum) AS total,'
							  . ' tp.id AS teamplayer_id, tp.person_id, tp.picture AS teamplayerpic,'
							  . ' p.firstname, p.nickname, p.lastname, p.picture, p.country,'
							  . ' st.team_id, pt.picture AS projectteam_picture,'
							  . ' t.picture AS team_picture, t.name AS team_name, t.short_name AS team_short_name';
		
        $query_core	= SMStatistic::getPlayersRankingStatisticQuery($project_id, $division_id, $team_id, $sids, $query_select_count,'event');
        
        $res = new stdclass;
		$db->setQuery($query_core);
        
        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' query_core<br><pre>'.print_r($query_core->dump(),true).'</pre>'),'');
        
		$res->pagination_total = $db->loadResult();
        
        $query_core->clear('select');
        $query_core->select($query_select_details);
        //$query_core->group('tp.id');
		$query_core->order('total '.(!empty($order) ? $order : $this->getParam('ranking_order', 'DESC')).', tp.id'); 
        
        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' query_core<br><pre>'.print_r($query_core->dump(),true).'</pre>'),'');

		$db->setQuery($query_core, $limitstart, $limit);
		$res->ranking = $db->loadObjectList();
	
		if ($res->ranking)
		{
			$precision = $this->getPrecision();
			// get ranks
			$previousval = 0;
			$currentrank = 1 + $limitstart;
			foreach ($res->ranking as $k => $row) 
			{
				if ($row->total == $previousval) {
					$res->ranking[$k]->rank = $currentrank;
				}
				else {
					$res->ranking[$k]->rank = $k + 1 + $limitstart;
				}
				$previousval = $row->total;
				$currentrank = $res->ranking[$k]->rank;

				$res->ranking[$k]->total = self::formatValue($res->ranking[$k]->total, $precision);
			}
		}

		return $res;
	}

	function getTeamsRanking($project_id, $limit = 20, $limitstart = 0, $order=null)
	{		
		$sids = SMStatistic::getQuotedSids($this->_ids);
		$option = JFactory::getApplication()->input->getCmd('option');
	$app = JFactory::getApplication();
		$db = sportsmanagementHelper::getDBConnection();
		
		$query = ' SELECT SUM(ms.event_sum) AS total, '
		       . ' pt.team_id ' 
		       . ' FROM #__joomleague_team_player AS tp '
		       . ' INNER JOIN #__joomleague_person AS p ON p.id = tp.person_id '
		       . ' INNER JOIN #__joomleague_project_team AS pt ON pt.id = tp.projectteam_id '
		       . ' INNER JOIN #__joomleague_team AS t ON pt.team_id = t.id '
		       . ' INNER JOIN #__joomleague_match_event AS ms ON ms.teamplayer_id = tp.id '
		       . '   AND ms.event_type_id IN ('. implode(',', $sids) .')'
		       . ' INNER JOIN #__joomleague_match AS m ON m.id = ms.match_id '
		       . '   AND m.published = 1 '
		       . ' WHERE pt.project_id = '. $db->Quote($project_id)
		       . '   AND p.published = 1 '
		       . '   AND tp.published = 1 '
		       . ' GROUP BY pt.id '
		       . ' ORDER BY total '.(!empty($order) ? $order : $this->getParam('ranking_order', 'DESC')).', tp.id'
		       ;
		$db->setQuery($query, $limitstart, $limit);
        
        $app->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' query<br><pre>'.print_r($query->dump(),true).'</pre>'),'');
        
		$res = $db->loadObjectList();

		if (!empty($res))
		{
			$precision = SMStatistic::getPrecision();
			// get ranks
			$previousval = 0;
			$currentrank = 1 + $limitstart;
			foreach ($res as $k => $row) 
			{
				if ($row->total == $previousval) {
					$res[$k]->rank = $currentrank;
				}
				else {
					$res[$k]->rank = $k + 1 + $limitstart;
				}
				$previousval = $row->total;
				$currentrank = $res[$k]->rank;

				$res[$k]->total = self::formatValue($res[$k]->total, $precision);
			}
		}
		return $res;		
	}

	/**
	 * SMStatisticSumevents::formatValue()
	 * 
	 * @param mixed $value
	 * @param mixed $precision
	 * @return
	 */
	function formatValue($value, $precision)
	{
		if (empty($value))
		{
			$value = 0;
		}
		return number_format($value, $precision);
	}
}