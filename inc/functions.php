<?php
	function daddslashes($string, $force = 0, $strip = FALSE) {
		if(!get_magic_quotes_gpc() || $force) {
			if(is_array($string)) {
				//如果其为一个数组则循环执行此函数
				foreach($string as $key => $val) {
					$string[$key] = daddslashes($val, $force, $strip);
				}
			} else {
				//下面是一个三元操作符，如果$strip为true则执行stripslashes去掉反斜线字符，再执行addslashes
				//这里为什么要将$string先去掉反斜线再进行转义呢，因为有的时候$string有可能有两个反斜线，stripslashes是将多余的反斜线过滤掉
				$string = addslashes($strip ? stripslashes($string) : $string);
			}
		}
		return $string;
	}
	/**
	 * 浏览器友好的变量输出
	 * @param mixed $var 变量
	 * @param boolean $echo 是否输出 默认为True 如果为false 则返回输出字符串
	 * @param string $label 标签 默认为空
	 * @param boolean $strict 是否严谨 默认为true
	 * @return void|string
	 */
	function dump($var, $echo = true, $label = null, $strict = true){
		$label = ($label === null) ? '' : rtrim($label).' ';
		if(!$strict){
			if(ini_get('html_errors')){
				$output = print_r($var, true);
				$output = '<pre>'.$label.htmlspecialchars($output, ENT_QUOTES).'</pre>';
			}else{
				$output = $label.print_r($var, true);
			}
		}else{
			ob_start();
			var_dump($var);
			$output = ob_get_clean();
			if(!extension_loaded('xdebug')){
				$output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
				$output = '<pre>'.$label.htmlspecialchars($output, ENT_QUOTES).'</pre>';
			}
		}
		if($echo){
			echo($output);
			return null;
		}else{
			return $output;
		}
	}
	/**
	 * URL重定向
	 * @param string $url 重定向的URL地址
	 * @param integer $time 重定向的等待时间（秒）
	 * @param string $msg 重定向前的提示信息
	 * @return void
	 */
	function redirect($url, $time=0, $msg=''){
		//多行URL地址支持
		$url = str_replace(array("\n", "\r"), '', $url);
		if(empty($msg)){
			$msg = "系统将在{$time}秒之后自动跳转到{$url}！";
		}
		if(!headers_sent()){
			// redirect
			if(0 === $time){
				header('Location: '.$url);
			}else{
				header("refresh:{$time};url={$url}");
				echo($msg);
			}
			exit();
		}else{
			$str = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
			if($time != 0){
				$str .= $msg;
			}
			exit($str);
		}
	}
	//文件地址处理
	function getFileInfo($str,$mode){
		if($str == '' || is_null($str)){
			return '';
		}
		switch($mode){
			case 'path' : return dirname($str); break;
			case 'name' : return basename($str,'.'.end(explode(".",$str))); break;
			case 'ext' : return end(explode(".",$str)); break;
			case 'simg' : return getFileInfo($str,"path")."/s_".getFileInfo($str,"name").".jpg"; break;
		}
	}
	//判断是否SSL协议
	function is_ssl(){
		if(isset($_SERVER['HTTPS']) && ('1' == $_SERVER['HTTPS'] || 'on' == strtolower($_SERVER['HTTPS']))){
			return true;
		}elseif(isset($_SERVER['SERVER_PORT']) && ('443' == $_SERVER['SERVER_PORT'])){
			return true;
		}
		return false;
	}
	//获取内网IP，0返回IP地址，1返回IPV4地址数字
	function getIp($type = 0){
		$type = $type ? 1 : 0;
		static $ip = NULL;
		if($ip !== NULL){
			return $ip[$type];
		}
		if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
			$pos = array_search('unknown', $arr);
			if(false !== $pos) unset($arr[$pos]);
			$ip = trim($arr[0]);
		}elseif(isset($_SERVER['HTTP_CLIENT_IP'])){
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		}elseif(isset($_SERVER['REMOTE_ADDR'])){
			$ip = $_SERVER['REMOTE_ADDR'];
		}
		// IP地址合法验证
		$long = sprintf("%u",ip2long($ip));
		$ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
		return $ip[$type];
	}
	//字符截断，支持中英文不乱码
	function cutstr($str,$len=0,$dot='...',$encoding='utf-8'){if(!is_numeric($len)){$len=intval($len);}if(!$len || strlen($str)<= $len){return $str;}$tempstr='';$str=str_replace(array('&', '"', '<', '>'),array('&', '"', '<', '>'),$str);if($encoding=='utf-8'){$n=$tn=$noc=0;while($n < strlen($str)){$t = ord($str[$n]);if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {$tn = 1; $n++; $noc++;} elseif (194 <= $t && $t <= 223) {$tn = 2; $n += 2; $noc += 2;} elseif (224 <= $t && $t < 239) {$tn = 3; $n += 3; $noc += 2;} elseif (240 <= $t && $t <= 247) {$tn = 4; $n += 4; $noc += 2;} elseif (248 <= $t && $t <= 251) {   $tn = 5; $n += 5; $noc += 2;} elseif ($t == 252 || $t == 253) {$tn = 6; $n += 6; $noc += 2;} else {$n++;}if($noc >= $len){break;}}if($noc > $len) {$n -= $tn;}$tempstr = substr($str, 0, $n);} elseif ($encoding == 'gbk') {for ($i=0; $i<$len; $i++) {$tempstr .= ord($str{$i}) > 127 ? $str{$i}.$str{++$i} : $str{$i};}}$tempstr = str_replace(array('&', '"', '<', '>'), array('&', '"', '<', '>'), $tempstr);return $tempstr.$dot;}
	//生成随机字符串
	function getRandStr($len = 4){
		$chars = array("a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z","A","B","C","D","E","F","G","H","I","J","K","L","M","N","O","P","Q","R","S","T","U","V","W","X","Y","Z","0","1","2","3","4","5","6","7","8","9");
		$charsLen = count($chars) - 1;
		shuffle($chars);
		$output = "";
		for($i=0; $i<$len; $i++){
			$output .= $chars[mt_rand(0, $charsLen)];
		}
		return $output;
	}
	//连续创建带层级的文件夹
	function recursive_mkdir($folder){
		$folder = preg_split( "/[\\\\\/]/" , $folder );
		$mkfolder = '';
		for($i=0; isset($folder[$i]); $i++){
			if(!strlen(trim($folder[$i]))){
				continue;
			}
			$mkfolder .= $folder[$i];
			if(!is_dir($mkfolder)){
				mkdir("$mkfolder",0777);
			}
			$mkfolder .= DIRECTORY_SEPARATOR;
		}
	}
	//创建缩略图
	function imageResize($source, $destination, $width = 0, $height = 0, $crop = false, $quality = 80) {
		$quality = $quality ? $quality : 80;
		$image = imagecreatefromstring($source);
		if($image){
			// Get dimensions
			$w = imagesx($image);
			$h = imagesy($image);
			if(($width && $w > $width) || ($height && $h > $height)){
				$ratio = $w / $h;
				if(($ratio >= 1 || $height == 0) && $width && !$crop){
					$new_height = $width / $ratio;
					$new_width = $width;
				}elseif($crop && $ratio <= ($width / $height)){
					$new_height = $width / $ratio;
					$new_width = $width;
				}else{
					$new_width = $height * $ratio;
					$new_height = $height;
				}
			}else{
				$new_width = $w;
				$new_height = $h;
			}
			$x_mid = $new_width * .5;  //horizontal middle
			$y_mid = $new_height * .5; //vertical middle
			// Resample
			error_log('height: ' . $new_height . ' - width: ' . $new_width);
			$new = imagecreatetruecolor(round($new_width), round($new_height));
			
			$c = imagecolorallocatealpha($new , 0 , 0 , 0 , 127);//拾取一个完全透明的颜色
			imagealphablending($new , false);//关闭混合模式，以便透明颜色能覆盖原画布
			imagefill($new , 0 , 0 , $c);//填充
			imagesavealpha($new , true);//设置保存PNG时保留透明通道信息
			
			imagecopyresampled($new, $image, 0, 0, 0, 0, $new_width, $new_height, $w, $h);
			// Crop
			if($crop){
				$crop = imagecreatetruecolor($width ? $width : $new_width, $height ? $height : $new_height);
				imagecopyresampled($crop, $new, 0, 0, ($x_mid - ($width * .5)), 0, $width, $height, $width, $height);
				//($y_mid - ($height * .5))
			}
			// Output
			// Enable interlancing [for progressive JPEG]
			imageinterlace($crop ? $crop : $new, true);

			$dext = strtolower(pathinfo($destination, PATHINFO_EXTENSION));
			if($dext == ''){
				$dext = $ext;
				$destination .= '.' . $ext;
			}
			switch($dext){
				case 'jpeg':
				case 'jpg':
					imagejpeg($crop ? $crop : $new, $destination, $quality);
					break;
				case 'png':
					$pngQuality = ($quality - 100) / 11.111111;
					$pngQuality = round(abs($pngQuality));
					imagepng($crop ? $crop : $new, $destination, $pngQuality);
					break;
				case 'gif':
					imagegif($crop ? $crop : $new, $destination);
					break;
			}
			@imagedestroy($image);
			@imagedestroy($new);
			@imagedestroy($crop);
		}
	}
	
	/*****以下方法仅限该项目*****/
	
	//获取用户头像
	function getAvatar($memberid, $size = 's'){
		switch($size){
			case 's':
				$size = 24;
				break;
			case 'n':
				$size = 48;
				break;
			case 'l':
				$size = 120;
		}
		if(file_exists('uploads/member/'.$memberid.'/avatar/'.$size.'.jpg')){
			$avatar = 'uploads/member/'.$memberid.'/avatar/'.$size.'.jpg';
		}else{
			$avatar = 'img/ui/avatar_'.$size.'.jpg';
		}
		return $avatar;
	}
	//获取图片缩略图地址
	function getSimgSrc($string){
		return preg_replace("#(\w*\..*)$#U", "s_\${1}", $string);
	}
	//安装应用
	function addApp($opt){
		global $db;
		switch($opt['type']){
			case 'folder':
				$set = array(
					'icon = "'.$opt['icon'].'"',
					'name = "'.$opt['name'].'"',
					'width = 650',
					'height = 400',
					'type = "'.$opt['type'].'"',
					'dt = now()',
					'lastdt = now()',
					'member_id = '.$_SESSION['member']['id']
				);
				$appid = $db->insert(0, 2, 'tb_member_app', $set);
				break;
			case 'papp':
			case 'pwidget':
				$set = array(
					'icon = "'.$opt['icon'].'"',
					'name = "'.$opt['name'].'"',
					'url = "'.$opt['url'].'"',
					'type = "'.$opt['type'].'"',
					'width = '.$opt['width'],
					'height = '.$opt['height'],
					'isresize = '.$opt['isresize'],
					'isopenmax = '.$opt['isopenmax'],
					'isflash = '.$opt['isflash'],
					'dt = now()',
					'lastdt = now()',
					'member_id = '.$_SESSION['member']['id']
				);
				$appid = $db->insert(0, 2, 'tb_member_app', $set);
				break;
			default:
				//检查应用是否已安装
				$count = $db->select(0, 2, 'tb_member_app', '*', 'and realid = '.$opt['id'].' and member_id = '.$_SESSION['member']['id']);
				if($count == 0){
					//查找应用信息
					$app = $db->select(0, 1, 'tb_app', '*', 'and tbid = '.$opt['id']);
					//在安装应用表里更新一条记录
					$set = array(
						'realid = '.$opt['id'],
						'name = "'.$app['name'].'"',
						'icon = "'.$app['icon'].'"',
						'url = "'.$app['url'].'"',
						'type = "'.$app['type'].'"',
						'width = '.$app['width'],
						'height = '.$app['height'],
						'isresize = '.$app['isresize'],
						'isopenmax = '.$app['isopenmax'],
						'issetbar = '.$app['issetbar'],
						'isflash = '.$app['isflash'],
						'dt = now()',
						'lastdt = now()',
						'member_id = '.$_SESSION['member']['id']
					);
					$appid = $db->insert(0, 2, 'tb_member_app', $set);
					//更新使用人数
					$db->update(0, 0, 'tb_app', 'usecount = usecount + 1', 'and tbid = '.$opt['id']);
				}
		}
		if(!empty($appid)){
			//将安装应用表返回的id记录到用户表
			$rs = $db->select(0, 1, 'tb_member', 'desk'.$opt['desk'], 'and tbid='.$_SESSION['member']['id']);
			$deskapp = $rs['desk'.$opt['desk']] == '' ? $appid : $rs['desk'.$opt['desk']].','.$appid;
			$db->update(0, 0, 'tb_member', 'desk'.$opt['desk'].'="'.$deskapp.'"', 'and tbid='.$_SESSION['member']['id']);
		}
	}
	//删除应用
	function delApp($id){
		global $db;
		$member_app = $db->select(0, 1, 'tb_member_app', 'realid, type, folder_id', 'and tbid = '.$id.' and member_id = '.$_SESSION['member']['id']);
		//如果不是文件夹，则直接删除，反之先删除文件夹内的应用，再删除文件夹
		switch($member_app['type']){
			case 'folder':
				$rs = $db->select(0, 0, 'tb_member_app', 'tbid', 'and folder_id = '.$id);
				if($rs != NULL){
					foreach($rs as $v){
						delApp($v['tbid']);

					}
				}
				delAppStr($id);
				break;
			case 'app':
			case 'widget':
				delAppStr($id);
				$db->update(0, 0, 'tb_app', 'usecount = usecount - 1', 'and tbid = '.$member_app['realid']);
				break;
			case 'papp':
			case 'pwidget':
				delAppStr($id);
				break;
		}
	}
	function delAppStr($id){
		global $db;
		$rs = $db->select(0, 1, 'tb_member', 'dock, desk1, desk2, desk3, desk4, desk5', 'and tbid = '.$_SESSION['member']['id']);
		$flag = false;
		$set = '';
		if($rs['dock'] != ''){
			$dockapp = explode(',', $rs['dock']);
			foreach($dockapp as $k => $v){
				if($v == $id){
					$flag = true;
					unset($dockapp[$k]);
					break;
				}
			}
			$set .= 'dock = "'.implode(',', $dockapp).'"';
		}else{
			$set .= 'dock = ""';
		}
		for($i=1; $i<=5; $i++){
			if($rs['desk'.$i] != ''){
				$deskapp = explode(',', $rs['desk'.$i]);
				foreach($deskapp as $k => $v){
					if($v == $id){
						$flag = true;
						unset($deskapp[$k]);
						break;
					}
				}
				$set .= ',desk'.$i.' = "'.implode(',', $deskapp).'"';
			}else{
				$set .= ',desk'.$i.' = ""';
			}
		}
		if($flag){
			$db->update(0, 0, 'tb_member', $set, 'and tbid = '.$_SESSION['member']['id']);
		}
		$db->delete(0, 0, 'tb_member_app', 'and tbid = '.$id.' and member_id = '.$_SESSION['member']['id']);
	}
	//获取我的应用id数组
	function getMyAppList(){
		global $db;
		foreach($db->select(0, 0, 'tb_member_app', 'tbid', 'and member_id = '.$_SESSION['member']['id']) as $value){
			$myapplist[] = $value['tbid'];
		}
		return $myapplist != NULL ? $myapplist : NULL ;
	}
	//验证是否已安装该应用
	function checkAppIsMine($id){
		$flag = false;
		$myapplist = getMyAppList();
		if($myapplist != NULL){
			if(in_array($id, $myapplist)){
				$flag = true;
			}
		}
		return $flag;
	}
	//强制格式化appid，如：'10,13,,17,4,6,'，格式化后：'10,13,17,4,6'
	function formatAppidArray($arr){
		foreach($arr as $k => $v){
			if($v==''){
				unset($arr[$k]);
			}
		}
		return $arr;
	}
	//验证是否登入
	function checkLogin(){
		return $_SESSION['member'] != NULL ? true : false;
	}
	//验证是否为管理员
	function checkAdmin(){
		global $db;
		$user = $db->select(0, 1, 'tb_member', 'type', 'and tbid='.$_SESSION['member']['id']);
		return $user['type'] == 1 ? true : false;
	}
	//验证是否有权限
	function checkPermissions($app_id){
		global $db;
		$isHavePermissions = false;
		$user = $db->select(0, 1, 'tb_member', 'permission_id', 'and tbid='.$_SESSION['member']['id']);
		if($user['permission_id'] != ''){
			$permission = $db->select(0, 1, 'tb_permission', 'apps_id', 'and tbid='.$user['permission_id']);
			if($permission['apps_id'] != ''){
				$apps = explode(',', $permission['apps_id']);
				if(in_array($app_id, $apps)){
					$isHavePermissions = true;
				}
			}
		}
		return $isHavePermissions;
	}
?>