<?php 
	/**
	 * 普通列表页面
	 * @since 2020-3-18
	 */
?>
<li class="item" id="post-<?php the_ID(); ?>">
	<div class="media">
		<div class="media-left">
			<a href="<?php the_permalink(); ?>" target="_blank" title="<?php the_title_attribute(); ?>">
				<img class="media-object" src="<?php bloginfo('template_url'); ?>/image/favicon.ico" alt="LOGO">
			</a>
		</div>
		<div class="media-body">
			<h5 class="media-heading"><a href="<?php the_permalink(); ?>" target="_blank" class="media-myTitle" title="<?php the_title_attribute(); ?>"><?php the_title_attribute(); ?></a></h5>
			<h6 class="media-about"><span class="glyphicon glyphicon-user"></span> <?php the_author();//作者?> <span class="glyphicon glyphicon-dashboard"></span> <?php the_time('y-m-d g:i');//发布时间?><span class="glyphicon glyphicon-menu-down float-right"></span></h6>
			<?php 
				the_excerpt();//文章摘要
				if (has_post_thumbnail()) {
					echo '<img src="';
            		the_post_thumbnail_url('full');
        			echo '" alt="'.get_the_title().'" />';
				}
			?>
		</div>
	</div>
</li>