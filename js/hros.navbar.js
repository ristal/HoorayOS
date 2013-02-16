/*
**  分页导航
*/
HROS.navbar = (function(){
	return {
		/*
		**  初始化
		*/
		init : function(){
			$('#nav-bar').css({
				'left' : $(document).width() / 2 - 105,
				'top' : 80
			}).show();
			HROS.navbar.hotkey();
			HROS.navbar.getAvatar();
			HROS.navbar.move();
		},
		/*
		**  快捷键
		*/
		hotkey : function(){
			Mousetrap.bind(['ctrl+up', 'command+up'], function(){
				HROS.appmanage.init();
				return false;
			});
			Mousetrap.bind(['ctrl+f', 'command+f'], function(){
				HROS.searchbar.init();
				return false;
			});
		},
		/*
		**  获取头像
		*/
		getAvatar : function(){
			$('#nav-bar .indicator-header-img').attr('src', 'img/ui/loading_24.gif');
			$.ajax({
				type : 'POST',
				url : ajaxUrl,
				data : 'ac=getAvatar'
			}).done(function(msg){
				$('#nav-bar .indicator-header-img').attr('src', msg);
			});
		},
		/*
		**  设置头像
		*/
		setAvatar : function(){
			HROS.window.createTemp({
				appid : 'txsz',
				title : '头像设置',
				url : 'sysapp/avatar/index.php',
				width : 550,
				height : 550
			});
		},
		/*
		**  拖动
		*/
		move : function(){
			$('#nav-bar, #navbarHeaderImg, #nav-bar .nav-container a.indicator').on('mousedown', function(e){
				$('.popup-menu').hide();
				$('.quick_view_container').remove();
				if(e.button == 0 || e.button == 1){
					var x, y, cx, cy, dx, dy, lay, obj = $('#nav-bar'), thisobj = $(this);
					dx = cx = obj.offset().left;
					dy = cy = obj.offset().top;
					x = e.clientX - dx;
					y = e.clientY - dy;
					//绑定鼠标移动事件
					$(document).on('mousemove', function(e){
						lay = HROS.maskBox.desk();
						lay.show();
						cx = e.clientX - x <= 0 ? 0 : e.clientX - x > $(document).width() - 210 ? $(document).width() - 210 : e.clientX - x;
						cy = e.clientY - y <= 10 ? 10 : e.clientY - y > $(document).height() - 50 ? $(document).height() - 50 : e.clientY - y;
						obj.css({
							left : cx,
							top : cy
						});
					}).on('mouseup', function(){
						if(dx == cx && dy == cy){
							if(typeof(thisobj.attr('index')) !== 'undefined'){
								HROS.navbar.switchDesk(thisobj.attr('index'));
							}else if(thisobj.hasClass('indicator-manage')){
								//初始化全局视图
								HROS.appmanage.init();
							}else if(thisobj.hasClass('indicator-search')){
								//初始化搜索栏
								HROS.searchbar.init();
							}else if(thisobj.hasClass('indicator-header')){
								HROS.navbar.setAvatar();
							}
						}
						if(typeof(lay) !== 'undefined'){
							lay.hide();
						}
						$(this).off('mousemove').off('mouseup');
					});
				}
			});
		},
		/*
		**  切换桌面
		*/
		switchDesk : function(deskNumber){
			//验证传入的桌面号是否为1-5的正整数
			var r = /^\+?[1-5]*$/;
			deskNumber = r.test(deskNumber) ? deskNumber : 1;
			var nav = $('#navContainer'), currindex = HROS.CONFIG.desk, switchindex = deskNumber,
			currleft = $('#desk-' + currindex).offset().left, switchleft = $('#desk-' + switchindex).offset().left;
			if(currindex != switchindex){
				if(!$('#desk-' + switchindex).hasClass('animated') && !$('#desk-' + currindex).hasClass('animated')){
					$('#desk-' + currindex).addClass('animated').animate({
						left : switchleft
					}, 500, 'easeInOutCirc', function(){
						$(this).removeClass('animated');
					});
					$('#desk-'+switchindex).addClass('animated').animate({
						left : currleft
					}, 500, 'easeInOutCirc', function(){
						$(this).removeClass('animated');
						nav.removeClass('nav-current-' + currindex).addClass('nav-current-' + switchindex);
						HROS.CONFIG.desk = switchindex;
					});
				}
			}
		}
	}
})();