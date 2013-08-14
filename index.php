<?php
	require('global.php');
	
	cookie('fromsite', NULL);
	$setting = $db->select(0, 1, 'tb_setting');
	//检查是否登录
	if(!checkLogin()){
		//未登录用户的ID默认为0
		session('member_id', 0);
		cookie('memberID', 0, 3600 * 24 * 7);
		//检查cookie里用户是否存在
		if(cookie('userinfo') != NULL){
			$userinfo = json_decode(stripslashes(cookie('userinfo')), true);
			//检查列表里的第一个用户是否开启自动登录
			if($userinfo['rememberMe'] == 1){
				$sqlwhere = array(
					'username = "'.$userinfo['username'].'"',
					'password = "'.sha1(authcode($userinfo['password'], 'DECODE')).'"'
				);
				$row = $db->select(0, 1, 'tb_member', '*', $sqlwhere);
				//检查登录是否成功
				if(!empty($row)){
					session('member_id', $row['tbid']);
					cookie('memberID', $row['tbid'], time() + 3600 * 24 * 7);
					$db->update(0, 0, 'tb_member', 'lastlogindt = now(), lastloginip = "'.getIp().'"', 'and tbid = '.$row['tbid']);
					$skin = $db->select(0, 1, 'tb_member', 'skin', 'and tbid = '.session('member_id'));
				}
			}
		}
	}
	if(checkLogin()){
		$rs_member = $db->select(0, 1, 'tb_member', 'skin', 'and tbid = '.session('member_id'));
		$skin = $rs_member['skin'];
	}else{
		$skin = 'default';
	}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $setting['title']; ?></title>
<meta name="description" content="<?php echo $setting['description']; ?>" />
<meta name="keywords" content="<?php echo $setting['keywords']; ?>" />
<link rel="stylesheet" href="js/HoorayLibs/hooraylibs.css">
<link rel="stylesheet" href="img/ui/index.css">
<link rel="stylesheet" href="img/skins/<?php echo $skin; ?>.css" id="window-skin">
</head>

<body>
<div class="loading"></div>
<!-- 浏览器升级提示 -->
<div id="update_browser_box">
	<div class="update_browser">
		<div class="subtitle">您正在使用的IE浏览器版本过低，<br>我们建议您升级或者更换浏览器，以便体验顺畅、兼容、安全的互联网。</div>
		<div class="title">选择一款<span>新</span>浏览器吧</div>
		<div class="browser">
			<a href="http://windows.microsoft.com/zh-CN/internet-explorer/downloads/ie" class="ie" target="_blank" title="ie浏览器">ie浏览器</a>
			<a href="http://www.google.cn/chrome/intl/zh-CN/landing_chrome.html" class="chrome" target="_blank" title="谷歌浏览器">谷歌浏览器</a>
			<a href="http://www.firefox.com.cn" class="firefox" target="_blank" title="火狐浏览器">火狐浏览器</a>
			<a href="http://www.opera.com" class="opera" target="_blank" title="opera浏览器">opera浏览器</a>
			<a href="http://www.apple.com.cn/safari" class="safari" target="_blank" title="safari浏览器">safari浏览器</a>
		</div>
		<div class="bottomtitle">[&nbsp;<a href="http://www.baidu.com/search/theie6countdown.html" target="_blank">对IE6说再见</a>&nbsp;]</div>
	</div>
