<?php
	require('../../global.php');
	require('inc/setting.inc.php');
	
	switch($ac){
		case 'edit':
			$val_password = $val_password == '' ? $val_password : sha1($val_password);			
			if($value_1 == ''){
				$set = array(
					"username = '$val_username'",
					"password = '$val_password'",
					"type = $val_type"
				);
				if($value_4 == 1){
					$set[] = "permission_id = '$val_permission_id'";
				}
				$db->insert(0, 0, 'tb_member', $set);
			}else{
				$set = array("type = $val_type");
				if($password != ''){
					$set[] = "password = '$val_password'";
				}
				if($value_4 == 1){
					$set[] = "permission_id = '$val_permission_id'";
				}else{
					$set[] = "permission_id = ''";
				}
				$db->update(0, 0, 'tb_member', $set, "and tbid = $id");
			}
			break;
	}
?>