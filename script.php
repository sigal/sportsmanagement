<?php
/** SportsManagement ein Programm zur Verwaltung für Sportarten
 * @version   1.0.05
 * @file      script.php
 * @author    diddipoeler, stony, svdoldie und donclumsy (diddipoeler@gmx.de)
 * @copyright Copyright: © 2013 Fussball in Europa http://fussballineuropa.de/ All rights reserved.
 * @license   This file is part of SportsManagement.
 * @package   sportsmanagement
 * @subpackage installation
 */

/**
 * Joomla 4
 * Potential backward compatibility issues in Joomla 4
 * https://docs.joomla.org/Potential_backward_compatibility_issues_in_Joomla_4
 * Class 'JToolBarHelper' not found #14330
 * https://github.com/joomla/joomla-cms/issues/14330
 * https://api.joomla.org/cms-3/deprecated.html
 * https://www.joomla.org/announcements/release-news/5718-joomla-4-0-alpha-1-release.html
 * 
 * https://www.spiralscripts.co.uk/Joomla-Tips/modal-windows-in-joomla-3.html
 * 
 * 
 */

/**
 * wichtige links
 * 
 * bootstrap beispiele
 * http://bootsnipp.com/
 * 
 * bootstrap css
 * http://getbootstrap.com/css/
 * 
 * bootstrap javascript
 * http://getbootstrap.com/javascript/
 * 
 * Retrieving request data using JInput
 * https://docs.joomla.org/Retrieving_request_data_using_JInput
 * 
 * https://docs.joomla.org/J2.5:Managing_Component_Updates_(Script.php)
 * 
 * Joomla developer network
 * https://developer.joomla.org/coding-standards/php-code.html
 * 
 * Google Calendar API nutzen
 * http://www.codefreaks.net/google-calendar-api-mit-php-nuetzen/
 * 
 * 
 * https://github.com/google/google-api-php-client
 * https://github.com/google/google-api-php-client-services
 * 
 * 
 * https://vi-solutions.de/de/tips-vom-joomla-spezialist/265-joomla-komponente-mit-sql-update
 * 
 * Time difference between php timestamps
 * https://stackoverflow.com/questions/9732553/time-difference-between-php-timestamps-in-hours
 * https://stackoverflow.com/questions/40330156/timestampdiff-how-to-use-it-in-php-mysql-to-calculate-difference-between-date
 * http://php.net/manual/de/datetime.diff.php
 * 
 * protokollierung von fehlern
 * http://eddify.me/posts/logging-in-joomla-with-jlog.html
 * 
 * 
 * 
 */

defined('_JEXEC') or die('Restricted access');
use Joomla\CMS\Factory;
use Joomla\CMS\Language\Text;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Filesystem\Folder;
use Joomla\CMS\Filesystem\Path;
use Joomla\CMS\Filesystem\File;

if (! defined('DS'))
{
	define('DS', DIRECTORY_SEPARATOR);
}

jimport('joomla.installer.installer');
 
if(version_compare(JVERSION,'3.0.0','ge')) 
{
jimport('joomla.html.html.bootstrap');
}
        
/**
 * com_sportsmanagementInstallerScript
 * 
 * @package   
 * @author 
 * @copyright diddi
 * @version 2014
 * @access public
 */
class com_sportsmanagementInstallerScript
{

    /**
     * The release value would ideally be extracted from <version> in the manifest file,
     * but at preflight, the manifest file exists only in the uploaded temp folder.
     */
    private $release = '1.0.71';
    
    
    /**
	 * Constructor
	 *
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 */    
    public function __construct( $adapter)
    {
    // https://api.joomla.org/cms-3/deprecated.html
if(version_compare( substr(JVERSION,0,5) ,'4.0.0','ge')) 
{
$this->startPane = 'startTabSet';
$this->endPane = 'endTabSet';
$this->addPanel = 'addTab';
$this->endPanel = 'endTab';
//$this->release = (string) $adapter->getManifest()->version;
}
else
{
$this->startPane = 'startPane';
$this->endPane = 'endPane';
$this->addPanel = 'addPanel';
$this->endPanel = 'endPanel';
//$this->release = $adapter->get( "manifest" )->version;
}

    
    }

	
    
    /**
	 * Called on installation
	 *
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	function install( $adapter) 
	{

	}
 
	/**
	 * Called on uninstallation
	 *
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 */
	function uninstall( $adapter) 
	{
		echo '<p>' . Text::_('COM_SPORTSMANAGEMENT_UNINSTALL_TEXT') . '</p>';
	}
 
