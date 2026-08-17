<?php

/**
 * @package     Joomla.Site
 * @subpackage  Layout
 *
 * @copyright   (C) 2014 Open Source Matters, Inc. <https://www.joomla.org>
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;

$params    = $displayData['params'];
$item      = $displayData['item'];
$direction = Factory::getLanguage()->isRtl() ? 'left' : 'right';
?>

<div class="readmore">
    <?php if (!$params->get('access-view')) : ?>
        <a class="btn btn-secondary" href="<?php echo $displayData['link']; ?>" aria-label="<?php echo Text::_('JGLOBAL_REGISTER_TO_READ_MORE') . ' ' . $this->escape($item->title); ?>">
            <?php echo '<span class="icon-chevron-' . $direction . '" aria-hidden="true"></span>'; ?>
            <?php echo Text::_('JGLOBAL_REGISTER_TO_READ_MORE'); ?>
        </a>
    <?php elseif ($readmore = $item->alternative_readmore) : ?>
        <a class="btn btn-secondary" href="<?php echo $displayData['link']; ?>" aria-label="<?php echo $this->escape($readmore . ' ' . $item->title); ?>">
            <?php echo '<span class="icon-chevron-' . $direction . '" aria-hidden="true"></span>'; ?>
            <?php echo $readmore; ?>
            <?php if ($params->get('show_readmore_title', 0) != 0) : ?>
                <?php echo HTMLHelper::_('string.truncate', $item->title, $params->get('readmore_limit')); ?>
            <?php endif; ?>
        </a>
    <?php elseif ($params->get('show_readmore_title', 0) == 0) : ?>
        <a class="btn btn-secondary" href="<?php echo $displayData['link']; ?>" aria-label="<?php echo Text::sprintf('JGLOBAL_READ_MORE_TITLE', $this->escape($item->title)); ?>">
            <?php echo '<span class="icon-chevron-' . $direction . '" aria-hidden="true"></span>'; ?>
            <?php echo Text::_('JGLOBAL_READ_MORE'); ?>
        </a>
    <?php else : ?>
        <a class="" href="<?php echo $displayData['link']; ?>" aria-label="<?php echo Text::sprintf('TPL_CLARAVOZ_LEARN_MORE', $this->escape($item->title)); ?>">
            <?php echo '<i aria-hidden="true" class="fas fa-long-arrow-alt-' . $direction . '"></i>'; ?>
            <?php echo Text::sprintf('TPL_CLARAVOZ_LEARN_MORE', HTMLHelper::_('string.truncate', $item->title, $params->get('readmore_limit'))); ?>
        </a>
    <?php endif; ?>
</div>


