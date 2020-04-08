<?php
	/**
	 * 未登录用户可在首页发表言论
	 * @author //www.wpso.net/363
	 * @since 2020-4-8
	 */

if ( post_password_required() ){return;}
if ( !comments_open() ) :
// If registration required and not logged in.
	elseif ( get_option('comment_registration') && !is_user_logged_in() ) :
?>
<p><a href="<?php echo wp_login_url( get_permalink() ); ?>"><?php _e('Sign in','feedkim') ?></a><?php _e('Comment','feedkim') ?></p>
<?php else: ?>
<!-- Comment Form -->
<form id="commentform" name="commentform" action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="post" class="form-horizontal" role="form">
<?php if ( !is_user_logged_in() ) : ?>
	<textarea name="comment" id="message comment" rows="3" class="form-control" placeholder="<?php _e('想说点啥','feedkim')?>?" required tabindex="1"></textarea>

	<div class="input-group col-sm-6 bbs-author"><!-- 称呼 -->
		<div class="input-group-addon"><span class=" glyphicon glyphicon-user"></span></div>
		<label for="author" class="display"><?php _e('称呼','feedkim') ?>(*)</label>
		<input type="text" name="author" id="author" placeholder="<?php _e('您的称呼','feedkim') ?>" value="<?php echo $comment_author; ?>" tabindex="2" class="form-control" required>
	</div>

	<div class="input-group col-sm-6 bbs-email"><!-- email -->
		<div class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></div>
		<label for="email" class="display">email</label>
		<input type="email" name="email" id="email" placeholder="@" value="<?php echo $comment_author_email; ?>" tabindex="3" class="form-control" required>
	</div>

	<div class="input-group"><!-- 评论网址 -->
		<div class="input-group-addon"><span class="glyphicon glyphicon-link"></span></div>
		<label for="url" class="display"><?php _e('个人博客','feedkim') ?></label>
		<input name="url" id="url" class="form-control" type="text" placeholder="http(s)://" tabindex="4" value="<?php echo $comment_author_url; ?>">
<?php else: ?>
	<textarea name="comment" id="message comment" rows="3" class="form-control" placeholder="<?php _e('想说点啥','feedkim')?>?" required tabindex="1"></textarea>
	<div class="input-group">
		<input name="fk_title" class="form-control" type="text" tabindex="2" disabled>
<?php endif; ?>
		<span class="input-group-btn">
			<button type="submit" class="btn btn-default" name="sumbit" tabindex="99" onclick="Javascript:document.forms['commentform'].submit()"><?php _e('提交','feedkim') ?></button>
		</span>
	</div>
	<?php comment_id_fields(); ?>
    <?php do_action('comment_form', $post->ID); ?>
</form><!-- Comment Form End -->
<?php endif; ?>