<?php
if (!isset($gCms)) exit;

$languages = I18nTranslation::doSelect(array('group_by' => 'culture'));

$array = array();

foreach($languages as $language)
{
	$culture = new CultureInfo($language->getCulture());
	$array[$language->getCulture()] = trim(substr($culture->getNativeName(), 0, strpos( $culture->getNativeName(),'(')));
}

if (isset($params['assign_to']))
{
	$this->smarty->assign($params['assign_to'],$array);
}
else
{
	$this->smarty->assign('languages_list',$array);
}