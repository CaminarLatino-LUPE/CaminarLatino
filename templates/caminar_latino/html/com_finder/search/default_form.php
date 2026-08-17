<?php

/**
 * @package     Joomla.Site
 * @subpackage  com_finder
 *
 * @copyright   (C) 2011 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

/** @var \Joomla\Component\Finder\Site\View\Search\HtmlView $this */
/*
* This segment of code sets up the autocompleter.
*/
if ($this->params->get('show_autosuggest', 1)) {
    $this->getDocument()->getWebAssetManager()->usePreset('awesomplete');
    $this->getDocument()->addScriptOptions('finder-search', ['url' => Route::_('index.php?option=com_finder&task=suggestions.suggest&format=json&tmpl=component', false)]);

    Text::script('COM_FINDER_SEARCH_FORM_LIST_LABEL');
    Text::script('JLIB_JS_AJAX_ERROR_OTHER');
    Text::script('JLIB_JS_AJAX_ERROR_PARSE');
}

?>

<form action="<?php echo Route::_($this->query->toUri()); ?>" method="get" class="js-finder-searchform">
    <?php echo $this->getFields(); ?>
    <fieldset class="com-finder__search word mb-3">
        <legend class="com-finder__search-legend visually-hidden">
            <?php echo Text::_('COM_FINDER_SEARCH_FORM_LEGEND'); ?>
        </legend>
        <div class="form-inline">
            <label for="q" class="me-2">
                <?php echo Text::_('COM_FINDER_SEARCH_TERMS'); ?>
            </label>
            <div class="input-group">
                <input type="text" name="q" id="q" class="js-finder-search-query form-control" value="<?php echo $this->escape($this->query->input); ?>">
                <button type="submit" class="btn btn-primary">
                    <span class="" aria-hidden="true"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1061.92 1061.92" class="icon-search" style="vertical-align: baseline;fill: var(--text-color);">
                            <title>Search</title>
                            <path d="M1039.61 968.8 839.02 768.21c31.62-39.13 56.55-82.69 74.15-129.65 19.63-52.36 29.59-107.51 29.59-163.9 0-63.12-12.37-124.36-36.75-182.02-23.55-55.68-57.27-105.69-100.21-148.63S712.85 67.36 657.17 43.8C599.51 19.41 538.27 7.05 475.15 7.05S350.79 19.42 293.13 43.8c-55.68 23.55-105.69 57.27-148.63 100.21S67.85 236.96 44.29 292.64C19.9 350.3 7.54 411.54 7.54 474.66s12.37 124.36 36.75 182.02c23.55 55.68 57.27 105.69 100.21 148.63s92.95 76.66 148.63 100.21c57.66 24.39 118.9 36.75 182.02 36.75 56.15 0 111.06-9.87 163.22-29.32 46.78-17.45 90.21-42.17 129.25-73.53l200.69 200.69c9.52 9.52 22.18 14.77 35.65 14.77s26.13-5.25 35.65-14.77 14.77-22.18 14.77-35.65-5.25-26.13-14.77-35.65Zm-197.7-494.14c0 97.97-38.15 190.07-107.42 259.34S573.12 841.42 475.15 841.42 285.08 803.27 215.81 734 108.39 572.63 108.39 474.66s38.15-190.07 107.42-259.34S377.18 107.9 475.15 107.9s190.07 38.15 259.34 107.42 107.42 161.37 107.42 259.34"></path>
                        </svg></span>
                    <?php echo Text::_('JSEARCH_FILTER_SUBMIT'); ?>
                </button>
                <?php if ($this->params->get('show_advanced', 1)) : ?>
                    <?php HTMLHelper::_('bootstrap.collapse'); ?>
                    <button class="btn btn-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#advancedSearch" aria-expanded="<?php echo ($this->params->get('expand_advanced', 0) ? 'true' : 'false'); ?>">
                        <span class="icon-search-plus" aria-hidden="true"></span>
                        <?php echo Text::_('COM_FINDER_ADVANCED_SEARCH_TOGGLE'); ?></button>
                <?php endif; ?>
            </div>
        </div>
    </fieldset>

    <?php if ($this->params->get('show_advanced', 1)) : ?>
        <fieldset id="advancedSearch" class="com-finder__advanced js-finder-advanced collapse<?php if ($this->params->get('expand_advanced', 0)) {
            echo ' show';
                                                                                             } ?>">
            <legend class="com-finder__search-advanced visually-hidden">
                <?php echo Text::_('COM_FINDER_SEARCH_ADVANCED_LEGEND'); ?>
            </legend>
            <?php if ($this->params->get('show_advanced_tips', 1)) : ?>
                <div class="com-finder__tips card card-outline-secondary mb-3">
                    <div class="card-body">
                        <?php echo Text::_('COM_FINDER_ADVANCED_TIPS_INTRO'); ?>
                        <?php echo Text::_('COM_FINDER_ADVANCED_TIPS_AND'); ?>
                        <?php echo Text::_('COM_FINDER_ADVANCED_TIPS_NOT'); ?>
                        <?php echo Text::_('COM_FINDER_ADVANCED_TIPS_OR'); ?>
                        <?php if ($this->params->get('tuplecount', 1) > 1) : ?>
                            <?php echo Text::_('COM_FINDER_ADVANCED_TIPS_PHRASE'); ?>
                        <?php endif; ?>
                        <?php echo Text::_('COM_FINDER_ADVANCED_TIPS_OUTRO'); ?>
                    </div>
                </div>
            <?php endif; ?>
            <div id="finder-filter-window" class="com-finder__filter">
                <?php echo HTMLHelper::_('filter.select', $this->query, $this->params); ?>
            </div>
        </fieldset>
    <?php endif; ?>
</form>

<style type="text/css">
.com-finder * {
    vertical-align: baseline;
}
</style>
