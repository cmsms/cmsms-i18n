<?php

/*
    Class i18N

    This class is the core of the i18N functions

    copyrights: Jean-Christophe Cuvelier - Morris & Chapman 2010
*/

class I18nBase
{
    var $culture;
    var $main_culture;

    var $language;
    var $links;
    var $default_language;
    var $iso_language;
    var $content_id;

    var $translations;

    /*

        This object is loaded at the begining. It should be aware of the current page language and culture.

    */

    private static $languages_iso = array(
        'en' => 'en_GB'
    , 'fr' => 'fr_FR'
    , 'de' => 'de_DE'
    , 'es' => 'es_ES'
    , 'ro' => 'ro_RO'
    , 'it' => 'it_IT'
    , 'nl' => 'nl_NL'
    ); // FIXME: Should be handled differently ! DEPRECATED

    public function __construct($content_id = '', $culture = null)
    {
        if (!empty($content_id)) {
            $this->content_id = $content_id;
        } elseif (!is_null($culture)) {

            if (CultureInfo::validCulture($culture) !== false) {
                $this->setCulture($culture);
                $this->language = substr($culture, 0, 2);
            }
        } else {
            $this->content_id = cms_utils::get_current_pageid();
        }
    }


    public function getLanguage()
    {
        if (!empty($this->language)) {
            return $this->language;
        } else {
            $this->language = self::getLanguageForPage($this->content_id);
            return $this->language;
        }
    }

    public static function getLanguageForPage($content_id)
    {
        // var_dump( $content_id);
        return substr(self::getCultureFromPage($content_id), 0, 2);
    }

    public static function getCultureFromPage($content_id)
    {
        global $gCms;
        $manager =& $gCms->GetHierarchyManager();
        $node =& $manager->getNodeById($content_id);
        if (!isset($node) || $node === FALSE) return false;
        $content =& $node->GetContent();
        $root = self::getRootPage($content);

        if (false !== $root) {
            $culture = $root->Alias();
        } elseif (!empty($this->default_language)) {
            $culture = $this->default_language;
        } else {
            $culture = 'en'; // FIXME: Should be taken from an option
        }

        // Dirty fix for old websites
        if (strlen($culture) == 2) {
            if (array_key_exists($culture, self::$languages_iso)) {
                $culture = self::$languages_iso[$culture];
            }
        }

        if (CultureInfo::validCulture($culture) !== false) {
            return $culture;
        } else {
            return null;
        }
    }

    public static function getRootPage($content)
    {
        if ($content->ParentId() != '-1') {
            $path = explode('.', $content->IdHierarchy());
            if (isset($path[0])) {
                global $gCms;
                $manager =& $gCms->GetHierarchyManager();
                $node =& $manager->getNodeById($path[0]);
                if (!isset($node) || $node === FALSE) return false;
                $content =& $node->GetContent();
                return $content;
            } else {
                return false;
            }
        } else {
            return $content;
        }
    }


    public function getCulture()
    {
        if (empty($this->culture)) {
            $this->culture = self::getCultureFromPage($this->content_id);
        }
        return $this->culture;
    }

    public function getMainCulture()
    {
        if(!isset($this->main_culture))
        {
            $i18n_module = cms_utils::get_module('I18n');
            $this->main_culture = $i18n_module->getPreference('default_culture', 'en_GB');
        }

        return $this->main_culture;
    }

    public function setCulture($culture)
    {
        $this->culture = $culture;
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
        if (empty($this->translations))
            $this->getTranslations();
        if (isset($this->translations[html_entity_decode($key)]))
            return $this->translations[html_entity_decode($key)]->getTarget();
        return null;
    }

    public function getLinks()
    {
        if (empty($this->links)) {
            $links = I18nLink::doSelect(array('where' => array('culture' => $this->getCulture())));
            foreach ($links as $link) {
                $this->links[$link->getSourceAlias()] = $link;
            }
        }
        return $this->translations;
    }

    public function getLink($key)
    {
        if (empty($this->links))
            $this->getLinks();
        if (isset($this->links[html_entity_decode($key)]))
            return $this->links[html_entity_decode($key)]->getTargetAlias();
        return null;
    }

    // REFACTORING ALL ABOVE !!!

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