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
          <ul class="nav navbar-nav">
          <?php
          global $wpdb;
          $table_wp_posts = $wpdb->prefix . "posts";
          $table_wp_postmeta = $wpdb->prefix . "postmeta";

          $sql = "SELECT `ID`,`post_title` FROM ".$table_wp_posts." WHERE `post_type` = 'nav_menu_item' AND `post_title` <> ''";
          $results = $wpdb->get_results($sql);
          for ($i=0; $i<count($results); $i++) { 
            $nav_ID = $results[$i]->ID;
            $nav_title = $results[$i]->post_title;
            $nav_IDtoTitle[$nav_ID] = $nav_title;//单数组，结构{ID=>title}
          }

          $sql = "SELECT * FROM ".$table_wp_postmeta." WHERE `meta_key` = '_menu_item_url' AND `meta_value` != ''";
          $results = $wpdb->get_results($sql);
          for ($i=0; $i<count($results); $i++) {
            $nav_ID = $results[$i]->post_id;
            $nav_url = $results[$i]->meta_value;
            $nav_IDtoUrl[$nav_ID] = $nav_url;//单数组，结构{ID=>url}
          }
          $nav_IDtoUrl = array_unique($nav_IDtoUrl);//删除重复项

          //关闭数据库
          $wpdb->flush();

          foreach ($nav_IDtoUrl as $key => $value) {
            echo '<li><button type="submit" class="btn btn-link" name="feedbutton" value="'.$value.'">'.$nav_IDtoTitle[$key].'</button></li>';
          }

          ?>
          </ul>
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