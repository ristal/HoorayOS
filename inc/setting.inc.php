<?php
require('inc/function.inc.php');
require('inc/db.class.php');
require('inc/db.config.php');

error_reporting( E_ERROR | E_WARNING );
if(PHP_VERSION < 6) {
	set_magic_quotes_runtime(0);
}

ob_start();
session_start();
header("Content-type: text/html; charset=utf-8");

$db = new HRDB($db_hoorayos_config);

$_GET		= daddslashes($_GET, 1, TRUE);
$_POST		= daddslashes($_POST, 1, TRUE);
$_COOKIE	= daddslashes($_COOKIE, 1, TRUE);
$_SERVER	= daddslashes($_SERVER);
$_FILES		= daddslashes($_FILES);
$_REQUEST	= daddslashes($_REQUEST, 1, TRUE);

trim(@extract($_COOKIE));
trim(@extract($_REQUEST));
trim(@extract($_POST));
trim(@extract($_GET));

//文件上传大小限制，单位MB
$uploadFileMaxSize = 20;
//允许文件上传类型
$uploadFileType = array(
	array('ext'=>'rar', 'icon'=>'img/ui/file_rar.png'),
	array('ext'=>'zip', 'icon'=>'img/ui/file_zip.png'),
	array('ext'=>'7z', 'icon'=>'img/ui/file_7z.png'),
	array('ext'=>'jpeg', 'icon'=>'img/ui/file_jpeg.png'),
	array('ext'=>'jpg', 'icon'=>'img/ui/file_jpeg.png'),
	array('ext'=>'gif', 'icon'=>'img/ui/file_gif.png'),
	array('ext'=>'bmp', 'icon'=>'img/ui/file_bmp.png'),
	array('ext'=>'png', 'icon'=>'img/ui/file_png.png'),
	array('ext'=>'ico', 'icon'=>'img/ui/file_ico.png'),
	array('ext'=>'doc', 'icon'=>'img/ui/file_word.png'),
	array('ext'=>'docx', 'icon'=>'img/ui/file_word.png'),
	array('ext'=>'xls', 'icon'=>'img/ui/file_excel.png'),
	array('ext'=>'xlsx', 'icon'=>'img/ui/file_excel.png'),
	array('ext'=>'ppt', 'icon'=>'img/ui/file_ppt.png'),
	array('ext'=>'pptx', 'icon'=>'img/ui/file_ppt.png'),
	array('ext'=>'pdf', 'icon'=>'img/ui/file_pdf.png'),
	array('ext'=>'wma', 'icon'=>'img/ui/file_wma.png'),
	array('ext'=>'mp3', 'icon'=>'img/ui/file_mp3.png'),
	array('ext'=>'txt', 'icon'=>'img/ui/file_txt.png'),
	array('ext'=>'js', 'icon'=>'img/ui/file_txt.png'),
	array('ext'=>'css', 'icon'=>'img/ui/file_txt.png'),
	array('ext'=>'html', 'icon'=>'img/ui/file_txt.png')
);
//应用分类
$apptype = array(
	array('id'=>1, 'name'=>'系统'),
	array('id'=>2, 'name'=>'游戏'),
	array('id'=>3, 'name'=>'影音'),
	array('id'=>4, 'name'=>'图书'),
	array('id'=>5, 'name'=>'生活'),
	array('id'=>6, 'name'=>'工具')
);
//错误代码
$errorcode = array(
	'noLogin'=>'1000',
	'noAdmin'=>'1001',
	'noPermissions'=>'1002'
);
?>