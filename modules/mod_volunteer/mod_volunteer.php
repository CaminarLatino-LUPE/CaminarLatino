<?php
	/**
	* @package Volunteer
	* @copyright (C) 2026 Caminar Latino. All rights reserved.
	*/

	// no direct access
	defined('_JEXEC') or die;

	use Joomla\CMS\Factory;
	use Joomla\CMS\Helper\ModuleHelper;	
	use Joomla\CMS\HTML\HTMLHelper;
	use Joomla\CMS\Uri\Uri;


	// Register helper file
	JLoader::register('modVolunteerHelper', __DIR__ . '/helper.php');



	// get module id
	$moduleId = ModuleHelper::getModule('mod_volunteer')->id;

	// Get parameters
	$title = $params->get('title', '', 'string');
	$introText = $params->get('intro_text', '', 'string');
	$moduleClass = $params->get('moduleclass_sfx', '', 'string');
	$siteKey = $params->get('captcha_site', '', 'string');
	$secretKey = $params->get('captcha_secret', '', 'string');
	$layout = $params->get('layout', 'default');


	//Get language code
	$lang = Factory::getLanguage();
	$lang_tag = $lang->getTag();
	$lang_code_array = explode("-", $lang_tag);
	$lang_code =  $lang_code_array[0];



	HTMLHelper::script(Uri::base() . 'modules/mod_volunteer/assets/js/script.js');

	
	// Include template
	require ModuleHelper::getLayoutPath('mod_volunteer', $layout);

	

?>

