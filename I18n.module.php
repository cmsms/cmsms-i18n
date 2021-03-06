<?php
#-------------------------------------------------------------------------
# Module: I18n - This module helps to manage internationalized for translations, language selection, flags, etc.
# Version: 0.0.1, Jean-Christophe Cuvelier
#
#-------------------------------------------------------------------------
# CMS - CMS Made Simple is (c) 2010 by Ted Kulp (wishy@cmsmadesimple.org)
# This project's homepage is: http://www.cmsmadesimple.org
#
# This file originally created by ModuleMaker module, version 0.3.1
# Copyright (c) 2010 by Samuel Goldstein (sjg@cmsmadesimple.org) 
#
#-------------------------------------------------------------------------
#
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or
# (at your option) any later version.
#
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
# You should have received a copy of the GNU General Public License
# along with this program; if not, write to the Free Software
# Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
# Or read it online: http://www.gnu.org/licenses/licenses.html#GPL
#
#-------------------------------------------------------------------------

#-------------------------------------------------------------------------
# For Help building modules:
# - Read the Documentation as it becomes available at
#   http://dev.cmsmadesimple.org/
# - Check out the Skeleton Module for a commented example
# - Look at other modules, and learn from the source
# - Check out the forums at http://forums.cmsmadesimple.org
# - Chat with developers on the #cms IRC channel
#-------------------------------------------------------------------------

require_once('lib/prado/I18N/core/CultureInfo.php');

class I18n extends CMSModule
{

    function GetName()
    {
        return 'I18n';
    }

    function GetFriendlyName()
    {
        return $this->Lang('friendlyname');
    }

    function GetVersion()
    {
        return '0.9.9';
    }

    function GetHelp()
    {
        return $this->Lang('help');
    }

    function GetAuthor()
    {
        return 'Jean-Christophe Cuvelier';
    }

    function GetAuthorEmail()
    {
        return 'jcc@atomseeds.com';
    }

    function GetChangeLog()
    {
        return $this->Lang('changelog');
    }

    function IsPluginModule()
    {
        return true;
    }

    function HasAdmin()
    {
        return true;
    }

    function GetAdminSection()
    {
        return 'extensions';
    }

    function GetAdminDescription()
    {
        return $this->Lang('admindescription');
    }

    function VisibleToAdminUser()
    {
        return $this->CheckAccess();
    }

    function CheckAccess()
    {
        return $this->CheckPermission('Manage I18n');
    }

    function DisplayErrorPage($id, &$params, $return_id, $message = '')
    {
        $this->smarty->assign('title_error', $this->Lang('error'));
        $this->smarty->assign_by_ref('message', $message);

        // Display the populated template
        echo $this->ProcessTemplate('error.tpl');
    }

    function GetDependencies()
    {
        return array();
    }

    function MinimumCMSVersion()
    {
        return "1.9";
    }

    function InstallPostMessage()
    {
        return $this->Lang('postinstall');
    }

    function UninstallPostMessage()
    {
        return $this->Lang('postuninstall');
    }

    function UninstallPreMessage()
    {
        return $this->Lang('really_uninstall');
    }

    function InitializeFrontend()
    {
        $this->RegisterModulePlugin();

        $this->smarty->register_function('I18n_link', array('I18n', 'executeLinkAction'));
        $this->smarty->register_function('I18n_init', array('I18n', 'I18nInit', false));
        $this->smarty->register_block('I18nTranslate', array('I18n', 'I18nTranslate'));
        $this->smarty->register_block('__', array('I18n', 'I18nTranslate'));

        // $this->InitVars();
    }

    public static function I18nInit($params, Smarty_Internal_Template &$smarty)
    {
        $translator = Translator::get_instance();
        $smarty->assign('i18n', $translator);
        $smarty->assign('culture', $translator->getCulture());
        $smarty->assign('language', $translator->getLanguage());
        $smarty->assign('lg', $translator->getLanguage());
    }

    public static function availableCultures()
    {
        return I18nPage::getCulturesFromRoots();
    }

    public static function availableLanguages()
    {
        return I18nPage::getLanguagesFromRoots();
    }

    public static function executeLinkAction($params, Smarty_Internal_Template &$smarty)
    {
        global $id;
        global $returnid;
        $module = cms_utils::get_module('I18n');
        $module->DoAction('link', $id, $params, $returnid);
    }

    public static function I18nTranslate($params, $content, $template, &$repeat)
    {
        $elements = array();

        foreach ($params as $key => $param) {
            if ((strpos($key, '__') !== false) && (strpos($key, '__') === 0)) {
                // $nkey = str_replace('__', '', $key);
                $elements[$key] = $param;
            }
        }

        return self::__($content, $elements);
    }

    public static function __($content, $elements = array())
    {
        /** @var I18n $i18n */
        $i18n =& cms_utils::get_module('I18n');

        return $i18n->Translate($content, $elements);
    }

    public function Translate($content, $elements = array())
    {
        if ($content != '') {

            $translator = Translator::get_instance();
            $translation = $translator->translate($content);

            if (empty($translation) && ($this->getPreference('harvest') == 'true')) {

                if ($translator->getCulture() != '') {
                    $translation = $translator->createTranslation($content)->getTarget();
                }
                else
                {
                    $translation = html_entity_decode($content);
                }
            }

            foreach ($elements as $key => $element) {
                $translation = str_replace($key, $element, $translation);
            }

            return $translation;
        }

        return null;
    }

    public function redirectTo($alias)
    {
        $manager = cmsms()->GetHierarchyManager();
        $node =& $manager->sureGetNodeByAlias($alias);
        if (!isset($node)) {
            return false;
        }
        $content =& $node->GetContent();

        if ($content !== false && is_object($content)) {
            $pageid = $content->Id();
            $alias = $content->Alias();
            $url = $content->GetUrl();

            $currentid = cms_utils::get_current_pageid();

            if ($pageid != $currentid) {
                header('Location: ' . $url);

                return true;
            }
        }
    }

    // DEPRECATED

    /**
     * @param $text
     * @param string $lang
     * @param null $target_lang
     * @return null|string
     * @deprecated since 0.9.9
     */
    public function getTranslation($text, $lang = 'en', $target_lang = null)
    {
        $translator = Translator::get_instance();
        $translation = $translator->translate($text);

        if (empty($translation) && ($this->getPreference('harvest') == 'true')) {
            if ($translator->getCulture() != '') {
                $translation = $translator->createTranslation($text)->getTarget();
            } else {
                $translation = html_entity_decode($text);
            }
        }

        return $translation;
    }

    /**
     * @return I18nBase
     * @deprecated since 0.9.9
     */
    public static function getTranslator()
    {
        global $i18n;
        if (!isset($i18n)) {
            $i18n = new I18nBase();
        }

        return $i18n;
    }

    /**
     * @param $text
     * @param $culture
     * @param null $target_lang
     * @return mixed
     * @deprecated since 0.9.9
     */
    public function createTranslation($text, $culture, $target_lang = null)
    {
        $translator = Translator::get_instance();
        $translation = $translator->createTranslation($text, $culture);

        return $translation->getTarget();
    }

    public static function getTranslations($array, $lang = 'en', $target_lang = null)
    {
        $translations = array();

        $module = cms_utils::get_module('I18n');

        foreach ($array as $key => $translation) {
            $translations[$key] = $module->getTranslation($translation, $lang, $target_lang);
        }

        return $translations;
    }


}

?>
