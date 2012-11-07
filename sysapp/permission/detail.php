<?php
	require('../../global.php');
	require('inc/setting.inc.php');
	
	//验证是否登入
	if(!checkLogin()){
		header('Location: ../error.php?code='.$errorcode['noLogin']);
	}
	//验证是否为管理员
	else if(!checkAdmin()){
		header('Location: ../error.php?code='.$errorcode['noAdmin']);
	}
	//验证是否有权限
	else if(!checkPermissions(1)){
		header('Location: ../error.php?code='.$errorcode['noPermissions']);
	}
	
	if(isset($permissionid)){
		$permission = $db->select(0, 1, 'tb_permission', '*', 'and tbid = '.$permissionid);
		if($permission['apps_id'] != ''){
			$appsrs = $db->select(0, 0, 'tb_app', 'tbid,name,icon', 'and tbid in ('.$permission['apps_id'].')');
			$permission['appsinfo'] = $appsrs;
		}
	}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>权限管理</title>
<?php include('sysapp/global_css.php'); ?>
<link rel="stylesheet" href="../../img/ui/sys.css">
</head>

<body>
<form action="detail.ajax.php" method="post" name="form" id="form">
<input type="hidden" name="ac" value="edit">
<input type="hidden" name="id" value="<?php echo $permissionid; ?>">
<div class="creatbox">
	<div class="middle">
		<p class="detile-title">
			<strong>编辑权限</strong>
		</p>
		<div class="input-label">
			<label class="label-text">权限名称：</label>
			<div class="label-box">
				<?php
					if(isset($permissionid)){
						echo $permission['name'];
					}else{
				?>
				<input type="text" name="val_name">
				<?php } ?>
			</div>
		</div>
		<div class="input-label">
			<label class="label-text">专属应用：</label>
			<div class="label-box">
				<div class="permissions_apps">
					<?php
						if($permission['appsinfo'] != NULL){
							foreach($permission['appsinfo'] as $v){
								echo '<div class="app" appid="'.$v['tbid'].'">';
									echo '<img src="../../'.$v['icon'].'" alt="'.$v['name'].'" title="'.$v['name'].'">';
									echo '<span class="del">删</span>';
								echo '</div>';
							}
						}
					?>
				</div>
				<a class="btn btn-mini" href="javascript:;" menu="addapps">添加应用</a>
				<input type="hidden" name="val_apps_id" id="val_apps_id" value="<?php $permission['apps_id']; ?>">
			</div>
		</div>
	</div>
</div>
<div class="bottom-bar">
	<div class="con">
		<a class="btn btn-large btn-primary fr" menu="submit" href="javascript:;"><i class="icon-white icon-ok"></i> 确定</a>
		<a class="btn btn-large" menu="back" href="index.php"><i class="icon-arrow-left"></i> 返回权限列表</a>
	</div>
</div>
</form>
<?php include('sysapp/global_js.php'); ?>
<script>
$().ready(function(){
	//初始化ajaxForm
	var options = {
		beforeSubmit : showRequest,
		success : showResponse,
		type : 'POST'
	};
	$('#form').ajaxForm(options);
	//提交
	$('a[menu=submit]').click(function(){
		$('#form').submit();
	});
	//添加应用
	$('a[menu=addapps]').click(function(){
		$.dialog.data('appsid', $('#val_apps_id').val());
		$.dialog.open('sysapp/permission/alert_addapps.php', {
			id : 'alert_addapps',
			title : '添加应用',
			resize: false,
			width : 350,
			height : 300,
			ok : function(){
				$('#val_apps_id').val($.dialog.data('appsid'));
				updateApps($.dialog.data('appsid'));
			},
			cancel : true
		});
	});
	//删除应用
	$('.permissions_apps').on('click','.app .del',function(){
		var appid = $(this).parent().attr('appid');
		var appsid = $('#val_apps_id').val().split(',');
		var newappsid = [];
		for(var i=0, j=0; i<appsid.length; i++){
			if(appsid[i] != appid){
				newappsid[j] = appsid[i];
				j++;
			}
		}
		$('#val_apps_id').val(newappsid.join(','));
		$(this).parent().remove();
	});
});
function showRequest(formData, jqForm, options){
	//alert('About to submit: \n\n' + $.param(formData));
	return true;
}
function showResponse(responseText, statusText, xhr, $form){
	//alert('status: ' + statusText + '\n\nresponseText: \n' + responseText + '\n\nThe output div should have already been updated with the responseText.');
	if($('input[name="value_1"]').val() != ''){
		if(responseText == ''){
			$.dialog({
				id : 'ajaxedit',
				content : '修改成功',
				ok : function(){
					$.dialog.list['ajaxedit'].close();
				}
			});
		}
	}else{
		if(responseText == ''){
			art.dialog({
				id : 'ajaxedit',
				content : '添加成功',
				ok : function(){
					$.dialog.list['ajaxedit'].close();
				}
			});
		}
	}
}
function updateApps(appsid){
	$.ajax({
		type : 'POST',
		url : 'detail.ajax.php',
		data : 'ac=updateApps&appsid=' + appsid,
		success : function(msg){
			$('.permissions_apps').html(msg);
		}
	});
}
</script>
</body>
</html>