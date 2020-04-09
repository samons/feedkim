<nav class="navbar navbar-default navbar-fixed-top" id="navbar">
  <div class="container">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only"><?php bloginfo('name'); ?></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand logoImg" href="<?php echo home_url('/');?>"><img alt="Brand" src="<?php bloginfo('template_url'); ?>/image/favicon.ico"></a> 
      <a class="navbar-brand logoText" href="<?php echo home_url('/');?>"><?php bloginfo('name'); ?></a>
    </div>
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <?php //RSS源，对应的是feeds菜单
        if ( has_nav_menu('feeds')) { ?>
        <form method="POST" action="<?php echo home_url('/');?>" role="form" class=" visible-xs">
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
        <hr>
        </form>
      <?php }//RSS-end ?>
      <?php   
        if ( has_nav_menu( 'primary' )) {
           wp_nav_menu( array(  
            'theme_location' => 'primary',  
            'depth' => 2,   
            'container' => false,   
            'menu_class' => 'nav navbar-nav navbar-right',   
            'fallback_cb' => 'wp_page_menu')   
            );
        }
      ?>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>