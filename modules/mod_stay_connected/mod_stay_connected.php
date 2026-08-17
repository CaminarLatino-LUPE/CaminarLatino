<?php
	/**
	* @package Stay Connected
	* @copyright (C) 2026 Caminar Latino. All rights reserved.
	*/

	// no direct access
	defined('_JEXEC') or die;

	use Joomla\CMS\Factory;
	use Joomla\CMS\Helper\ModuleHelper;	
	use Joomla\CMS\HTML\HTMLHelper;
	use Joomla\CMS\Uri\Uri;


	// Register helper file
	JLoader::register('modStayConnectedHelper', __DIR__ . '/helper.php');



	// get module id
	$moduleId = ModuleHelper::getModule('mod_stay_connected', 'Stay Connected - Footer')->id;

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



	HTMLHelper::script(Uri::base() . 'modules/mod_stay_connected/assets/js/script.js');
	HTMLHelper::script('https://www.google.com/recaptcha/api.js?onload=CaptchaCallback&render=explicit&hl=' . $lang_code);
	
	// Include template
	require ModuleHelper::getLayoutPath('mod_stay_connected', $layout);

	

?>

