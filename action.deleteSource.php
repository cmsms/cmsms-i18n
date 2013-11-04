<?php
if (!isset($gCms)) exit;

if (! $this->CheckAccess())
	{
	return $this->DisplayErrorPage($id, $params, $returnid,$this->Lang('accessdenied'));
	}

if(isset($params['source']))
{
  $translations = I18nTranslation::doSelect(array('where' => array('source' => html_entity_decode($params['source']))));
  foreach($translations as $translation)
  {
   $translation->delete(); 
  }
}

return $this->Redirect($id, 'defaultadmin', $returnid, array());