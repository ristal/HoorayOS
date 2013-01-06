<?php
	require('global.php');
	require('inc/setting.inc.php');
	
	if(checkLogin()){
		header('Location:index.php');
	}else{
		$setting = $db->select(0, 1, 'tb_setting');
	}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $setting['title']; ?></title>
<meta name="description" content="<?php echo $setting['description']; ?>" />
<meta name="keywords" content="<?php echo $setting['keywords']; ?>" />
<link rel="stylesheet" href="img/ui/index.css">
</head>

<body>
<div class="loginmask"></div>
<div class="loading"></div>
<div class="login">
	<div class="loginbox">
		<div class="top">
			HoorayOS 桌面
		</div>
		<form action="ajax.php" method="post" id="login_form">
		<input type="hidden" name="ac" value="login">
		<div class="middle"> 
			<div class="left">
				<img src="img/ui/avatar_120.jpg" id="avatar">
			</div>
			<div class="right">
				<div class="input_box username">
					<input type="input" name="username" id="username" autocomplete="off" placeholder="请输入用户名" tabindex="1" datatype="*" nullmsg="请您输入用户名后再登录">
					<a href="javascript:;" class="down" id="dropdown_btn"></a>
					<div class="tip">
						<div class="text">
							<span class="arrow">◆</span>
							<span class="arrow arrow1">◆</span>
							<p></p>
						</div>
					</div> 
					<button type="button" id="regiter_btn">注册账号</button>
					<div class="dropdown" id="dropdown_list"></div>
				</div>
				<div class="input_box password">
					<input type="password" name="password" id="password" placeholder="请输入密码" tabindex="2" datatype="*" nullmsg="请您输入密码后再登录">
					<div class="tip">
						<div class="text">
							<span class="arrow">◆</span>
							<span class="arrow arrow1">◆</span>
							<p></p>
						</div>
					</div> 
					<button type="button" id="find_btn">找回密码</button>
					<label><input type="checkbox" name="rememberPswd" id="rememberPswd">记住密码</label>
					<label style="left:100px"><input type="checkbox" name="autoLogin" id="autoLogin">自动登录</label>
				</div>
			</div>
		</div>
		<div class="bottom">
			<button type="submit" id="submit_btn" tabindex="3">登　　录</button>
		</div>
		</form>
	</div>
