<?php
/** SportsManagement ein Programm zur Verwaltung f�r alle Sportarten
* @version         1.0.05
* @file                agegroup.php
* @author                diddipoeler, stony, svdoldie und donclumsy (diddipoeler@arcor.de)
* @copyright        Copyright: � 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
* @license                This file is part of SportsManagement.
*
* SportsManagement is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* SportsManagement is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with SportsManagement.  If not, see <http://www.gnu.org/licenses/>.
*
* Diese Datei ist Teil von SportsManagement.
*
* SportsManagement ist Freie Software: Sie k�nnen es unter den Bedingungen
* der GNU General Public License, wie von der Free Software Foundation,
* Version 3 der Lizenz oder (nach Ihrer Wahl) jeder sp�teren
* ver�ffentlichten Version, weiterverbreiten und/oder modifizieren.
*
* SportsManagement wird in der Hoffnung, dass es n�tzlich sein wird, aber
* OHNE JEDE GEW�HELEISTUNG, bereitgestellt; sogar ohne die implizite
* Gew�hrleistung der MARKTF�HIGKEIT oder EIGNUNG F�R EINEN BESTIMMTEN ZWECK.
* Siehe die GNU General Public License f�r weitere Details.
*
* Sie sollten eine Kopie der GNU General Public License zusammen mit diesem
* Programm erhalten haben. Wenn nicht, siehe <http://www.gnu.org/licenses/>.
*
* Note : All ini files need to be saved as UTF-8 without BOM
*/
/**
  60  header icons 
  61  .icon-48-generic         { background-image: url(../images/header/icon-48-generic.png); }
  62  .icon-48-checkin         { background-image: url(../images/header/icon-48-checkin.png); }
  63  .icon-48-cpanel         { background-image: url(../images/header/icon-48-cpanel.png); }
  64  .icon-48-config         { background-image: url(../images/header/icon-48-config.png); }
  65  .icon-48-module         { background-image: url(../images/header/icon-48-module.png); }
  66  .icon-48-menu             { background-image: url(../images/header/icon-48-menu.png); }
  67  .icon-48-menumgr         { background-image: url(../images/header/icon-48-menumgr.png); }
  68  .icon-48-trash         { background-image: url(../images/header/icon-48-trash.png); }
  69  .icon-48-user             { background-image: url(../images/header/icon-48-user.png); }
  70  .icon-48-inbox         { background-image: url(../images/header/icon-48-inbox.png); }
  71  .icon-48-msgconfig     { background-image: url(../images/header/icon-48-message_config.png); }
  72  .icon-48-langmanager { background-image: url(../images/header/icon-48-language.png); }
  73  .icon-48-mediamanager{ background-image: url(../images/header/icon-48-media.png); }
  74  .icon-48-plugin     { background-image: url(../images/header/icon-48-plugin.png); }
  75  .icon-48-help_header { background-image: url(../images/header/icon-48-help_header.png); }
  76  .icon-48-impressions { background-image: url(../images/header/icon-48-stats.png); }
  77  .icon-48-browser         { background-image: url(../images/header/icon-48-stats.png); }
  78  .icon-48-searchtext     { background-image: url(../images/header/icon-48-stats.png); }
  79  .icon-48-thememanager{ background-image: url(../images/header/icon-48-themes.png); }
  80  .icon-48-massemail     { background-image: url(../images/header/icon-48-massemail.png); }
  81  .icon-48-frontpage     { background-image: url(../images/header/icon-48-frontpage.png); }
  82  .icon-48-sections     { background-image: url(../images/header/icon-48-section.png); }
  83  .icon-48-addedit         { background-image: url(../images/header/icon-48-article-add.png); }
  84  .icon-48-article         { background-image: url(../images/header/icon-48-article.png); }
  85  .icon-48-categories     { background-image: url(../images/header/icon-48-category.png); }
  86  .icon-48-install         { background-image: url(../images/header/icon-48-extension.png); }
  87  .icon-48-dbbackup        { background-image: url(../images/header/icon-48-backup.png); }
  88  .icon-48-dbrestore     { background-image: url(../images/header/icon-48-dbrestore.png); }
  89  .icon-48-dbquery         { background-image: url(../images/header/icon-48-query.png); }
  90  .icon-48-systeminfo     { background-image: url(../images/header/icon-48-info.png); }
  91  .icon-48-massemail     { background-image: url(../images/header/icon-48-massmail.png); }
  
 */

// Check to ensure this file is included in Joomla!
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view' );
jimport( 'joomla.html.html.select' );



/**
 * sportsmanagementViewjsminlinehockey
 * 
 * @package 
 * @author Dieter Pl�ger
 * @copyright 2017
 * @version $Id$
 * @access public
 */
class sportsmanagementViewjsminlinehockey extends sportsmanagementView
{


	
	
	/**
	 * sportsmanagementViewjsminlinehockey::init()
	 * 
	 * @return void
	 */
	function init()
	{
		$option = JRequest::getCmd('option');
		$mainframe = JFactory::getApplication();

		$db	= JFactory::getDBO();
		$uri = JFactory::getURI();
		$user = JFactory::getUser();
		
		//$model = $this->getModel();

    
    $this->projectid = $this->jinput->get("pid", '0' );
    if ( !$this->projectid )
    {
    $this->projectid = $mainframe->getUserState( "$option.pid", '0' );    
    }
    
    $mainframe->enqueueMessage(JText::_(__METHOD__.' '.__LINE__.' projectid -> '.$this->projectid.''),'');
    $this->matchlink = $this->model->getMatchLink($this->projectid);
    //$project = sportsmanagementModelProject::getProject($projectid);
    
	

    
   // if ( empty($projectid) )
//    {
//    JError::raiseWarning( 500, JText::_( 'COM_SPORTSMANAGEMENT_JSMINLINEHOCKEY_NO_PROJECT' ) );
//    $mainframe->redirect( 'index.php?option=' . $option .'&view=projects' );
//    }
//    else
//    {
    $mainframe->enqueueMessage(JText::_('COM_SPORTSMANAGEMENT_JSMINLINEHOCKEY_PROJECT_SELECT'),'');
//    }
   
   JToolBarHelper::title(JText::_('COM_SPORTSMANAGEMENT_JSMINLINEHOCKEY_TITLE'),'install');
		
        $this->request_url = $uri->toString();
		
	}
    
    /**
	* Add the page title and toolbar.
	*
	* @since	1.7
	*/
	protected function addToolbar()
	{
	// Get a refrence of the page instance in joomla
        $document = JFactory::getDocument();
        // Set toolbar items for the page
        JToolBarHelper::save('jsminlinehockey.getteams', 'COM_SPORTSMANAGEMENT_JSMINLINEHOCKEY_GET_TEAMS');
JToolBarHelper::save('jsminlinehockey.getclubs', 'COM_SPORTSMANAGEMENT_JSMINLINEHOCKEY_GET_CLUBS');

if ( $this->projectid )
{
JToolBarHelper::save('jsminlinehockey.getmatches', 'COM_SPORTSMANAGEMENT_JSMINLINEHOCKEY_GET_MATCHES');
}

    }    


}
?>
