<?php
if (!isset($gCms)) exit;

/* -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-

   Code for I18n "urlselection" action

   -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
   
   Typically, this will display something from a template
   or do some other task.
   
*/

// SET THE USER CHOICE THEN ECHO THE URL

if (isset($params['selected_page']))
{
	// If user have already made his choice, we redirect him to the selected page
	
		$manager =& $gCms->GetHierarchyManager();
		$node =& $manager->sureGetNodeByAlias($params['selected_page']);
		if (!isset($node)) return;
		$content =& $node->GetContent();
		if ($content !== FALSE && is_object($content))
		{
			$pageid = $content->Id();
			$alias = $content->Alias();
			$name = $content->Name(); //mbv - 21-06-2005
			$url = $content->GetUrl();
			$menu_text = $content->MenuText();
			$titleattr = $content->TitleAttribute();
			
			setcookie('i18n_url_user_choice', $params['selected_page'], (time()+60*60*24*30));
			header('Location: '. $url);
			exit;
		}
}

?>