	/**
	 * Called on update
	 *
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	function update( $adapter) 
	{
		echo '<p>' . Text::_('COM_SPORTSMANAGEMENT_UPDATE_TEXT') . $this->release . '</p>';
	}
 

	/**
	 * Called before any type of action
	 *
	 * @param   string  $route  Which action is happening (install|uninstall|discover_install|update)
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	function preflight($route,  $adapter) 
	{
	    
    if ( $route == 'update' ) 
    {
        $this->oldRelease = $this->getParam('version');
        if (version_compare($this->oldRelease, $this->release, 'lt'))
        {
            try {
            //Repair table #__schema which was not used before
            //Just create a dataset with extension id and old version (before update).
            $db = Factory::getDbo();
            $query = $db->getQuery(true);
            $query->select($db->quoteName('extension_id'))
                ->from('#__extensions')
                ->where($db->quoteName('type') . ' = ' . $db->quote('component') . ' AND ' . $db->quoteName('element') . ' = ' . $db->quote('com_sportsmanagement') . ' AND ' . $db->quoteName('name') . ' = ' . $db->quote('myComponent'));
            $db->setQuery($query);
            if ($eid = $db->loadResult())
            {
                $query->clear();
                $query->insert($db->quoteName('#__schemas'));
                $query->columns(array($db->quoteName('extension_id'), $db->quoteName('version_id')));
                $query->values($eid . ', ' . $db->quote($this->oldRelease));
                $db->setQuery($query);
if(version_compare(JVERSION,'3.0.0','ge')) 
{
    $db->execute();        
}    
else
{
    $db->query();
}
            }
            } catch (Exception $e) {
            $msg = $e->getMessage(); // Returns "Normally you would have other code...
            $code = $e->getCode(); // Returns '500';
            Factory::getApplication()->enqueueMessage(__METHOD__.' '.__LINE__.' '.$msg, 'error'); // commonly to still display that error
            }
        }
    }
    
    
    
    
       
       if(version_compare(JVERSION,'3.0.0','ge')) 
        {
            // Define tabs options for version of Joomla! 3.0
        $tabsOptions = array(
            "active" => "tab1_id" // It is the ID of the active tab.
        );  
            $image1 = '<img src="../media/com_sportsmanagement/jl_images/ext_com.png">';
            $image2 = '<img src="../media/com_sportsmanagement/jl_images/ext_mod.png">';
            $image3 = '<img src="../media/com_sportsmanagement/jl_images/ext_plugin.png">';
            $image4 = '<img src="../media/com_sportsmanagement/jl_images/ext_esp.png">';
            ?>
            <!-- This is a list with tabs names. -->
    	<ul class="nav nav-tabs" id="ID-Tabs-Group">
        	<li class="active">
        		<a data-toggle="tab" href="#tab1_id"><?php echo $image1.Text::_(' Component'); ?></a>
        	</li>
        	<li>
        		<a data-toggle="tab" href="#tab2_id"><?php echo $image2.Text::_(' Modules'); ?></a>
    		</li>
            <li>
        		<a data-toggle="tab" href="#tab3_id"><?php echo $image3.Text::_(' Plugins'); ?></a>
    		</li>
            <li>
        		<a data-toggle="tab" href="#tab4_id"><?php echo $image4.Text::_(' Create/Update Images Folders'); ?></a>
    		</li>
            
        </ul>
            
            <?PHP
            echo HTMLHelper::_('bootstrap.'.$this->startPane, 'ID-Tabs-Group', $tabsOptions);
            echo HTMLHelper::_('bootstrap.'.$this->addPanel, 'ID-Tabs-Group', 'tab1_id',Text::_(' Component')); 
            echo '<h2>' . Text::_('COM_SPORTSMANAGEMENT_DESCRIPTION') .'</h2>';
            echo HTMLHelper::_('bootstrap.'.$this->endPanel);
 
             
            }
            else
            {
	   echo HTMLHelper::_('sliders.start','steps',array(
						'allowAllClose' => true,
						'startTransition' => true,
						true));
       $image = '<img src="../media/com_sportsmanagement/jl_images/ext_com.png">';
		echo HTMLHelper::_('sliders.panel', $image.' Component', 'panel-component');
        echo '<h2>' . Text::_('COM_SPORTSMANAGEMENT_DESCRIPTION') .'</h2>';
        }                      
        
        ?>
		
		<img
			src="../administrator/components/com_sportsmanagement/assets/icons/logo_transparent.png"
			alt="SportsManagement" title="SportsManagement" width="180"/>
		<?php
        $j = new JVersion();
        echo '<h1>' . sprintf(Text::_('COM_SPORTSMANAGEMENT_JOOMLA_VERSION'), $j->getShortVersion() ) .'</h1>';
        ?>
        <img
			src="../media/com_sportsmanagement/jl_images/compat_25.png"
			alt="JSM Joomla Sports Management" title="JSM Joomla Sports Management" width="auto"/>
        <img
			src="../media/com_sportsmanagement/jl_images/Compat_icon_3_8_long.png"
			alt="JSM Joomla Sports Management" title="JSM Joomla Sports Management" width="auto"/>
        <img
			src="../media/com_sportsmanagement/jl_images/Compat_icon_4_0_long.png"
			alt="JSM Joomla Sports Management" title="JSM Joomla Sports Management" width="auto"/>
         <?php       
        echo '<p>' . Text::_('COM_SPORTSMANAGEMENT_PREFLIGHT_' . $route . '_TEXT' ) . $this->release . '</p>';
        
        
        
        
        
	}
 

	/**
	 * com_sportsmanagementInstallerScript::getParam()
	 * get a variable from the manifest file (actually, from the manifest cache).
	 * @param mixed $name
	 * @return
	 */
	function getParam( $name ) {
		$db = Factory::getDbo();
		$db->setQuery('SELECT manifest_cache FROM #__extensions WHERE name = "com_sportsmanagement" ');
		$manifest = json_decode( $db->loadResult(), true );
		return $manifest[ $name ];
	}