</div>
<!-- 登录 -->
<div id="lrbox" <?php if($setting['isforcedlogin'] == 1 && !checkLogin()){ ?>style="top:0"<?php } ?> data-isforcedlogin="<?php echo $setting['isforcedlogin']; ?>">
	<?php if($setting['isforcedlogin'] == 0){ ?><a href="javascript:;" class="back">取消登录</a><?php } ?>
	<div class="title"><?php echo $setting['title']; ?></div>
	<div class="lrbox">
		<div class="bg"></div>
		<div style="width:1000px">
			<div class="loginbox">
				<form action="login.ajax.php" method="post" id="loginForm">
					<input type="hidden" name="ac" value="login">
					<div class="middle">
						<div class="left">
							<img src="img/ui/avatar_120.jpg" id="avatar">
						</div>
						<div class="right">
							<div class="input_box username">
								<input type="input" name="username" id="username" autocomplete="off" placeholder="请输入用户名" datatype="s6-18" nullmsg="请您输入用户名后再登录" errormsg="用户名长度为6-18个字符">
								<div class="tip">
									<div class="text">
										<span class="arrow">◆</span>
										<span class="arrow arrow1">◆</span>
										<p></p>
									</div>
								</div>
							</div>
							<div class="input_box password">
								<input type="password" name="password" id="password" placeholder="请输入密码" datatype="*6-18" nullmsg="请您输入密码后再登录" errormsg="密码长度在6~18位之间">
								<div class="tip">
									<div class="text">
										<span class="arrow">◆</span>
										<span class="arrow arrow1">◆</span>
										<p></p>
									</div>
								</div> 
							</div>
							<div class="label-box">
								<label><input type="checkbox" name="rememberMe" id="rememberMe">记住我，下次自动登录</label>
							</div>
						</div>
					</div>
					<div class="bottom">
						<button class="login_btn" id="submit_login_btn" type="submit">登　　录</button>
						<button class="register_btn" id="go_register_btn" type="button" tabindex="-1">去注册 <font style="font-size:14px">&raquo;</font></button>
					</div>
				</form>
			</div>
			<div class="registerbox">
				<form action="login.ajax.php" method="post" id="registerForm">
					<input type="hidden" name="ac" value="register">
					<div class="middle"> 
						<div class="right all">
							<div class="input_box username">
								<input type="input" name="reg_username" id="reg_username" autocomplete="off" placeholder="请输入用户名" datatype="s6-18" ajaxurl="login.ajax.php?ac=checkUsername" nullmsg="请输入用户名" errormsg="用户名长度为6-18个字符">
								<div class="tip">
									<div class="text">
										<span class="arrow">◆</span>
										<span class="arrow arrow1">◆</span>
										<p></p>
									</div>
								</div> 
							</div>
							<div class="input_box password">
								<input type="password" name="reg_password" id="reg_password" placeholder="请输入密码" datatype="*6-18" nullmsg="请输入密码" errormsg="密码长度在6~18位之间">
								<div class="tip">
									<div class="text">
										<span class="arrow">◆</span>
										<span class="arrow arrow1">◆</span>
										<p></p>
									</div>
								</div> 
							</div>
						</div>
					</div>
					<div class="bottom">
						<button class="login_btn" id="go_login_btn" type="button" tabindex="-1"><font style="font-size:14px">&laquo;</font> 去登录</button>
						<button class="register_btn" id="submit_register_btn" type="submit">注　　册</button>
					</div>
				</form>
			</div>
		</div>
		<?php if((SINAWEIBO_AKEY && SINAWEIBO_SKEY) || (TWEIBO_AKEY && TWEIBO_SKEY) || (T163WEIBO_AKEY && T163WEIBO_SKEY) || (RENREN_AID && RENREN_AKEY && RENREN_SKEY) || (BAIDU_AKEY && BAIDU_SKEY)){ ?>
		<div class="disanfangdenglu">
			<label>合作网站帐号登录</label>
			<div class="box">
				<?php if(SINAWEIBO_AKEY && SINAWEIBO_SKEY){ ?>
					<a href="javascript:;" class="sinaweibo" data-type="sinaweibo" title="新浪微博登录"></a>
				<?php } ?>
				<?php if(TWEIBO_AKEY && TWEIBO_SKEY){ ?>
					<a href="javascript:;" class="tweibo" data-type="tweibo" title="腾讯微博登录"></a>
				<?php } ?>
				<?php if(T163WEIBO_AKEY && T163WEIBO_SKEY){ ?>
					<a href="javascript:;" class="t163weibo" data-type="t163weibo" title="网易微博登录"></a>
				<?php } ?>
				<?php if(RENREN_AID && RENREN_AKEY && RENREN_SKEY){ ?>
					<a href="javascript:;" class="renren" data-type="renren" title="人人网登录"></a>
				<?php } ?>
				<?php if(BAIDU_AKEY && BAIDU_SKEY){ ?>
					<a href="javascript:;" class="baidu" data-type="baidu" title="百度登录"></a>
				<?php } ?>
			</div>
		</div>
		<?php } ?>
		<div class="disanfangdenglutip">
			<span></span>帐号登录成功，请绑定你的 HoorayOS 账号。<a href="javascript:;" class="cancel">取消</a>
		</div>
	</div>
