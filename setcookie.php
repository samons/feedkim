<?php
/**
* 设置cookie值
* 解析$_POST['feedUrl']值并对齐进行设置
* @since 2020-3-12
* @param $_POST['feedbutton']
* @return $_COOKIE['feedKimUrls']
*/
if (isset($_POST['feedbutton'])) {
	setcookie('feedKimUrls',$_POST['feedbutton'],time()+604800);
}
// setcookie('feedKimLastTime','',time()-800);
// setcookie('feedKimLastTimeX','',time()-800);

//没有X时间并且存在lastTime时间时，借用1800秒作为过渡阅读使用
if(!$_COOKIE['feedKimLastTimeX']){
	if ($_COOKIE['feedKimLastTime']) {
		setcookie('feedKimLastTimeX',$_COOKIE['feedKimLastTime'],time()+28800);
	}else{
		setcookie('feedKimLastTimeX',date('Y-m-d H:i:s',time()-604800),time()+28800);
	}
}

setcookie('feedKimLastTime',date('Y-m-d H:i:s',time()),time()+604800);
?>