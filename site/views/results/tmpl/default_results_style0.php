<?php
/**
 * SportsManagement ein Programm zur Verwaltung für alle Sportarten
 * @version    1.0.05
 * @package    Sportsmanagement
 * @subpackage results
 * @file       default_results_style0.php
 * @author     diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright  Copyright: © 2013-2023 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Uri\Uri;

//echo '<pre>'.print_r($this->project,true).'</pre>';
?>
<style>
  #sbox-window {
    background-color: transparent;
  }
  .ex1 {
    width: auto;
    text-align: center !important;
  }

</style>
<script>
	function example_alertBox(boxText) {
		var options = {size: {x: 300, y: 250}};
		SqueezeBox.initialize(options);
		SqueezeBox.setContent('string', 'Spielnummer: ' + boxText);
	}
</script>

<?php





$nbcols        = 6;
$nbcols_header = 0;
$dates         = sportsmanagementViewResults::sortByDate($this->matches);

if ($this->config['show_division'])
{
	$nbcols++;
}

if ($this->config['show_match_number'])
{
	$nbcols++;
}

if ($this->config['show_events'])
{
	$nbcols++;
}

if ($this->config['show_match_summary'])
{
	$nbcols++;
}

if ($this->config['show_time'])
{
	$nbcols++;
}

if ($this->config['show_playground'] || $this->config['show_playground_alert'])
{
	$nbcols++;
}

if ($this->config['show_referee'])
{
	$nbcols++;
}

if ($this->config['show_scoresheet'])
{
	$nbcols++;
}

if ($this->config['result_style'] == 2)
{
	$nbcols++;
}

if ($this->config['show_attendance_column'])
{
	$nbcols++;
	$nbcols_header++;
}

if ($this->config['show_comments_count'])
{
	$commmentsInstance = sportsmanagementModelComments::CreateInstance($this->config);

	if (!$commmentsInstance->isEnabled())
	{
		$comJcomments = false;
	}
	else
	{
		$comJcomments = true;
		$nbcols++;
		$nbcols_header++;
	}
}
?>

<table class="<?PHP echo $this->config['table_class']; ?> " id="results">
	<?php
	foreach ($dates as $date => $games)
	{
	?>
		<!-- DATE HEADER -->
		<thead>
			<tr id="results-header" class="">

				<?php
				$timestamp = strtotime($date);

				if (($this->config['show_attendance_column']) || ($this->config['show_comments_count'] > 0))
				{
					?>
					<th id="results-header-head-column-count" colspan="<?php echo $nbcols - $nbcols_header; ?>">
						<span itemprop="startDate" datetime="<?php echo $date; ?>" content="<?php echo $date; ?>"></span>
						<?php
						if ($date == "0000-00-00")
						{
							echo Text::_('COM_SPORTSMANAGEMENT_NEXTMATCH_DATE_EMPTY');
						}
						else
						{
							echo HTMLHelper::date($date, Text::_('COM_SPORTSMANAGEMENT_RESULTS_GAMES_DATE_DAY'));
						}

						if ($this->config['show_matchday_dateheader'])
						{
							echo ' - ' . Text::sprintf('COM_SPORTSMANAGEMENT_RESULTS_GAMEDAY_NB', $this->roundcode);
						}
						?>
					</th>
					<?php
					if ($this->config['show_attendance_column'])
					{
						?>
						<th id="results-header-attendance" class="right"><?php echo Text::_('COM_SPORTSMANAGEMENT_RESULTS_ATTENDANCE'); ?></th>
					<?php
					}

					if ($this->config['show_comments_count'] > 0)
					{
						?>
						<th id="results-header-comments" class="center"><?php echo Text::_('COM_SPORTSMANAGEMENT_RESULTS_COMMENTS'); ?></th>
						<?php
					}
				}
				else
				{
					?>
					<th id="results-header-head" colspan="<?php echo $nbcols; ?>">
						<span itemprop="startDate">
							<?php
							if ($date == "0000-00-00")
							{
								echo Text::_('COM_SPORTSMANAGEMENT_NEXTMATCH_DATE_EMPTY');
							}
							else
							{
								echo HTMLHelper::date($date, Text::_('COM_SPORTSMANAGEMENT_RESULTS_GAMES_DATE_DAY'));
							}

							if ($this->config['show_matchday_dateheader'])
							{
								echo ' - ' . Text::sprintf('COM_SPORTSMANAGEMENT_RESULTS_GAMEDAY_NB', $this->roundcode);
							}
							?>
						</span>
					</th>
					<?php
				}
				?>
			</tr>
		</thead>
		<!-- DATE HEADER END-->
		<!-- GAMES -->
		<?php
		$k = 0;
		$history_link = '';

		foreach ($games as $game)
		{
			$this->game = $game;

			if ($game->published)
			{
				if (isset($game->team1_result))
				{
					$routeparameter                       = array();
					$routeparameter['cfg_which_database'] = Factory::getApplication()->input->getInt('cfg_which_database', 0);
					$routeparameter['s']                  = Factory::getApplication()->input->getInt('s', 0);
					$routeparameter['p']                  = $this->project->slug;
					$routeparameter['mid']                = $game->slug;
					$report_link                          = sportsmanagementHelperRoute::getSportsmanagementRoute('matchreport', $routeparameter);
					$history_link                         = sportsmanagementHelperRoute::getSportsmanagementRoute('nextmatch', $routeparameter);
				}
				else
				{
					$routeparameter                       = array();
					$routeparameter['cfg_which_database'] = Factory::getApplication()->input->getInt('cfg_which_database', 0);
					$routeparameter['s']                  = Factory::getApplication()->input->getInt('s', 0);
					$routeparameter['p']                  = $this->project->slug;
					$routeparameter['mid']                = $game->slug;
					$report_link                          = sportsmanagementHelperRoute::getSportsmanagementRoute('nextmatch', $routeparameter);
					$history_link                         = '';
				}

				if (!$this->config['show_historylink'])
				{
					$history_link = '';
				}

				$events = sportsmanagementModelProject::getMatchEvents($game->id, 0, 0, Factory::getApplication()->input->getInt('cfg_which_database', 0));
				$subs   = sportsmanagementModelProject::getMatchSubstitutions($game->id, Factory::getApplication()->input->getInt('cfg_which_database', 0));

				if ($this->config['use_tabs_events'])
				{
					$hasEvents = (count($events) + count($subs) > 0 && $this->config['show_events']);
				}
				else
				{
					// No subs are shown when not using tabs for displaying events so don't check for that
					$hasEvents = (count($events) > 0 && $this->config['show_events']);
				}

				?>
				<!-- Format teams correctly -->
				<?php
				if ($this->config['switch_home_guest'])
				{
					$team1 = $this->teams[$game->projectteam2_id];
					$team2 = $this->teams[$game->projectteam1_id];
				}
				else
				{
					$team1 = $this->teams[$game->projectteam1_id];
					$team2 = $this->teams[$game->projectteam2_id];
				}

				$favStyle  = '';
				$color     = '';
				$isFavTeam = in_array($team1->id, $this->favteams) ? 1 : in_array($team2->id, $this->favteams);

				if ($isFavTeam && $this->project->fav_team_highlight_type == 1 && $this->config['highlight_fav'] == 1)
				{
					if (trim($this->project->fav_team_color) != "")
					{
						$color = trim($this->project->fav_team_color);
					}

					$format   = "%s";
					$favStyle = ' style="';
					$favStyle .= ($this->project->fav_team_text_bold != '') ? 'font-weight:bold;' : '';
					$favStyle .= (trim($this->project->fav_team_text_color) != '') ? 'color:' . trim($this->project->fav_team_text_color) . ';' : '';
					$favStyle .= ($color != '') ? 'background-color:' . $color . ';' : '';

					if ($favStyle != ' style="')
					{
						$favStyle .= '"';
					}
					else
					{
						$favStyle = '';
					}
				}
				?>
				<div itemscope itemtype="http://schema.org/SportsEvent">
					<?php
					$config_tmp = $this->config;
					$config_tmp['mark_now_playing'] = 0;
					$starttime = sportsmanagementHelperHtml::showMatchTime($game, $config_tmp, $this->overallconfig, $this->project);
					?>
					<span itemprop="startDate" datetime="<?php echo $date; ?>T<?php echo $starttime; ?>" content="<?php echo $date; ?>T<?php echo $starttime; ?>"></span>
					<?php
					$endtime = sportsmanagementHelperHtml::showMatchTime($game, $config_tmp, $this->overallconfig, $this->project);

					$ergebnis = date('H:i:s', strtotime('+' . $this->project->game_regular_time + $this->project->halftime . ' minutes', strtotime($game->match_date)));

					//echo '<pre>'.print_r($ergebnis,true).'</pre>';

					?>
					<span itemprop="endDate" datetime="<?php echo $date; ?>T<?php echo $ergebnis; ?>" content="<?php echo $date; ?>T<?php echo $ergebnis; ?>"></span>

					<span itemprop="name" content="<?php echo $this->project->name; ?>"></span>
					<span itemprop="description" content="<?php echo $this->project->round_name; ?>"></span>
					<span itemprop="image" content="<?php echo Uri::base() . $this->project->picture; ?>"></span>
					<span itemprop="sport" content="<?php echo Text::_($this->project->sport_type_name); ?>"></span>
					<div itemprop="location"itemscope itemtype="http://schema.org/Place">
						<span itemprop="name" content="<?php echo $game->playground_name; ?>"></span>
						<div itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
							<span itemprop="streetAddress" content="<?php echo $game->playground_address; ?>"</span>
							<span itemprop="postalCode" content="<?php echo $game->playground_zipcode; ?>"</span>
						</div>
					</div>

					<tr class="team<?php echo $game->projectteam1_id; ?>  team<?php echo $game->projectteam2_id; ?> "<?php echo $favStyle; ?> >
						<?php
						if ($this->config['show_match_number'])
						{
							?>
							<!-- show matchnumber -->
							<td width="" class="" id="matchnumber"><?php
								if ($game->match_number > 0)
								{
									echo $game->match_number;
								}
								else
								{
									echo "&nbsp;";
								}
							?></td>
							<?php
						}
						?>
						<?php
						if ($this->config['show_events'])
						{
							?>
							<!-- show matcheventsimage -->
							<td width="" class=""><?php
							if ($hasEvents)
							{
								$link   = "javascript:void(0);";
								$img    = HTMLHelper::image('media/com_sportsmanagement/jl_images/events.png', 'events.png');
								$params = array("title" => Text::_('COM_SPORTSMANAGEMENT_TEAMPLAN_EVENTS'),
									"onclick" => 'switchMenu(\'info' . $game->id . '\');return false;');
								echo HTMLHelper::link($link, $img, $params);
							}
							else
							{
								echo "&nbsp;";
							}
							?>
							</td>
							<?php
						}

						// Diddipoeler
						if ($this->config['show_match_summary'])
						{
							$imgTitle = $team1->name;
							$imgTitle .= ' - ' . $team2->name;
							$imgsummary = 'media/com_sportsmanagement/jl_images/discuss.gif';
							$imgcontent = 'media/com_sportsmanagement/jl_images/information.png';
							?>
							<td width="" class="">
								<?PHP
								if ($game->content_id)
								{
									echo sportsmanagementHelperHtml::getBootstrapModalImage(
										'match_content' . $game->id,
										$imgcontent,
										$imgTitle,
										'20',
										Uri::base() . 'index.php?tmpl=component&option=com_content&view=article&id=' . $game->content_id,
										$this->modalwidth,
										$this->modalheight,
										$this->overallconfig['use_jquery_modal']
									);
								}
								?>
							</td>
							<?php
						}
						?>
						<!-- show divisions -->
						<?php
						if ($this->config['show_division'])
						{
							echo '<td width="" class="" nowrap="nowrap" id="divisions">';
							echo sportsmanagementHelperHtml::showDivisonRemark(
								$this->teams[$game->projectteam1_id],
								$this->teams[$game->projectteam2_id],
								$this->config, $game->division_id
							);
							echo '</td>';
						}
                        
                        switch ( $this->project->sport_type_name )
                            {
                                case 'COM_SPORTSMANAGEMENT_ST_GOLF_BILLARD':
                                ?>
							<!-- show matchtime -->
							<td width='' class='' id="matchlink">
                            <a href='<?php echo $report_link; ?>'>
									<img src='<?php echo Uri::root(); ?>components/com_sportsmanagement/assets/images/lupe.png'
										width='30'
										alt='<?php echo Text::_('COM_SPORTSMANAGEMENT_HISTORY'); ?>'
										title='<?php echo Text::_('COM_SPORTSMANAGEMENT_HISTORY'); ?>'>
									</a>
                            <abbr title='' class='dtstart'> 
                            </td>
                            <td width='' class='' id="matchtime">
                            <?php 
                            /** match_timestamp oder match_date*/ 
                            echo date("D d-m-Y - H:i",$game->match_timestamp);
                            //echo sportsmanagementHelperHtml::showMatchTime($game, $this->config, $this->overallconfig, $this->project); 
                            ?>
							</abbr>
                            </td>
							<?php
                                break;
                                default:
                        

						if ($this->config['show_time'])
						{
							?>
							<!-- show matchtime -->
							<td width='' class='' id="matchtime"><abbr title=''
								class='dtstart'> <?php echo sportsmanagementHelperHtml::showMatchTime($game, $this->config, $this->overallconfig, $this->project); ?>
							</abbr></td>
							<?php
						}
                        break;
                        }
                        
						?>

						<?php
						if (($this->config['show_playground'] || $this->config['show_playground_alert']))
						{
							?>
							<!-- show playground -->
							<td>
								<?php echo sportsmanagementHelperHtml::showMatchPlayground($game, $this->config); ?>
							</td>
							<?php
						}

						// --------------------------------------------------------------------------------------------------------------
						if ($this->config['result_style'] == 0)
						{
							switch ($this->config['show_logo_small'])
							{
								case 0:
								case 1:
								case 2:
								case 5:
								case 6:
								case 7:
									$width = 'auto';
									break;
								case 3:
								case 4:
									$width = '40';
									break;
							}

							switch ($this->config['show_logo_small'])
							{
								case 1:
									$pic1 = $team1->logo_small;
									$pic2 = $team2->logo_small;
									break;
								case 5:
									$pic1 = $team1->logo_middle;
									$pic2 = $team2->logo_middle;
									break;
								case 6:
									$pic1 = $team1->logo_big;
									$pic2 = $team2->logo_big;
									break;
							}
                            
                            switch ( $this->project->sport_type_name )
                            {
                                case 'COM_SPORTSMANAGEMENT_ST_GOLF_BILLARD':
                                
                                break;
                                default:

							?>
							<!-- show team-icons and/or -names -->
							<td class="ex1" id="logodruck">
								<?php
								if ($this->config['club_link_logo'])
								{
									echo sportsmanagementViewResults::getTeamClubIcon(
										$team1,
										$this->config['show_logo_small'],
										array('class' => 'teamlogo'),
										$this->modalwidth,
										$this->modalheight,
										$this->overallconfig['use_jquery_modal'],
										'itemprop',
										'logo'
									);
								}
								else
								{
									echo ' ' . sportsmanagementHelper::getPictureThumb($pic1, $team1->name, 'auto', '20', 3);
								}
								?>
							</td>
                            <?php
                            break;
                            }
                            ?>
							<td id="homeTeam">
								<span itemprop="homeTeam" content="<?php echo $team1->name; ?>" ></span>
								<?php

								//echo '<pre>'.print_r($team1,true).'</pre>';

								$isFavTeam = in_array($team1->id, $this->favteams);
								echo sportsmanagementHelper::formatTeamName($team1, 'g' . $game->id, $this->config, $isFavTeam, null, Factory::getApplication()->input->getInt('cfg_which_database', 0));
								?>

							</td>
                            <?php
                            switch ( $this->project->sport_type_name )
                            {
                                case 'COM_SPORTSMANAGEMENT_ST_GOLF_BILLARD':
                                
                                break;
                                default:
                            ?>
							<td class="ex1" id="logodruck">
								<?php
								if ($this->config['club_link_logo'])
								{
									echo sportsmanagementViewResults::getTeamClubIcon(
										$team2,
										$this->config['show_logo_small'],
										array('class' => 'teamlogo'),
										$this->modalwidth,
										$this->modalheight,
										$this->overallconfig['use_jquery_modal'],
										'itemprop',
										'logo'
									);
								}
								else
								{
									echo ' ' . sportsmanagementHelper::getPictureThumb($pic2, $team2->name, 'auto', '20', 3);
								}
								?>
							</td>
                            <?php
                            break;
                            }
                            ?>
							<td id="awayTeam">
								<span itemprop="awayTeam" content="<?php echo $team2->name; ?>"></span>
								<?php
								$isFavTeam = in_array($team2->id, $this->favteams);
								echo sportsmanagementHelper::formatTeamName($team2, 'g' . $game->id, $this->config, $isFavTeam, null, Factory::getApplication()->input->getInt('cfg_which_database', 0));
								?>

							</td>
							<!-- show match score -->
							<td width='' class='score' id="showmatchscore">
								<?php
								echo sportsmanagementViewResults::formatResult($this->teams[$game->projectteam1_id], $this->teams[$game->projectteam2_id], $game, $report_link, $this->config);

								if ($history_link)
								{
									?>
									<a href='<?php echo $history_link; ?>'>
									<img src='<?php echo Uri::root(); ?>components/com_sportsmanagement/assets/images/history-icon-png--21.png'
										width='20'
										alt='<?php echo Text::_('COM_SPORTSMANAGEMENT_HISTORY'); ?>'
										title='<?php echo Text::_('COM_SPORTSMANAGEMENT_HISTORY'); ?>'>
									</a>
									<?php
								}

								?>
							</td>
							<?php
						}

						// --------------------------------------------------------------------------------------------------------------
						?>
						<?php
						// --------------------------------------------------------------------------------------------------------------
						if ($this->config['result_style'] == 1)
						{
							?>
							<!-- show team-icons and/or -names -->
							<td class='ex1'>
								<?php
								$isFavTeam = in_array($team1->id, $this->favteams);
								echo sportsmanagementHelper::formatTeamName($team1, 'g' . $game->id, $this->config, $isFavTeam);
								?>
							</td>
							<td width=''>
								<?php echo sportsmanagementViewResults::getTeamClubIcon(
									$team1,
									$this->config['show_logo_small'],
									array('class' => 'teamlogo'),
									$this->modalwidth,
									$this->modalheight,
									$this->overallconfig['use_jquery_modal']
								); ?>
							</td>
							<!-- show match score -->
							<td width='' class='' nowrap='nowrap'>
								<?php
								echo '&nbsp;';
								echo sportsmanagementViewResults::formatResult($this->teams[$game->projectteam1_id], $this->teams[$game->projectteam2_id], $game, $report_link, $this->config);
								echo '&nbsp;';

								if ($history_link)
								{
									?>
									<a href='<?php echo $history_link; ?>'>
									<img src='<?php echo Uri::root(); ?>components/com_sportsmanagement/assets/images/history-icon-png--21.png'
										width='20'
										alt='<?php echo Text::_('COM_SPORTSMANAGEMENT_HISTORY'); ?>'
										title='<?php echo Text::_('COM_SPORTSMANAGEMENT_HISTORY'); ?>'>
									</a>
									<?php
								}
								?>
							</td>
							<td class="ex1">
								<?php echo sportsmanagementViewResults::getTeamClubIcon(
									$team2, $this->config['show_logo_small'], array('class' => 'teamlogo'),
									$this->modalwidth,
									$this->modalheight,
									$this->overallconfig['use_jquery_modal']
								); ?>
							</td>
							<td class=''>
								<?php
								$isFavTeam = in_array($team2->id, $this->favteams);
								echo sportsmanagementHelper::formatTeamName($team2, 'g' . $game->id, $this->config, $isFavTeam);
								?>
							</td>
							<?php
						}

						// --------------------------------------------------------------------------------------------------------------
						?>
						<?php
						// --------------------------------------------------------------------------------------------------------------
						if ($this->config['result_style'] == 2)
						{
							?>
							<!-- show team-icons and/or -names -->
							<td class=''>
								<?php
								$isFavTeam = in_array($team1->id, $this->favteams);
								echo sportsmanagementHelper::formatTeamName($team1, 'g' . $game->id, $this->config, $isFavTeam);
								?>
							</td>
							<td width=''>
								<?php echo sportsmanagementViewResults::getTeamClubIcon(
									$team1, $this->config['show_logo_small'], array('class' => 'teamlogo'),
									$this->modalwidth,
									$this->modalheight,
									$this->overallconfig['use_jquery_modal']
								); ?>
							</td>
							<td width=''>
							-
							</td>
							<td width=''>
								<?php echo sportsmanagementViewResults::getTeamClubIcon(
									$team2, $this->config['show_logo_small'], array('class' => 'teamlogo'),
									$this->modalwidth,
									$this->modalheight,
									$this->overallconfig['use_jquery_modal']
								); ?>
							</td>
							<td class=''>
								<?php
								$isFavTeam = in_array($team2->id, $this->favteams);
								echo sportsmanagementHelper::formatTeamName($team2, 'g' . $game->id, $this->config, $isFavTeam);
								?>
							</td>
							<!-- show match score -->
							<td width='' class='' nowrap='nowrap'>
								<?php
								echo '&nbsp;';
								echo sportsmanagementViewResults::formatResult($this->teams[$game->projectteam1_id], $this->teams[$game->projectteam2_id], $game, $report_link, $this->config);
								echo '&nbsp;';

								if ($history_link)
								{
									?>
									<a href='<?php echo $history_link; ?>'>
									<img src='<?php echo Uri::root(); ?>components/com_sportsmanagement/assets/images/history-icon-png--21.png'
										width='20'
										alt='<?php echo Text::_('COM_SPORTSMANAGEMENT_HISTORY'); ?>'
										title='<?php echo Text::_('COM_SPORTSMANAGEMENT_HISTORY'); ?>'>
								</a>
								<?php
								}
								?>
							</td>
							<?php
						}

						// --------------------------------------------------------------------------------------------------------------
                        
						
                        switch ( $this->project->sport_type_name )
                            {
                                case 'COM_SPORTSMANAGEMENT_ST_GOLF_BILLARD':
                                
                                break;
                                default:
                        ?>
                        

						<!-- show hammer if there is a alternative decision of the score -->
						<td width="" class="" id="showReportDecisionIcons">
							<?php sportsmanagementViewResults::showReportDecisionIcons($game);?>
						</td>

						<?php
                        break;
                        }
                        
						switch ($this->config['show_referee'])
						{
							case 0:
								break;
							case 1:
								?>
								<!-- show matchreferees icon with tooltip -->
								<td width="" class="" id="show_referee">
									<?php sportsmanagementViewResults::showMatchRefereesAsTooltip($game, $this->project, $this->config);?>
								</td>
								<?php
								break;
							case 2:
								?>
								<!-- show matchreferees icon with tooltip -->
								<td width="" class="" id="show_referee">
									<?php sportsmanagementViewResults::showMatchRefereesAsTooltip($game, $this->project, $this->config);?>
								</td>
								<?php
							break;
						}

						if ($this->config['show_scoresheet'])
						{
							?>
							<td width="" class="" id="show_scoresheet">
								<?php
								$routeparameter                       = array();
								$routeparameter['cfg_which_database'] = Factory::getApplication()->input->getInt('cfg_which_database', 0);
								$routeparameter['p']                  = $this->project->id;
								$routeparameter['mid']                = $game->id;
								$link                                 = sportsmanagementHelperRoute::getSportsmanagementRoute('scoresheet', $routeparameter);

								$text    = HTMLHelper::_('image', 'media/com_sportsmanagement/jl_images/pdf_button.png', Text::_('COM_SPORTSMANAGEMENT_SCORESHEET_EXPORT'));
								$attribs = array('title' => Text::_('COM_SPORTSMANAGEMENT_SCORESHEET_EXPORT'));
								echo HTMLHelper::_('link', $link, $text, $attribs);
								?>
							</td>
							<?php
						}
						?>

						<?php
						if ($this->config['show_attendance_column'])
						{
							?>
							<!-- show attendance -->
							<td class="" id="show_attendance_column">
                            <?php
								if ($game->crowd > 0)
								{
									echo $game->crowd;
								}
								else
								{
									echo '&nbsp;';
								}
								?>
							</td>
							<?php
						}
						?>

						<?php
						if ($this->config['show_comments_count'] > 0 && $comJcomments)
						{
							?>
							<!-- show comments -->
							<td class="" id="show_comments_count">
                            <?php
								echo $commmentsInstance->showMatchCommentIcon($game, $this->teams[$game->projectteam1_id], $this->teams[$game->projectteam2_id], $this->config, $this->project);
							?></td>
							<?php
						}
						?>

					</tr>
				</div>
				<?php
				if ($hasEvents)
				{
					?>
					<!-- show icon for editing events in edit mode -->
					<tr class="events <?php echo ($k == 0) ? '' : 'alt'; ?>">
						<td colspan="<?php echo $nbcols; ?>">
							<div id="info<?php echo $game->id; ?>" style="display: none;"
								class="resultsevents jsmeventsshowhide">
								<table class='table'>
									<tr>
										<td><?php
											echo sportsmanagementViewResults::showEventsContainerInResults($game, $this->projectevents, $events, $subs, $this->config, $this->project);
										?></td>
									</tr>
								</table>
							</div>
						</td>
					</tr>
					<?php
				}

				$k = 1 - $k;
			}
		}
		?>
		<!-- GAMES END -->
		<?php
	}
	?>
</table>

<br/>
