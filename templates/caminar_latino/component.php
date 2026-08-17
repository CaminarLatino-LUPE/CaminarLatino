<?php
    /**
     * @package     Joomla.Site
     * @subpackage  Template.system
     *
     * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
     * @license     GNU General Public License version 2 or later; see LICENSE.txt
     */

    defined('_JEXEC') or die;

    use Joomla\CMS\Factory;
    use Joomla\CMS\HTML\HTMLHelper;

    /** @var Joomla\CMS\Document\HtmlDocument $this */
?>

<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
    <head>
        <jdoc:include type="metas" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <jdoc:include type="styles" />
        <jdoc:include type="scripts" />
    </head>
    <body class="<?php echo $this->direction === 'rtl' ? 'rtl' : ''; ?>">
        <jdoc:include type="message" />
        <jdoc:include type="component" />
    </body>
</html>    