</div>
<!-- 桌面 -->
<div id="desktop">
	<div id="zoom-tip"><div><i>​</i>​<span></span></div><a href="javascript:;" class="close" onClick="HROS.zoom.close();">×</a></div>
	<div id="desk">
		<div id="desk-1" class="desktop-container"><div class="scrollbar scrollbar-x"></div><div class="scrollbar scrollbar-y"></div></div>
		<div id="desk-2" class="desktop-container"><div class="scrollbar scrollbar-x"></div><div class="scrollbar scrollbar-y"></div></div>
		<div id="desk-3" class="desktop-container"><div class="scrollbar scrollbar-x"></div><div class="scrollbar scrollbar-y"></div></div>
		<div id="desk-4" class="desktop-container"><div class="scrollbar scrollbar-x"></div><div class="scrollbar scrollbar-y"></div></div>
		<div id="desk-5" class="desktop-container"><div class="scrollbar scrollbar-x"></div><div class="scrollbar scrollbar-y"></div></div>
		<div id="dock-bar">
			<div id="dock-container">
				<div class="dock-middle">
					<div class="dock-applist"></div>
					<div class="dock-toollist">
						<a href="javascript:;" class="dock-tool-setting" title="桌面设置"></a>
						<a href="javascript:;" class="dock-tool-style" title="主题设置"></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="task-bar-bg1"></div>
	<div id="task-bar-bg2"></div>
	<div id="task-bar">
		<div id="task-next"><a href="javascript:;" id="task-next-btn" hidefocus="true"></a></div>
		<div id="task-content">
			<div id="task-content-inner"></div>
		</div>
		<div id="task-pre"><a href="javascript:;" id="task-pre-btn" hidefocus="true"></a></div>
	</div>
	<div id="nav-bar">
		<div class="nav-wrapper">
			<div class="nav-container nav-current-1" id="navContainer">
				<div class="indicator indicator-header" id="navbarHeaderImg"><img src="img/ui/loading_24.gif" class="indicator-header-img"></div>
				<a class="indicator indicator-1" href="javascript:;" index="1" title="桌面1，Ctrl + 1">
					<span class="indicator-icon-bg"></span>
					<span class="indicator-icon indicator-icon-1">1</span>
				</a>
				<a class="indicator indicator-2" href="javascript:;" index="2" title="桌面2，Ctrl + 2">
					<span class="indicator-icon-bg"></span>
					<span class="indicator-icon indicator-icon-2">2</span>
				</a>
				<a class="indicator indicator-3" href="javascript:;" index="3" title="桌面3，Ctrl + 3">
					<span class="indicator-icon-bg"></span>
					<span class="indicator-icon indicator-icon-3">3</span>
				</a>
				<a class="indicator indicator-4" href="javascript:;" index="4" title="桌面4，Ctrl + 4">
					<span class="indicator-icon-bg"></span>
					<span class="indicator-icon indicator-icon-4">4</span>
				</a>
				<a class="indicator indicator-5" href="javascript:;" index="5" title="桌面5，Ctrl + 5">
					<span class="indicator-icon-bg"></span>
					<span class="indicator-icon indicator-icon-5">5</span>
				</a>
				<a class="indicator indicator-search" href="javascript:;" title="搜索，Ctrl + F"></a>
				<a class="indicator indicator-manage" href="javascript:;" title="全局视图，Ctrl + ↑"></a>
			</div>
		</div>
	</div>
	<div id="search-bar">
		<input id="pageletSearchInput" placeholder="搜索应用...">
		<input type="buttom" value="" id="pageletSearchButton" title="搜索...">
	</div>
	<div id="search-suggest">
		<ul class="resultBox"></ul>
		<div class="resultList openAppMarket"><a href="javascript:;"><div>去应用市场搜搜...</div></a></div>
	</div>
