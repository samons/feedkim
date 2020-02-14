<?php //首页面，基础列表页面
get_header();

error_reporting(E_ALL ^ (E_WARNING|E_NOTICE));// 屏蔽域名不存在等访问问题的警告
?>
<div class="container">
	<div class="row index-list">
		<div class="col-sm-2 col-sm-offset-1 left-feeds">	
			<?php //RSS源，对应的是feeds菜单
			if ( has_nav_menu('feeds')) { ?>
			<form method="POST" action="" role="form">
			<?php 
			if (isset($_POST['feedUrl'])) {
				//解析输出订阅数据
				echo $_POST['feedUrl'];
			}
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
		<div class="col-sm-5 list">
			<?php get_template_part('index-article');//列表页面 ?>
		</div>
		<div class="col-sm-3 right">right</div>
		<div class="clearfix visible-xs-block"></div>
	</div>
</div>

<?php get_footer();?>