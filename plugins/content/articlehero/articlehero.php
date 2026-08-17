<?php
    /**
     * @package     Joomla.Plugin
     * @subpackage  Content.articlehero
     *
     * @copyright   (C) 2018 Open Source Matters, Inc. <https://www.joomla.org>
     * @license     GNU General Public License version 2 or later; see LICENSE.txt
     */

    defined('_JEXEC') or die;

    use Joomla\CMS\Factory;
    use Joomla\CMS\Plugin\CMSPlugin; 
    use Joomla\CMS\Uri\Uri;
    use Joomla\CMS\Form\Form;
    use Joomla\CMS\Language\Text;

    class PlgContentArticlehero extends CMSPlugin {

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

            $this->images = json_decode($article->images);


        }

        public function onAfterRender() {
            $app = Factory::getApplication();


            if ($app->isClient('administrator')) {
                return true;
            }
        



            // Get Page Header
            $regex1 = "/(<div class=\"page-header\">.*?<\/div>)/is";
            preg_match($regex1, $app->getBody(), $matches1, PREG_OFFSET_CAPTURE);
            $pattern = '#<div[^>]*>\s*(<h1>.*?</h1>)\s*</div>#is';

            if(empty($matches1)) {
                return true;
            }


            $h1 = preg_replace($pattern, '$1', $matches1[0][0]);


            // Get Page Subheading
            $regex2 = "/(<h2 class=\"subheading \">.*?<\/h2>)/is";
            preg_match($regex2, $app->getBody(), $matches2, PREG_OFFSET_CAPTURE);
            $subHeading = $matches2[0][0] ?? '';


            // Get Page Subheading Text
            $regex3 = "/(<p class=\"heading-text \">.*?<\/p>)/is";
            preg_match($regex3, $app->getBody(), $matches3, PREG_OFFSET_CAPTURE);
            $subHeadingText = $matches3[0][0] ?? '';


            // Get Hero Image
            $regex4 = "/(<p class=\"hero-image \">.*?<\/p>)/is";
            preg_match($regex4, $app->getBody(), $matches4, PREG_OFFSET_CAPTURE);
            $pattern2 = '/<p[^>]*>\s*(<img[^>]+>)\s*<\/p>/s';
            $heroImage = $matches4 ? preg_replace($pattern2, '$1', $matches4[0][0]) : '';            


            // Get Button URL
            $regex5 = "/(<p class=\"button-url \">\s*(.*?)\s*<\/p>)/is";
            preg_match($regex5, $app->getBody(), $matches5, PREG_OFFSET_CAPTURE);
            $buttonURL = $matches5 ? preg_replace($regex5, '$2', $matches5[0][0]) : '';          

            // Get Button Text
            $regex6 = "/(<p class=\"button-text \">\s*(.*?)\s*<\/p>)/is";
            preg_match($regex6, $app->getBody(), $matches6, PREG_OFFSET_CAPTURE);
            $buttonText = $matches6 ? preg_replace($regex6, '$2', $matches6[0][0]) : '';  




            // if(!empty($subHeading) && empty($heroImage)) {
            //     $pattern3 = '/(<h2\s+class="subheading\s*)"/i';
            //     $subHeading = preg_replace($pattern3, '$1corner-icon-right"', $subHeading);
            // }


            if(!empty($subHeading) && empty($heroImage)) {
                $pattern3 = '/<h2 class="subheading ">\s*(.*?)\s*<\/h2>/u';
                $subHeading = preg_replace($pattern3, '<h2 class="subheading "><span>$1</span><span class="icon-corner-span"></span></h2>', $subHeading);
            }



            $hero = '<section class="hero"><div class="hero-inner">';


            $hero .=  '<div class="hero-intro">' . $h1 . $subHeading . $subHeadingText . (!empty($buttonURL) && !empty($buttonText) ? '<a href="' . $buttonURL . '" class="btn btn-primary hero-btn">' . $buttonText . '</a>' : '' ) . '</div>';

            if (!empty($heroImage)) {
                $hero .= '<div class="hero-image text-center"><div class="corner-icon-left">' . $heroImage . '</div></div>';
            }


            $hero .= '</div></section>';
         




                
            $body = $app->getBody();

            // Remove original Page Header
            $body = preg_replace( "/(<div class=\"page-header\">.*?<\/div>)/is", '', $body);       

            // Remove original Page Subheading
            $body = preg_replace( "/(<h2 class=\"subheading \">.*?<\/h2>)/is", '', $body);   

            // Remove original Page Heading Text
            $body = preg_replace( "/(<p class=\"heading-text \">.*?<\/p>)/is", '', $body);  

            // Remove original Page Hero Image
            $body = preg_replace( "/(<p class=\"hero-image \">.*?<\/p>)/is", '', $body);             

            // Remove original Button URL
            $body = preg_replace( "/(<p class=\"button-url \">.*?<\/p>)/is", '', $body); 

            // Remove original Button Text
            $body = preg_replace( "/(<p class=\"button-text \">.*?<\/p>)/is", '', $body); 


            // Add hero section
            $find = '</header>';            
            $body = str_ireplace($find, $find . $hero, $body);
     

            // Remove original image
            $body = preg_replace( "/(<figure class=\"hero item-image\">.*?<\/figure>)/is", '', $body);

            // Remove original links
            $body = preg_replace( "/(<div class=\"com-content-article__links content-links\">.*?<\/div>)/is", '', $body);

            // Add class to body tag
            $body = preg_replace( "/(<body id=\"body\" class=\")/is", "<body id=\"body\" class=\"w-hero ", $body);
            $app->setBody($body);


            return true;


        }


    }           