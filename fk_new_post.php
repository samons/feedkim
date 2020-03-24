<?php
/**
 * 用户登录后可在前台直接发文,由于需要重新转向所以函数必须放在页面顶部
 * //segmentfault.com/q/1010000005920615
 * @since 2020-3-24
 * @param <form name = 'fk_new_post'>
 * @return wp_insert_post($fk_new_post)
 */
if('POST' == $_SERVER['REQUEST_METHOD'] && $_POST['fk_content'] ){
	$current_user = wp_get_current_user();//获取当前用户信息
    $fk_content = $_POST['fk_content'];//文章内容
    $fk_title = $_POST['fk_title'];//文章标题

    $fk_new_post = array(
    	'post_category' => array($_POST['cat']),
    	'post_content' => $fk_content
    );

    if ($_POST['fk_title']) {//标题
    	$fk_new_post['post_title'] = $_POST['fk_title'];
    }else{
		$fk_new_post['post_title'] = $current_user->display_name.':'.substr($fk_content,0,42).'...';
    }
    if ($_POST['fk_tags']) {//标签
    	$fk_new_post['tags_input'] = $_POST['fk_tags'];
    }
    
	if(current_user_can('publish_posts' )) {//提交正常
		$fk_new_post['post_status'] = 'publish';
  	}else{//没有直接发文权限时
  		$fk_new_post['post_status'] = 'pending';//状态为等待审核
		echo '<pre>'.__('文章正在审核审核中','feedkim').'</pre>';
	}
	wp_insert_post($fk_new_post);
	setcookie('feedKimUrls',home_url('/'),time()+86400);
	wp_redirect(home_url('/'),301);
}
?>