<?php //首页面，基础列表页面
get_header();

error_reporting(E_ALL ^ (E_WARNING|E_NOTICE));// 屏蔽域名不存在等访问问题的警告
?>
<div class="container">
	<div class="row index-list">
		<div class="col-sm-3 col-md-2 left-feeds sidebar hidden-xs">
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
		<div class="col-sm-9 col-md-7 list">
			<ul id="indexListUl">
				<?php
				if(is_user_logged_in()){
					get_template_part('user-logged-in');//快速发文
				}else{
					if(get_option('feedkim_bbs')){
						get_template_part('user-bbs');//BBS
					}
				}
				if(is_tag() || is_category() || is_archive()){
					get_template_part('index-list');//正常发文列表
				}elseif($_GET['s'] || $_GET['cat']){
					get_template_part('index-list');//正常发文列表
				}elseif($_POST['feedUrl']){
					if ($_POST['feedUrl'] == home_url('/')) {
						get_template_part('index-list');//正常发文列表
					}else{
						get_template_part('index-feed');//调用feed
					}
				}elseif($_COOKIE['feedKimUrls']){
					if ($_COOKIE['feedKimUrls'] == home_url() || $_COOKIE['feedKimUrls'] == home_url('/')) {
						get_template_part('index-list');//正常发文列表
					}else{
						get_template_part('index-feed');//调用feed
					}
				}else{
					get_template_part('index-list');//正常发文列表
				}?>
			</ul>
		</div>
		<div class="col-md-3 hidden-sm hidden-xs right sidebar">
			<div class="theiaStickySidebar"><!-- 侧栏滚动 -->
				<?php get_sidebar();//获取侧栏 ?>
			</div>
		</div>
		<div class="clearfix visible-xs-block hidden-xs"></div>
	</div>
</div>

<?php get_footer();?>