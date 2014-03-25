<?php

/*
    Class i18N

    This class is the core of the i18N functions

    copyrights: Jean-Christophe Cuvelier - Morris & Chapman 2010
*/

/**
 * Class I18nBase
 * @deprecated use Translator
 */
class I18nBase
{
    var $content_id;
    var $culture;



    var $main_culture;

    var $language;
    var $links;
    var $default_language;
    var $iso_language;


    var $translations;

    /*

        This object is loaded at the beginning. It should be aware of the current page language and culture.

    */

    public function __construct($content_id = null, $culture = null)
    {
        if(is_null($content_id))
        {
            $this->content_id = cms_utils::get_current_pageid();
        }
        else
        {
            $this->content_id = $content_id;
        }

        if(!is_null($culture))
        {
            if (CultureInfo::validCulture($culture) !== false) {
                $this->setCulture($culture);
                $this->language = $this->getLanguage();
            }
        }
    }

    public function getLanguage()
    {
        if (empty($this->language)) {
            $this->language = I18nPage::getLanguage($this->content_id);
        }

        return $this->language;
    }

    public function getCulture()
    {
        if (empty($this->culture)) {
            $this->culture = I18nPage::getCulture($this->content_id);
        }

        return $this->culture;
    }

    public function getMainCulture()
    {
        if (empty($this->main_culture)) {
            $this->main_culture = I18nCulture::getDefault('en_GB');
        }

        return $this->main_culture;
    }


    public function cultureFromLanguage()
    {
        if (CultureInfo::validCulture($this->getLanguage()) !== false) {
            return $this->getLanguage();
        } else {
            return false;
        }

        /*if (array_key_exists($this->getLanguage(), self::$languages_iso))
        {
            return self::$languages_iso[$this->language];
        }
        else
        {
            return null;
        }*/
    }

    public function getTranslations()
    {
        if (empty($this->translations)) {
            // TODO: Handle cache
            $translations = I18nTranslation::doSelect(array('where' => array('culture' => $this->getCulture())));
            foreach ($translations as $translation) {
                $this->translations[$translation->getSource()] = $translation;
            }
        }

        return $this->translations;
    }

    public function getTranslation($key)
    {
        $translator = Translator::get_instance();
        $translator->setContentId($this->content_id);

        return $translator->translate($key);
    }

    public function getLinks()
    {
        if (empty($this->links)) {
            $links = I18nLink::doSelect(array('where' => array('culture' => $this->getCulture())));
            foreach ($links as $link) {
                $this->links[$link->getSourceAlias()] = $link;
            }
        }

        return $this->links;
    }

    public function getLink($key)
    {
        if (empty($this->links)) {
            $this->getLinks();
        }
        if (isset($this->links[html_entity_decode($key)])) {
            return $this->links[html_entity_decode($key)]->getTargetAlias();
        }

        return null;
    }

    // REFACTORING ALL ABOVE !!!


    public function setCulture($culture)
    {
        $this->culture = $culture;
    }

    // DEPRECATED

    /**
     * @param $content_id
     * @return string
     * @deprecated
     */
    public static function getLanguageForPage($content_id)
    {
        return I18nPage::getLanguage($content_id);
    }

    /**
     * @param $content_id
     * @return null
     * @deprecated
     */
    public static function getCultureFromPage($content_id)
    {
        return I18nPage::getCulture($content_id);
    }

    /**
     * @param ContentBase $content
     * @deprecated since 0.9.9 Use I18nPage class
     * @return bool|ContentBase
     */
    public static function getRootPage(ContentBase $content)
    {
        return I18nPage::getRootPage($content);
    }


    /*
        public function setCultureFromLanguage($language)
        {
            // TODO: Implement that better !!!

            $language = strtolower($language);

            switch($language)
            {
                case 'en':
                    $this->culture = 'en_US';
                    break;
                case 'fr':
                    $this->culture = 'fr_FR';
                    break;
                case 'es':
                    $this->culture = 'es_ES';
                    break;
                case 'de':
                    $this->culture = 'de_DE';
                    break;
                case 'nl':
                    $this->culture = 'nl_NL';
                    break;
                default:
                    // UNABLE TO FIND CULTURE = ENGLISH. THIS IS A VERY BAD BEHAVIOR !!! FIXME
                    $this->culture = 'en_US';
                    break;
            }
        }

        // PAGES PROPERTIES

        public function getPageLanguage($content_id)
        {
            global $gCms;
            $manager =& $gCms->GetHierarchyManager();
            $node =& $manager->getNodeById($content_id);
            if( !isset($node) || $node === FALSE ) return false;
            $content =& $node->GetContent();

            if ($content->HasProperty('language'))
            {
                return $content->GetPropertyValue('language');
            }
            else
            {
                $language = self::checkLanguage(self::findPageLanguage($content));
//				var_dump($language);
                $content->SetPropertyValue('language', $language);

                var_dump( $content->GetPropertyValue('language'));

                $content->Save();

            //	var_dump($content->ParentId());
                return 'No language found for ';
            }
        }

    */


}