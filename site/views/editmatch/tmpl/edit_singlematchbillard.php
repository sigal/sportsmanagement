<?php
/**
 * SportsManagement ein Programm zur Verwaltung f�r alle Sportarten
 * @version    1.0.05
 * @package    Sportsmanagement
 * @subpackage editmatch
 * @file       edit_singlematchbillard.php
 * @author     diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright  Copyright: � 2013-2023 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;


//echo 'match <pre>'.print_r($this->match,true).'</pre>';
//echo 'singlematch <pre>'.print_r($this->singlematches,true).'</pre>';

$starters_home    = sportsmanagementModelMatch::getMatchPersons($this->match->projectteam1_id, 0, $this->match->id, 'player');
$starters_away    = sportsmanagementModelMatch::getMatchPersons($this->match->projectteam2_id, 0, $this->match->id, 'player');
//echo 'starters_home <pre>'.print_r($starters_home,true).'</pre>';
//echo 'starters_away <pre>'.print_r($starters_away,true).'</pre>';

/** erst einmal 5 spiele */
for ($a=1; $a < 6;$a++)
{
?>
<div class="row">
<?php
$checksinglematch = $this->model->getSingleMatchData($this->match->id,$a);
if ( $checksinglematch )
{
?>
<div class="text-bg-primary p-3">Spiel <?php echo $a; ?> vorhanden</div>
<?php    
}
else
{
    ?>
    <div class="text-bg-danger p-3">Spiel <?php echo $a; ?> nicht vorhanden</div>
    <?php
    //echo 'nicht vorhanden<br>';
    foreach ( $starters_home as $keyhome => $valuehome ) if ( $valuehome->trikot_number == $a)
    {
    foreach ( $starters_away as $keyaway => $valueaway ) if ( $valueaway->trikot_number == $a)
    {
    $insertsinglematch = $this->model->insertSingleMatchData($this->match->id,$a,$valuehome->teamplayer_id, $valueaway->teamplayer_id,$valuehome->projectteam_id, $valueaway->projectteam_id);    
        if ( $insertsinglematch )
        {
            ?>
            <div class="text-bg-success p-3">Spiel <?php echo $a; ?> angelegt</div>
            <?php
            //echo 'spiel angelegt <br>';
        }
        else
        {
            ?>
            <div class="text-bg-danger p-3">Fehler beim Anlegen des Spiels <?php echo $a; ?></div>
            <?php
            //echo 'spiel nicht angelegt <br>';
        }
       
        
    }    
        
        
        
    }
    
    
}
?>
</div>
<?php
}

$this->singlematches = $this->model->getSingleMatchDatas($this->match->id);
//echo 'singlematch <pre>'.print_r($this->singlematches,true).'</pre>';
$this->lists['homeplayer'] = $starters_home;
$this->lists['awayplayer'] = $starters_away;
?>
<div class="table-responsive" id="editcell">
    <fieldset class="adminform">
        <legend><?php echo Text::sprintf('COM_SPORTSMANAGEMENT_ADMIN_MATCHES_TITLE2', '<i>' . $this->roundws->name . '</i>', '<i>' . $this->projectws->name . '</i>'); ?></legend>

        <!-- Start games list -->
        <form action="<?php echo $this->request_url; ?>" method="post" name="adminForm" id='adminForm'>

            <fieldset>
                <div class="fltlft">
                    <button type="button" onclick="Joomla.submitform('editmatch.applyshortsinglematch', this.form);">
						<?php echo Text::_('JSAVE'); ?></button>
                    <button type="button"
                            onclick="$('close').value=1; Joomla.submitform('editmatch.saveshortsinglematch', this.form);">
						<?php echo Text::_('JSAVEANDCLOSE'); ?></button>

                    <button type="button" onclick="Joomla.submitform('editmatch.deletesinglematch', this.form);">
						<?php echo Text::_('JACTION_DELETE'); ?></button>

