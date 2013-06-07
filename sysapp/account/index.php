<?php
	require('../../global.php');
	
	//验证是否登入
	if(!checkLogin()){
		redirect('../error.php?code='.$errorcode['noLogin']);
	}
	$member = $db->select(0, 1, 'tb_member', '*', 'and tbid = '.session('member_id'));
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>基本信息</title>
<?php include('sysapp/global_css.php'); ?>
<link rel="stylesheet" href="../../img/ui/sys.css">
</head>

<body>
<div class="title">
	<ul>
		<li class="focus">基本信息</li>
		<li><a href="avatar.php">修改头像</a></li>
		<li><a href="bind.php">社区绑定</a></li>
		<li><a href="security.php">账号安全</a></li>
	</ul>
</div>
<div class="input-label">
	<label class="label-text">用户名：</label>
	<div class="label-box form-inline"><?=$member['username']?></div>
</div>
<div class="input-label">
	<label class="label-text">注册时间：</label>
	<div class="label-box form-inline"><?=$member['regdt']?></div>
</div>
<div class="input-label">
	<label class="label-text">最近一次登录时间：</label>
	<div class="label-box form-inline">
		<?=$member['lastlogindt']?>
		<a href="security.php" class="btn btn-link">如果不是你登录的，请及时修改密码</a>
	</div>
</div>
<div class="input-label">
	<label class="label-text">最近一次登录IP：</label>
	<div class="label-box form-inline"><?=$member['lastloginip']?></div>
</div>
</body>
</html>