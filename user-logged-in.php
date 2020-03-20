<?php 
	/**
	 * 用户登录后可在前台直接发文
	 * //segmentfault.com/q/1010000005920615
	 * @author 碎石头 //sheng.iteye.com
	 * @since 2020-3-20
	 */
$fk_new_post_error = '';
if( $_SERVER['REQUEST_METHOD'] == 'POST' && $_POST['fk_action'] == 'post' ){
	if(current_user_can('publish_posts' )) {//提交正常
	    check_admin_referer('fk_new_post');
	    $fk_userId  = $current_user->user_id;//用户ID
	    $fk_content = $_POST['fk_content'];//文章内容
	    $fk_title = $_POST['fk_title'];//文章标题
  	}else{//没有直接发文权限时，转为草稿
		echo '没有发文权限';
	}
}?>

<li class="top-input">
<form name="fk_new_post" action="" method="post">
	<textarea name="fk_content" class="form-control" rows="3" required></textarea>
	<div class="input-group">
		<div class="input-group-addon"><?php _e('标题','feedkim');?></div>
		<input name="fk_title" class="form-control" type="text" value="<?php echo date('Y-m-d h:s').__('记录','feedkim');?>" required>
		<span class="input-group-btn">
			<input type="hidden" name="fk_action" value="post">
			<input type="hidden" name="post_ID" value="175">
			<input type="hidden" name="post_type" value="post">
			<input type="hidden" id="_wpnonce" name="_wpnonce" value="662266a0c2">
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