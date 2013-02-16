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
			$('#pageletSearchInput').focus();
		}
	}
})();