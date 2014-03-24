<?php

/**
 * Date: 24/03/14
 * Time: 17:43
 * Author: Jean-Christophe Cuvelier <jcc@morris-chapman.com>
 */
class I18nCulture
{

    private static $languages_iso = array(
        'en' => 'en_GB'
    ,
        'fr' => 'fr_FR'
    ,
        'de' => 'de_DE'
    ,
        'es' => 'es_ES'
    ,
        'ro' => 'ro_RO'
    ,
        'it' => 'it_IT'
    ,
        'nl' => 'nl_NL'
    ); // FIXME: Should be handled differently !

    public static function checkCulture($culture)
    {
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

    public static function getLanguage($culture)
    {
        return substr($culture, 0, 2);
    }

    public static function getDefault($default = 'en')
    {
        $i18n = cms_utils::get_module('I18n');

        return $i18n->GetPreference('default_culture', $default);
    }

    public static function getAvailableCultures()
    {
        $cultures = CultureInfo::getCultures();
        $cultures_array = array();
        foreach ($cultures as $culture) {
            $cultures_array[$culture] = $culture;
        }

        return $cultures_array;
    }
} 