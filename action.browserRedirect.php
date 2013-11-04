<?php
if (!isset($gCms)) exit;

$lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);

if(!isset($params['force']) && isset($_COOKIE['i18n_url_user_choice']) )
{
  if($this->redirectTo($_COOKIE['i18n_url_user_choice']))
  {
    exit;
  }
}

if(isset($params[$lang]))
{
  if($this->redirectTo($params[$lang]))
  {
    exit;
  }
}

if(isset($params['default']))
{
  if($this->redirectTo($params['default']))
  {
    exit;
  }
}