</div>
<script src="js/jquery-1.8.3.min.js"></script>
<script src="js/HoorayLibs/hooraylibs.js"></script>
<script src="js/Validform_v5.2.1/Validform_v5.2.1_min.js"></script>
<script>
$(function(){
	//IE6升级提示
	if($.browser.msie && $.browser.version < 8){
		if($.browser.version < 7){
			DD_belatedPNG.fix('.update_browser .browser');
		}
		$('.login').html('<div class="update_browser">'+
			'<div class="subtitle">您正在使用的IE浏览器版本过低，<br>我们建议您升级或者更换浏览器，以便体验顺畅、兼容、安全的互联网。</div>'+
			'<div class="title">选择一款<span>新</span>浏览器吧</div>'+
			'<div class="browser">'+
				'<a href="http://windows.microsoft.com/zh-CN/internet-explorer/downloads/ie" class="ie" target="_blank" title="ie浏览器">ie浏览器</a>'+
				'<a href="http://www.google.cn/chrome/intl/zh-CN/landing_chrome.html" class="chrome" target="_blank" title="谷歌浏览器">谷歌浏览器</a>'+
				'<a href="http://www.firefox.com.cn" class="firefox" target="_blank" title="火狐浏览器">火狐浏览器</a>'+
				'<a href="http://www.opera.com" class="opera" target="_blank" title="opera浏览器">opera浏览器</a>'+
				'<a href="http://www.apple.com.cn/safari" class="safari" target="_blank" title="safari浏览器">safari浏览器</a>'+
			'</div>'+
			'<div class="bottomtitle">[&nbsp;<a href="http://www.theie6countdown.cn" target="_blank">对IE6说再见</a>&nbsp;]</div>'+
		'</div>');
	}
	$('#regiter_btn').click(function(){
		alert('暂时还没有哦~');
//		if($('#reg_1').val()!="" && $('#reg_2').val()!="" && $('#reg_3').val()!=""){
//			if($('#reg_2').val() != $('#reg_3').val()){
//				$('.reg .submit').show();
//				$('.reg .check').hide();
//				$('.reg .tip').text('确认密码不正确').show();
//			}else{
//				var username = $('#reg_1').val();
//				$('.reg .submit').hide();
//				$('.reg .check').show();
//				$('.reg .tip').text('').hide();
//				$.ajax({
//					type:'POST',
//					url:'ajax.php',
//					data:'ac=reg&value_1='+$('#reg_1').val()+'&value_2='+$('#reg_2').val(),
//					success:function(msg){
//						$('.reg .submit').show();
//						$('.reg .check').hide();
//						if(msg){
//							$('.log .tip').text('恭喜你，注册成功').show();
//							$('#value_1').val(username).focus().blur();
//							$('#value_2').focus();
//							$('.log').fadeIn().removeClass('disn');
//							$('.reg').fadeOut().addClass('disn');
//						}else{
//							$('.reg .tip').text('用户名已存在，请更换').show();
//							$('#reg_1').val('').focus();
//						}
//					}
//				});
//			}
//		}
	});
	$('#find_btn').click(function(){
		alert('暂时还没有哦~');
	});
	var dropdownReset = function(){
		$('#dropdown_btn').removeClass('checked');
		$('#dropdown_list').fadeOut();
	}
	$(document).click(function(){
		dropdownReset();
	});
	$('#dropdown_btn').click(function(){
		$(this).addClass('checked');
		$('#dropdown_list').fadeIn();
		return false;
	});
	$('#rememberPswd').click(function(){
		if($(this).attr('checked') !== 'checked'){
			$('#autoLogin').attr('checked', false);
		}
	});
	$('#autoLogin').click(function(){
		if($(this).attr('checked') === 'checked'){
			$('#rememberPswd').attr('checked', true);
		}
	});
	//下拉列表选择用户
	$('#dropdown_list').on('click', '.user', function(){
		var id = $(this).attr('data-id');
		if($.cookie('userlist') != null){
			var userlist = eval("(" + $.cookie('userlist') + ")"), len = userlist.length;
			for(var i = 0; i < len; i++){
				if(userlist[i].id == id){
					$('#avatar').attr('src', userlist[i].avatar);
					$('#username').val(userlist[i].username);
					$('#password').val(userlist[i].password);
					$('#rememberPswd').attr('checked', userlist[i].rememberPswd ? true : false);
					$('#autoLogin').attr('checked', userlist[i].autoLogin ? true : false);
					break;
				}
			}
		}
	});
	//下拉列表删除用户
	$('#dropdown_list').on('click', '.del', function(){
		var id = $(this).parents('.user').attr('data-id');
		if($.cookie('userlist') != null){
			var userlist = eval("(" + $.cookie('userlist') + ")"), len = userlist.length, json = [];
			for(var i = 0; i < len; i++){
				if(userlist[i].id != id){
					json.push("{'id':'" + userlist[i].id + "','username':'" + userlist[i].username + "','password':'" + userlist[i].password + "','rememberPswd':'" + userlist[i].rememberPswd + "','autoLogin':'" + userlist[i].autoLogin + "','avatar':'" + userlist[i].avatar + "'}");
				}
			}
			if(json == ''){
				$.cookie('userlist', null);
			}else{
				$.cookie('userlist', '[' + json.join(',') + ']', {expires : 365});
			}
		}
		if($.cookie('userlist') == null){
			$('#dropdown_btn').hide();
			$('#dropdown_list').hide();
		}
		$(this).parents('.user').remove();
		return false;
	});
	//表单登录初始化
	var loginForm = $('#login_form').Validform({
		btnSubmit: '#submit_btn',
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
		callback: function(data){
			if(data.status == 'y'){
				alert('登录成功');
			}else{
				alert('登录失败');
			}
		}
	});
	//初始化登录用户列表
	if($.cookie('userlist') != null){
		$('#dropdown_btn').show();
		var userTemp = template(
			'<div class="user" data-id="<%=id%>">'+
				'<img src="<%=avatar%>" class="avatar">'+
				'<div class="info">'+
					'<p><%=username%></p>'+
					'<p class="realname">19900905</p>'+
					'<a href="javascript:;" class="del">×</a>'+
				'</div>'+
			'</div>'
		);
		var userlist = eval("(" + $.cookie('userlist') + ")"), len = userlist.length, dropdown = '';
		for(var i = 0; i < len; i++){
			dropdown += userTemp({
				'id' : userlist[i]['id'],
				'avatar' : userlist[i]['avatar'],
				'username' : userlist[i]['username']
			});
		}
		$('#dropdown_list').append(dropdown);
		//将列表里第一个用户信息放入登录界面中
		$('#avatar').attr('src', userlist[0].avatar);
		$('#username').val(userlist[0].username);
		$('#password').val(userlist[0].password);
		$('#rememberPswd').attr('checked', userlist[0].rememberPswd ? true : false);
		$('#autoLogin').attr('checked', userlist[0].autoLogin ? true : false);
		//如果符合自动登录条件，则进行登录
		if(userlist[0].autoLogin && $.cookie('autoLogin')){
			loginForm.submitForm();
		}
	}
	$('.loading').fadeOut(750, function(){
		$('.login').fadeIn(750);
	});
});
</script>
</body>
</html>