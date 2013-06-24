<?php
	date_default_timezone_set('Asia/Shanghai');
	$h = (int)date('H');
	$m = (int)date('i');
	$s = (int)date('s');
	$h = $h > 12 ? $h - 12 : $h;
	$h = $h * 360 / 12 + 360;
	$m = $m * 360 / 60 + 360;
	$s = $s * 360 / 60 + 360;
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>无标题文档</title>
<style type="text/css">
@-webkit-keyframes h{
	from{-webkit-transform: rotate(0deg)}
	to{-webkit-transform: rotate(<?=$h?>deg)}
}
@-webkit-keyframes m{
	from{-webkit-transform: rotate(0deg)}
	to{-webkit-transform: rotate(<?=$m?>deg)}
}
@-webkit-keyframes s{
	from{-webkit-transform: rotate(0deg)}
	to{-webkit-transform: rotate(<?=$s?>deg)}
}
body{margin:0;padding:0}
#clock-box{width:130px;height:130px;background:url(trad.png) no-repeat;position:relative}
#clock-box div{width:13px;height:129px;position:absolute;top:0px;left:58px}
#clock-box .dot{background:url(trad_dot.png) no-repeat}
#clock-box .h{background:url(trad_h.png) no-repeat;-webkit-animation:h 1s ease 0s 1 alternate}
#clock-box .m{background:url(trad_m.png) no-repeat;-webkit-animation:m 1s ease 0s 1 alternate}
#clock-box .s{background:url(trad_s.png) no-repeat;-webkit-animation:s 1s ease 0s 1 alternate}
</style>
</head>

<body>
<div id="clock-box">
	<div class="dot"></div>
	<div class="h"></div>
	<div class="m"></div>
	<div class="s"></div>
</div>
<script type="text/javascript" src="../../js/jquery-1.8.3.min.js"></script>
<script type="text/javascript">
$(function(){
	var clock = $('#clock-box');
	var dom_h = clock.children('.h'), dom_m = clock.children('.m'), dom_s = clock.children('.s');
	setInterval(function(){
		var time = new Date(), h = time.getHours(), m = time.getMinutes(), s = time.getSeconds(); 
		h = h > 12 ? h - 12 : h;
		h = h * 360 / 12;
		m = m * 360 / 60;
		s = s * 360 / 60;
		dom_h.css('transform', 'rotate(' + h + 'deg)');
		dom_m.css('transform', 'rotate(' + m + 'deg)');
		dom_s.css('transform', 'rotate(' + s + 'deg)');
	}, 500);
});
</script>
</body>
</html>