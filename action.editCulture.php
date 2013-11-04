<?php
if (!isset($gCms)) exit;

if (! $this->CheckAccess())
	{
	return $this->DisplayErrorPage($id, $params, $returnid,$this->Lang('accessdenied'));
	}


if(isset($params['cancel']))
{
  return $this->Redirect($id, 'defaultadmin', $returnid, array());
}

if(isset($params['culture']))
{
  echo '<h3>Translations for '. $params['culture'].':</h3>';
  
  
  echo $this->CreateFormStart($id, 'editCulture', $returnid);

  echo $this->CreateInputHidden($id, 'culture', $params['culture']);
  
  $translations = I18nTranslation::doSelect(array('where' => array('culture' => $params['culture'])));
  
  $responses = isset($params['translation'])?$params['translation']:array();
  
  foreach($translations as $translation)
  {
    if(isset($responses[$translation->getId()]))
    {
      $translation->target = $responses[$translation->getId()];
      $translation->save();
    }
    
    echo '<div style="margin-top: 25px; margin-bottom: 10px;">' . $translation->source . '</div>';
    
    if (strlen($translation->source) < 60)
    {
    	echo $this->CreateInputText($id, 'translation['.$translation->getId().']', $translation->target, 80);
    }
    elseif (strpos('<', $translation->source) !== false)
    {
    	echo $this->CreateTextArea(true, $id, $translation->target, 'translation['.$translation->getId().']', 'pagebigtextarea', '','', '', 90, 15, 'EditArea');
    }
    else
    {
    	echo $this->CreateTextArea(true, $id, $translation->target, 'translation['.$translation->getId().']', 'pagebigtextarea', '','', '', 90, 15);
    }
  }
  
  echo '<br /><br />';
  echo $this->CreateInputSubmit($id, 'submit', $this->Lang('submit'));
  echo $this->CreateInputSubmit($id, 'cancel', $this->Lang('cancel'));
  echo $this->CreateFormEnd();
}

if(isset($params['submit']))
{
  return $this->Redirect($id, 'defaultadmin', $returnid, array());
}