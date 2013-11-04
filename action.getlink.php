<?php
if (!isset($gCms)) exit;

/* -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-

   Code for I18n "link" action

   -=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-=-
   
   Typically, this will display something from a template
   or do some other task.
   
*/

if (isset($params['selected_page']))
{
  
  if (!isset($_REQUEST['clear_user_choice']) && isset($_COOKIE['i18n_url_user_choice']) && $_COOKIE['i18n_url_user_choice'] == $params['selected_page'])  {
    if($this->redirectTo($params['selected_page']))
    {
      exit;
    }
  }

  echo  $this->createLink($id, 'urlselection', $returnid, $contents='', array('selected_page' => $params['selected_page']), '', true, true);

}