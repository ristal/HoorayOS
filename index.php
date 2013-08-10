<?php
	require('global.php');
	
	$setting = $db->select(0, 1, 'tb_setting');
	//检查是否登录
	if(!checkLogin()){
		//未登录用户的ID默认为0
		session('member_id', 0);
		cookie('memberID', 0, 3600 * 24 * 7);
		//检查cookie里用户是否存在
		if(cookie('userinfo') != NULL){
			$userinfo = json_decode(stripslashes(cookie('userinfo')), true);
			//检查列表里的第一个用户是否开启自动登录
			if($userinfo['rememberMe'] == 1){
				$sqlwhere = array(
					'username = "'.$userinfo['username'].'"',
					'password = "'.sha1(authcode($userinfo['password'], 'DECODE')).'"'
				);
				$row = $db->select(0, 1, 'tb_member', '*', $sqlwhere);
				//检查登录是否成功
				if(!empty($row)){
					session('member_id', $row['tbid']);
					cookie('memberID', $row['tbid'], time() + 3600 * 24 * 7);
					$db->update(0, 0, 'tb_member', 'lastlogindt = now(), lastloginip = "'.getIp().'"', 'and tbid = '.$row['tbid']);
					$skin = $db->select(0, 1, 'tb_member', 'skin', 'and tbid = '.session('member_id'));
				}
			}
		}
	}
	if(checkLogin()){
		$rs_member = $db->select(0, 1, 'tb_member', 'skin', 'and tbid = '.session('member_id'));
		$skin = $rs_member['skin'];
	}else{
		$skin = 'default';
	}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $setting['title']; ?></title>
<meta name="description" content="<?php echo $setting['description']; ?>" />
<meta name="keywords" content="<?php echo $setting['keywords']; ?>" />
<link rel="stylesheet" href="js/HoorayLibs/hooraylibs.css">
<link rel="stylesheet" href="img/ui/index.css">
<link rel="stylesheet" href="img/skins/<?php echo $skin; ?>.css" id="window-skin">
</head>

<body>
<div class="loading"></div>
<!-- 浏览器升级提示 -->
<div class="update_browser_box">
	<div class="update_browser">
		<div class="subtitle">您正在使用的IE浏览器版本过低，<br>我们建议您升级或者更换浏览器，以便体验顺畅、兼容、安全的互联网。</div>
		<div class="title">选择一款<span>新</span>浏览器吧</div>
		<div class="browser">
			<a href="http://windows.microsoft.com/zh-CN/internet-explorer/downloads/ie" class="ie" target="_blank" title="ie浏览器">ie浏览器</a>
			<a href="http://www.google.cn/chrome/intl/zh-CN/landing_chrome.html" class="chrome" target="_blank" title="谷歌浏览器">谷歌浏览器</a>
			<a href="http://www.firefox.com.cn" class="firefox" target="_blank" title="火狐浏览器">火狐浏览器</a>
			<a href="http://www.opera.com" class="opera" target="_blank" title="opera浏览器">opera浏览器</a>
			<a href="http://www.apple.com.cn/safari" class="safari" target="_blank" title="safari浏览器">safari浏览器</a>
		</div>
		<div class="bottomtitle">[&nbsp;<a href="http://www.baidu.com/search/theie6countdown.html" target="_blank">对IE6说再见</a>&nbsp;]</div>
	</div>
