<?php
	require_once('../../global.php');
	
	//png1,png2,png3分别为3个尺寸头像的参数，经过base64解密后保存即可
	$folder = 'uploads/member/'.$_SESSION['member']['id'].'/avatar/';
	recursive_mkdir($folder);
	$filename120 = $folder.'120.jpg';
	$filename48 = $folder.'48.jpg';
	$filename24 = $folder.'24.jpg';
	
	$somecontent1 = base64_decode($_POST['png1']);
	$somecontent2 = base64_decode($_POST['png2']);
	$somecontent3 = base64_decode($_POST['png3']);
	
	if($handle = fopen($filename120, 'w+')){
		if(!fwrite($handle, $somecontent1) == false){
			fclose($handle);
		}
	}
	if($handle = fopen($filename48, 'w+')){
		if(!fwrite($handle, $somecontent2) == false){
			fclose($handle);
		}
	}
	if($handle = fopen($filename24, 'w+')){
		if(!fwrite($handle, $somecontent3) == false){
			fclose($handle);
		}
	}
	//更新cookie头像
	if(isset($_COOKIE['userlist'])){
		$userlist = json_decode(stripslashes($_COOKIE['userlist']), true);
		foreach($userlist as &$v){
			if($v['id'] == $_SESSION['member']['id']){
				$v['avatar'] = getAvatar($_SESSION['member']['id'], 'l');
				break;
			}
		}
		setcookie('userlist', json_encode($userlist), time() + 3600 * 24 * 365);
	}
	echo 'success=上传成功';
?>