<?php
	require('../../global.php');
	
	//验证是否登入
	if(!checkLogin()){
		redirect('../error.php?code='.$errorcode['noLogin']);
	}
		
	$dock = $db->select(0, 1, 'tb_member', 'dockpos', 'and tbid='.$_SESSION['member']['id']);
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>桌面设置</title>
<?php include('sysapp/global_css.php'); ?>
<link rel="stylesheet" href="../../img/ui/sys.css">
</head>

<body>
	<div class="title">应用码头位置</div>
	<div class="dock_setting">
		<table>
			<tr>
				<td colspan="3">
					<div class="set_top"><label class="radio"><input type="radio" name="dockpos" value="top" <?php if($dock['dockpos'] == 'top'){echo 'checked';} ?>>顶部</label></div>
				</td>
			</tr>
			<tr>
				<td width="75">
					<div class="set_left"><label class="radio"><input type="radio" name="dockpos" value="left" <?php if($dock['dockpos'] == 'left'){echo 'checked';} ?>>左部</label></div>
				</td>
				<td class="set_view set_view_{$dock}"></td>
				<td width="75">
					<div class="set_right"><label class="radio"><input type="radio" name="dockpos" value="right" <?php if($dock['dockpos'] == 'right'){echo 'checked';} ?>>右部</label></div>
				</td>
			</tr>
		</table>
	</div>
<?php include('sysapp/global_js.php'); ?>
<script>
$(function(){
	$('input[name="dockpos"]').change(function(){
		var pos = $('input[name="dockpos"]:checked').val();
		$('.set_view').removeClass('set_view_top').removeClass('set_view_left').removeClass('set_view_right');
		$('.set_view').addClass('set_view_'+pos);
		window.parent.HROS.dock.updatePos(pos,function(){
			//更新码头位置
			window.parent.HROS.dock.setPos();
			//更新图标位置
			window.parent.HROS.deskTop.appresize();
			//更新滚动条
			window.parent.HROS.app.getScrollbar();
		});
	});
});
</script>
</body>
</html>