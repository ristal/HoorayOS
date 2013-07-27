<?php
	require('../../global.php');
	
	//验证是否登入
	if(!checkLogin()){
		redirect('../error.php?code='.$errorcode['noLogin']);
	}
		
	$pos = getDockPos();
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
<div class="title">应用码头设置</div>
<div class="dock_setting">
	<table>
		<tr>
			<td colspan="3">
				<div class="set_top"><label class="radio"><input type="radio" name="dockpos" value="top" <?php if($pos == 'top'){echo 'checked';} ?>>顶部</label></div>
			</td>
		</tr>
		<tr>
			<td width="75">
				<div class="set_left"><label class="radio"><input type="radio" name="dockpos" value="left" <?php if($pos == 'left'){echo 'checked';} ?>>左侧</label></div>
			</td>
			<td class="set_view set_view_<?php echo $pos; ?>"></td>
			<td width="75">
				<div class="set_right"><label class="radio"><input type="radio" name="dockpos" value="right" <?php if($pos == 'right'){echo 'checked';} ?>>右侧</label></div>
			</td>
		</tr>
		<tr>
			<td colspan="3">
				<div class="set_none"><label class="radio"><input type="radio" name="dockpos" value="none" <?php if($pos == 'none'){echo 'checked';} ?>>停用并隐藏（如果应用码头存在应用，则会将应用转移到当前桌面）</label></div>
			</td>
		</tr>
	</table>
</div>
<?php include('sysapp/global_js.php'); ?>
<script>
$(function(){
	$('input[name="dockpos"]').change(function(){
		var pos = $('input[name="dockpos"]:checked').val();
		$('.set_view').removeClass('set_view_top set_view_left set_view_right set_view_none');
		$('.set_view').addClass('set_view_' + pos);
		window.parent.HROS.dock.updatePos(pos);
	});
});
</script>
</body>
</html>