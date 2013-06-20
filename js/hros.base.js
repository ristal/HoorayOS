/*
**  一个不属于其他模块的模块
*/
HROS.base = (function(){
	return {
		/*
		**	系统初始化
		*/
		init : function(){
			//配置artDialog全局默认参数
			(function(config){
				config['lock'] = true;
				config['fixed'] = true;
				config['resize'] = false;
				config['background'] = '#000';
				config['opacity'] = 0.5;
			})($.dialog.defaults);
			//增加离开页面确认窗口
			window.onbeforeunload = Util.confirmExit;
			//更新当前用户ID
			HROS.CONFIG.memberID = $.cookie('memberID');
			//文件上传
			//HROS.uploadFile.init();
			//绑定body点击事件，主要目的就是为了强制隐藏右键菜单
			$('#desktop').on('mousedown', function(){
				HROS.popupMenu.hide();
				HROS.folderView.hide();
				HROS.searchbar.hide();
			});
			//隐藏浏览器默认右键菜单
			$('body').on('contextmenu', function(){
				$(".popup-menu").hide();
				return false;
			});
			//绑定浏览器resize事件
			$(window).on('resize', function(){
				HROS.deskTop.resize();
			});
			//用于判断网页是否缩放
			HROS.zoom.init();
			//初始化壁纸
			HROS.wallpaper.init();
			//初始化分页栏
			HROS.navbar.init();
			//初始化任务栏
			HROS.taskbar.init();
			
			// 6/19 代码迭代到此
			
			//获得dock的位置
			HROS.dock.getPos(function(){
				//获取应用排列顺序
				HROS.app.getXY(function(){
					/*
					**      当dockPos为top时          当dockPos为left时         当dockPos为right时
					**  -----------------------   -----------------------   -----------------------
					**  | o o o         dock  |   | o | o               |   | o               | o |
					**  -----------------------   | o | o               |   | o               | o |
					**  | o o                 |   | o | o               |   | o               | o |
					**  | o +                 |   |   | o               |   | o               |   |
					**  | o             desk  |   |   | o         desk  |   | o         desk  |   |
					**  | o                   |   |   | +               |   | +               |   |
					**  -----------------------   -----------------------   -----------------------
					**  因为desk区域的尺寸和定位受dock位置的影响，所以加载应用前必须先定位好dock的位置
					*/
					HROS.app.init();
				});
			});
			//绑定应用码头2个按钮的点击事件
			$('.dock-tool-setting').on('mousedown', function(){
				return false;
			}).on('click',function(){
				if(HROS.base.checkLogin()){
					HROS.window.createTemp({
						appid : 'hoorayos-zmsz',
						title : '桌面设置',
						url : 'sysapp/desksetting/index.php',
						width : 750,
						height : 450,
						isflash : false
					});
				}else{
					HROS.base.login();
				}
			});
			$('.dock-tool-style').on('mousedown', function(){
				return false;
			}).on('click', function(){
				if(HROS.base.checkLogin()){
					HROS.window.createTemp({
						appid : 'hoorayos-ztsz',
						title : '主题设置',
						url : 'sysapp/wallpaper/index.php',
						width : 580,
						height : 520,
						isflash : false
					});
				}else{
					HROS.base.login();
				}
			});
			//桌面右键
			$('#desk').on('contextmenu', function(e){
				$(".popup-menu").hide();
				$('.quick_view_container').remove();
				var popupmenu = HROS.popupMenu.desk();
				l = ($(document).width() - e.clientX) < popupmenu.width() ? (e.clientX - popupmenu.width()) : e.clientX;
				t = ($(document).height() - e.clientY) < popupmenu.height() ? (e.clientY - popupmenu.height()) : e.clientY;
				popupmenu.css({
					left : l,
					top : t
				}).show();
				return false;
			});
			//还原widget
			HROS.widget.reduction();
			//加载新手帮助
			HROS.base.help();
			//页面加载后运行
			HROS.base.run();
			//绑定ajax全局验证
			$(document).ajaxSuccess(function(event, xhr, settings){
				if($.trim(xhr.responseText) == 'ERROR_NOT_LOGGED_IN'){
					HROS.CONFIG.memberID = 0;
					$.dialog({
						title : '温馨提示',
						icon : 'warning',
						content : '系统检测到您尚未登录，为了更好的操作，是否登录？',
						ok : function(){
							HROS.base.login();
						}
					});
				}
			});
			//如果未登录，弹出登录框（用于开放平台审核用，审核通过即可删除）
//			if(!HROS.base.checkLogin()){
//				HROS.base.login();
//			}
			console.log(' __    __ ________ ________ _______  ________ __    __    ________ ________ ');
			console.log('|  |  |  |   __   |   __   |   __  \\|   __   |  |  |  |  |   __   |   _____|');
			console.log('|  |__|  |  |  |  |  |  |  |  |__|  |  |__|  |  |__|  |  |  |  |  |  |_____ ');
			console.log('|   __   |  |  |  |  |  |  |      _/|   __   |__    __|  |  |  |  |_____   |');
			console.log('|  |  |  |  |__|  |  |__|  |  |\\  \\ |  |  |  |  |  |     |  |__|  |_____|  |');
			console.log('|__|  |__|________|________|__| \\__\\|__|  |__|  |__|     |________|________|');
			console.log('想学习HoorayOS，还是发现了什么bug？不如和我们一起为HoorayOS添砖加瓦吧！');
			console.log('QQ群：213804727');
			console.log('Github：https://github.com/hooray/HoorayOS');
		},
		login : function(){
			$.dialog.open('login.php', {
				id : 'logindialog',
				title : false
			});
		},
		logout : function(){
			$.ajax({
				type : 'POST',
				url : 'login.ajax.php',
				data : 'ac=logout',
				success : function(){
					window.onbeforeunload = null;
					location.reload();
				}
			});
		},
		checkLogin : function(){
			return HROS.CONFIG.memberID != 0 ? true : false;
		},
		getSkin : function(callback){
			$.ajax({
				type : 'POST',
				url : ajaxUrl,
				data : 'ac=getSkin',
				success : function(skin){
					function styleOnload(node, callback) {
						// for IE6-9 and Opera
						if(node.attachEvent){
							node.attachEvent('onload', callback);
							// NOTICE:
							// 1. "onload" will be fired in IE6-9 when the file is 404, but in
							// this situation, Opera does nothing, so fallback to timeout.
							// 2. "onerror" doesn't fire in any browsers!
						}
						// polling for Firefox, Chrome, Safari
						else{
							setTimeout(function(){
								poll(node, callback);
							}, 0); // for cache
						}
					}
					function poll(node, callback) {
						if(callback.isCalled){
							return;
						}
						var isLoaded = false;
						//webkit
						if(/webkit/i.test(navigator.userAgent)){
							if (node['sheet']) {
								isLoaded = true;
							}
						}
						// for Firefox
						else if(node['sheet']){
							try{
								if (node['sheet'].cssRules) {
									isLoaded = true;
								}
							}catch(ex){
								// NS_ERROR_DOM_SECURITY_ERR
								if(ex.code === 1000){
									isLoaded = true;
								}
							}
						}
						if(isLoaded){
							// give time to render.
							setTimeout(function() {
								callback();
							}, 1);
						}else{
							setTimeout(function() {
								poll(node, callback);
							}, 1);
						}
					}					
					//将原样式修改id，并载入新样式
					$('#window-skin').attr('id', 'window-skin-ready2remove');
					var css = document.createElement('link');
					css.rel = 'stylesheet';
					css.href = 'img/skins/' + skin + '.css?' + version;
					css.id = 'window-skin';
					document.getElementsByTagName('head')[0].appendChild(css);
					//新样式载入完毕后清空原样式
					//方法为参考seajs源码并改编，文章地址：http://www.blogjava.net/Hafeyang/archive/2011/10/08/360183.html
					styleOnload(css, function(){
						$('#window-skin-ready2remove').remove();
						callback && callback();
					});
				}
			});
		},
		help : function(){
			if($.cookie('isLoginFirst') == null){
				$.cookie('isLoginFirst', '1', {expires : 95});
				if(!$.browser.msie || ($.browser.msie && $.browser.version < 9)){
					$('body').append(helpTemp);
					//IE6,7,8基本就告别新手帮助了
					$('#step1').show();
					$('.close').on('click', function(){
						$('#help').remove();
					});
					$('.next').on('click', function(){
						var obj = $(this).parents('.step');
						var step = obj.attr('step');
						obj.hide();
						$('#step' + (parseInt(step) + 1)).show();
					});
					$('.over').on('click', function(){
						$('#help').remove();
					});
				}
			}
		},
		run : function(){
			var url = location.search;
			var request = new Object();
			if(url.indexOf("?") != -1){
				var str = url.substr(1);
				strs = str.split("&");
				for(var i = 0; i < strs.length; i ++) {
					request[strs[i].split("=")[0]]=unescape(strs[i].split("=")[1]);
				}
			}
			if(typeof request['run'] != 'undefined' && typeof request['type'] != 'undefined'){
				if(request['type'] == 'app'){
					HROS.window.create(request['run']);
				}else{
					//判断挂件是否存在cookie中，因为如果存在则自动会启动
					if(!HROS.widget.checkCookie(request['run'])){
						HROS.widget.create(request['run']);
					}
				}
			}
		}
	}
})();