<?php
/**
 * 输出列表函数，方面套入式输入
 * 
 * @since  2020-4-23
 * @param  array object
 * @return html
 */
function feedkim_feedli_children($array,$children=""){
  
  if ($children=="children") {
    $echo = '<ul class="sub-menu">';
  }else{
    $echo = '<ul class="feed-menu">';
    if (current_user_can('level_10')){//管理员刷新rss时间
      require_once('feeds-update.php');
      $echo .= feedkim_feeds_update();
    }
  }

  foreach ($array as $items => $itemsV) {
    $item_ID = $itemsV -> ID;
    $item_post_title = $itemsV -> post_title;//自定义标题
    $item_title = $itemsV -> title;//普通文章标题
    $item_url = $itemsV -> url;
    $item_type_label = $itemsV -> type_label;
    $item_children = $itemsV -> children;
    //管理员才能刷新最后更新时间
    // if (current_user_can('level_10') && $item_type_label == '自定义链接') {
    //     $wpdb->update('wp_posts',array('post_date'=>date('Y-m-d h:i:s')),array('ID'=>$item_ID));
    // }

    $echo .= '<li class="menu-item" id="item-'.$item_ID.'">';
    if($item_type_label == '自定义链接'){
      $echo .= '<img src="//'.feedkim_parse_url($item_url).'/favicon.ico" alt="favicon.ico" class="favicon-ico" onerror="javascript:this.src=\''.get_stylesheet_directory_uri().'/image/favicon.ico\';">';
      if($item_children){
        foreach ($item_children as $item_children_url_k => $item_children_url_v) {
          $item_children_url = $item_children_url_v -> url;
          $item_children_url_array[] = $item_children_url;
        }
        $item_children_urls = implode(',',$item_children_url_array);
        $echo .= '<button type="submit" class="btn btn-link" name="feedbutton" value="'.$item_children_urls.'">'.$item_post_title.'</button>';
      }else{
        $echo .= '<button type="submit" class="btn btn-link" name="feedbutton" value="'.$item_url.'">'.$item_post_title.'</button>';
        //$echo .= '<span>1</span>';//图标
      }
    }else{
      $echo .= '<a href="'.$item_url.'" title="'.$item_title.'">'.$item_title.'</a>';
    }

    if($item_children){//如果有子集就嵌套输入
      $echo .= feedkim_feedli_children($item_children,'children');
    }

    $echo .= '</li>';
  }

  $echo .= '</ul>';
  return $echo;
}
/*
输出feeds导航的模块
*/
$feedsTree = feedkim_get_nav_menu_tree('feeds');

$echoTree = '<div id="feeds-div" class="feeds-div">';
$echoTree .= feedkim_feedli_children($feedsTree);
$echoTree .= '</div>';

echo $echoTree;
?>