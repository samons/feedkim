<?php 
	/**
	 * 用户登录后可在前台直接发文
	 * //segmentfault.com/q/1010000005920615
	 * @author 碎石头 //sheng.iteye.com
	 * @since 2020-3-20
	 */
$current_user = wp_get_current_user();//获取当前用户信息

if('POST' == $_SERVER['REQUEST_METHOD'] && $_POST['fk_content'] ){
    $fk_content = $_POST['fk_content'];//文章内容
    $fk_title = $_POST['fk_title'];//文章标题

    $fk_new_post = array(
    	$fk_new_post['post_category'] = $_POST['cat'],
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
	//wp_safe_redirect(home_url('/?v=301'),301);
	echo "<script>location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
}
?>

<li class="top-input">
<form name="fk_new_post" action="" method="post">
	<textarea name="fk_content" class="form-control" rows="3" placeholder="<?php _e('想说点啥','feedkim')?>?" required></textarea>
	<div class="input-group">
		<div class="input-group-addon"><?php _e('标题','feedkim');?></div>
		<input name="fk_title" class="form-control" type="text" placeholder="<?php echo $current_user->display_name;?>">
		<span class="input-group-btn">
			<input type="hidden" name="fk_action" value="post">
			<input type="submit" name="save" value="<?php _e('提交','feedkim');?>" class="btn btn-default">
		</span>
	</div>
	<div class="form-inline">
		<div class="form-group">
			<label class="control-label" for="fk_tags"><span class="glyphicon glyphicon-tags"></span></label>
		</div>
		<div class="form-group">
			<input type="text" name="fk_tags" class="form-control" placeholder="<?php _e('多个标签请以逗号分隔','feedkim');?>">
		</div>
		<div class="form-group">
			<label class="control-label" for="cat"><span class="glyphicon glyphicon-duplicate"></span></label>
		</div>
		<div class="form-group">
			<?php wp_dropdown_categories('taxonomy=category');?>
		</div>
	</div>
</form>
</li>