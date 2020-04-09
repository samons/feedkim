$(document).ready(function() {
	//将网站菜单中自定义a改为button按钮
	$('#feeds-div .menu-item-type-custom>a').each(function() {
		var $feedButtonVal = $(this).attr('href');
		$(this).before('<img src="'+feedkim_findICO($feedButtonVal)
				+'" onerror="javascript:this.src=\''+$('#myico').text()
				+'\';" alt="favicon.ico" class="favicon-ico">');
		if ($(this).parent().hasClass('menu-item-has-children')) {
			//有子集时
			$childrenUrls = $(this).parent().find(".menu-item-type-custom>a");
			for (var i = $childrenUrls.length - 1; i >= 0; i--) {
				var $thisUrl = $($childrenUrls[i]).attr('href');
				$feedButtonVal = $feedButtonVal + ',' + $thisUrl;
			}
		}
		$(this).after('<button type="submit" class="btn btn-link" name="feedbutton" value="'
			+$feedButtonVal+'">'+$(this).text()+'</button>');
		$(this).hide();
		$(this).removeAttr('href');
		//console.log('x');
	});

	//按钮控制input值
	$('button[name=\"feedbutton\"]').each(function(){
		$(this).click(function() {
			var $thisUrl = $(this).val();
			$('input[name=\"feedUrl\"]').val($thisUrl);
		});
	});
	//侧栏跟随滚动
	$('.sidebar').theiaStickySidebar({
	      additionalMarginTop:70
	});
	//无线下拉控制
	$('#indexListUl').infinitescroll({     //#content是包含所有图或块的容器
	    navSelector  : "#pagerNav",   //导航的容器，成功后会被隐藏
	    nextSelector : "#pagerNav .next a",  // 包含下一页链接的容器
	    itemSelector : "li.item",  // 你将要取来的内容块
	    debug : true, //调试的时候，可以打开
	    maxPage : 100,
	    animate : true //当有新数据加载进来的时候，页面是否有动画效果，默认没有
	});
	//侧栏输入框增加bootstrap style
	$('.postform').addClass('form-control');
	$('#s').parent().addClass('input-group');
	$('.screen-reader-text').remove();
	$('#s').addClass('form-control');
	$('#searchsubmit').addClass('btn btn-default');
	$('#searchsubmit').wrap('<span class="input-group-btn"></span>');
	//手机浏览时隐藏分类、文章、页面
	$('.feeds-div .menu-item-object-category').addClass('hidden-xs');
	$('.feeds-div .menu-item-object-page').addClass('hidden-xs');
	$('.feeds-div .menu-item-object-post').addClass('hidden-xs');
})

//查找网址的ICO图标
function feedkim_findICO($url) {
	var $n = $url.indexOf('/');
	$n = $n + 2;
	$url = $url.substring($n);//del http
	var $n = $url.indexOf('/');
	if ($n>0) {
		$url = $url.substring(0,$n);
	}
	$url = '//'+$url+'/'+'favicon.ico';
	return $url;
}