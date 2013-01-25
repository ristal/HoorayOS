<script src="../../js/jquery-1.8.3.min.js"></script>
<script src="../../js/bootstrap/js/bootstrap.min.js"></script>
<script src="../../js/HoorayLibs/hooraylibs.js"></script>
<script src="../../js/artDialog4.1.6/jquery.artDialog.js?skin=simple"></script>
<script src="../../js/artDialog4.1.6/plugins/iframeTools.js"></script>
<script src="../../js/Validform_v5.3/Validform_v5.3_min.js"></script>
<script>
$(function(){
	//配置artDialog全局默认参数
	(function(config){
		config['lock'] = true;
		config['fixed'] = true;
		config['resize'] = false;
		config['background'] = '#000';
		config['opacity'] = 0.5;
	})($.dialog.defaults);
	//toolTip
	$('[rel="tooltip"]').tooltip();
	//detailIframe
	openDetailIframe = function(url){
		ZENG.msgbox.show('正在载入中，请稍后...', 6, 100000);
		$('#detailIframe iframe').attr('src', url).load(function(){
			ZENG.msgbox._hide();
			$('#detailIframe').animate({
				'top' : 0,
				'opacity' : 'show'
			}, 500);
		});
	};
	closeDetailIframe = function(callback){
		$('#detailIframe').animate({
			'top' : '-100px',
			'opacity' : 'hide'
		}, 500, function(){
			callback && callback();
		});
	};
});
</script>