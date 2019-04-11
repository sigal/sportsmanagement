<?php
/** SportsManagement ein Programm zur Verwaltung f�r Sportarten
 * @version   1.0.05
 * @file      checkboxes.php
 * @author    diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright Copyright: � 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license   This file is part of SportsManagement.
 * @package   sportsmanagement
 * @subpackage fields
 */

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );
use Joomla\CMS\Factory;
use Joomla\CMS\Form\FormField;
use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;

jimport('joomla.filesystem.folder');
FormHelper::loadFieldClass('list');
jimport('joomla.html.html');
jimport('joomla.form.formfield');

/**
 * FormFieldseasonteamperson
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class JFormFieldseasonteamperson extends FormField
{
	/**
	 * field type
	 * @var string
	 */
	public $type = 'checkboxes';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   11.1
	 */
	protected function getInput()
	{
		$app = Factory::getApplication();
        $option = Factory::getApplication()->input->getCmd('option');
        $select_id = Factory::getApplication()->input->getVar('id');
        $this->value = explode(",", $this->value);
        $targettable = $this->element['targettable'];
        $targetid = $this->element['targetid'];
        
        
   
    
        // Initialize variables.
		//$options = array();
    
    // teilnehmende saisons selektieren
    if ( $select_id )
    {
    //$db = Factory::getDbo();
    $query = Factory::getDbo()->getQuery(true);
			// saisons selektieren
			$query->select('stp.season_id,stp.team_id, t.name as teamname, s.name as seasonname, c.logo_big as clublogo');
			$query->from('#__sportsmanagement_'.$targettable.' as stp');
            $query->join('INNER', '#__sportsmanagement_team AS t ON t.id = stp.team_id');
            $query->join('INNER', '#__sportsmanagement_club AS c ON c.id = t.club_id');
            $query->join('INNER', '#__sportsmanagement_season AS s ON s.id = stp.season_id');
            
			$query->where($targetid.'='.$select_id);
            $query->order('s.name');
            
            $starttime = microtime(); 
            
			Factory::getDbo()->setQuery($query);
            
            if ( COM_SPORTSMANAGEMENT_SHOW_QUERY_DEBUG_INFO )
        {
            $app->enqueueMessage(Text::_(__METHOD__.' '.__LINE__.' <br><pre>'.print_r($query->dump(),true).'</pre>'),'Notice');
        $app->enqueueMessage(Text::_(__METHOD__.' '.__LINE__.' Ausfuehrungszeit query<br><pre>'.print_r(sportsmanagementModeldatabasetool::getQueryTime($starttime, microtime()),true).'</pre>'),'Notice');
        }
        
            $options = Factory::getDbo()->loadObjectList();
	}
    else
    {
        $options = '';
    }		
    
 


// Initialize variables.
            $html = '';
            $attribs['width'] = '25px';
            $attribs['height'] = '25px';
            
            if ( $options )
            {
            $html .= '<table>';
            foreach ($options as $i => $option)
            {
            
            $html .= '<tr>';
            $html .= '<td>'.$option->seasonname.'</td>';
            
            $html .= '<td>'.HTMLHelper::image($option->clublogo, '',	$attribs).'</td>';
            $html .= '<td>'.$option->teamname.'</td>';
            
            $html .= '</tr>';    
            }   
            
            $html .= '</table>';
            } 
            else
            {
                $html .= '<div class="alert alert-no-items">';
                $html .=Text::_('JGLOBAL_NO_MATCHING_RESULTS');
			$html .= '</div>';
            }
    
            return $html;    
    
    }
}
