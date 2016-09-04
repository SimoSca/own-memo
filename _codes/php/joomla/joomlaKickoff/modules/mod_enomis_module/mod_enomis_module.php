<?php
/**
 * Hello World! Module Entry Point
 *
 * @package    Joomla.Tutorials
 * @subpackage Modules
 * @license    GNU/GPL, see LICENSE.php
 * @link       http://docs.joomla.org/J3.x:Creating_a_simple_module/Developing_a_Basic_Module
 * mod_helloworld is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 */

// No direct access
defined('_JEXEC') or die;


//JLog::add(JText::_('JTEXT_ERROR_MESSAGE'), JLog::WARNING, 'jerror');

// test plugin event
JPluginHelper::importPlugin('enomis');
$dispatcher = JDispatcher::getInstance();
$results = $dispatcher->trigger( 'onEnomisTest', "Bella pluginzio!" );
var_dump($results);$results = $dispatcher->trigger( 'onEnomisTest', "Bella pluginzio!" );
$results = $dispatcher->trigger( 'onEnomisTest2', "Bella pluginzio2!" );
var_dump($results);

// internationalization test
echo JText::_( 'MOD_LOGIN_FIELD_GREETING_DESC' ) ."<br/>";


// Include the syndicate functions only once
require_once dirname(__FILE__) . '/helper.php';
$check = 'test';
$hello = modHelloWorldHelper::getHello($params);
// va a pescare il tmpl nella directory del file
require JModuleHelper::getLayoutPath('mod_enomis_module');
if(JDEBUG){
    var_dump('Debugging!!!');
}

// Tell the auto-loader to look for namespaced classes starting with MyLib in the JPATH_LIBRARIES/src directory
JLoader::registerNamespace('En', JPATH_LIBRARIES . '/enomis');
\En\Mytest::say();