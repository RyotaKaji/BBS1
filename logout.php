<?php
require_once('libs/Smarty.class.php'); 
$smarty = new Smarty();
$smarty->template_dir = 'BBS1/templates/';
$smarty->compile_dir  = 'BBS1/templates_c/';

session_start();
$_SESSION = array(); 
session_destroy();

//テンプレートへ出力
$smarty->display('logout.tpl');