<?php 
	/**
	 * 用户未登录可直接指定发布留言
	 * @author //www.wpso.net/363
	 * @since 2020-4-8
	 */
?>
<li class="top-input">
<?php
	query_posts(get_option('feedkim_bbs'));
	while(have_posts()):the_post();?>
		<p><a href="<?php the_permalink() ?>" title="<?php the_title(); ?>" target="_blank"><?php the_title(); ?></a><?php _e('已有留言','feedkim');comments_popup_link(0,1,'%',',');_e('条','feedkim'); ?></p>
<?php
	endwhile;
	comments_template('/comments-bbs.php', true );
	wp_reset_query();
?>
</li>