</div>
<!-- 桌面 -->
<div id="desktop">
	<div id="zoom-tip"><div><i>​</i>​<span></span></div><a href="javascript:;" class="close" onClick="HROS.zoom.close();">×</a></div>
	<div id="desk">
		<div id="desk-1" class="desktop-container"><div class="scrollbar scrollbar-x"></div><div class="scrollbar scrollbar-y"></div></div>
		<div id="desk-2" class="desktop-container"><div class="scrollbar scrollbar-x"></div><div class="scrollbar scrollbar-y"></div></div>
		<div id="desk-3" class="desktop-container"><div class="scrollbar scrollbar-x"></div><div class="scrollbar scrollbar-y"></div></div>
		<div id="desk-4" class="desktop-container"><div class="scrollbar scrollbar-x"></div><div class="scrollbar scrollbar-y"></div></div>
		<div id="desk-5" class="desktop-container"><div class="scrollbar scrollbar-x"></div><div class="scrollbar scrollbar-y"></div></div>
		<div id="dock-bar">
			<div id="dock-container">
				<div class="dock-middle">
					<div class="dock-applist"></div>
					<div class="dock-toollist">
						<a href="javascript:;" class="dock-tool-setting" title="桌面设置"></a>
						<a href="javascript:;" class="dock-tool-style" title="主题设置"></a>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div id="task-bar-bg1"></div>
	<div id="task-bar-bg2"></div>
	<div id="task-bar">
		<div id="task-next"><a href="javascript:;" id="task-next-btn" hidefocus="true"></a></div>
		<div id="task-content">
			<div id="task-content-inner"></div>
		</div>
		<div id="task-pre"><a href="javascript:;" id="task-pre-btn" hidefocus="true"></a></div>
	</div>
	<div id="nav-bar">
		<div class="nav-wrapper">
			<div class="nav-container nav-current-1" id="navContainer">
				<div class="indicator indicator-header" id="navbarHeaderImg"><img src="img/ui/loading_24.gif" class="indicator-header-img"></div>
				<a class="indicator indicator-1" href="javascript:;" index="1" title="桌面1，Ctrl + 1">
					<span class="indicator-icon-bg"></span>
					<span class="indicator-icon indicator-icon-1">1</span>
				</a>
				<a class="indicator indicator-2" href="javascript:;" index="2" title="桌面2，Ctrl + 2">
					<span class="indicator-icon-bg"></span>
					<span class="indicator-icon indicator-icon-2">2</span>
				</a>
				<a class="indicator indicator-3" href="javascript:;" index="3" title="桌面3，Ctrl + 3">
					<span class="indicator-icon-bg"></span>
					<span class="indicator-icon indicator-icon-3">3</span>
				</a>
				<a class="indicator indicator-4" href="javascript:;" index="4" title="桌面4，Ctrl + 4">
					<span class="indicator-icon-bg"></span>
					<span class="indicator-icon indicator-icon-4">4</span>
				</a>
				<a class="indicator indicator-5" href="javascript:;" index="5" title="桌面5，Ctrl + 5">
					<span class="indicator-icon-bg"></span>
					<span class="indicator-icon indicator-icon-5">5</span>
				</a>
				<a class="indicator indicator-search" href="javascript:;" title="搜索，Ctrl + F"></a>
				<a class="indicator indicator-manage" href="javascript:;" title="全局视图，Ctrl + ↑"></a>
			</div>
		</div>
	</div>
	<div id="search-bar">
		<input id="pageletSearchInput" placeholder="搜索应用...">
		<input type="buttom" value="" id="pageletSearchButton" title="搜索...">
	</div>
	<div id="search-suggest">
		<ul class="resultBox"></ul>
		<div class="resultList openAppMarket"><a href="javascript:;"><div>去应用市场搜搜...</div></a></div>
	</div>
</div>
<!-- 全局视图 -->
<div id="appmanage">
	<a class="amg_close" href="javascript:;"></a>
	<div id="amg_dock_container"></div>
	<div class="amg_line_x"></div>
	<div id="amg_folder_container">
		<div class="folderItem">
			<div class="folder_bg folder_bg1"></div>
			<div class="folderOuter">
				<div class="folderInner" desk="1"></div>
				<div class="scrollBar"></div>
			</div>
		</div>
		<div class="folderItem">
			<div class="folder_bg folder_bg2"></div>
			<div class="folderOuter">
				<div class="folderInner" desk="2"></div>
				<div class="scrollBar"></div>
			</div>
			<div class="amg_line_y"></div>
		</div>
		<div class="folderItem">
			<div class="folder_bg folder_bg3"></div>
			<div class="folderOuter">
				<div class="folderInner" desk="3"></div>
				<div class="scrollBar"></div>
			</div>
			<div class="amg_line_y"></div>
		</div>
		<div class="folderItem">
			<div class="folder_bg folder_bg4"></div>
			<div class="folderOuter">
				<div class="folderInner" desk="4"></div>
				<div class="scrollBar"></div>
			</div>
			<div class="amg_line_y"></div>
		</div>
		<div class="folderItem">
			<div class="folder_bg folder_bg5"></div>
			<div class="folderOuter">
				<div class="folderInner" desk="5"></div>
				<div class="scrollBar"></div>
			</div>
			<div class="amg_line_y"></div>
		</div>
	</div>
