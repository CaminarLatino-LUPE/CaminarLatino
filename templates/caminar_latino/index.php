<?php

    /*
    -----------------------------------------
    CAMINAR_LATINO Main Theme
    -----------------------------------------
    Site:      centertoadvancepeace.org
    Email:     info@centertoadvancepeace.org
    @license:  Copyrighted Commercial Software
    @copyright (C) 2022 Caminar Latino/LUPE

     */

    defined( '_JEXEC' ) or die( 'Restricted access' );

    use Joomla\CMS\Factory;
    use Joomla\CMS\HTML\HTMLHelper;
    use Joomla\CMS\Language\Text;
    use Joomla\CMS\Uri\Uri;
    use Joomla\CMS\Router\Route;

    // Remove Generator Line
    $this->setGenerator('');

    // Set Base URL
    $this->baseurl = Uri::base();

    // Set Template URL
    $templateUrl = $this->baseurl .'templates/'. $this->template; 
    
    $app = Factory::getApplication();
    $input = $app->getInput();
    $wa    = $this->getWebAssetManager();


    // determine if this is the home page
    $isHome = false;    
    $menu = $app->getMenu();
    $activeMenu = $menu->getActive();
    if($activeMenu == $menu->getDefault('en-GB') || $activeMenu == $menu->getDefault('es-ES')){
        $isHome = true;
    };

    // Detecting Active Variables
    $option   = $input->getCmd('option', '');
    $view     = $input->getCmd('view', '');
    $layout   = $input->getCmd('layout', '');
    $task     = $input->getCmd('task', '');
    $itemid   = $input->getCmd('Itemid', '');
    $menu     = $app->getMenu()->getActive();
    $siteName = htmlspecialchars($app->get('sitename'), ENT_QUOTES, 'UTF-8');
    $pageClass = $activeMenu !== null ? $activeMenu->getParams()->get('pageclass_sfx', '') : '';
    $siteLogo = $this->params->get('logo_svg', '');
    $gtmNumber = $this->params->get('gtm_number', '');


    // Output as HTML5
    $this->setHtml5(true);

    // positions
    $showContentBottom = $this->countModules('content-bottom');
    $showHero = $this->countModules('hero');    
    $showAside = $this->countModules('aside');
    $showBanner = $this->countModules('banner'); 

    $this->setMetaData('viewport', 'width=device-width, initial-scale=1');


