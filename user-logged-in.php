<?php 
	/**
	 * 用户登录后可在前台直接发文，配合fk_new_post.PHP使用
	 * //segmentfault.com/q/1010000005920615
	 * @author 碎石头 //sheng.iteye.com
	 * @since 2020-3-20
	 */
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