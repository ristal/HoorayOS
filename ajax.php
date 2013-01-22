<?php
	require('global.php');
		
	switch($ac){
		//登入
		case 'login':
			$rememberPswd = isset($rememberPswd) ? 1 : 0;
			$autoLogin = isset($autoLogin) ? 1 : 0;
			$sqlwhere = array(
				'username = "'.$username.'"',
				'password = "'.sha1($password).'"'
			);
			$row = $db->select(0, 1, 'tb_member', '*', $sqlwhere);
			if(!empty($row)){
				$_SESSION['member']['id'] = $row['tbid'];
				$_SESSION['member']['name'] = $row['username'];
				$db->update(0, 0, 'tb_member', 'lastlogindt = now(), lastloginip = "'.getIp().'"', 'and tbid = '.$row['tbid']);
				setcookie('memberID', $row['tbid'], time() + 3600 * 24 * 365);
				//是否自动登录
				setcookie('autoLogin', $autoLogin, time() + 3600 * 24 * 365);
				//处理登录用户列表
				$userlist = isset($_COOKIE['userlist']) ? json_decode(stripslashes($_COOKIE['userlist']), true) : array();
				if($userlist != NULL){
					$isNewUser = true;
					$from = 0;
					foreach($userlist as $k => &$v){
						if($v['id'] == $row['tbid']){
							$v['username'] = $username;
							$v['password'] = $rememberPswd ? $password : '';
							$v['rememberPswd'] = $rememberPswd;
							$v['autoLogin'] = $autoLogin;
							$v['avatar'] = getAvatar($v->id, 'l');
							$isNewUser = false;
							$from = $k;
							break;
						}
					}
					//是否为新用户
					if($isNewUser){
						$newUser = array();
						$newUser['id'] = $row['tbid'];
						$newUser['username'] = $username;
						$newUser['password'] = $rememberPswd ? $password : '';
						$newUser['rememberPswd'] = $rememberPswd;
						$newUser['autoLogin'] = $autoLogin;
						$newUser['avatar'] = getAvatar($row['tbid'], 'l');
						$userlist[] = $newUser;
						$from = count($userlist) - 1;
					}
					//将刚登入的账号排到首位
					$tmp = $userlist[$from];
					for($i = $from; $i > 0; $i--){
						$userlist[$i] = $userlist[$i-1];
					}
					$userlist[0] = $tmp;
				}else{
					$userlist[0]['id'] = $row['tbid'];
					$userlist[0]['username'] = $username;
					$userlist[0]['password'] = $rememberPswd ? $password : '';
					$userlist[0]['rememberPswd'] = $rememberPswd;
					$userlist[0]['autoLogin'] = $autoLogin;
					$userlist[0]['avatar'] = getAvatar($row['tbid'], 'l');
				}
				setcookie('userlist', json_encode($userlist), time() + 3600 * 24 * 365);
				$cb['info'] = '';
				$cb['status'] = 'y';
			}else{
				$cb['info'] = '';
				$cb['status'] = 'n';
			}
			echo json_encode($cb);
			break;
		//注册
		case 'register':
			$isreg = $db->select(0, 1, 'tb_member', 'tbid', 'and username = "'.$reg_username.'"');
			if(empty($isreg)){
				$set = array(
					'username = "'.$reg_username.'"',
					'password = "'.sha1($reg_password).'"',
					'regdt = now()'
				);
				$db->insert(0, 0, 'tb_member', $set);
				$cb['info'] = $reg_username;
				$cb['status'] = 'y';
			}else{
				$cb['info'] = '';
				$cb['status'] = 'n';
			}
			echo json_encode($cb);
			break;
		case 'checkUsername':
			$isreg = $db->select(0, 1, 'tb_member', 'tbid', 'and username = "'.$param.'"');
			if(empty($isreg)){
				$cb['info'] = '';
				$cb['status'] = 'y';
			}else{
				$cb['info'] = '用户名已存在，请更换';
				$cb['status'] = 'n';
			}
			echo json_encode($cb);
			break;
		//登出
		case 'logout':
			session_unset();
			setcookie('autoLogin', '', time() - 3600);
			break;
		//获得头像
		case 'getAvatar':
			echo getAvatar($_SESSION['member']['id']);
			break;
		//获得主题
		case 'getWallpaper':
			$rs = $db->select(0, 1, 'tb_member', 'wallpaper_id, wallpapertype, wallpaperwebsite, wallpaperstate', 'and tbid = '.$_SESSION['member']['id']);
			switch($rs['wallpaperstate']){
				case '1':
				case '2':
					$table = $rs['wallpaperstate'] == 1 ? 'tb_wallpaper' : 'tb_pwallpaper';
					$wallpaper = $db->select(0, 1, $table, 'url, width, height', 'and tbid = '.$rs['wallpaper_id']);
					$wallpaper_array = array(
						$rs['wallpaperstate'],
						$wallpaper['url'],
						$rs['wallpapertype'],
						$wallpaper['width'],
						$wallpaper['height']
					);
					echo implode('<{|}>', $wallpaper_array);
					break;
				case '3':
					$wallpaper_array = array(
						$rs['wallpaperstate'],
						$rs['wallpaperwebsite']
					);
					echo implode('<{|}>', $wallpaper_array);
					break;
			}
			break;
		//更新主题
		case 'setWallpaper':
			$set = array(
				'wallpaperstate = '.$wpstate,
				'wallpapertype = "'.$wptype.'"'
			);
			switch($wpstate){
				case '0':
					$set = array(
						'wallpapertype = "'.$wptype.'"'
					);
					break;
				case '1':
				case '2':
					if($wp != ''){
						$set[] = 'wallpaper_id = '.$wp;
					}					
					break;
				case '3':
					if($wp != ''){
						$set[] = 'wallpaperwebsite = "'.$wp.'"';
					}
					break;
			}
			$db->update(0, 0, 'tb_member', $set, 'and tbid = '.$_SESSION['member']['id']);
			break;
		//获得窗口皮肤
		case 'getSkin':
			$skin = $db->select(0, 1, 'tb_member', 'skin', 'and tbid = '.$_SESSION['member']['id']);
			echo $skin['skin'];
			break;
		//获得应用码头位置
		case 'getDockPos':
			$dockpos = $db->select(0, 1, 'tb_member', 'dockpos', 'and tbid = '.$_SESSION['member']['id']);
			echo $dockpos['dockpos'];
			break;
		//更新应用码头位置
		case 'setDockPos':
			$db->update(0, 0, 'tb_member', 'dockpos = "'.$dock.'"', 'and tbid = '.$_SESSION['member']['id']);
			break;
		//获得图标排列方式
		case 'getAppXY':
			$appxy = $db->select(0, 1, 'tb_member', 'appxy', 'and tbid = '.$_SESSION['member']['id']);
			echo $appxy['appxy'];
			break;
		//更新图标排列方式
		case 'setAppXY':
			$db->update(0, 0, 'tb_member', 'appxy = "'.$appxy.'"', 'and tbid = '.$_SESSION['member']['id']);
			break;
		//获得文件夹内图标
		case 'getMyFolderApp':
			$rs = $db->select(0, 0, 'tb_member_app', '*', 'and folder_id = '.$folderid.' and member_id = '.$_SESSION['member']['id'], 'lastdt asc');
			$data = array();
			if($rs != NULL){
				foreach($rs as $v){
					$tmp['type'] = $v['type'];
					$tmp['appid'] = $v['tbid'];
					$tmp['name'] = $v['name'];
					$tmp['icon'] = $v['icon'];
					$data[] = $tmp;
				}
			}
			echo json_encode($data);
			break;
		//获得桌面图标
		case 'getMyApp':
			$appid = $db->select(0, 1, 'tb_member', 'dock, desk1, desk2, desk3, desk4, desk5', 'and tbid = '.$_SESSION['member']['id']);
			$desktop['dock'] = array();
			for($i = 1; $i <= 5; $i++){
				$desktop['desk'.$i] = array();
			}
			if($appid['dock'] != ''){
				$rs = $db->select(0, 0, 'tb_member_app', 'tbid, name, icon, type', 'and tbid in('.$appid['dock'].')', 'field(tbid, '.$appid['dock'].')');
				if($rs != NULL){
					foreach($rs as $v){
						$tmp['type'] = $v['type'];
						$tmp['appid'] = $v['tbid'];
						$tmp['name'] = $v['name'];
						$tmp['icon'] = $v['icon'];
						$data[] = $tmp;
					}
					$desktop['dock'] = $data;
					unset($data);
				}
			}
			for($i = 1; $i <= 5; $i++){
				if($appid['desk'.$i] != ''){
					$rs = $db->select(0, 0, 'tb_member_app', 'tbid, name, icon, type', 'and tbid in('.$appid['desk'.$i].')', 'field(tbid, '.$appid['desk'.$i].')');
					if($rs != NULL){
						foreach($rs as $v){
							$tmp['type'] = $v['type'];
							$tmp['appid'] = $v['tbid'];
							$tmp['name'] = $v['name'];
							$tmp['icon'] = $v['icon'];
							$data[] = $tmp;
						}
						$desktop['desk'.$i] = $data;
						unset($data);
					}
				}
			}
			echo json_encode($desktop);
			break;
		//根据id获取图标
		case 'getMyAppById':
			//E100 应用不存在
			$flag = checkAppIsMine($id);
			if($flag){
				$rs = $db->select(0, 1, 'tb_member_app', '*', 'and tbid = '.$id.' and member_id = '.$_SESSION['member']['id']);
				if($rs != NULL){
					if($rs['type'] == 'app' || $rs['type'] == 'widget'){
						$ishas = $db->select(0, 2, 'tb_app', '*', 'and tbid = '.$rs['realid']);
						if($ishas == 0){
							$app['error'] = 'E100';
							echo json_encode($app);
							exit;
						}
					}
					$app['type'] = $rs['type'];
					$app['appid'] = $rs['tbid'];
					$app['realappid'] = $rs['realid'];
					$app['name'] = $rs['name'];
					$app['icon'] = $rs['icon'];
					$app['width'] = $rs['width'];
					$app['height'] = $rs['height'];
					$app['isresize'] = $rs['isresize'];
					$app['isopenmax'] = $rs['isopenmax'];
					$app['issetbar'] = $rs['issetbar'];
					$app['isflash'] = $rs['isflash'];
					if($rs['type'] == 'app' || $rs['type'] == 'widget'){
						$realurl = $db->select(0, 1, 'tb_app', 'url', 'and tbid = '.$rs['realid']);
						$app['url'] = $realurl['url'];
					}else{
						$app['url'] = $rs['url'];
					}
					echo json_encode($app);
				}
			}
			break;
		//添加桌面图标
		case 'addMyApp':
			addApp(array(
				'type' => '',
				'id' => $id,
				'desk' => $desk
			));
			break;
		//删除桌面图标
		case 'delMyApp':
			delApp($id);
			break;
		//更新桌面图标
		case 'moveMyApp':
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
				$set .= 'dock="'.implode(',', $dockapp).'"';
			}else{
				$set .= 'dock=""';
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
					$set .= ',desk'.$i.'="'.implode(',', $deskapp).'"';
				}else{
					$set .= ',desk'.$i.'=""';
				}
			}
			if($flag){
				$db->update(0, 0, 'tb_member', $set, 'and tbid = '.$_SESSION['member']['id']);
			}else{
				$db->update(0, 0, 'tb_member_app', 'folder_id = 0', 'and tbid = '.$id.' and member_id = '.$_SESSION['member']['id']);
			}
			$rs = $db->select(0, 1, 'tb_member', 'desk'.$todesk, 'and tbid='.$_SESSION['member']['id']);
			$rs['desk'.$todesk] = $rs['desk'.$todesk] == '' ? $id : $rs['desk'.$todesk].','.$id;
			$db->update(0, 0, 'tb_member', 'desk'.$todesk.' = "'.$rs['desk'.$todesk].'"', 'and tbid = '.$_SESSION['member']['id']);
			break;
		case 'updateMyApp':
			switch($movetype){
				case 'dock-folder':
					$rs = $db->select(0, 1, 'tb_member', 'dock', 'and tbid = '.$_SESSION['member']['id']);
					$dock_arr = explode(',', $rs['dock']);
					$key = array_search($id, $dock_arr);
					unset($dock_arr[$key]);
					$db->update(0, 0, 'tb_member', 'dock = "'.implode(',', $dock_arr).'"', 'and tbid = '.$_SESSION['member']['id']);
					$db->update(0, 0, 'tb_member_app', 'folder_id = '.$to, 'and tbid = '.$id.' and member_id = '.$_SESSION['member']['id']);
					break;
				case 'dock-dock':
					$rs = $db->select(0, 1, 'tb_member', 'dock', 'and tbid = '.$_SESSION['member']['id']);
					$dock_arr = explode(',', $rs['dock']);
					//判断传入的应用id和数据库里的id是否吻合
					if($dock_arr[$from] == $id){
						if($from > $to){
							for($i = $from; $i > $to; $i--){
								$dock_arr[$i] = $dock_arr[$i-1];
							}
							$dock_arr[$to] = $id;
						}else if($to > $from){
							for($i = $from; $i < $to; $i++){
								$dock_arr[$i] = $dock_arr[$i+1];
							}
							$dock_arr[$to] = $id;
						}
						$dock_arr = formatAppidArray($dock_arr);
						$db->update(0, 0, 'tb_member', 'dock = "'.implode(',', $dock_arr).'"', 'and tbid = '.$_SESSION['member']['id']);
					}
					break;
				case 'dock-desk':
					$rs = $db->select(0, 1, 'tb_member', 'dock, desk'.$desk, 'and tbid = '.$_SESSION['member']['id']);
					$dock_arr = explode(',', $rs['dock']);
					$desk_arr = explode(',', $rs['desk'.$desk]);
					unset($dock_arr[$from]);
					if($desk_arr[0] == ''){
						$desk_arr[0] = $id;
					}else{
						array_splice($desk_arr, $to, 0, $id);
					}
					$db->update(0, 0, 'tb_member', 'dock = "'.implode(',', $dock_arr).'", desk'.$desk.' = "'.implode(',', $desk_arr).'"', 'and tbid = '.$_SESSION['member']['id']);
					break;
				case 'desk-folder':
					$rs1 = $db->select(0, 1, 'tb_member', 'desk'.$desk, 'and tbid = '.$_SESSION['member']['id']);
					$desk_arr = explode(',', $rs1['desk'.$desk]);
					$key = array_search($id, $desk_arr);
					unset($desk_arr[$key]);
					$db->update(0, 0, 'tb_member', 'desk'.$desk.' = "'.implode(',', $desk_arr).'"', 'and tbid = '.$_SESSION['member']['id']);
					$db->update(0, 0, 'tb_member_app', 'folder_id = '.$to, 'and tbid = '.$id.' and member_id = '.$_SESSION['member']['id']);
					break;
				case 'desk-dock':
					$rs = $db->select(0, 1, 'tb_member', 'dock, desk'.$desk, 'and tbid = '.$_SESSION['member']['id']);
					$dock_arr = explode(',', $rs['dock']);
					$desk_arr = explode(',', $rs['desk'.$desk]);
					unset($desk_arr[$from]);
					if($dock_arr[0] == ''){
						$dock_arr[0] = $id;
					}else{
						array_splice($dock_arr, $to, 0, $id);						
					}
					if(count($dock_arr) > 7){
						$desk_arr[] = $dock_arr[7];
						unset($dock_arr[7]);
					}
					$db->update(0, 0, 'tb_member', 'dock = "'.implode(',', $dock_arr).'", desk'.$desk.' = "'.implode(',', $desk_arr).'"', 'and tbid = '.$_SESSION['member']['id']);
					break;
				case 'desk-desk':
					$rs = $db->select(0, 1, 'tb_member', 'desk'.$desk, 'and tbid = '.$_SESSION['member']['id']);
					$desk_arr = explode(',', $rs['desk'.$desk]);
					//判断传入的应用id和数据库里的id是否吻合
					if($desk_arr[$from] == $id){
						if($from > $to){
							for($i = $from; $i > $to; $i--){
								$desk_arr[$i] = $desk_arr[$i-1];
							}
							$desk_arr[$to] = $id;
						}else if($to > $from){
							for($i = $from; $i < $to; $i++){
								$desk_arr[$i] = $desk_arr[$i+1];
							}
							$desk_arr[$to] = $id;
						}
						$desk_arr = formatAppidArray($desk_arr);
						$db->update(0, 0, 'tb_member', 'desk'.$desk.' = "'.implode(',', $desk_arr).'"', 'and tbid = '.$_SESSION['member']['id']);
					}
					break;
				case 'desk-otherdesk':
					$rs = $db->select(0, 1, 'tb_member', 'desk'.$desk.', desk'.$otherdesk, 'and tbid = '.$_SESSION['member']['id']);
					$desk_arr = explode(',', $rs['desk'.$desk]);
					$otherdesk_arr = explode(',', $rs['desk'.$otherdesk]);
					unset($desk_arr[$from]);
					if($otherdesk_arr[0] == ''){
						$otherdesk_arr[0] = $id;
					}else{
						array_splice($otherdesk_arr, $to, 0, $id);
					}
					$db->update(0, 0, 'tb_member', 'desk'.$desk.' = "'.implode(',', $desk_arr).'", desk'.$otherdesk.' = "'.implode(',', $otherdesk_arr).'"', 'and tbid = '.$_SESSION['member']['id']);
					break;
				case 'folder-folder':
					$db->update(0, 0, 'tb_member_app', 'folder_id = '.$to, 'and tbid = '.$id.' and member_id = '.$_SESSION['member']['id']);
					break;
				case 'folder-dock':
					$rs = $db->select(0, 1, 'tb_member', 'dock, desk'.$desk, 'and tbid = '.$_SESSION['member']['id']);
					$dock_arr = explode(',', $rs['dock']);
					$desk_arr = explode(',', $rs['desk'.$desk]);
					if($dock_arr[0] == ''){
						$dock_arr[0] = $id;
					}else{
						array_splice($dock_arr, $to, 0, $id);
					}
					if(count($dock_arr) > 7){
						$desk_arr[] = $dock_arr[7];
						unset($dock_arr[7]);
					}
					$db->update(0, 0, 'tb_member', 'dock = "'.implode(',', $dock_arr).'", desk'.$desk.' = "'.implode(',', $desk_arr).'"', 'and tbid = '.$_SESSION['member']['id']);
					$db->update(0, 0, 'tb_member_app', 'folder_id = 0', 'and tbid = '.$id.' and member_id = '.$_SESSION['member']['id']);
					break;
				case 'folder-desk':
					$rs = $db->select(0, 1, 'tb_member', 'desk'.$desk, 'and tbid = '.$_SESSION['member']['id']);
					$desk_arr = explode(',', $rs['desk'.$desk]);
					if($desk_arr[0] == ''){
						$desk_arr[0] = $id;
					}else{
						array_splice($desk_arr, $to, 0, $id);
					}
					$db->update(0, 0, 'tb_member', 'desk'.$desk.' = "'.implode(',', $desk_arr).'"', 'and tbid = '.$_SESSION['member']['id']);
					$db->update(0, 0, 'tb_member_app', 'folder_id = 0', 'and tbid='.$id.' and member_id = '.$_SESSION['member']['id']);
					break;
			}
			break;
		//新建文件夹
		case 'addFolder':
			addApp(array(
				'type' => 'folder',
				'icon' => $icon,
				'name' => $name,
				'desk' => $desk
			));
			break;
		//文件夹重命名
		case 'updateFolder':
			$db->update(0, 0, 'tb_member_app', 'icon = "'.$icon.'", name = "'.$name.'"', 'and tbid = '.$id.' and member_id = '.$_SESSION['member']['id']);
			break;
		//获得应用评分
		case 'getAppStar':
			$rs = $db->select(0, 1, 'tb_app', 'starnum', 'and tbid = '.$id);
			echo $rs['starnum'];
			break;
		//更新应用评分
		case 'updateAppStar':
			$isscore = $db->select(0, 2, 'tb_app_star', 'tbid', 'and app_id = '.$id.' and member_id = '.$_SESSION['member']['id']);
			if($isscore == 0){
				$set = array(
					'app_id = '.$id,
					'member_id = '.$_SESSION['member']['id'],
					'starnum = '.$starnum,
					'dt = now()'
				);
				$db->insert(0, 0, 'tb_app_star', $set);
				$scoreavg = $db->select(0, 1, 'tb_app_star', 'avg(starnum) as starnum', 'and app_id = '.$id);
				$db->update(0, 0, 'tb_app', 'starnum = "'.$scoreavg['starnum'].'"', 'and tbid = '.$id);
				echo true;
			}else{
				echo false;
			}
			break;
		case 'html5upload':
			$r = new stdClass();
			//文件名转码，防止中文出现乱码，最后输出时再转回来
			$file_array = explode('.', iconv('UTF-8', 'gb2312', $_FILES['xfile']['name']));
			//取出扩展名
			$extension = $file_array[count($file_array) - 1];
			unset($file_array[count($file_array) - 1]);
			//取出文件名
			$name = implode('.', $file_array);
			//拼装新文件名（含扩展名）
			$file = $name.'_'.sha1(@microtime().$_FILES['xfile']['name']).'.'.$extension;
			//验证文件是否合格
			if(in_array($extension, $uploadFileUnType)){
				$r->error = "上传文件类型系统不支持";
			}else if($_FILES['xfile']['size'] > ($uploadFileMaxSize * 1048576)){
				$r->error = "上传文件单个大小不能超过 $uploadFileMaxSize MB";
			}else{
				$icon = '';
				foreach($uploadFileType as $uft){
					if($uft['ext'] == $extension){
						$icon = $uft['icon'];
						break;
					}
				}
				if($icon == ''){
					$icon = 'img/ui/file_unknow.png';
				}
				//生成文件存放路径
				$dir = 'uploads/member/'.$_SESSION['member']['id'].'/file/';
				if(!is_dir($dir)){
					//循环创建目录
					recursive_mkdir($dir);
				}
				//上传
				move_uploaded_file($_FILES['xfile']["tmp_name"], $dir.$file);
				
				$r->dir = $dir;
				$r->file = iconv('gb2312', 'UTF-8', $file);
				$r->name = iconv('gb2312', 'UTF-8', $name);
				$r->extension = iconv('gb2312', 'UTF-8', $extension);
				$r->icon = $icon;
			}
			echo json_encode($r);
			break;
	}
?>