	/**
	 * Called after any type of action
	 *
	 * @param   string  $route  Which action is happening (install|uninstall|discover_install|update)
	 * @param   JAdapterInstance  $adapter  The object responsible for running this script
	 *
	 * @return  boolean  True on success
	 */
	function postflight($route,  $adapter) 
	{
	$mainframe = Factory::getApplication();
    $db = Factory::getDbo();
    
    // sicherheitshalber dateien löschen, die ich falsch angelegt habe.
    // aber nur wenn sie vorhanden sind
$files = array(
			// Joomla 4.0
			'/administrator/components/com_sportsmanagement/models/fields/link.php',
            '/administrator/components/com_sportsmanagement/models/fields/message.php',
            '/administrator/components/com_sportsmanagement/models/fields/subtitle.php',
            '/administrator/components/com_sportsmanagement/models/fields/title.php',
            '/administrator/components/com_sportsmanagement/views/agegroup/tmpl/edit.php',
            '/administrator/components/com_sportsmanagement/views/agegroup/tmpl/edit_3.php',
            '/administrator/components/com_sportsmanagement/views/club/tmpl/edit.php',
            '/administrator/components/com_sportsmanagement/views/club/tmpl/edit_3.php',
            '/administrator/components/com_sportsmanagement/views/club/tmpl/edit_4.php',
            '/administrator/components/com_sportsmanagement/views/clubname/tmpl/edit.php',
            '/administrator/components/com_sportsmanagement/views/clubname/tmpl/edit_3.php',
            '/administrator/components/com_sportsmanagement/views/division/tmpl/edit.php',
            '/administrator/components/com_sportsmanagement/views/division/tmpl/edit_3.php',
            '/administrator/components/com_sportsmanagement/views/eventtype/tmpl/edit.php',
            '/administrator/components/com_sportsmanagement/views/eventtype/tmpl/edit_3.php',
            '/administrator/components/com_sportsmanagement/views/extrafield/tmpl/edit.php',
            '/administrator/components/com_sportsmanagement/views/extrafield/tmpl/edit_details.php',
            '/administrator/components/com_sportsmanagement/views/extrafield/tmpl/edit_extended.php',
            '/administrator/components/com_sportsmanagement/views/extrafield/tmpl/edit_picture.php',
            '/administrator/components/com_sportsmanagement/views/extrafield/tmpl/edit_3.php',
            '/administrator/components/com_sportsmanagement/views/league/tmpl/edit.php',
            '/administrator/components/com_sportsmanagement/views/league/tmpl/edit_3.php',
            '/administrator/components/com_sportsmanagement/views/person/tmpl/edit.php',
            '/administrator/components/com_sportsmanagement/views/person/tmpl/edit_3.php',
            '/administrator/components/com_sportsmanagement/views/jlextassociation/tmpl/edit.php',
            '/administrator/components/com_sportsmanagement/views/jlextassociation/tmpl/edit_3.php',
            '/administrator/components/com_sportsmanagement/views/jlextcountry/tmpl/edit.php',
            '/administrator/components/com_sportsmanagement/views/jlextcountry/tmpl/edit_3.php',
            '/administrator/components/com_sportsmanagement/views/jlextfederation/tmpl/edit.php',
            '/administrator/components/com_sportsmanagement/views/jlextfederation/tmpl/edit_3.php',
            '/administrator/components/com_sportsmanagement/views/playground/tmpl/edit.php',
            '/administrator/components/com_sportsmanagement/views/playground/tmpl/edit_3.php',
            '/administrator/components/com_sportsmanagement/views/round/tmpl/edit.php',
            '/administrator/components/com_sportsmanagement/views/round/tmpl/edit_3.php',
            '/administrator/components/com_sportsmanagement/views/projectreferee/tmpl/edit.php',
            '/administrator/components/com_sportsmanagement/views/projectreferee/tmpl/edit_details.php',
            '/administrator/components/com_sportsmanagement/views/projectreferee/tmpl/edit_description.php', 
            '/administrator/components/com_sportsmanagement/views/projectreferee/tmpl/edit_extended.php',
            '/administrator/components/com_sportsmanagement/views/projectreferee/tmpl/edit_picture.php',
            '/administrator/components/com_sportsmanagement/views/projectreferee/tmpl/edit_3.php',
            '/administrator/components/com_sportsmanagement/views/season/tmpl/edit.php',
            '/administrator/components/com_sportsmanagement/views/season/tmpl/edit_3.php',
            '/administrator/components/com_sportsmanagement/views/position/tmpl/edit.php',
            '/administrator/components/com_sportsmanagement/views/position/tmpl/edit_details.php',
            '/administrator/components/com_sportsmanagement/views/position/tmpl/edit_events.php',
            '/administrator/components/com_sportsmanagement/views/position/tmpl/edit_extended.php',
            '/administrator/components/com_sportsmanagement/views/position/tmpl/edit_statistics.php',
            '/administrator/components/com_sportsmanagement/views/position/tmpl/edit_3.php',
            '/administrator/components/com_sportsmanagement/views/position/tmpl/edit_3_details.php',
            '/administrator/components/com_sportsmanagement/views/position/tmpl/edit_3_events.php',
            '/administrator/components/com_sportsmanagement/views/position/tmpl/edit_3_extended.php',
            '/administrator/components/com_sportsmanagement/views/position/tmpl/edit_3_statistics.php',
            '/administrator/components/com_sportsmanagement/views/smquote/tmpl/edit.php',
            '/administrator/components/com_sportsmanagement/views/smquote/tmpl/edit_3.php',
            '/administrator/components/com_sportsmanagement/views/sportstype/tmpl/edit.php',
            '/administrator/components/com_sportsmanagement/views/sportstype/tmpl/edit_3.php',
            '/administrator/components/com_sportsmanagement/views/team/tmpl/edit.php',
            '/administrator/components/com_sportsmanagement/views/team/tmpl/edit_3.php',
            '/administrator/components/com_sportsmanagement/views/template/tmpl/edit.php',
            '/administrator/components/com_sportsmanagement/views/template/tmpl/edit_3.php',
            '/administrator/components/com_sportsmanagement/views/treeto/tmpl/edit.php',
            '/administrator/components/com_sportsmanagement/views/treeto/tmpl/edit_3.php',
            
            '/administrator/components/com_sportsmanagement/views/teamperson/tmpl/edit.php',
            '/administrator/components/com_sportsmanagement/views/teamperson/tmpl/edit_3.php',
            '/administrator/components/com_sportsmanagement/views/teamperson/tmpl/edit_description.php',
            '/administrator/components/com_sportsmanagement/views/teamperson/tmpl/edit_3_description.php',
            '/administrator/components/com_sportsmanagement/views/teamperson/tmpl/edit_3_extended.php',
            '/administrator/components/com_sportsmanagement/views/teamperson/tmpl/edit_extended.php',
            '/administrator/components/com_sportsmanagement/views/teamperson/tmpl/edit_picture.php',
            '/administrator/components/com_sportsmanagement/views/teamperson/tmpl/edit_3_picture.php',
            
            '/administrator/components/com_sportsmanagement/views/treetonode/tmpl/edit.php',
            '/administrator/components/com_sportsmanagement/views/treetonode/tmpl/edit_3.php',
            
            '/administrator/components/com_sportsmanagement/views/treetonode/tmpl/edit_3_description.php',
            '/administrator/components/com_sportsmanagement/views/treetonode/tmpl/form.php',
            '/administrator/components/com_sportsmanagement/views/treetonode/tmpl/form_description.php',
		);
            
foreach ($files as $file)
		{
			if (File::exists(JPATH_ROOT . $file) && !File::delete(JPATH_ROOT . $file))
			{
				echo Text::sprintf('FILES_JOOMLA_ERROR_FILE_FOLDER', $file) . '<br>';
			}
		}



		
    if(version_compare(JVERSION,'3.0.0','ge')) 
        {

            echo '<p>' . Text::_('COM_SPORTSMANAGEMENT_POSTFLIGHT_' . $route . '_TEXT' ) . $this->release . '</p>';

$params = JComponentHelper::getParams('com_sportsmanagement');
$xmlfile = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_sportsmanagement'.DS.'config.xml';  
$jRegistry = new JRegistry;
$jRegistry->loadString($params->toString('ini'), 'ini');
////$form =& JForm::getInstance('com_sportsmanagement', $xmlfile, array('control'=> 'params'), false, "/config");
$newparams = array();


$form = JForm::getInstance('com_sportsmanagement', $xmlfile,array('control'=> ''), false, "/config");
$form->bind($jRegistry);
//foreach($form->getFieldset($fieldset->name) as $field)
foreach($form->getFieldset() as $field)
        {
        $newparams[$field->name] = $field->value;
        }
//$mainframe->enqueueMessage(Text::_('postflight newparams<br><pre>'.print_r($newparams,true).'</pre>'   ),'');


    switch ($route)        
    {
    case "install":
    echo HTMLHelper::_('bootstrap.'.$this->addPanel, 'ID-Tabs-Group', 'tab2_id',Text::_(' Modules')); 
    self::installModules($adapter);
    echo HTMLHelper::_('bootstrap.'.$this->endPanel); 
    
    echo HTMLHelper::_('bootstrap.'.$this->addPanel, 'ID-Tabs-Group', 'tab3_id',Text::_(' Plugins'));
    self::installPlugins($adapter);
    echo HTMLHelper::_('bootstrap.'.$this->endPanel); 

    echo HTMLHelper::_('bootstrap.'.$this->addPanel, 'ID-Tabs-Group', 'tab4_id',Text::_(' Create/Update Images Folders'));  
    self::createImagesFolder();
    self::installJoomlaExtensions($adapter);
    echo HTMLHelper::_('bootstrap.'.$this->endPanel); 
    
    self::setParams($newparams);    
    break;
    case "update":
    echo HTMLHelper::_('bootstrap.'.$this->addPanel, 'ID-Tabs-Group', 'tab2_id',Text::_(' Modules'));
    self::installModules($adapter);
    echo HTMLHelper::_('bootstrap.'.$this->endPanel); 
    
    echo HTMLHelper::_('bootstrap.'.$this->addPanel, 'ID-Tabs-Group', 'tab3_id',Text::_(' Plugins'));
    self::installPlugins($adapter);
    echo HTMLHelper::_('bootstrap.'.$this->endPanel); 

    echo HTMLHelper::_('bootstrap.'.$this->addPanel, 'ID-Tabs-Group', 'tab4_id',Text::_(' Create/Update Images Folders'));  
    self::createImagesFolder();
    self::installJoomlaExtensions($adapter);
    echo HTMLHelper::_('bootstrap.'.$this->endPanel);
    
    self::setParams($newparams);
    break;
    case "discover_install":
    break;
        
    }
            
            
            echo HTMLHelper::_('bootstrap.'.$this->endPane, 'ID-Tabs-Group');
            }
            else
            {
                   
		echo '<p>' . Text::_('COM_SPORTSMANAGEMENT_POSTFLIGHT_' . $route . '_TEXT' ) . $this->release . '</p>';

$params = JComponentHelper::getParams('com_sportsmanagement');
$xmlfile = JPATH_ADMINISTRATOR.DS.'components'.DS.'com_sportsmanagement'.DS.'config.xml';  
$jRegistry = new JRegistry;
$jRegistry->loadString($params->toString('ini'), 'ini');
////$form =& JForm::getInstance('com_sportsmanagement', $xmlfile, array('control'=> 'params'), false, "/config");
$form = JForm::getInstance('com_sportsmanagement', $xmlfile,array('control'=> ''), false, "/config");
$form->bind($jRegistry);
$newparams = array();
foreach($form->getFieldset() as $field)
        {
        $newparams[$field->name] = $field->value;
        }

switch ($route)        
    {
    case "install":
    self::setParams($newparams);
//    self::installComponentLanguages();
$image = '<img src="../media/com_sportsmanagement/jl_images/ext_mod.png">';
		echo HTMLHelper::_('sliders.panel', $image.' Modules', 'panel-modules');
    self::installModules($adapter);
    $image = '<img src="../media/com_sportsmanagement/jl_images/ext_plugin.png">';
		echo HTMLHelper::_('sliders.panel', $image.' Plugins', 'panel-plugins');
    self::installPlugins($adapter);
    $image = '<img src="../media/com_sportsmanagement/jl_images/ext_esp.png">';
		echo HTMLHelper::_('sliders.panel', $image.' Create/Update Images Folders', 'panel-images');
    self::createImagesFolder();
//    self::migratePicturePath();
//    self::deleteInstallFolders();
//    self::sendInfoMail();
    break;
    case "update":
//    self::installComponentLanguages();
$image = '<img src="../media/com_sportsmanagement/jl_images/ext_mod.png">';
		echo HTMLHelper::_('sliders.panel', $image.' Modules', 'panel-modules');
    self::installModules($adapter);
    $image = '<img src="../media/com_sportsmanagement/jl_images/ext_plugin.png">';
		echo HTMLHelper::_('sliders.panel', $image.' Plugins', 'panel-plugins');
    self::installPlugins($adapter);
    $image = '<img src="../media/com_sportsmanagement/jl_images/ext_esp.png">';
		echo HTMLHelper::_('sliders.panel', $image.' Create/Update Images Folders', 'panel-images');
    self::createImagesFolder();
//    self::migratePicturePath();
      self::setParams($newparams);
//    self::deleteInstallFolders();
//    self::sendInfoMail();
    break;
    case "discover_install":
    break;
        
    }

echo HTMLHelper::_('sliders.end');
echo self::getFxInitJSCode('steps');

}

	}
    
    
    