</div>
<!-- 全局视图 -->
<div id="appmanage">
	<a class="amg_close" href="javascript:;"></a>
	<div id="amg_dock_container"></div>
	<div class="amg_line_x"></div>
	<div id="amg_folder_container">
		<div class="folderItem">
			<div class="folder_bg folder_bg1"></div>
			<div class="folderOuter">
				<div class="folderInner" desk="1"></div>
				<div class="scrollBar"></div>
			</div>
		</div>
		<div class="folderItem">
			<div class="folder_bg folder_bg2"></div>
			<div class="folderOuter">
				<div class="folderInner" desk="2"></div>
				<div class="scrollBar"></div>
			</div>
			<div class="amg_line_y"></div>
		</div>
		<div class="folderItem">
			<div class="folder_bg folder_bg3"></div>
			<div class="folderOuter">
				<div class="folderInner" desk="3"></div>
				<div class="scrollBar"></div>
			</div>
			<div class="amg_line_y"></div>
		</div>
		<div class="folderItem">
			<div class="folder_bg folder_bg4"></div>
			<div class="folderOuter">
				<div class="folderInner" desk="4"></div>
				<div class="scrollBar"></div>
			</div>
			<div class="amg_line_y"></div>
		</div>
		<div class="folderItem">
			<div class="folder_bg folder_bg5"></div>
			<div class="folderOuter">
				<div class="folderInner" desk="5"></div>
				<div class="scrollBar"></div>
			</div>
			<div class="amg_line_y"></div>
		</div>
	</div>
