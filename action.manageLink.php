<?php
if (!isset($gCms)) exit;

if (! $this->CheckAccess())
{
	return $this->DisplayErrorPage($id, $params, $returnid,$this->Lang('accessdenied'));
}


if (isset($params['trans_id']))
{
	$translation = I18nLink::retrieveByPk($params['trans_id']);
	
	if(isset($params['target_alias']))
	{
		$translation->setTargetAlias($params['target_alias']);
		if (!isset($params['cancel']))
		{
			$translation->save();
		}
		if (isset($params['submit']) || isset($params['cancel']))
		{
			$this->Redirect($id, 'defaultadmin', $returnid, array('tab_links' => true));
		}
	}
}
else
{
	$translation = new I18nLink();
}

if (isset($params['culture']) && isset($params['source_alias']))
{
	$translation->setCulture($params['culture']);
	$translation->setSourceAlias(html_entity_decode($params['source_alias']));
	
	if (!isset($params['cancel']))
	{
		$translation->save();
	}
}

$this->smarty->assign('trans_id', $this->CreateInputHidden($id, 'trans_id', $translation->getId()));

$this->smarty->assign('culture_title', $this->lang('culture'));
$this->smarty->assign('culture', $translation->getCulture());
$this->smarty->assign('source_title', $this->lang('source'));
$this->smarty->assign('source_alias', htmlentities($translation->getSourceAlias()));

$this->smarty->assign('target_title', $this->lang('target'));
$source = $translation->getSourceAlias();
$target = $translation->getTargetAlias();

//	$this->smarty->assign('target_alias', $this->CreateInputText($id, 'target_alias', $target, 80));

$contentops =& $gCms->GetContentOperations();
$this->smarty->assign('target_alias', $contentops->CreateHierarchyDropdown('',$translation->getTargetAlias(), $id.'target_alias'));  

$this->smarty->assign('form_start', $this->CreateFormStart($id, 'manageLink', $returnid));
$this->smarty->assign('submit', $this->CreateInputSubmit($id, 'submit', $this->Lang('submit')));
$this->smarty->assign('cancel', $this->CreateInputSubmit($id, 'cancel', $this->Lang('cancel')));
$this->smarty->assign('form_end',$this->CreateFormEnd());


echo $this->ProcessTemplate('manageLink.tpl');