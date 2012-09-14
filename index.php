<?php
	require('global.php');
	require('inc/setting.inc.php');
	require('inc/smarty.php');
	
	switch($ac){
		default:
			if(checkLogin()){
				$setting = $db->select(0, 1, 'tb_setting');
				$smarty->assign('setting', $setting);
				$skin = $db->select(0, 1, 'tb_member', 'skin', 'and tbid = '.$_SESSION['member']['id']);
				$smarty->assign('skin', $skin['skin']);
				$smarty->display('index.tpl');
			}else{
				header('Location:login.php');
			}
	}
?>