<?php
	require('../global.php');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>啊，出错了</title>
<link rel="stylesheet" href="../img/ui/globle.css">
<link rel="stylesheet" href="../js/HoorayLibs/hooraylibs.css">
<link rel="stylesheet" href="../img/ui/sys.css">
</head>

<body>
<script src="../js/jquery-1.8.1.min.js"></script>
<script src="../js/HoorayLibs/hooraylibs.js"></script>
<script type="text/javascript">
$(function(){
	<?php if($code == $errorcode['noLogin']){ ?>
		window.top.ZENG.msgbox.show("对不起，您还没有登入！", 1, 2000);
	<?php }elseif($code == $errorcode['noAdmin']){ ?>
		window.top.ZENG.msgbox.show("对不起，您不是管理员！", 1, 2000);
	<?php }elseif($code == $errorcode['noPermissions']){ ?>
		window.top.ZENG.msgbox.show("对不起，您没有权限操作！", 1, 2000);
	<?php } ?>
});
</script>
</body>
</html>