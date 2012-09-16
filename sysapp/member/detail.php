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
	
	if(isset($memberid)){
		$member = $db->select(0, 1, 'tb_member', '*', 'and tbid = '.$memberid);
	}
	$permission = $db->select(0, 0, 'tb_permission', 'tbid,name');
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>用户管理</title>
<?php include('sysapp/global_css.php'); ?>
<link rel="stylesheet" href="../../img/ui/sys.css">
</head>

<body>
<form action="detail.ajax.php" method="post" name="form" id="form">
<input type="hidden" name="ac" value="edit">
<input type="hidden" name="id" value="{$member.tbid}">
<div class="creatbox">
	<div class="middle">
		<p class="detile-title">
			<strong>编辑用户</strong>
		</p>
		<div class="input-label">
			<label class="label-text">用户名：</label>
			<div class="label-box form-inline">
				<?php
					if(isset($memberid)){
						echo $member['username'];
					}else{
				?>
				<input type="text" name="val_username">
				<?php } ?>
			</div>
		</div>
		<div class="input-label">
			<label class="label-text">用户密码：</label>
			<div class="label-box form-inline">
				<input type="text" name="val_password">
				<?php if(isset($memberid)){ ?>
				<span class="help-inline">（如果无需修改则不填）</span>
				<?php } ?>
			</div>
		</div>
		<div class="input-label">
			<label class="label-text">用户类型：</label>
			<div class="label-box form-inline">
				<label class="radio inline"><input type="radio" name="val_type" value="0" <?php if($member['type'] == 0 || !isset($memberid)){echo 'checked';} ?>>普通会员</label>
				<label class="radio inline"><input type="radio" name="val_type" value="1" <?php if($member['type'] == 1){echo 'checked';} ?>>管理员</label>
			</div>
		</div>
		<div class="input-label input-label-permission <?php if($member['type'] == 0){echo 'hide';} ?>">
			<label class="label-text">用户权限：</label>
			<div class="label-box form-inline">
				<?php
					foreach($permission as $v){
						echo '<label class="checkbox inline"><input type="checkbox" name="val_permission_id" value="'.$v['tbid'].'" ';
						if($member['permission_id'] == $v['tbid']){
							echo 'checked';
						}
						echo '>'.$v['name'].'</label>';
					}
				?>
				<span class="help-inline">[<a href="javascript:;" rel="tooltip" title="权限最多只能选一项">?</a>]</span>
			</div>
		</div>
	</div>
</div>
<div class="bottom-bar">
	<div class="con">
		<a class="btn btn-large btn-primary fr" menu="submit" href="javascript:;"><i class="icon-white icon-ok"></i> 确定</a>
		<a class="btn btn-large" menu="back" href="index.php"><i class="icon-arrow-left"></i> 返回用户列表</a>
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
	$('input[name="val_type"]').change(function(){
		if($(this).val() == 1){
			$('.input-label-permission').slideDown();
		}else{
			$('.input-label-permission').slideUp();
		}
	});
	checkboxMax();
	$('input[name="val_permission_id"]').change(function(){
		checkboxMax();
	});
	//提交
	$('a[menu=submit]').click(function(){
		$('#form').submit();
	});
});
function checkboxMax(){
	if($('input[name="val_permission_id"]').filter(':checked').length >= 1){
		$('input[name="val_permission_id"]').not(':checked').each(function(){
			$(this).attr('disabled',true);
		});
	}else{
		$('input[name="val_permission_id"]').not(':checked').each(function(){
			$(this).attr('disabled',false);
		});
	}
}
function showRequest(formData, jqForm, options){
	//alert('About to submit: \n\n' + $.param(formData));
	return true;
}
function showResponse(responseText, statusText, xhr, $form){
	//alert('status: ' + statusText + '\n\nresponseText: \n' + responseText + '\n\nThe output div should have already been updated with the responseText.');
	if($('input[name="value_1"]').val() != ''){
		if(responseText == ''){
			art.dialog({
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
</script>
</body>
</html>