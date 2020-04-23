<?php
/**
	 * 设置cookie值
	 * 解析$_POST['feedUrl']值并对齐进行设置
	 * @since 2020-3-12
	 * @param $_POST['feedbutton']
	 * @return $_COOKIE['feedKimUrls']
	 */
	if (isset($_POST['feedbutton'])) {
		setcookie('feedKimUrls',$_POST['feedbutton'],time()+86400);
		setcookie('feedKimLastTime',date('y-m-d H:i'),time()+86400);
	}
?>