<button type="button" onclick="Joomla.submitform('editmatch.cancel', this.form);">
				<?php echo Text::_('JCANCEL'); ?></button>

                </div>

            </fieldset>


			<?php
			$colspan = ($this->projectws->allow_add_time) ? 16 : 15;
			?>
            <table class="table table-striped" id="<?php echo $this->view; ?>list">
                <thead>
                <tr>
                    <th width="5"><?php echo count($this->singlematches) . '/' . $this->pagination->total; ?></th>
                    <th width="20">
                        <?php echo HTMLHelper::_('grid.checkall'); ?>
                    </th>

                    <th class="title"
                        nowrap="nowrap"><?php echo Text::_('COM_SPORTSMANAGEMENT_ADMIN_MATCHES_MATCHNR'); ?></th>


                    <th class="title"
                        nowrap="nowrap"><?php echo Text::_('COM_SPORTSMANAGEMENT_ADMIN_MATCHES_SINGLE_MATCH_TYPE'); ?></th>

                    <th class="title"
                        nowrap="nowrap"><?php echo Text::_('COM_SPORTSMANAGEMENT_ADMIN_MATCHES_HOME_TEAM_PLAYER'); ?></th>
                    <th class="title"
                        nowrap="nowrap"><?php echo Text::_('COM_SPORTSMANAGEMENT_ADMIN_MATCHES_AWAY_TEAM_PLAYER'); ?></th>
                    <th style="  "><?php echo Text::_('COM_SPORTSMANAGEMENT_ADMIN_MATCHES_RESULT'); ?></th>
					<?php
					if ($this->projectws->allow_add_time)
					{
						?>
                        <th style="text-align:center;  "><?php echo Text::_('COM_SPORTSMANAGEMENT_ADMIN_MATCHES_RESULT_TYPE'); ?></th>
						<?php
					}
					?>


                    <th width="1%" nowrap="nowrap"><?php echo Text::_('JSTATUS'); ?></th>
                    <th width="1%" class="title" nowrap="nowrap">
						<?php echo HTMLHelper::_('grid.sort', 'JGRID_HEADING_ID', 'mc.id', $this->sortDirection, $this->sortColumn); ?>
                    </th>
                </tr>
                </thead>

                <tfoot>
               <tr>
        <td colspan="3">
			<?php //echo $this->pagination->getListFooter(); ?>
        </td>
        <td colspan="3">
			<?php //echo $this->pagination->getResultsCounter(); ?>
        </td>
    </tr>
                
                
                
                </tfoot>

                <tbody>
				<?php
                foreach ($this->singlematches as $this->count_i => $this->item)
	{
				//$k = 0;

			//	for ($i = 0, $n = count($this->matches); $i < $n; $i++)
//				{
//					$row       =& $this->matches[$i];
					$checked   = HTMLHelper::_('grid.checkedout', $this->item, $this->count_i, 'id');
					$published = HTMLHelper::_('grid.published', $this->item, $this->count_i, 'tick.png', 'publish_x.png', 'jlextindividualsportes.');

					list($date, $time) = explode(" ", $this->item->match_date);
					$time = date("%H:%i", strtotime($time));
					?>
                    <tr class="row<?php echo $this->count_i % 2; ?>" >
						<?php
						if (($this->item->cancel) > 0)
						{
							$style = "text-align:center;  background-color: #FF9999;";
						}
						else
						{
							$style = "text-align:center; ";
						}
						?>
                        <td style="<?php echo $style; ?>">
							<?php
							//echo $this->pagination->getRowOffset($this->count_i);
							?>
                        </td>
                        <td style="text-align:center; ">
							<?php
							echo $checked;
							?>
                        </td>
                        <td class="center">
                            <input onchange="document.getElementById('cb<?php echo $this->count_i; ?>').checked=true" type="text"
                                   name="match_number<?php echo $this->item->id; ?>"
                                   value="<?php echo $this->item->match_number; ?>" size="6" tabindex="1" class="inputbox"/>
                        </td>
                        <td style="text-align:center; ">
							<?php
$dwOptions  = array();
$dwOptions[] = HTMLHelper::_('select.option', 'SINGLE', Text::_('COM_SPORTSMANAGEMENT_PERSON_SINGLE'));
$dwOptions[] = HTMLHelper::_('select.option', 'DOUBLE', Text::_('COM_SPORTSMANAGEMENT_PERSON_DOUBLE'));
echo HTMLHelper::_('select.genericlist', $dwOptions, 'match_type'.$this->item->id, 'class="inputbox" onchange="document.getElementById(\'cb'.$this->count_i.'\').checked=true"', 'value', 'text', $this->item->match_type);
       
							//echo Text::_('COM_SPORTSMANAGEMENT_' . $this->item->match_type);
							?>
                        </td>


                        <td style="" nowrap="">

							<?php

							if ($this->item->match_type == 'SINGLE')
							{
								$append = '';

								if ($this->item->teamplayer1_id == 0)
								{
									$append = ' style="background-color:#bbffff"';
								}

								$append .= ' onchange="document.getElementById(\'cb' . $this->count_i . '\').checked=true" ';
								echo HTMLHelper::_(
									'select.genericlist', $this->lists['homeplayer'], 'teamplayer1_id' . $this->item->id,
									'class="inputbox select-hometeam" size="1"' . $append, 'value', 'text', $this->item->teamplayer1_id
								);
							}
                            elseif ($this->item->match_type == 'DOUBLE')
							{
								$append = '';

								if ($this->item->double_team1_player1 == 0)
								{
									$append = ' style="background-color:#bbffff"';
								}

								$append .= ' onchange="document.getElementById(\'cb' . $this->count_i . '\').checked=true" ';
								echo HTMLHelper::_(
									'select.genericlist', $this->lists['homeplayer'], 'double_team1_player1' . $this->item->id,
									'class="inputbox select-hometeam" size="1"' . $append, 'value', 'text', $this->item->double_team1_player1
								);
								echo '<br />';

								$append = '';

								if ($this->item->double_team1_player2 == 0)
								{
									$append = ' style="background-color:#bbffff"';
								}

								$append .= ' onchange="document.getElementById(\'cb' . $this->count_i . '\').checked=true" ';
								echo HTMLHelper::_(
									'select.genericlist', $this->lists['homeplayer'], 'double_team1_player2' . $this->item->id,
									'class="inputbox select-hometeam" size="1"' . $append, 'value', 'text', $this->item->double_team1_player2
								);
							}


							?>
                        </td>
                        <td style="" nowrap="">
							<?php
							if ($this->item->match_type == 'SINGLE')
							{
								$append = '';

								if ($this->item->teamplayer2_id == 0)
								{
									$append = ' style="background-color:#bbffff"';
								}

								$append .= ' onchange="document.getElementById(\'cb' . $this->count_i . '\').checked=true" ';
								echo HTMLHelper::_(
									'select.genericlist', $this->lists['awayplayer'], 'teamplayer2_id' . $this->item->id,
									'class="inputbox select-awayteam" size="1"' . $append, 'value', 'text', $this->item->teamplayer2_id
								);
							}
                            elseif ($this->item->match_type == 'DOUBLE')
							{
								$append = '';

								if ($this->item->double_team2_player1 == 0)
								{
									$append = ' style="background-color:#bbffff"';
								}

								$append .= ' onchange="document.getElementById(\'cb' . $this->count_i . '\').checked=true" ';
								echo HTMLHelper::_(
									'select.genericlist', $this->lists['awayplayer'], 'double_team2_player1' . $this->item->id,
									'class="inputbox select-hometeam" size="1"' . $append, 'value', 'text', $this->item->double_team2_player1
								);
								echo '<br />';

								$append = '';

								if ($this->item->double_team2_player2 == 0)
								{
									$append = ' style="background-color:#bbffff"';
								}

								$append .= ' onchange="document.getElementById(\'cb' . $this->count_i . '\').checked=true" ';
								echo HTMLHelper::_(
									'select.genericlist', $this->lists['awayplayer'], 'double_team2_player2' . $this->item->id,
									'class="inputbox select-hometeam" size="1"' . $append, 'value', 'text', $this->item->double_team2_player2
								);
							}


							?>

                        </td>
                        <td nowrap="nowrap" style="text-align: right; ">
                            <input onchange="document.getElementById('cb<?php echo $this->count_i; ?>').checked=true" <?php if ($this->item->alt_decision == 1)
							{
								echo "class=\"subsequentdecision\" title=\"" . Text::_('COM_SPORTSMANAGEMENT_ADMIN_MATCHES_SUB_DECISION') . "\"";
							} ?> type="text" name="team1_result<?php echo $this->item->id; ?>"
                                   value="<?php echo $this->item->team1_result; ?>" size="2" tabindex="4" class="inputbox"/> :
                            <input onchange="document.getElementById('cb<?php echo $this->count_i; ?>').checked=true" <?php if ($this->item->alt_decision == 1)
							{
								echo "class=\"subsequentdecision\" title=\"" . Text::_('COM_SPORTSMANAGEMENT_ADMIN_MATCHES_SUB_DECISION') . "\"";
							} ?> type="text" name="team2_result<?php echo $this->item->id; ?>"
                                   value="<?php echo $this->item->team2_result; ?>" size="2" tabindex="4" class="inputbox"/>


                            <br/>

                            <span id="part<?php echo $this->item->id; ?>" style="">
					   <br/>
											<table>
											
						<?php
                        $partresults1 = array();
$partresults2 = array();
					if ( !is_null($this->item->team1_result_split) )
					{
						$partresults1 = explode(";", $this->item->team1_result_split);
					}
						if ( !is_null($this->item->team2_result_split) )
					{
						$partresults2 = explode(";", $this->item->team2_result_split);
				}

						for ($x = 0; $x < ($this->projectws->game_parts); $x++)
						{
							?>
<tr>
                            <td>
										<?PHP
										echo ($x + 1) . ".: ";
										?>
						<!--	</td>
                            <td> -->

										 <input onchange="document.getElementById('cb<?php echo $this->count_i; ?>').checked=true"
                                                onchange="document.getElementById(\'cb'<?php echo $this->count_i; ?>'\').checked=true"
                                                type="text" style="font-size: 9px;"
                                                name="team1_result_split<?php echo $this->item->id; ?>[]"
                                                value="<?php echo (isset($partresults1[$x])) ? $partresults1[$x] : ''; ?>"
                                                size="3" tabindex="1" class="inputbox"/>
						<!--	</td>
                            <td> -->
										<input onchange="document.getElementById('cb<?php echo $this->count_i; ?>').checked=true"
                                               onchange="document.getElementById(\'cb'<?php echo $this->count_i; ?>'\').checked=true"
                                               type="text" style="font-size: 9px;"
                                               name="team2_result_split<?php echo $this->item->id; ?>[]"
                                               value="<?php echo (isset($partresults2[$x])) ? $partresults2[$x] : ''; ?>"
                                               size="3" tabindex="1" class="inputbox"/>
                                               <br />
							</td>
</tr>
							<?php
						}
						?>
									
									</table>
									<?PHP
									if ($this->projectws->allow_add_time == 1)
									{
										echo 'OT:'; ?>

                                        <input onchange="document.getElementById('cb<?php echo $this->count_i; ?>').checked=true"
                                               type="text" style="font-size: 9px;"
                                               name="team1_result_ot<?php echo $this->item->id; ?>"
                                               value="<?php echo (isset($this->item->team1_result_ot)) ? $this->item->team1_result_ot : ''; ?>"
                                               size="3" tabindex="1" class="inputbox"/> :
															 <input
                                            onchange="document.getElementById('cb'<?php echo $this->count_i; ?>').checked=true"
                                            type="text" style="font-size: 9px;"
                                            name="team2_result_ot<?php echo $this->item->id; ?>"
                                            value="<?php echo (isset($this->item->team2_result_ot)) ? $this->item->team2_result_ot : ''; ?>"
                                            size="3" tabindex="1" class="inputbox"/>
                                        <br/>
																<?php echo 'SO:'; ?>

															 <input
                                            onchange="document.getElementById('cb<?php echo $this->count_i; ?>').checked=true"
                                            type="text" style="font-size: 9px;"
                                            name="team1_result_so<?php echo $this->item->id; ?>"
                                            value="<?php echo (isset($this->item->team1_result_so)) ? $this->item->team1_result_so : ''; ?>"
                                            size="3" tabindex="1" class="inputbox"/> :
															 <input
                                            onchange="document.getElementById('cb<?php echo $this->count_i; ?>').checked=true"
                                            type="text" style="font-size: 9px;"
                                            name="team2_result_so<?php echo $this->item->id; ?>"
                                            value="<?php echo (isset($this->item->team2_result_so)) ? $this->item->team2_result_so : ''; ?>"
                                            size="3" tabindex="1" class="inputbox"/>
                                        <br/>
										<?php
									}
									?>
					  </span>
                        </td>
						<?php
						if ($this->projectws->allow_add_time)
						{
							?>
                            <td nowrap="nowrap">
								<?php
								echo HTMLHelper::_(
									'select.genericlist', $this->lists['match_result_type'],
									'match_result_type' . $this->item->id, 'class="inputbox" size="1"', 'value', 'text',
									$this->item->match_result_type
								);
								?>
                            </td>
							<?php
						}
						?>


                        
                        <td class="center">
                        <div class="btn-group">
							<?php echo HTMLHelper::_('jgrid.published', $this->item->published, $this->count_i, 'matches.', $canChange, 'cb'); ?>
							<?php
							/** Create dropdown items and render the dropdown list. */
							if ($canChange)
							{
								HTMLHelper::_('actionsdropdown.' . ((int) $this->item->published === 2 ? 'un' : '') . 'archive', 'cb' . $this->count_i, 'matches');
								HTMLHelper::_('actionsdropdown.' . ((int) $this->item->published === -2 ? 'un' : '') . 'trash', 'cb' . $this->count_i, 'matches');
								echo HTMLHelper::_('actionsdropdown.render', $this->escape($this->item->id));
							}
							?>
                        </div>
                    </td>
                        
                        
                        
                        
                        
                        <td style="text-align:center; ">
							<?php
							echo $this->item->id;
							?>
                        </td>
                    </tr>
					<?php
					//$k = 1 - $k;
				}
				?>
                </tbody>
            </table>

			<?php $dValue = $this->roundws->round_date_first . ' ' . $this->projectws->start_time; ?>

            <input type='hidden' name='match_date' value='<?php echo $dValue; ?>'/>
            <input type='hidden' name='act' value='' id='short_act'/>
            <input type='hidden' name='boxchecked' value='0'/>
            <input type='hidden' name='search_mode' value='<?php echo $this->lists['search_mode']; ?>'/>
            <input type='hidden' name='filter_order' value='<?php echo $this->sortColumn; ?>'/>
            <input type='hidden' name='filter_order_Dir' value='<?php echo $this->sortDirection; ?>'/>
            <input type='hidden' name='rid' value='<?php echo $this->match->round_id; ?>'/>
            <input type='hidden' name='project_id' value='<?php echo $this->project->id; ?>'/>
            <input type="hidden" name="close" id="close" value="0"/>
            <input type='hidden' name='match_id' value='<?php echo $this->match->id; ?>'/>
            <input type='hidden' name='projectteam1_id' value='<?php echo $this->match->projectteam1_id; ?>'/>
            <input type='hidden' name='projectteam2_id' value='<?php echo $this->match->projectteam2_id; ?>'/>
            <input type='hidden' name='act' value=''/>
            <input type='hidden' name='task' value='' id='task'/>
			<?php echo HTMLHelper::_('form.token') . "\n"; ?>
        </form>
    </fieldset>
</div>

























