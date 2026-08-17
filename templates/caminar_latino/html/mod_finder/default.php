<?php

/**
 * @package     Joomla.Site
 * @subpackage  mod_finder
 *
 * @copyright   (C) 2011 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

// Load the smart search component language file.
$lang = $app->getLanguage();
$lang->load('com_finder', JPATH_SITE);

$input = '<input type="text" name="q" id="mod-finder-searchword' . $module->id . '" class="js-finder-search-query form-control" value="' . htmlspecialchars($app->getInput()->get('q', '', 'string'), ENT_COMPAT, 'UTF-8') . '"'
    . ' placeholder="' . Text::_('MOD_FINDER_SEARCH_VALUE') . '" style="position: relative; right: -30px; height: 60px; padding: 10px 36px 10px 20px; border-radius: 30px 0 0 30px; ">';

$showLabel  = $params->get('show_label', 1);
$labelClass = (!$showLabel ? 'visually-hidden ' : '') . 'finder';
$label      = '<label for="mod-finder-searchword' . $module->id . '" class="' . $labelClass . '">' . $params->get('alt_label', Text::_('JSEARCH_FILTER_SUBMIT')) . '</label>';

$output = '';

if ($params->get('show_button', 0)) {
    $output .= $label;
    $output .= '<div class="mod-finder__search input-group">';
    $output .= $input;
    $output .= '<button class="btn btn-primary" type="submit" style="width: 60px; height: 60px; padding: 16px;font-size: 16px;"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1061.92 1061.92"><path d="m484.06 725.65 66.37 66.37 227.88-227.87c18.29-18.3 18.29-48.07 0-66.37L550.44 269.91l-66.37 66.37 147.75 147.75h-509.7c-25.88 0-46.94 21.06-46.94 46.94s21.06 46.94 46.94 46.94h509.69L484.06 725.66Z"/><path d="M939.8 16.77H277.87c-25.88 0-46.94 21.06-46.94 46.94v358.44h93.88v-311.5h568.06v840.62H324.8v-311.5h-93.88v358.44c0 25.88 21.06 46.94 46.94 46.94H939.8c25.88 0 46.94-21.06 46.94-46.94V63.71c0-25.88-21.06-46.94-46.94-46.94"/></svg></button>';
    $output .= '</div>';
} else {
    $output .= $label;
    $output .= $input;
}

Text::script('MOD_FINDER_SEARCH_VALUE');

/** @var Joomla\CMS\WebAsset\WebAssetManager $wa */
$wa = $app->getDocument()->getWebAssetManager();
$wa->getRegistry()->addExtensionRegistryFile('com_finder');

/*
 * This segment of code sets up the autocompleter.
 */
if ($params->get('show_autosuggest', 1)) {
    $wa->usePreset('awesomplete');
    $app->getDocument()->addScriptOptions('finder-search', ['url' => Route::_('index.php?option=com_finder&task=suggestions.suggest&format=json&tmpl=component', false)]);

    Text::script('COM_FINDER_SEARCH_FORM_LIST_LABEL');
    Text::script('JLIB_JS_AJAX_ERROR_OTHER');
    Text::script('JLIB_JS_AJAX_ERROR_PARSE');
}

$wa->useScript('com_finder.finder');

$finderHelper = $app->bootModule('mod_finder', 'site')->getHelper('FinderHelper');

?>

<form class="mod-finder js-finder-searchform form-search" action="<?php echo Route::_($route); ?>" method="get" role="search">
    <?php echo $output; ?>

    <?php $show_advanced = $params->get('show_advanced', 0); ?>
    <?php if ($show_advanced == 2) : ?>
        <br>
        <a href="<?php echo Route::_($route); ?>" class="mod-finder__advanced-link"><?php echo Text::_('COM_FINDER_ADVANCED_SEARCH'); ?></a>
    <?php elseif ($show_advanced == 1) : ?>
        <div class="mod-finder__advanced js-finder-advanced">
            <?php echo HTMLHelper::_('filter.select', $query, $params); ?>
        </div>
    <?php endif; ?>
    <?php echo $finderHelper->getHiddenFields($route); ?>
</form>
