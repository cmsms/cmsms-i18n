<?php
if (!isset($gCms)) exit;
global $i18n;
if (!isset($i18n)) $i18n = new I18nBase();
//$locale = new Zend_Locale($i18n->getLanguage());
$this->smarty->assign('i18n',$i18n);

$this->smarty->assign('language',$i18n->getLanguage());
$this->smarty->assign('culture', $i18n->getCulture());

$this->smarty->assign('default_language',(isset($params['default']))? $params['default'] : 'en');
$this->smarty->assign('default_culture', $this->getPreference('default_culture'));

$this->smarty->assign('iso_language', $i18n->getCulture());
$this->smarty->assign('default_iso_language',(isset($params['default']))? $languages[$params['default']] : 'en_GB');
$this->smarty->assign('root_alias', 'home-'.$i18n->getLanguage());

setlocale(LC_ALL, $i18n->getCulture());