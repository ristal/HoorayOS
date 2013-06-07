<?php
	require('../../global.php');
	
	//验证是否登入
	if(!checkLogin()){
		redirect('../error.php?code='.$errorcode['noLogin']);
	}
	$avatar = '../../'.getAvatar(session('member_id'), 'l');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>修改头像</title>
<?php include('sysapp/global_css.php'); ?>
<link rel="stylesheet" href="../../img/ui/sys.css">
<script> 
function avatar_success(){
	window.parent.HROS.navbar.getAvatar();
	alert('头像保存成功');
	location.reload();
}
</script>
</head>

<body>
<div class="title">
	<ul>
		<li><a href="index.php">基本信息</a></li>
		<li class="focus">修改头像</li>
		<li><a href="bind.php">社区绑定</a></li>
		<li><a href="security.php">账号安全</a></li>
	</ul>
</div>
<div style="width:530px;margin:0 auto">
	<embed src="../../libs/avatar_face/face.swf" quality="high" wmode="opaque" FlashVars="defaultImg=<?php echo $avatar; ?>?id=<?php echo getRandStr(10); ?>" pluginspage="http://www.adobe.com/shockwave/download/download.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" width="530" height="480"></embed>
</div>
<?php include('sysapp/global_js.php'); ?>
</body>
</html>