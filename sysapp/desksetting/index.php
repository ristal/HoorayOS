<?php
	require('../../global.php');
	require('inc/setting.inc.php');
	require('inc/smarty.php');
	
	switch($ac){
		default:
			$dockpos = $db->select(0, 1, 'tb_member', 'dockpos', 'and tbid='.$_SESSION['member']['id']);
			$smarty->assign('dock', $dockpos['dockpos']);
			$smarty->display('sysapp/desksetting/index.tpl');
	}
?>