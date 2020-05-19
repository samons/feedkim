<?php 
/*
Template Name: 文章模板
*/
get_header();

error_reporting(E_ALL ^ (E_WARNING|E_NOTICE));// 屏蔽域名不存在等访问问题的警告
?>
<div class="container">
	<div class="row index-list">
		<div class="col-sm-2 left-feeds sidebar hidden-xs">
			<div class="theiaStickySidebar"><!-- 侧栏滚动 -->
				<?php //RSS源，对应的是feeds菜单
				if ( has_nav_menu('feeds')) { ?>
				<form method="POST" action="<?php echo home_url('/');?>" role="form">
					<?php require_once('feeds-menu-tree.php');?>
				</form>
				<?php } ?>
			</div>
		</div>
		<div class="col-sm-10 single-box">
			<h1><?php single_post_title(); ?></h1><hr>
			<ol class="breadcrumb">
				<li><a href="<?php echo home_url('/'); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a></li>
				<?php //路径导航
					$category = get_the_category();
					$thisCate = $category[0]->cat_name;
					$thisCateID = $category[0]->cat_ID;
					$thisCateUrl = get_category_link($thisCateID);
				?>
				<li><a href="<?php echo $thisCateUrl;?>" target="_blank" title="<?php echo $thisCate;?>"><?php echo $thisCate;?></a></li>
				<li class="active hidden-xs">
				<?php 
					$thisAuthorID = get_post($post->ID)->post_author;
					$feedkim_authors = get_post_meta(get_the_ID(),'author');
					if (empty($feedkim_authors[0])) {
						feedkim_the_author($thisAuthorID);						
					}else{
						echo $feedkim_authors[0];
					}
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
				$feedkim_author_url = get_post_meta(get_the_ID(),'url');
				if (!empty($feedkim_author_url[0])) {
					echo '<p class="single-author">'.__('文章来自：','feedkim').$feedkim_author_url[0].'</p>';
				}
				comments_template();
			?>
		</div>
		<div class="clearfix visible-xs-block hidden-xs"></div>
	</div>
</div>

<?php get_footer();?>