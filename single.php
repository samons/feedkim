<?php //文章内页
get_header();

error_reporting(E_ALL ^ (E_WARNING|E_NOTICE));// 屏蔽域名不存在等访问问题的警告
?>
<div class="container">
	<div class="row index-list">
		<div class="col-sm-2 left-feeds sidebar">
			<div class="theiaStickySidebar"><!-- 侧栏滚动 -->
				<?php //RSS源，对应的是feeds菜单
				if ( has_nav_menu('feeds')) { ?>
				<form method="POST" action="<?php echo home_url('/');?>" role="form">
				<?php 
					wp_nav_menu( array(  
					'theme_location' => 'feeds',
					'container'  => 'div',
					'container_id'  => 'feeds-div',
					'container_class' => 'feeds-div',
					'items_wrap' => '<ul class="%2$s">%3$s</ul>',
					'menu_class' => 'feed-menu',
				),); ?>
				<!-- 图标地址，获取用，不显示 -->
				<span class="display" id="myico"><?php bloginfo('template_url'); ?>/image/favicon.ico</span>
				<!-- input，获取用，不显示 -->
				<input class="display" type="text" name="feedUrl" value="">
				</form>
				<?php } ?>
			</div>
		</div>
		<div class="col-sm-10 single-box">
			<h1><?php single_post_title(); ?></h1><hr>
			<ol class="breadcrumb">
				<li><a href="<?php echo admin_url(); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a></li>
				<?php //路径导航
					$category = get_the_category();
					$thisCate = $category[0]->cat_name;
					$thisCateID = $category[0]->cat_ID;
					$thisCateUrl = get_category_link($thisCateID);
				?>
				<li><a href="<?php echo $thisCateUrl;?>" target="_blank" title="<?php echo $thisCate;?>"><?php echo $thisCate;?></a></li>
				<li class="active">
				<?php 
					$thisAuthorID = get_post($post->ID)->post_author;
					feedkim_the_author($thisAuthorID);
					echo ' @ ';
					the_time('Y-m-d h:s');
				?>
				<span class="glyphicon glyphicon-edit"></span> <?php edit_post_link(__('编辑','feedkim'));?>
				</li>
			</ol>
			<?php
			while ( have_posts() ) {
					the_post();
					the_content();
				}
				comments_template();
			?>

		</div>
		<div class="clearfix visible-xs-block"></div>
	</div>
</div>

<?php get_footer();?>