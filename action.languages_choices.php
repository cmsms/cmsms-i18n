<?php
/**
 *
 * Project: I18n
 * Created on: 08/07/13 09:43
 *
 * Copyright 2013 Atom Seeds - Jean-Christophe Cuvelier <jcc@atomseeds.com>
 */

if (!cmsms()) exit;

global $i18n;
if (!isset($i18n)) $i18n = new I18nBase();

$languages = I18nTranslation::doSelect(array('group_by' => 'culture'));
$page_id = cmsms()->get_variable('page_id');
$source_id = I18nLink::getMainPageId($page_id, $i18n->getCulture());

$links = I18nLink::getTranslatedLinks($page_id);

if(!$source_id && ($i18n->getCulture() == $i18n->getMainCulture()))
{
    $trans = new I18nLink();
    $trans->setCulture($i18n->getMainCulture());
    $trans->setSourceAlias($page_id);
    $trans->setTargetAlias($page_id);

    if ($this->getPreference('harvest')) {
        $trans->save();
    }
    $links[$i18n->getCulture()] = $trans->getTargetAlias();
}


foreach ($languages as $language) {
    if (!isset($links[$language->getCulture()])) {
        $links[$language->getCulture()] = $page_id;

        // TODO: FIX LINKS
    }
}

var_dump($links);