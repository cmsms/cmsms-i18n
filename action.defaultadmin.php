<?php
    if (!isset($gCms)) exit;

    if (!$this->CheckAccess()) {
        return $this->DisplayErrorPage($id, $params, $returnid, $this->Lang('accessdenied'));
    }

    /* -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-

       Code for I18n "defaultadmin" admin action

       -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-

       Typically, this will display something from a template
       or do some other task.

    */

    $this->smarty->assign('tab_headers', $this->StartTabHeaders() .
        $this->SetTabHeader('translations', $this->Lang('title_translations')) .
        $this->SetTabHeader('links', $this->Lang('title_links'), isset($params['tab_links']) ? true : false) .
        $this->SetTabHeader('preferences', $this->Lang('title_preferences'), isset($params['submit']) ? true : false) .
        $this->EndTabHeaders() . $this->StartTabContent());
    $this->smarty->assign('end_tab', $this->EndTab());
    $this->smarty->assign('tab_footers', $this->EndTabContent());
    $this->smarty->assign('start_translations_tab', $this->StartTab('translations'));
    $this->smarty->assign('start_links_tab', $this->StartTab('links'));
    $this->smarty->assign('start_preferences_tab', $this->StartTab('preferences'));
    $this->smarty->assign('title_section', 'defaultadmin');


    $form = new CMSForm($this->GetName(), $id, 'defaultadmin', $returnid);
    $form->setWidget('default_culture', 'select', array('values' => Translator::getAvailableCultures(), 'preference' => 'default_culture'));
    $form->setWidget('harvest', 'checkbox', array('text' => $this->Lang('harvest_tips'), 'preference' => 'harvest'));

    if($form->isSent())
    {
        $form->process();
    }

    $smarty->assign('form', $form);




//    if (isset($params['default_culture'])) {
//        $this->SetPreference('default_culture', $params['default_culture']);
//    }

// TRANSLATIONS

    $cultures        = I18nTranslation::getKnownCultures();
    $default_culture = $this->getPreference('default_culture');
    $cultures        = array_diff($cultures, array($default_culture));
    $this->smarty->assign('default_culture', $default_culture);
    $cultures_array = array();
    foreach ($cultures as $culture) {
        $cultures_array[] = array(
            'name' => $culture,
            'link' => $this->CreateLink($id, 'editCulture', $returnid, '', array('culture' => $culture), '', true)
        );
    }


    $this->smarty->assign('cultures', $cultures_array);

    $translations = I18nTranslation::doSelect();
    $sort         = array();
    foreach ($translations as $translation) {
        $sort[$translation->getCulture()][$translation->getSource()] = $translation;
    }
    $translations = array();
    $sources      = array();
    foreach ($sort as $cult => $lang_translations) {
        foreach ($lang_translations as $source => $translation) {
            if ($cult == $default_culture) {
                $sources[$source] = array(
                    'name'   => $source,
                    'link'   => $this->CreateLink($id, 'editSource', $returnid, '', array('source' => $source), '', true),
                    'delete' => $this->CreateLink($id, 'deleteSource', $returnid, '', array('source' => $source), '', true)
                );
            } else {
                if (!isset($translations[$default_culture][$source])) {
                    $sources[$source] = array(
                        'name'   => $source,
                        'link'   => $this->CreateLink($id, 'editSource', $returnid, '', array('source' => $source), '', true),
                        'delete' => $this->CreateLink($id, 'deleteSource', $returnid, '', array('source' => $source), '', true)
                    );
                }
                $target                       = $translation->getTarget();
                $translations[$cult][$source] = $this->CreateLink($id, 'manageTranslation', $returnid,
                    !empty($target) ? htmlentities(substr($target, 0, 30)) : $this->lang('translate_me'),
                    array('trans_id' => $translation->getId())
                );
            }
        }
    }
    foreach ($sources as $source) {
        foreach ($cultures as $culture) {
            if (!isset($translations[$culture][$source['name']])) {
                $trans = new I18nTranslation();
                $trans->setCulture($culture);
                $trans->setSource($source['name']);
                $trans->save();

                $translations[$culture][$source['name']] = $this->CreateLink($id, 'manageTranslation', $returnid,
                    $this->lang('translate_me'),
                    array('trans_id' => $trans->getId())
                );

                // $translations[$culture][$source] = $this->CreateLink($id, 'manageTranslation', $returnid,
                // 			$this->lang('translate_me'),
                // 			array('culture' => $culture, 'source' => $source)
                // 			);
            }
        }
    }

    $this->smarty->assign('sources', $sources);
    $this->smarty->assign('translations', $translations);

