<?php
/**
	 * 设置cookie值
	 * 解析$_POST['feedUrl']值并对齐进行设置
	 * @since 2020-3-12
	 * @param $_POST['feedUrl']
	 * @return $_COOKIE['feedKimUrls']
	 */
	if (isset($_POST['feedUrl']) && $_POST['feedUrl'] != home_url('/')) {
		setcookie('feedKimUrls',$_POST['feedUrl'],time()+86400);
	}
?>