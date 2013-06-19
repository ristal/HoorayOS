/*
**  搜索栏
*/
HROS.searchbar = (function(){
	return {
		/*
		**  初始化
		*/
		init : function(){
			$('#search-bar').css({
				'left' : $('#nav-bar').offset().left + 27,
				'top' : $('#nav-bar').offset().top + 35
			}).show();
			$('#search-suggest').css({
				'left' : $('#nav-bar').offset().left + 27,
				'top' : $('#nav-bar').offset().top + 68
			}).children('.resultBox').html('');
			var oldSearchVal = '';
			$('#pageletSearchInput').val('').focus();
			searchFunc = setInterval(function(){
				var searchVal = $('#pageletSearchInput').val();
				if(searchVal != ''){
					$('#search-suggest').show();
					if(searchVal != oldSearchVal){
						oldSearchVal = searchVal;
						alert(1)
					}
				}else{
					$('#search-suggest').hide();
				}
			}, 1000);
		},
		hide : function(){
			if(typeof searchFunc != 'undefined'){
				clearInterval(searchFunc);
			}
			$('#search-bar, #search-suggest').hide();
		}
	}
})();