// Links

    $link_cultures = I18nLink::getKnownCultures();
    $link_cultures = array_diff($link_cultures, array($default_culture));
    $this->smarty->assign('default_culture', $default_culture);
    $this->smarty->assign('link_cultures', $link_cultures);

    $links = I18nLink::doSelect();
    $sort  = array();
    foreach ($links as $link) {
        $sort[$link->getCulture()][$link->getSourceAlias()] = $link;
    }
    $links         = array();
    $links_sources = array();
    foreach ($sort as $cult => $lang_translations) {
        foreach ($lang_translations as $source => $translation) {
            if ($cult == $default_culture) {
                $links_sources[$source] = $source;
            } else {
                if (!isset($translations[$default_culture][$source])) {
                    $links_sources[$source] = $source;
                }
                $target = $translation->getTargetAlias();

                $target_title = $this->lang('translate_me');
                if (!empty($target)) {
                    $ttitle = I18nLink::getAliasForSure($target);
                    if (!empty($ttitle)) {
                        $target_title = $ttitle;
                    }
                }


                $links[$cult][$source] = $this->CreateLink($id, 'manageLink', $returnid,
                    $target_title,
                    array('trans_id' => $translation->getId())
                );
            }
        }
    }

    foreach ($links_sources as $source) {
        foreach ($link_cultures as $culture) {
            if (!isset($links[$culture][$source])) {
                $trans = new I18nLink();
                $trans->setCulture($culture);
                $trans->setSourceAlias($source);
                $trans->save();

                $links[$culture][$source] = $this->CreateLink($id, 'manageLink', $returnid,
                    $this->lang('translate_me'),
                    array('trans_id' => $trans->getId())
                );

                // $translations[$culture][$source] = $this->CreateLink($id, 'manageTranslation', $returnid,
                // 			$this->lang('translate_me'),
                // 			array('culture' => $culture, 'source' => $source)
                // 			);
            }
        }
    }

    $this->smarty->assign('links_sources', $links_sources);
    $this->smarty->assign('links', $links);

    /*
    ?><pre><?php
    var_dump($sources);
    var_dump($translations);
    ?></pre><?php
    */

// PREFERENCES

    if (isset($params['submitbutton']) && !isset($params['cancelbutton'])) {
        if (isset($params['harvest'])) {
            $this->SetPreference('harvest', 'true');
        } else {
            $this->SetPreference('harvest', 'false');
        }
    }

    $this->smarty->assign('form_start', $this->CreateFormStart($id, 'defaultadmin', $returnid));
    $this->smarty->assign('form_end', $this->CreateFormEnd());
    $this->smarty->assign('submit', $this->CreateInputSubmit($id, 'submitbutton', $this->Lang('submit')));
    $this->smarty->assign('cancel', $this->CreateInputSubmit($id, 'cancelbutton', $this->Lang('cancel')));


    $this->smarty->assign('title_harvest', $this->lang('title_harvest'));
    $this->smarty->assign('harvest_tips', $this->lang('harvest_tips'));
    $this->smarty->assign('harvest', $this->CreateInputCheckbox($id, 'harvest', 'true', $this->getPreference('harvest', 'false')));

    $this->smarty->assign('title_cache', $this->lang('title_cache'));
    $this->smarty->assign('cache_label', $this->lang('cache_label'));
    $this->smarty->assign('cache', $this->CreateInputCheckbox($id, 'cache', 'true', $this->getPreference('cache', 'false')));

    $this->smarty->assign('default_culture_title', $this->lang('default_culture'));

    $cultures       = CultureInfo::getCultures();
    $cultures_array = array();
    foreach ($cultures as $culture) {
        $cultures_array[$culture] = $culture;
    }

    $default_culture = $this->GetPreference('default_culture');
    if (!empty($default_culture)) {
        $scu = array($default_culture);
    } else {
        $scu = array();
    }
//    $this->smarty->assign('default_cultures', $this->CreateInputSelectList($id, 'default_culture', $cultures_array, $scu, 1, '', false));

    echo $this->ProcessTemplate('adminpanel.tpl');

    echo $this->CreateLink($id, 'labs', $returnid, 'Labs');


?>