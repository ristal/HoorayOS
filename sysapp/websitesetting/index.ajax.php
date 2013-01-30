<?php
	require('../../global.php');
		
	switch($ac){
		case 'edit':
			$set = array(
				"title = '$val_title'",
				"keywords = '$val_keywords'",
				"description = '$val_description'"
			);
			$db->update(0, 0, 'tb_setting', $set);
			break;
		case 'checkVersion':
			//获取url地址
			$url = 'http://hoorayos.com/version.xml';
			//取出远程url的xml文件
			$html = file_get_contents($url);
			if($html == ""){
				echo 0;
			}else{
				//将文件装到一个数组当中
				$arr = simplexml_load_string($html);
				foreach($arr as $value){
					if($value['version'] == $version){
						echo 1;
					}else{
						echo $value['download'];
					}
					break;
				}
			}
			break;
	}
?>