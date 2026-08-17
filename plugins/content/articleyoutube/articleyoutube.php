<?php
    /**
     * @package     Joomla.Plugin
     * @subpackage  Content.articleyoutube
     *
     * @copyright   (C) 2018 Open Source Matters, Inc. <https://www.joomla.org>
     * @license     GNU General Public License version 2 or later; see LICENSE.txt
     */

    defined('_JEXEC') or die;

    use Joomla\CMS\Factory;
    use Joomla\CMS\Plugin\CMSPlugin; 
    use Joomla\CMS\Plugin\PluginHelper; 
    use Joomla\CMS\Uri\Uri;
    use Joomla\CMS\Form\Form;
    use Joomla\CMS\Language\Text;

    class PlgContentArticleyoutube extends CMSPlugin {

        private $images;



        /**
         * Extracts images from Article item
         * @param  string  $context  The context of the content being passed to the plugin
         * @param  mixed  &$article  A reference to the article that is being rendered
         * @param  mixed  &$params   Additional parameters
         * @param  integer $page     Optional page number. Unused. Defaults to zero
         * @return boolean           True on success
         */
        public function onContentPrepare($context, &$article, &$params, $page = 0) {
            // Only run when the content is from an Article
            if ($context !== 'com_content.article') {
                return true;
            }

            $this->renderAllVideos($article, $params);


        }


        function renderAllVideos(&$article, $params) {

            // API
            jimport('joomla.filesystem.file');
            $app = Factory::getApplication();
            $document  = Factory::getDocument();

            $sitePath = JPATH_SITE;
            $siteUrl  = URI::root(true);


            // Check if plugin is enabled
            if (PluginHelper::isEnabled('content',$this->_name)==false) return;

            // Load the language file for the plugin
            $this->loadLanguage();
            
            $regex = "#{youtube}.*?{/youtube}#is";

            if (preg_match($regex, $article->text)==false) return;


            // if ($app->input->getCmd('format')=='html' || $app->input->getCmd('format')=='') {

            //     // CSS
            //     $document->addStyleSheet(JURI::base(). "plugins/content/articleyoutube/css/articleyoutube_styles.css");
            //     // JS
            //     $document->addScript(JURI::base(). "plugins/content/articleyoutube/scripts/articleyoutube_script.js");
            // }           

            // Process Tags
            if (preg_match_all($regex, $article->text, $matches, PREG_PATTERN_ORDER)) {

                foreach ($matches[0] as $key => $match) {
                    $tagcontent = preg_replace("/{.+?}/", "", $match);
                    $tagcontent = str_replace(array('"','\'','`'), array('&quot;','&apos;','&#x60;'), $tagcontent);
                    $tagcontent = trim(strip_tags($tagcontent));

                    $videoID = substr($tagcontent, strrpos($tagcontent, '/') + 1);

                    ob_start();                     
                    include($siteUrl."plugins/content/articleyoutube/tmpl/default.php");
                    $getTemplate = ob_get_contents();
                    ob_end_clean();

                    // Output
                    $article->text = preg_replace("#{youtube}".preg_quote($tagcontent)."{/youtube}#is", $getTemplate , $article->text);

                }
            }

        }



    }           