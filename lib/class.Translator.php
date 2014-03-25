<?php

class Translator
{
    /** @var  Translator */
    private static $_instance;

    private $content_id;
    private $language;
    private $culture;
    private $cultures;

    private $translations;

    private function __construct()
    {
        $this->setContentId();
    }

    /**
     * @return Translator
     */
    public static function get_instance()
    {
        if (!self::$_instance) {
            self::$_instance = new Translator();
        }

        return self::$_instance;
    }

    /**
     * @return I18n
     */
    private function getI18n()
    {
        return cms_utils::get_module('I18n');
    }

    /**
     * @param int $content_id
     */
    public function setContentId($content_id = null)
    {
        if (is_null($content_id)) {
            $this->content_id = cms_utils::get_current_pageid();
        } else {
            $this->content_id = $content_id;
        }
        $this->initCulture();
    }

    private function initCulture()
    {
        if (!empty($this->content_id)) {
            $this->culture = I18nPage::getCulture($this->content_id);
            if (!empty($this->culture)) {
                $this->language = I18nCulture::getLanguage($this->culture);
                $this->translations = I18nTranslation::getTranslations($this->culture);
            }
        }
    }

    public function getCultures()
    {
        if(empty($this->cultures))
        {
            $this->cultures = $this->cultures = I18nPage::getCulturesFromRoots();
        }
        return $this->cultures;
    }

    public function getCulture()
    {
        return $this->culture;
    }

    public function getLanguage()
    {
        return $this->language;
    }

    public function translate($source)
    {
        $translation = $this->getTranslation($source);
        if ($translation) {
            return $translation->getTarget();
        }

        return null;
    }

    public function createTranslation($source, $culture = null)
    {
        if (is_null($culture)) {
            $culture = $this->getCulture();
        }
        $source = html_entity_decode($source);

        $translation = new I18nTranslation();
        $translation->setCulture($culture);
        $translation->setSource($source);
        if ($culture == $this->getI18n()->getPreference('default_culture')) {
            $translation->setTarget($source);
        }
        $translation->save();

        return $translation;
    }

    /**
     * @param $key
     * @return I18nTranslation
     */
    private function getTranslation($key)
    {
        if (isset($this->translations[html_entity_decode($key)])) {
            return $this->translations[html_entity_decode($key)];
        }

        return null;
    }

    // Refactor

    private function languageDetection()
    {
        $detection_method = $this->I18n->GetPreference('detection_method', 'structure');
    }

    // DEPRECATED

    /**
     * @return array
     * @deprecated since 0.9.9
     */
    public static function getAvailableCultures()
    {
        return I18nCulture::getAvailableCultures();
    }

}