</div>
<script src="js/jquery-1.8.3.min.js"></script>
<script src="js/HoorayLibs/hooraylibs.js"></script>
<script src="js/Validform_v5.3.2/Validform_v5.3.2_min.js"></script>
<script src="js/sugar/sugar-1.3.9.min.js"></script>
<script src="js/templates.js"></script>
<script src="js/util.js"></script>
<script src="js/core.js"></script>
<script src="js/hros.app.js"></script>
<script src="js/hros.appmanage.js"></script>
<script src="js/hros.base.js"></script>
<script src="js/hros.desktop.js"></script>
<script src="js/hros.dock.js"></script>
<script src="js/hros.folderView.js"></script>
<script src="js/hros.grid.js"></script>
<script src="js/hros.maskBox.js"></script>
<script src="js/hros.navbar.js"></script>
<script src="js/hros.popupMenu.js"></script>
<script src="js/hros.searchbar.js"></script>
<script src="js/hros.taskbar.js"></script>
<script src="js/hros.uploadFile.js"></script>
<script src="js/hros.wallpaper.js"></script>
<script src="js/hros.widget.js"></script>
<script src="js/hros.window.js"></script>
<script src="js/hros.zoom.js"></script>
<script src="js/artDialog4.1.7/jquery.artDialog.js?skin=default"></script>
<script src="js/artDialog4.1.7/plugins/iframeTools.js"></script>
<script>
var childWindow, interval;
$(function(){
	$('#lrbox .lrbox').css('marginTop', ($('.lrbox').height() / 2) * -1).show();
	$('#lrbox .back').on('click', function(){
		$('#lrbox').animate({
			top : '-100%'
		}, 500);
	});
	changeTabindex('login');
	$('#go_register_btn').click(function(){
		$('.loginbox').animate({marginLeft : '-420px'}, 500, function(){
			changeTabindex('register');
		});
	});
	$('#go_login_btn').click(function(){
		$('.loginbox').animate({marginLeft : 0}, 500, function(){
			changeTabindex('login');
		});
	});
	//初始化登录用户
	if($.parseJSON($.cookie('userinfo')) != '' && $.parseJSON($.cookie('userinfo')) != null){
		var userinfo = $.parseJSON($.cookie('userinfo'));
		$('#avatar').attr('src', userinfo.avatar);
		$('#username').val(userinfo.username);
		$('#password').focus();
	}
	//表单登录初始化
	var loginForm = $('#loginForm').Validform({
		btnSubmit: '#submit_login_btn',
		postonce: false,
		showAllError: false,
		tipSweep: true,
		//msg：提示信息;
		//o:{obj:*,type:*,curform:*}, obj指向的是当前验证的表单元素（或表单对象），type指示提示的状态，值为1、2、3、4， 1：正在检测/提交数据，2：通过验证，3：验证失败，4：提示ignore状态, curform为当前form对象;
		//cssctl:内置的提示信息样式控制函数，该函数需传入两个参数：显示提示信息的对象 和 当前提示的状态（既形参o中的type）;
		tiptype: function(msg, o){
			if(!o.obj.is('form')){//验证表单元素时o.obj为该表单元素，全部验证通过提交表单时o.obj为该表单对象;
				var B = o.obj.parent('.input_box').children('.tip');
				var T = B.find('p');
				if(o.type == 2){
					B.hide();
					T.text('');
				}else{
					B.show();
					T.text(msg);
				}
			}
		},
		ajaxPost: true,
		beforeSubmit: function(){
			$('#submit_login_btn').addClass('disabled').prop('disabled', true);
		},
		callback: function(data){
			$('#submit_login_btn').removeClass('disabled').prop('disabled', false);
			if(data.status == 'y'){
				if(!$.browser.msie){
					onbeforeunload = null;
				}
				location.reload();
			}else{
				if(data.info == 'ERROR_OPENID_IS_USED'){
					ZENG.msgbox.show('该账号已经绑定过' + $('.disanfangdenglutip span').text() + '账号，请更换其它账号，或者取消绑定，直接登录', 5, 3000);
				}else{
					ZENG.msgbox.show('登录失败，请检查用户名或密码是否正确', 5, 2000);
				}
			}
		}
	});
	//表单注册初始化
	var registerForm = $('#registerForm').Validform({
		btnSubmit: '#submit_register_btn',
		postonce: true,
		showAllError: false,
		tipSweep: true,
		//msg：提示信息;
		//o:{obj:*,type:*,curform:*}, obj指向的是当前验证的表单元素（或表单对象），type指示提示的状态，值为1、2、3、4， 1：正在检测/提交数据，2：通过验证，3：验证失败，4：提示ignore状态, curform为当前form对象;
		//cssctl:内置的提示信息样式控制函数，该函数需传入两个参数：显示提示信息的对象 和 当前提示的状态（既形参o中的type）;
		tiptype: function(msg, o){
			if(!o.obj.is('form')){//验证表单元素时o.obj为该表单元素，全部验证通过提交表单时o.obj为该表单对象;
				var B = o.obj.parent('.input_box').children('.tip');
				var T = B.find('p');
				if(o.type == 2){
					B.hide();
					T.text('');
				}else{
					B.show();
					T.text(msg);
				}
			}
		},
		ajaxPost: true,
		beforeSubmit: function(){
			$('#submit_register_btn').addClass('disabled').prop('disabled', true);
		},
		callback: function(data){
			$('#submit_register_btn').removeClass('disabled').prop('disabled', false);
			registerForm.resetStatus();
			if(data.status == 'y'){
				$('#go_login_btn').click();
				$('#avatar').attr('src', 'img/ui/avatar_120.jpg');
				$('#username').val(data.info);
				$('#password').val('');
				$('#rememberMe').prop('checked', false);
				$('#reg_username, #reg_password').val('');
			}else{
				ZENG.msgbox.show('注册失败', 5, 2000);
			}
		}
	});
	$('.disanfangdenglu .box a').click(function(){
		checkUserLogin();
		childWindow = window.open('connect/' + $(this).data('type') + '/redirect.php', 'LoginWindow', 'width=850,height=520,menubar=0,scrollbars=1,resizable=1,status=1,titlebar=0,toolbar=0,location=1');
	});
	$('.disanfangdenglutip .cancel').click(function(){
		$.removeCookie('fromsite', {path:'/'});
		$('.disanfangdenglutip').hide();
		$('.disanfangdenglu').show();
	});
});
function changeTabindex(mode){
	$('#username, #password, #submit_login_btn, #reg_username, #reg_password, #submit_register_btn').attr('tabindex', '-1');
	if(mode == 'login'){
		$('#username').attr('tabindex', 1);
		$('#password').attr('tabindex', 2);
		$('#submit_login_btn').attr('tabindex', 3);
		$('#username').focus();
	}else{
		$('#reg_username').attr('tabindex', 1);
		$('#reg_password').attr('tabindex', 2);
		$('#submit_register_btn').attr('tabindex', 3);
		$('#reg_username').focus();
	}
}
function checkUserLogin(){
	$.removeCookie('fromsite', {path:'/'});
	interval = setInterval(function(){
		getLoginCookie(interval);
	}, 500);
}
function getLoginCookie(){
	if($.cookie('fromsite')){
		childWindow.close();
		window.clearInterval(interval);
		//验证该三方登录账号是否已绑定过本地账号，有则直接登录，否则执行绑定账号流程
		$.ajax({
			url:'login.ajax.php',
			data:'ac=3login',
			success: function(msg){
				if(msg == 'ERROR_LACK_OF_DATA'){
					ZENG.msgbox.show('未知错误，建议重启浏览器后重新操作', 1, 2000);
				}else if(msg == 'ERROR_NOT_BIND'){
					var title;
					switch($.cookie('fromsite')){
						case 'sinaweibo': title = '新浪微博'; break;
						case 'tweibo': title = '腾讯微博'; break;
						case 't163weibo': title = '网易微博'; break;
						case 'renren': title = '人人网'; break;
						case 'baidu': title = '百度'; break;
						default: return false;
					}
					$('.disanfangdenglu').hide();
					$('.disanfangdenglutip').show().children('span').text(title);
				}else{
					window.onbeforeunload = null;
					location.reload();
				}
			}
		});
	}
}
</script>
<script>
$(function(){
	//IE下禁止选中
	document.body.onselectstart = document.body.ondrag = function(){return false;}
	//隐藏加载遮罩层
	$('.loading').hide();
	//IE6,7升级提示
	if($.browser.msie && $.browser.version < 8){
		if($.browser.version < 7){
			//虽然不支持IE6，但还是得修复PNG图片透明的问题
			DD_belatedPNG.fix('#update_browser_box .browser');
		}
		$('#update_browser_box').show();
	}else{
		if($('#lrbox').data('isforcedlogin') == 0 || $.cookie('memberID') != 0){
			$('#desktop').show();
			//初始化一些桌面信息
			HROS.CONFIG.sinaweiboAppkey = '<?php echo SINAWEIBO_AKEY; ?>';
			HROS.CONFIG.tweiboAppkey = '<?php echo TWEIBO_AKEY; ?>';
			<?php
				$w = explode('<{|}>', getWallpaper());
			?>
			HROS.CONFIG.wallpaperState = <?php echo $w[0]; ?>;
			<?php
				switch($w[0]){
					case 1:
					case 2:
			?>
			HROS.CONFIG.wallpaper = '<?php echo $w[1]; ?>';
			HROS.CONFIG.wallpaperType = '<?php echo $w[2]; ?>';
			HROS.CONFIG.wallpaperWidth = <?php echo $w[3]; ?>;
			HROS.CONFIG.wallpaperHeight = <?php echo $w[4]; ?>;
			<?php
						break;
					case 3:
			?>
			HROS.CONFIG.wallpaper = '<?php echo $w[1]; ?>';
			<?php
						break;
				}
			?>
			HROS.CONFIG.dockPos = '<?php echo getDockPos(); ?>';
			HROS.CONFIG.appXY = '<?php echo getAppXY(); ?>';
			HROS.CONFIG.appSize = '<?php echo getAppSize(); ?>';
			//加载桌面
			HROS.base.init();
			$.dialog({
				title: '欢迎使用 HoorayOS',
				icon: 'face-smile',
				width: 320,
				content: 'HoorayOS 是否就是你一直想要的 web 桌面么？<br>' + '那么我非常期待您能够热情的提供<font style="color:red"> 50 元</font>或者其他金额的捐赠鼓励，正如您支持其他开源项目一样。<br>' + '支付宝：<a href="https://me.alipay.com/hooray" style="color:#214FA3" target="_blank">https://me.alipay.com/hooray</a><div style="width:100%;height:0px;font-size:0;border-bottom:1px solid #ccc"></div>如果你对本框架感兴趣，欢迎加入讨论群：<br>213804727' + '<div style="width:100%;height:0px;font-size:0;border-bottom:1px solid #ccc"></div>' +
						'HoorayOS 仅供个人学习交流，未经授权禁止用于商业用途，版权归作者所有，未经作者同意，不得删除代码中作者信息。若需要商业使用，请联系 QQ：304327508 进行授权'
			});
		}
	}
});
</script>
</body>
</html>