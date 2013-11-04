<?php

    class Translator
    {
        /** @var  Translator */
        private static $_instance;

        private $language;
        private $culture;

        private function __construct()
        {

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

        private function languageDetection()
        {
            $i18n             = cms_utils::get_module('I18n');
            $detection_method = $i18n->GetPreference('detection_method', 'structure');
        }

        /**
         * @return array
         */
        public static function getAvailableCultures()
        {
            $cultures       = CultureInfo::getCultures();
            $cultures_array = array();
            foreach ($cultures as $culture) {
                $cultures_array[$culture] = $culture;
            }

            return $cultures_array;
        }

    }