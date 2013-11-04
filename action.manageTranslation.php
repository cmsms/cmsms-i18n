<?php
if (!isset($gCms)) exit;

if (! $this->CheckAccess())
{
	return $this->DisplayErrorPage($id, $params, $returnid,$this->Lang('accessdenied'));
}


if (isset($params['trans_id']))
{
	$translation = I18nTranslation::retrieveByPk($params['trans_id']);
	
	if(isset($params['target']))
	{
		$translation->setTarget($params['target']);
		if (!isset($params['cancel']))
		{
			$translation->save();
		}
		if (isset($params['submit']) || isset($params['cancel']))
		{
			$this->Redirect($id, 'defaultadmin', $returnid);
		}
	}
}
else
{
	$translation = new I18nTranslation();
}

if (isset($params['culture']) && isset($params['source']))
{
	$translation->setCulture($params['culture']);
	$translation->setSource(html_entity_decode($params['source']));
	
	if (!isset($params['cancel']))
	{
		$translation->save();
	}
}

$this->smarty->assign('trans_id', $this->CreateInputHidden($id, 'trans_id', $translation->getId()));

$this->smarty->assign('culture_title', $this->lang('culture'));
$this->smarty->assign('culture', $translation->getCulture());
$this->smarty->assign('source_title', $this->lang('source'));
$this->smarty->assign('source', htmlentities($translation->getSource()));

$this->smarty->assign('target_title', $this->lang('target'));
$source = $translation->getSource();
$target = $translation->getTarget();
if (strlen($source) < 60)
{
	$this->smarty->assign('target', $this->CreateInputText($id, 'target', $target, 80));
}
elseif (strpos('<', $source) !== false)
{
	$this->smarty->assign('target', $this->CreateTextArea(true, $id, $target, 'target', 'pagebigtextarea', '','', '', 90, 15, 'EditArea'));
}
else
{
	$this->smarty->assign('target', $this->CreateTextArea(true, $id, $target, 'target', 'pagebigtextarea', '','', '', 90, 15));
}

$this->smarty->assign('form_start', $this->CreateFormStart($id, 'manageTranslation', $returnid));
$this->smarty->assign('submit', $this->CreateInputSubmit($id, 'submit', $this->Lang('submit')));
$this->smarty->assign('cancel', $this->CreateInputSubmit($id, 'cancel', $this->Lang('cancel')));
$this->smarty->assign('form_end',$this->CreateFormEnd());


echo $this->ProcessTemplate('manageTranslation.tpl');