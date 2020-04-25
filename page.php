<?php 
/*
Template Name: 页面模板
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
				<li><a href="<?php echo admin_url(); ?>" title="<?php bloginfo('name'); ?>"><?php bloginfo('name'); ?></a></li>
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
		<div class="clearfix visible-xs-block hidden-xs"></div>
	</div>
</div>

<?php get_footer();?>