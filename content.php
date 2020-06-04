<?php 
	/**
	 * 普通列表页面
	 * @since 2020-5-10
	 */
?>
<li class="item" id="post-<?php the_ID(); ?>">
	<div class="media">
		<div class="media-left hidden-xs">
			<a href="<?php the_permalink(); ?>" target="_blank" title="<?php the_title_attribute(); ?>">
				<?php $feedkim_author_url = get_post_meta(get_the_ID(),'url');
				if (empty($feedkim_author_url[0])):?>
					<img class="media-object ico" src="<?php bloginfo('template_url'); ?>/image/favicon.ico" alt="LOGO">
				<?php else: ?>
					<img src="//<?php echo feedkim_parse_url($feedkim_author_url[0]);?>/favicon.ico" alt="favicon.ico" class="media-object ico" onerror="javascript:this.src='<?php echo get_stylesheet_directory_uri()?>/image/favicon.ico';">
				<?php endif ?>
			</a>
		</div>
		<div class="media-body">
			<h4 class="media-heading"><a href="<?php the_permalink(); ?>" target="_blank" class="media-myTitle" title="<?php the_title_attribute(); ?>"><?php the_title_attribute(); ?></a></h4>
			<h6 class="media-about"><span class="glyphicon glyphicon-user"></span> <?php
				$feedkim_authors = get_post_meta(get_the_ID(),'author');
				if (empty($feedkim_authors[0])) {//作者
					the_author();
				}else{
			 		echo $feedkim_authors[0];
			 	}
			 ?> <span class="glyphicon glyphicon-dashboard"></span> <?php the_time('y-m-d g:i');//发布时间?></h6>
			<div class="media">
				<div class="media-body excerpt"><?php the_excerpt();//文章摘要?></div>
			<?php if (has_post_thumbnail()):?>
				<div class="media-right">
					<a href="<?php the_permalink(); ?>" target="_blank" title="<?php the_title();?>"><img class="media-object right-img" src="<?php echo the_post_thumbnail_url('thumbnail');?>" alt="<?php the_title();?>" /></a>
				</div>
			<?php endif	?>
      		</div>				
		</div>		
	</div>
</li>