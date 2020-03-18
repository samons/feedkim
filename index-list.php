<?php 
/**
 * 调用基础列表页面
 */
if(have_posts()){
	while ( have_posts() ) {
		the_post();
		get_template_part('content', get_post_format()); 
	};
};?>
<li id="pagerNav">
	<nav>
	  <ul class="pager">
	    <li class="previous"><?php previous_posts_link(__('上一页','limiwu')) ?></li>
	    <li class="next" id="older_posts"><?php next_posts_link(__('下一页','limiwu')) ?></li>
	  </ul>
	</nav>
</li><!-- pagerNav end -->