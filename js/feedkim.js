$(document).ready(function() {
	//将网站菜单中自定义a改为button按钮
	var $feedButton = $('#feeds-div .menu-item-type-custom>a');
	$feedButton.each(function() {
		var $feedButtonVal = $(this).attr('href');
		$(this).before('<img src="'+feedkim_findICO($feedButtonVal)
				+'" onerror="javascript:this.src=\''+$('#myico').text()
				+'\';" alt="favicon.ico" class="favicon-ico">');
		if ($(this).parent().hasClass('menu-item-has-children')) {
			//有子集就不变成按钮			
		}else{
			$(this).after('<button type="button" class="btn btn-link" name="feedbutton" value="'
				+$feedButtonVal+'">'+$(this).text()+'</button>');
			$(this).hide();
		}
		$(this).removeAttr('href');
		//console.log('x');
	});
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