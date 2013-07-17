<?php
	require('global.php');
	cookie('fromsite', NULL);
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>登录</title>
<link rel="stylesheet" href="img/ui/login.css">
</head>

<body>
<div class="lrbox <?php echo date('G') >= 6 && date('G') < 18 ? 'lrbox_day' : 'lrbox_night'; ?>">
	<div style="width:1000px">
		<div class="loginbox">
			<form action="login.ajax.php" method="post" id="loginForm">
				<input type="hidden" name="ac" value="login">
				<div class="top">登　录</div>
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
				<div class="top">注　册</div>
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
	<?php
		if(
			(SINAWEIBO_AKEY && SINAWEIBO_SKEY) ||
			(TWEIBO_AKEY && TWEIBO_SKEY) ||
			(T163WEIBO_AKEY && T163WEIBO_SKEY) ||
			(RENREN_AID && RENREN_AKEY && RENREN_SKEY) ||
			(BAIDU_AKEY && BAIDU_SKEY)
		){
		?>
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
<script src="js/jquery-1.8.3.min.js"></script>
<script src="js/HoorayLibs/hooraylibs.js"></script>
<script src="js/Validform_v5.3.2/Validform_v5.3.2_min.js"></script>
<script>
var childWindow, int;
$(function(){
	changeTabindex('login');
	$('#go_register_btn').click(function(){
		$('.loginbox').animate({marginLeft: '-410px'}, 500, function(){
			changeTabindex('register');
		});
	});
	$('#go_login_btn').click(function(){
		$('.loginbox').animate({marginLeft: '10px'}, 500, function(){
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
					window.top.onbeforeunload = null;
				}
				window.top.location.reload();
			}else{
				if(data.info == 'ERROR_OPENID_IS_USED'){
					window.top.ZENG.msgbox.show('该账号已经绑定过' + $('.disanfangdenglutip span').text() + '账号，请更换其它账号，或者取消绑定，直接登录', 5, 3000);
				}else{
					window.top.$.dialog.list['logindialog'].shake();
					window.top.ZENG.msgbox.show('登录失败，请检查用户名或密码是否正确', 5, 2000);
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
				window.parent.ZENG.msgbox.show('注册失败', 5, 2000);
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
	int = setInterval(function(){
		getLoginCookie(int);
	}, 500);
}
function getLoginCookie(){
	if($.cookie('fromsite')){
		childWindow.close();
		window.clearInterval(int);
		//验证该三方登录账号是否已绑定过本地账号，有则直接登录，否则执行绑定账号流程
		$.ajax({
			url:'login.ajax.php',
			data:'ac=3login',
			success: function(msg){
				if(msg == 'ERROR_LACK_OF_DATA'){
					window.parent.ZENG.msgbox.show('未知错误，建议重启浏览器后重新操作', 1, 2000);
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
					window.top.window.onbeforeunload = null;
					window.top.location.reload();
				}
			}
		});
	}
}
</script>
</body>
</html>