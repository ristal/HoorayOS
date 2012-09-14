<?php
	require('../../global.php');
	require('inc/setting.inc.php');
	require('inc/smarty.php');
	
	$apps = $db->select(0, 0, 'tb_app', 'tbid,name,icon', 'and kindid = 1');
	$smarty->assign('apps', $apps);
	$smarty->display('sysapp/permission/alert_addapps.tpl');
?>