</div>
<script src="js/jquery-1.8.3.min.js"></script>
<script src="js/HoorayLibs/hooraylibs.js"></script>
<script src="js/sugar/sugar-1.3.9.min.js"></script>
<script src="js/templates.js"></script>
<script src="js/util.js"></script>
<script src="js/core.js"></script>
<script src="js/hros.app.js"></script>
<script src="js/hros.appmanage.js"></script>
<script src="js/hros.base.js"></script>
<script src="js/hros.desktop.js"></script>
<script src="js/hros.dock.js"></script>
<script src="js/hros.folderView.js"></script>
<script src="js/hros.grid.js"></script>
<script src="js/hros.maskBox.js"></script>
<script src="js/hros.navbar.js"></script>
<script src="js/hros.popupMenu.js"></script>
<script src="js/hros.searchbar.js"></script>
<script src="js/hros.taskbar.js"></script>
<script src="js/hros.uploadFile.js"></script>
<script src="js/hros.wallpaper.js"></script>
<script src="js/hros.widget.js"></script>
<script src="js/hros.window.js"></script>
<script src="js/hros.zoom.js"></script>
<script src="js/artDialog4.1.7/jquery.artDialog.js?skin=default"></script>
<script src="js/artDialog4.1.7/plugins/iframeTools.js"></script>
<script>
$(function(){
	//IE下禁止选中
	document.body.onselectstart = document.body.ondrag = function(){return false;}
	//隐藏加载遮罩层
	$('.loading').hide();
	//IE6,7升级提示
	if($.browser.msie && $.browser.version < 8){
		if($.browser.version < 7){
			//虽然不支持IE6，但还是得修复PNG图片透明的问题
			DD_belatedPNG.fix('.update_browser .browser');
		}
		$('.update_browser_box').show();
	}else{
		$('#desktop').show();
		//初始化一些桌面信息
		HROS.CONFIG.sinaweiboAppkey = '<?php echo SINAWEIBO_AKEY; ?>';
		HROS.CONFIG.tweiboAppkey = '<?php echo TWEIBO_AKEY; ?>';
		<?php
			$w = explode('<{|}>', getWallpaper());
		?>
		HROS.CONFIG.wallpaperState = <?php echo $w[0]; ?>;
		<?php
			switch($w[0]){
				case 1:
				case 2:
		?>
		HROS.CONFIG.wallpaper = '<?php echo $w[1]; ?>';
		HROS.CONFIG.wallpaperType = '<?php echo $w[2]; ?>';
		HROS.CONFIG.wallpaperWidth = <?php echo $w[3]; ?>;
		HROS.CONFIG.wallpaperHeight = <?php echo $w[4]; ?>;
		<?php
					break;
				case 3:
		?>
		HROS.CONFIG.wallpaper = '<?php echo $w[1]; ?>';
		<?php
					break;
			}
		?>
		HROS.CONFIG.dockPos = '<?php echo getDockPos(); ?>';
		HROS.CONFIG.appXY = '<?php echo getAppXY(); ?>';
		HROS.CONFIG.appSize = '<?php echo getAppSize(); ?>';
		//加载桌面
		HROS.base.init();
		$.dialog({
			title: '欢迎使用 HoorayOS',
			icon: 'face-smile',
			width: 320,
			content: 'HoorayOS 是否就是你一直想要的 web 桌面么？<br>' + '那么我非常期待您能够热情的提供<font style="color:red"> 50 元</font>或者其他金额的捐赠鼓励，正如您支持其他开源项目一样。<br>' + '支付宝：<a href="https://me.alipay.com/hooray" style="color:#214FA3" target="_blank">https://me.alipay.com/hooray</a><div style="width:100%;height:0px;font-size:0;border-bottom:1px solid #ccc"></div>如果你对本框架感兴趣，欢迎加入讨论群：<br>213804727' + '<div style="width:100%;height:0px;font-size:0;border-bottom:1px solid #ccc"></div>' +
					'HoorayOS 仅供个人学习交流，未经授权禁止用于商业用途，版权归作者所有，未经作者同意，不得删除代码中作者信息。若需要商业使用，请联系 QQ：304327508 进行授权'
		});
	}
});
/* 抖动效果 */
$.dialog.prototype.shake = (function(){
    var fx = function(ontween, onend, duration){
        var startTime = + new Date;
        var timer = setInterval(function(){
            var runTime = + new Date - startTime;
            var pre = runTime / duration;
            if(pre >= 1){
                clearInterval(timer);
                onend(pre);
            }else{
                ontween(pre);
            };
        }, 13);
    };
    var animate = function(elem, distance, duration){
        var quantity = arguments[3];
        if(quantity === undefined){
            quantity = 6;
            duration = duration / quantity;
        };
        var style = elem.style;
        var from = parseInt(style.marginLeft) || 0;
        fx(function(pre){
            elem.style.marginLeft = from + (distance - from) * pre + 'px';
        }, function(){
            if(quantity !== 0){
                animate(
                    elem,
                    quantity === 1 ? 0 : (distance / quantity - distance) * 1.3,
                    duration,
                    -- quantity
                );
            };
        }, duration);
    };
    return function(){
        animate(this.DOM.wrap[0], 40, 600);
        return this;
    };
})();
</script>
</body>
</html>