?>
<!DOCTYPE html>
<html lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">

    <head>
        <?php if($gtmNumber): ?>
            <!-- Google Tag Manager -->
            <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','<?php echo $gtmNumber; ?>');</script>
            <!-- End Google Tag Manager -->
        <?php endif; ?>    
        <jdoc:include type="metas" />
        <jdoc:include type="styles" />
        <jdoc:include type="scripts" />

            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;600;700&family=Mona+Sans:ital,wght@0,200..900;1,200..900&display=swap" rel="stylesheet">

            <link rel="stylesheet" href="<?php echo $templateUrl ?>/stylesheets/css/styles.min.css" type="text/css" />


            <script src="<?php echo $templateUrl ?>/js/build/vendors.min.js"></script>

            <link rel="apple-touch-icon" sizes="180x180" href="<?php echo $templateUrl ?>/images/favicon/apple-touch-icon.png">
            <link rel="icon" type="image/png" sizes="96x96" href="<?php echo $templateUrl ?>/images/favicon/favicon-96x96.png">
            <link rel="icon" type="image/png" sizes="32x32" href="<?php echo $templateUrl ?>/images/favicon/favicon-32x32.png">
            <link rel="icon" type="image/png" sizes="16x16" href="<?php echo $templateUrl ?>/images/favicon/favicon-16x16.png">
            <link rel="icon" type="image/svg+xml" href="<?php echo $templateUrl ?>/images/favicon/favicon.svg" />
            <link rel="manifest" href="<?php echo $templateUrl ?>/images/favicon/site.webmanifest">
            <meta name="apple-mobile-web-app-title" content="Caminar Latino">
            <meta name="application-name" content="Caminar Latino">
            <meta name="msapplication-TileColor" content="#093493">
            <meta name="theme-color" content="#093493">

            
            <base href="/" />

    </head>

    <body id="body" class="site <?php echo ($isHome ? 'home': '') . ($pageClass ? ' ' . $pageClass : ''); ?>">
        <?php if($gtmNumber): ?>
            <!-- Google Tag Manager (noscript) -->
            <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo $gtmNumber; ?>"
            height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
            <!-- End Google Tag Manager (noscript) -->
        <?php endif; ?>    

        <!--Skip Links-->
        <a href="#main" class="v-hidden focusable">
            <?php echo JText::_('TPL_CAMINAR_LATINO_SKIP_TO_CONTENT'); ?>
        </a>

        <a href="#main-nav" class="v-hidden focusable">
            <?php echo JText::_('TPL_CAMINAR_LATINO_JUMP_TO_NAV'); ?>
        </a>

        <!-- Banner -->
        <?php if($showBanner): ?>
            <div>
                <jdoc:include type="modules" name="banner" />
            </div>
        <?php endif; ?>
        <!-- Header -->
        <header>
            <div class="container">
                    <a class="site-branding" href="/" title="Go to Home Page">
                        <?php                           
                            include 'logo_file.php';                            
                        ?>
                    </a>                
                <div class="header-inner">


                    <!--Nav-->      
                    <nav id="main-nav" class="main-nav">
                        <!-- <button type="button" class="close" tabindex="0"></button> -->
                        <jdoc:include type="modules" name="main-nav" />
                    </nav>  
                    <button type="button" class="navbar-toggle" aria-controls="main-nav" aria-expanded="false" >
                        <svg viewBox="0 0 100 80" width="34" height="34">
                            <rect width="100" height="10"></rect>
                            <rect y="30" width="100" height="10"></rect>
                            <rect y="60" width="100" height="10"></rect>
                        </svg>
                    </button>


                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1061.92 1061.92" class="icon-search dropdown-button" aria-controls="search-field">
                            <title>Search</title>
                            <path d="M1039.61 968.8 839.02 768.21c31.62-39.13 56.55-82.69 74.15-129.65 19.63-52.36 29.59-107.51 29.59-163.9 0-63.12-12.37-124.36-36.75-182.02-23.55-55.68-57.27-105.69-100.21-148.63S712.85 67.36 657.17 43.8C599.51 19.41 538.27 7.05 475.15 7.05S350.79 19.42 293.13 43.8c-55.68 23.55-105.69 57.27-148.63 100.21S67.85 236.96 44.29 292.64C19.9 350.3 7.54 411.54 7.54 474.66s12.37 124.36 36.75 182.02c23.55 55.68 57.27 105.69 100.21 148.63s92.95 76.66 148.63 100.21c57.66 24.39 118.9 36.75 182.02 36.75 56.15 0 111.06-9.87 163.22-29.32 46.78-17.45 90.21-42.17 129.25-73.53l200.69 200.69c9.52 9.52 22.18 14.77 35.65 14.77s26.13-5.25 35.65-14.77 14.77-22.18 14.77-35.65-5.25-26.13-14.77-35.65Zm-197.7-494.14c0 97.97-38.15 190.07-107.42 259.34S573.12 841.42 475.15 841.42 285.08 803.27 215.81 734 108.39 572.63 108.39 474.66s38.15-190.07 107.42-259.34S377.18 107.9 475.15 107.9s190.07 38.15 259.34 107.42 107.42 161.37 107.42 259.34"/>
                        </svg>



                    <div id="search-field">
                        <jdoc:include type="modules" name="header-right" />
                    </div>

                    <button type="button" class="btn btn-primary btn-donate" >
                        <?php if($this->language === 'en-gb') : ?>
                            <a href="/donate">Donate</a>
                        <?php else: ?>
                            <a href="/donar">Donar</a>
                        <?php endif; ?>
                    </button>                    
                </div>
            </div>
        </header>

        <!-- Hero -->
        <?php if($showHero): ?>
            <section class="hero" >
                <jdoc:include type="modules" name="hero" style="html5" />
            </section>
        <?php endif; ?>  
        
        <!-- Alerts -->
        <jdoc:include type="message" />    

        <!-- Breadcrumbs -->
        <jdoc:include type="modules" name="breadcrumbs" />

        <!--Content-->
        <div class="container <?php echo $showAside ? ' flex-wrapper' : ''; ?>">
            <main id="main" class="<?php echo $showAside ? 'main-with-aside' : ''; ?>">
                <jdoc:include type="component" />
            </main>
            <?php if($showAside): ?>
                <aside class="round-corners">
                    <jdoc:include type="modules" name="aside" />
                </aside>
            <?php endif; ?>
        </div>

        <!-- Bottom Section -->
        <?php if($showContentBottom): ?>
        <div class="content-bottom clearfix">
            <jdoc:include type="modules" name="content-bottom" style="html5" />
        </div>
        <?php endif; ?>

        <!--Footer-->
        <footer>
            <div class="footer-top">
                <div class="footer-1">
                    <jdoc:include type="modules" name="footer-1" style="html5" />
                </div>
                <div class="footer-2">
                    <jdoc:include type="modules" name="footer-2" style="html5" />             
                </div>            
            </div>     
            <div class="footer-bottom">
                <div class="footer-3">
                    <jdoc:include type="modules" name="footer-3" style="html5" />
                </div>         
            </div>  
            <div style="margin: 0 0 0 -4.5%; font-size: 13.5vw; font-weight: 800; line-height: .8em; white-space: nowrap; color: transparent; -webkit-text-stroke: 3px var(--color-blue);">Caminar Latino</div>                             
        </footer>
        
        <!-- Escape button -->
        <button type="button" class="btn btn-green btn-escape" aria-label="Escape Site">Safe Exit</button>


        <!-- Modal -->
        <div class="modal boxed" tabindex="-1" role="dialog" data-modal="main">
            <button type="button" class="close close-modal"></button>     
            <div id="modal-message-container"></div>
            <div class="modal-inner">
                <jdoc:include type="modules" name="modal" />
            </div>  
        </div>


        <script src="<?php echo $templateUrl ?>/js/build/main.min.js"></script>                

        <jdoc:include type="modules" name="debug" style="none" />
    </body>

</html>
