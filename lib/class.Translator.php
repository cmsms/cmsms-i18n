<?php

    class Translator
    {
        /** @var  Translator */
        private static $_instance;

        private $content_id;
        private $language;
        private $culture;

        private function __construct()
        {
            $this->content_id = cms_utils::get_current_pageid();
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


        public function setContentId($content_id)
        {
            $this->content_id = $content_id;

        }


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