<?php
    /**
     * @package     Joomla.Site
     * @subpackage  Templates.caminar_latino
     *
     * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
     * @license     GNU General Public License version 2 or later; see LICENSE.txt
     */

    defined('_JEXEC') or die;

    use Joomla\CMS\Factory;
    use Joomla\CMS\HTML\HTMLHelper;
    use Joomla\CMS\Language\Text;
    use Joomla\CMS\Uri\Uri;


    /** @var JDocumentError $this */

    if (!isset($this->error)) {
        $this->error = JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
        $this->debug = false;
    }

    $app = Factory::getApplication();
    $config = Factory::getConfig();

    // get rid of the stupid generator line
    $this->setGenerator('');

    // get the error code
    $errCode = $this->error->getCode();
    $errNotice = '';

    switch($errCode){
        case 400:
            $errNotice =  'Bad Request (' . $errCode . ')';
            break;
        case 401:
            $errNotice =  'Unauthorized (' . $errCode . ')';
            break;
        case 403:
            $errNotice =  'Forbidden (' . $errCode . ')';
            break;
        case 404:
            $errNotice =  'Page Not Found (' . $errCode . ')';
            break;
        case 500:
            $errNotice =  'Internal Server Error (' . $errCode . ')';
            break;
        case 503:
            $errNotice =  'Service Unavailable (' . $errCode . ')';
            break;
        case 550:
            $errNotice =  'Permission Denied (' . $errCode . ')';
            break;
        default:
            $errNotice =  'Unknown Error (' . $errCode . ')';
            break;
    }

    ?>
    <!DOCTYPE html>
    <html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
        <head>
            <meta charset="utf-8" />
            <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
            <meta name="HandheldFriendly" content="true" />
            <meta name="MobileOptimized" content="width" />
            <meta name="apple-mobile-web-app-capable" content="YES" />
            <meta name="referrer" content="unsafe-url" />   
            <title><?php echo $config->get( 'sitename' ); ?></title>
            <link href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/stylesheets/css/styles.min.css" rel="stylesheet" />
            <?php if ($app->get('debug_lang', '0') == '1' || $app->get('debug', '0') == '1') : ?>
                <link href="<?php echo JUri::root(true); ?>/media/cms/css/debug.css" rel="stylesheet" />
            <?php endif; ?>
            <!--[if lt IE 9]><script src="<?php echo JUri::root(true); ?>/media/jui/js/html5.js"></script><![endif]-->
        </head>
        <body class="page-error">

            <!--Skip Links-->
            <a href="#main" class="v-hidden focusable">
                <?php echo Text::_('TPL_CAMINAR_LATINO_SKIP_TO_CONTENT'); ?>
            </a>

            <a href="#nav" class="v-hidden focusable">
                <?php echo Text::_('TPL_CAMINAR_LATINO_JUMP_TO_NAV'); ?>
            </a>



            <div id="main" class="container">
                    <div id="error">
                        <section class="error-heading">
                            <div class="page-header">
                                <h1><?php echo $errNotice; ?></h1>
                            </div>

                        </section>
                        <section class="error-body">
                            <p>
                                <?php echo Text::_('JERROR_LAYOUT_NOT_ABLE_TO_VISIT'); ?>
                                <b><?php echo htmlspecialchars($this->error->getMessage(), ENT_QUOTES, 'UTF-8'); ?></b>
                            </p>
                            

                            <p><?php echo Text::_('JERROR_LAYOUT_PLEASE_CONTACT_THE_SYSTEM_ADMINISTRATOR'); ?></p>
                            <div class="text-center">
                                <a href="<?php echo \JUri::root(true); ?>/index.php" title="<?php echo Text::_('JERROR_LAYOUT_GO_TO_THE_HOME_PAGE'); ?>" class="btn btn-primary"><?php echo Text::_('JERROR_LAYOUT_HOME_PAGE'); ?></a>
                            </div>
                        </section>


                    <div class="error-code">
                    <?php if ($this->debug) : ?>
                        <div>
                            <?php echo $this->renderBacktrace(); ?>
                            <?php // Check if there are more Exceptions and render their data as well ?>
                            <?php if ($this->error->getPrevious()) : ?>
                                <?php $loop = true; ?>
                                <?php // Reference $this->_error here and in the loop as setError() assigns errors to this property and we need this for the backtrace to work correctly ?>
                                <?php // Make the first assignment to setError() outside the loop so the loop does not skip Exceptions ?>
                                <?php $this->setError($this->_error->getPrevious()); ?>
                                <?php while ($loop === true) : ?>
                                    <p><strong><?php echo Text::_('JERROR_LAYOUT_PREVIOUS_ERROR'); ?></strong></p>
                                    <p><?php echo htmlspecialchars($this->_error->getMessage(), ENT_QUOTES, 'UTF-8'); ?></p>
                                    <?php echo $this->renderBacktrace(); ?>
                                    <?php $loop = $this->setError($this->_error->getPrevious()); ?>
                                <?php endwhile; ?>
                                <?php // Reset the main error object to the base error ?>
                                <?php $this->setError($this->error); ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    </div>
                    </div>


            </div>
            <footer class="bkgd-blue">
                <div class="container">
                <p>
        The production of this website was supported by Grant 90EV0531 from the Department of Health and Human Services, Administration for Children and Families. Its contents are solely the responsibility of Caminar Latino-Latinos United for Peace and Equity and do not necessarily represent the official views of the Department of Health and Human Services, Administration for Children and Families.
                </p>
                </div>
            </footer>
            <jdoc:include type="modules" name="debug" style="none" />
        </body>
    </html>
