<footer class="footer">
    <p>
        Copyright © 2019-2020 <a href="<?php bloginfo('url'); ?>/wp-admin" target="_blank"><?php bloginfo('name'); ?></a> <span class="glyphicon glyphicon-pencil"></span> 
        <a href="//beian.miit.gov.cn/" rel="nofollow" target="_blank">网站备案号</a> <!--网站备案号-->
        <span class="glyphicon glyphicon-tree-deciduous"></span> <a href="<?php bloginfo('rdf_url'); ?>" target="_blank">RSS</a> 
        Powered By <a href="//cn.wordpress.org" rel="nofollow" target="_blank">WordPress</a>. Theme by <a href="//feed.kim" target="_blank">feed.kim</a>
        <?php _e('商业授权版','feedkim' );?>
    </p>
</footer>

<!-- Bootstrap JS -->
<script src="<?php bloginfo('template_url')?>/js/jquery.min.js"></script>
<script src="<?php bloginfo('template_url')?>/js/bootstrap.min.js"></script>
<script src="<?php bloginfo('template_url')?>/js/feedkim.js"></script>
<!-- 侧栏下拉 JS -->
<script src="<?php bloginfo('template_url')?>/js/theia-sticky-sidebar.js"></script>
<script type="text/javascript">
	jQuery('.sidebar').theiaStickySidebar({
	      additionalMarginTop: 80
	});
</script>
</body>
</html>
<!-- 网页打开时间：<?php timer_stop(1); ?>秒 -->
<!-- 调用数据库次数：<?php echo get_num_queries(); ?>次 -->