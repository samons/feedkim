$(document).ready(function() {
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
	//RSS订阅超链接增加新增窗口
	$('.rsswidget').attr({target:"_blank",rel:"nofollow"});
})