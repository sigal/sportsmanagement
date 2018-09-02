<?php
/** SportsManagement ein Programm zur Verwaltung für Sportarten
 * @version   1.0.05
 * @file      view.html.php
 * @author    diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright Copyright: © 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license   This file is part of SportsManagement.
 * @package   sportsmanagement
 * @subpackage agegroup
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Language\Text;

/**
 * sportsmanagementViewagegroup
 * 
 * @package 
 * @author diddi
 * @copyright 2014
 * @version $Id$
 * @access public
 */
class sportsmanagementViewagegroup extends sportsmanagementView
{
    
	/**
	 * sportsmanagementViewagegroup::init()
	 * 
	 * @return
	 */
	public function init ()
	{

	}
	
	/**
	 * sportsmanagementViewagegroup::addToolBar()
	 * 
	 * @return void
	 */
	protected function addToolBar() 
	{ 
		$this->jinput->setVar('hidemainmenu', true);
		$isNew = $this->item->id ? $this->title = Text::_('COM_SPORTSMANAGEMENT_AGEGROUPE_EDIT') : $this->title = Text::_('COM_SPORTSMANAGEMENT_AGEGROUPE_NEW');
        $this->icon = 'agegroup';
        parent::addToolbar();
	}
	
}
