<?php

class Translator
{
    /** @var  Translator */
    private static $_instance;

    private $content_id;
    private $language;
    private $culture;

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