    /**
     * com_sportsmanagementInstallerScript::deleteFolders()
     * 
     * @param mixed $adapter
     * @return void
     */
    public function deleteFolders( $adapter)
    {
    $mainframe = Factory::getApplication();
    $excludeExtension = array();
    $excludeExtension[] = 'extensions';    
    $excludeExtension[] = 'sisdata';
    $folderAdmin  = Folder::folders(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_sportsmanagement',
													'.', false, false, $excludeExtension);
    
    foreach ($folderAdmin as $key => $value )
    {
        if( Folder::delete(JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_sportsmanagement'.DS.$value) )
        {
        //echo 'Der Ordner wurde gelöscht';
        }
    }
    $folderSite  = Folder::folders(JPATH_SITE.DS.'components'.DS.'com_sportsmanagement',
													'.', false, false, $excludeExtension);
   
    foreach ($folderSite as $key => $value )
    {
        if( Folder::delete(JPATH_SITE.DS.'components'.DS.'com_sportsmanagement'.DS.$value) )
        {
        //echo 'Der Ordner wurde gelöscht';
        }
    }
    
    }
    
    /**
     * com_sportsmanagementInstallerScript::getFxInitJSCode()
     * 
     * @param mixed $group
     * @return
     */
    private function getFxInitJSCode ($group) 
    {
		$params = array();
		$params['allowAllClose'] = 'true';
		$display = (isset($params['startOffset']) && isset($params['startTransition']) && $params['startTransition'])
		? (int) $params['startOffset'] : null;
		$show = (isset($params['startOffset']) && !(isset($params['startTransition']) && $params['startTransition']))
		? (int) $params['startOffset'] : null;
		$options = '{';
		$opt['onActive'] = "function(toggler, i) {toggler.addClass('pane-toggler-down');" .
				"toggler.removeClass('pane-toggler');i.addClass('pane-down');i.removeClass('pane-hide');Cookie.write('jpanesliders_"
				. $group . "',$$('div#" . $group . ".pane-sliders > .panel > h3').indexOf(toggler));}";
		$opt['onBackground'] = "function(toggler, i) {toggler.addClass('pane-toggler');" .
				"toggler.removeClass('pane-toggler-down');i.addClass('pane-hide');i.removeClass('pane-down');if($$('div#"
				. $group . ".pane-sliders > .panel > h3').length==$$('div#" . $group
				. ".pane-sliders > .panel > h3.pane-toggler').length) Cookie.write('jpanesliders_" . $group . "',-1);}";
		$opt['duration'] = (isset($params['duration'])) ? (int) $params['duration'] : 300;
		$opt['display'] = (isset($params['useCookie']) && $params['useCookie']) ? JRequest::getInt('jpanesliders_' . $group, $display, 'cookie')
		: $display;
		$opt['show'] = (isset($params['useCookie']) && $params['useCookie']) ? JRequest::getInt('jpanesliders_' . $group, $show, 'cookie') : $show;
		$opt['opacity'] = (isset($params['opacityTransition']) && ($params['opacityTransition'])) ? 'true' : 'false';
		$opt['alwaysHide'] = (isset($params['allowAllClose']) && (!$params['allowAllClose'])) ? 'false' : 'true';
		foreach ($opt as $k => $v)
		{
			if ($v)
			{
				$options .= $k . ': ' . $v . ',';
			}
		}
		if (substr($options, -1) == ',')
		{
			$options = substr($options, 0, -1);
		}
		$options .= '}';
		
		$js = "window.addEvent('domready', function(){ new Fx.Accordion($$('div#" . $group
		. ".pane-sliders > .panel > h3.pane-toggler'), $$('div#" . $group . ".pane-sliders > .panel > div.pane-slider'), " . $options
		. "); });";
		
		return '<script>'.$js.'</script>';
	}
    
    /**
     * com_sportsmanagementInstallerScript::createImagesFolder()
     * 
     * @return void
     */
    public function createImagesFolder()
	{
		$mainframe = Factory::getApplication();
  $db = Factory::getDBO();
  
        //echo Text::_('Creating new Image Folder structure');
		$dest = JPATH_ROOT.'/images/com_sportsmanagement';
		$update = Folder::exists($dest);
		$folders = array('agegroups',
		'clubs',
		'clubs/large',
		'clubs/medium',
		'clubs/small',
		'clubs/trikot_home',
		'clubs/trikot_away',
        'clubs/trikot',
        'laender_karten',
		'events',
		'leagues',
        'positions',
		'divisions',
		'person_playground',
		'associations',
		'flags_associations',
		'persons',
		'placeholders',
		'predictionusers',
		'playgrounds',
		'projects',
		'projectreferees',
		'projectteams',
		'projectteams/trikot_home',
		'projectteams/trikot_away',
		'associations',
		'rosterground',
		'matchreport',
		'seasons',
		'sport_types',
		'rounds',
		'teams',
		'flags',
		'teamplayers',
		'teamstaffs',
		'venues',
        'jl_images',
		'statistics');
		Folder::create(JPATH_ROOT.'/images/com_sportsmanagement');
		File::copy(JPATH_ROOT.'/images/index.html', JPATH_ROOT.'/images/com_sportsmanagement/index.html');
		Folder::create(JPATH_ROOT.'/images/com_sportsmanagement/database');
		File::copy(JPATH_ROOT.'/images/index.html', JPATH_ROOT.'/images/com_sportsmanagement/database/index.html');
		
        foreach ($folders as $folder) 
        {
			Folder::create(JPATH_ROOT.'/images/com_sportsmanagement/database/'.$folder);
			File::copy(JPATH_ROOT.'/images/index.html', JPATH_ROOT.'/images/com_sportsmanagement/database/'.$folder.'/index.html');
            echo '<p>' . Text::_('Imagefolder : ' ) . $folder . ' angelegt!</p>';
		}
        
		foreach ($folders as $folder) {
			$from = Path::clean(JPATH_ROOT.'/media/com_sportsmanagement/'.$folder);
			if(Folder::exists($from)) {
				$to = Path::clean($dest.'/database/'.$folder);
				if(!Folder::exists($to)) {
					$ret = Folder::move($from, $to);
				} else {
					$ret = Folder::copy($from, $to, '', true);
				}
			}
		}
        
        foreach ($folders as $folder) {
		switch ( $folder )
	    {
		case 'persons':
		File::copy(JPATH_ROOT.'/images/com_sportsmanagement/database/placeholders/men_small.png', JPATH_ROOT.'/images/com_sportsmanagement/database/'.$folder.'/men_small.png');
		File::copy(JPATH_ROOT.'/images/com_sportsmanagement/database/placeholders/men_large.png', JPATH_ROOT.'/images/com_sportsmanagement/database/'.$folder.'/men_large.png');
		File::copy(JPATH_ROOT.'/images/com_sportsmanagement/database/placeholders/woman_small.png', JPATH_ROOT.'/images/com_sportsmanagement/database/'.$folder.'/woman_small.png');
		File::copy(JPATH_ROOT.'/images/com_sportsmanagement/database/placeholders/woman_large.png', JPATH_ROOT.'/images/com_sportsmanagement/database/'.$folder.'/woman_large.png');
        File::copy(JPATH_ROOT.'/images/com_sportsmanagement/database/placeholders/placeholder_150_2.png', JPATH_ROOT.'/images/com_sportsmanagement/database/'.$folder.'/placeholder_150_2.png');
		break;
		case 'clubs/small':
        case 'clubs/trikot_home':
        case 'clubs/trikot_away':
        case 'clubs/trikot':
		File::copy(JPATH_ROOT.'/images/com_sportsmanagement/database/placeholders/placeholder_small.gif', JPATH_ROOT.'/images/com_sportsmanagement/database/'.$folder.'/placeholder_small.gif');
		break;
		case 'clubs/medium':
		File::copy(JPATH_ROOT.'/images/com_sportsmanagement/database/placeholders/placeholder_50.png', JPATH_ROOT.'/images/com_sportsmanagement/database/'.$folder.'/placeholder_50.png');
		break;
		case 'clubs/large':
		File::copy(JPATH_ROOT.'/images/com_sportsmanagement/database/placeholders/placeholder_150.png', JPATH_ROOT.'/images/com_sportsmanagement/database/'.$folder.'/placeholder_150.png');
		break;
	    }
		}

	}
    
    
    /**
     * com_sportsmanagementInstallerScript::setParams()
     * sets parameter values in the component's row of the extension table
     * @param mixed $param_array
     * @return void
     */
    function setParams($param_array) 
    {
        
        $mainframe = Factory::getApplication();
        $db = Factory::getDbo();
        
                if ( count($param_array) > 0 ) 
                {
                        try{
                        // read the existing component value(s)
                        $db = Factory::getDbo();
                        $db->setQuery('SELECT params FROM #__extensions WHERE name = "com_sportsmanagement"');
                        $params = json_decode( $db->loadResult(), true );
                       
                        // add the new variable(s) to the existing one(s)
                        foreach ( $param_array as $name => $value ) {
                                $params[ (string) $name ] = (string) $value;
                        }
                       
                        // store the combined new and existing values back as a JSON string
                        $paramsString = json_encode( $params );
                        $db->setQuery('UPDATE #__extensions SET params = ' .
                                $db->quote( $paramsString ) .
                                ' WHERE name = "com_sportsmanagement"' );
if(version_compare(JVERSION,'3.0.0','ge')) 
{
    $db->execute();        
}    
else
{
    $db->query();
}
                
                        } catch (Exception $e) {
                        $msg = $e->getMessage(); // Returns "Normally you would have other code...
                        $code = $e->getCode(); // Returns '500';
                        Factory::getApplication()->enqueueMessage(__METHOD__.' '.__LINE__.' '.$msg, 'error'); // commonly to still display that error
                        }
                }
                
        }
        

    
    
    /**
     * com_sportsmanagementInstallerScript::installJoomlaExtensions()
     * 
     * @param mixed $adapter
     * @return void
     */
    public function installJoomlaExtensions( $adapter)
	{
  $mainframe = Factory::getApplication();
  $src = $adapter->getParent()->getPath('source');
  $manifest = $adapter->getParent()->manifest;
  $db = Factory::getDBO();
  
  Folder::copy(JPATH_ROOT.'/administrator/components/com_sportsmanagement/libraries/joomla/', JPATH_ROOT.'/', '', true);
  
  }
  
  
	
	/**
	 * com_sportsmanagementInstallerScript::installPlugins()
	 * 
	 * @param mixed $adapter
	 * @return void
	 */
	public function installPlugins( $adapter)
	{
  $mainframe = Factory::getApplication();
  $src = $adapter->getParent()->getPath('source');
  $manifest = $adapter->getParent()->manifest;
  $db = Factory::getDBO();

  $plugins = $manifest->xpath('plugins/plugin');
  $plugins3 = $manifest->xpath('plugins3/plugin');
  
  if(version_compare(JVERSION,'3.0.0','ge')) 
        {
  foreach ($plugins3 as $plugin)
        {
        $name = (string)$plugin->attributes()->plugin;
        $group = (string)$plugin->attributes()->group;
        
        echo '<p>' . Text::_('Plugin : ' ) . $name . ' installiert!</p>';
       
        // Select some fields
        $query = $db->getQuery(true);
        $query->clear();
		$query->select('extension_id');
        // From table
        $query->from('#__extensions');
        $query->where("type = 'plugin' ");
        $query->where("element = '".$name."' ");
        $query->where("folder = '".$group."' ");
        $db->setQuery($query);
        $plugin_id = $db->loadResult();

        switch ( $name )
        {
        case 'jqueryeasy';
        if ( $plugin_id )
        {
            // plugin ist vorhanden
            // wurde vielleicht schon aktualisiert
        }
        else
        {
            // plugin ist nicht vorhanden
            // also installieren
            $path = $src.DS.'plugins'.DS.$name.'_3';
            $installer = new JInstaller;
            $result = $installer->install($path);    
        }    
        break;
        default:    
        $path = $src.DS.'plugins'.DS.$name;
        $installer = new JInstaller;
        $result = $installer->install($path);
        break;
        }
        
/**
 * das ein- und ausschalten den anwendern überlassen
 */
        /*
        // auf alle faelle erst mal nicht einschalten        
        // Create an object for the record we are going to update.
        $object = new stdClass();
        // Must be a valid primary key value.
        $object->extension_id = $plugin_id;
        $object->enabled = 0;
        // Update their details in the users table using id as the primary key.
        $result = Factory::getDbo()->updateObject('#__extensions', $object, 'extension_id');  
        */
        }
  }
  else
  {
  
  foreach ($plugins as $plugin)
        {
        $name = (string)$plugin->attributes()->plugin;
        $group = (string)$plugin->attributes()->group;
        
        echo '<p>' . Text::_('Plugin : ' ) . $name . ' installiert!</p>';
       
        // Select some fields
        $query = $db->getQuery(true);
        $query->clear();
		$query->select('extension_id');
        // From table
        $query->from('#__extensions');
        $query->where("type = 'plugin' ");
        $query->where("element = '".$name."' ");
        $query->where("folder = '".$group."' ");
        $db->setQuery($query);
        $plugin_id = $db->loadResult();

        switch ( $name )
        {
        case 'jqueryeasy';
        case 'jw_ts';
        case 'plugin_googlemap3';
        if ( $plugin_id )
        {
            // plugin ist vorhanden
            // wurde vielleicht schon aktualisiert
        }
        else
        {
            // plugin ist nicht vorhanden
            // also installieren
            $path = $src.DS.'plugins'.DS.$name;
            $installer = new JInstaller;
            $result = $installer->install($path);    
        }    
        break;
        default:    
        $path = $src.DS.'plugins'.DS.$name;
        $installer = new JInstaller;
        $result = $installer->install($path);
        break;
        }

/**
 * das ein- und ausschalten den anwendern überlassen
 */
        /*
        // auf alle faelle erst mal nicht einschalten        
        // Create an object for the record we are going to update.
        $object = new stdClass();
        // Must be a valid primary key value.
        $object->extension_id = $plugin_id;
        $object->enabled = 0;
        // Update their details in the users table using id as the primary key.
        $result = Factory::getDbo()->updateObject('#__extensions', $object, 'extension_id');  
        */
        }
        
        }
        
        

    
    }
    
    

	
	/**
	 * com_sportsmanagementInstallerScript::installModules()
	 * 
	 * @param mixed $adapter
	 * @return void
	 */
	public function installModules( $adapter)
	{
  $mainframe = Factory::getApplication();
  $src = $adapter->getParent()->getPath('source');
  $manifest = $adapter->getParent()->manifest;
  $db = Factory::getDBO();

  $modules = $manifest->xpath('modules/module');
        foreach ($modules as $module)
        {
            $name = (string)$module->attributes()->module;
            $client = (string)$module->attributes()->client;
            
            $position = (string)$module->attributes()->position;
            $published = (string)$module->attributes()->published;
            
            echo '<p>' . Text::_('Modul : ' ) . $name . ' installiert!</p>';
            
            if (is_null($client))
            {
                $client = 'site';
            }
            $path = $client == 'administrator' ? $src.DS.'admin'.DS.'modules'.DS.$name : $src.DS.'modules'.DS.$name;
            $installer = new JInstaller;
            $result = $installer->install($path);
            $ordering = '99';
            
            if( $client == 'administrator' )
			{
				$position = version_compare(JVERSION, '3.0', '<') && $name == 'mod_sportsmanagement_quickicon' ? 'icon' : 'cpanel';
                $ordering = '1';
            }    
            
            if ( $position )
            {
                try{
                $query = "UPDATE #__modules SET position='".$position."', ordering=".$ordering.", published=".$published." WHERE module='".$name."' ";
                $db->setQuery($query);
if(version_compare(JVERSION,'3.0.0','ge')) 
{
    $db->execute();        
}    
else
{
    $db->query();
}
                if ( $client == 'administrator' )
                {
                $query		 = $db->getQuery(true);
								$query->select('id')->from($db->qn('#__modules'))
									->where($db->qn('module') . ' = ' . $db->q($name));
								$db->setQuery($query);
								$moduleid	 = $db->loadResult();

								$query		 = $db->getQuery(true);
								$query->select('*')->from($db->qn('#__modules_menu'))
									->where($db->qn('moduleid') . ' = ' . $db->q($moduleid));
								$db->setQuery($query);
								$assignments = $db->loadObjectList();
								$isAssigned	 = !empty($assignments);

								if (!$isAssigned)
								{
									$o = (object) array(
											'moduleid'	 => $moduleid,
											'menuid'	 => 0
									);
									$db->insertObject('#__modules_menu', $o);
								}
                
                
                }
                } catch (Exception $e) {
                $msg = $e->getMessage(); // Returns "Normally you would have other code...
                $code = $e->getCode(); // Returns '500';
                Factory::getApplication()->enqueueMessage(__METHOD__.' '.__LINE__.' '.$msg, 'error'); // commonly to still display that error
                }   
                
            }
        }    
  
  
  

    }
    
    
                  
}
