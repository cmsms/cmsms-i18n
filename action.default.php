<?php
if (!isset($gCms)) exit;

/* -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-

   Code for I18n "default" action

   -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
   
   Typically, this will display something from a template
   or do some other task.
   
*/

global $i18n;
if (!isset($i18n)) $i18n = new I18nBase();


// URL Translation

if (isset($params['page']))
{
	$alias = $params['page'];
}

if (isset($params['href']))
{
	$alias = $params['href'];
}

if (isset($alias))
{
	$page = $i18n->getTranslation($alias);
	
	if (empty($page))
	{
		if ($this->getPreference('harvest') == 'true' && $i18n->getCulture() != '')
		{
		  $page = $this->createTranslation($alias, $i18n->getCulture());
		  
			// Create an entry in the database 
      // $trans = new I18nTranslation();
      // $trans->setCulture($i18n->getCulture());
      // $trans->setSource($alias);
      // if ($i18n->getCulture() == $this->getPreference('default_culture'))
      // {
      //  $trans->setTarget($alias);
      // }
      // $trans->save();
		}
		$page = $alias;
	}
	
	# check if the page exists in the db
	$manager =& $gCms->GetHierarchyManager();
	$node =& $manager->sureGetNodeByAlias($page);
	if (!isset($node)) return;
	$content =& $node->GetContent();
	if ($content !== FALSE && is_object($content))
	{
			$pageid = $content->Id();
			$alias = $content->Alias();
			$name = $content->Name(); //mbv - 21-06-2005
			$url = $content->GetUrl();
			$menu_text = $content->MenuText();
			$title = $content->TitleAttribute();
	}	
	
		$result = '<a href="'.$url.'" title="'.$title.'" ';
		if (isset($params['target']))
		{
			$result .= ' target="'.$params['target'].'"';
		}

		if (isset($params['id']))
		{
			$result .= ' id="'.$params['id'].'"';
		}

		if (isset($params['class']))
		{
			$result .= ' class="'.$params['class'].'"';
		}
		if (isset($params['tabindex']))
		{
			$result .= ' tabindex="'.$params['tabindex'].'"';
		}
		if (isset($params['more']))
		{
			$result .= ' '.$params['more'];
		}
		$result .= '>';
	
}


// Text translation 

if (isset($params['text']))
{	
	$translation = $i18n->getTranslation($params['text']);
	
	if (empty($translation))
	{
	  $translation = html_entity_decode($params['text']); // Show the default language text if translation do not exists.
		
		if ($this->getPreference('harvest') == 'true' && $i18n->getCulture() != '')
		{
		  $translation = $this->createTranslation($params['text'], $i18n->getCulture());
		  
      // // Create an entry in the database 
      // $trans = new I18nTranslation();
      // $trans->setCulture($i18n->getCulture());
      // $trans->setSource(html_entity_decode($params['text']));
      // if ($i18n->getCulture() == $this->getPreference('default_culture'))
      // {
      //  $trans->setTarget(html_entity_decode($params['text']));
      // }
      // $trans->save();
		}
	}
	
	if (isset($params['page']) && isset($result))
	{
		$translation = $result . $translation . '</a>';
	}
	elseif (isset($params['href']) && isset($url))
	{
		$translation = $url;
	}
	
	
	if (isset($params['assign_to']))
	{
		$this->smarty->assign($params['assign_to'], $translation);
	}
	else
	{
		echo $translation;
	}	
}

/*

if (!isset($I18n)) $this->init(); // If the culture is not set, we must to initialise it.

if ($I18n->getCulture() == $this->getPreference('default_culture'))
{
	// We wont traduce then...
	if ($this->getPreference('harvest'))
	{
		// We collect the strings then...
		
	}
}
*/