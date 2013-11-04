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

if(isset($params['source']))
{
  echo '<h3>Translation:</h3><p>Source:</p>'.$params['source'].'<br /><br /><p>Translations:</p>';
  
  
  echo $this->CreateFormStart($id, 'editSource', $returnid);

  echo $this->CreateInputHidden($id, 'source', $params['source']);
  
  $translations = I18nTranslation::doSelect(array('where' => array('source' => html_entity_decode($params['source']))));
  $responses = isset($params['translation'])?$params['translation']:array();
  foreach($translations as $translation)
  {
    if(isset($responses[$translation->getId()]))
    {
      $translation->target = $responses[$translation->getId()];
      $translation->save();
    }
    echo '<p>Translate in ' . $translation->culture . ':</p>';
    
    if (strlen($params['source']) < 60)
    {
    	echo $this->CreateInputText($id, 'translation['.$translation->getId().']', $translation->target, 80);
    }
    elseif (strpos('<', $params['source']) !== false)
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