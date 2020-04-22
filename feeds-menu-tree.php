<?php
/*
输出feeds导航的模块
*/
$feedsTree = feedkim_get_nav_menu_tree('feeds');

$echoTree = '<div id="feeds-div" class="feeds-div"><ul class="feed-menu">';

foreach ($feedsTree as $items => $itemsV) {
  //var_dump($itemsV -> post_title);
  $item_ID = $itemsV -> ID;
  $item_post_title = $itemsV -> post_title;
  $item_url = $itemsV -> url;
  $item_type_label = $itemsV -> type_label;

  $echoTree .= '<li>';
  if($item_type_label == '自定义链接'){
    $echoTree .= '<img src="//'.feedkim_parse_url($item_url).'/favicon.ico" alt="favicon.ico" class="favicon-ico" onerror="javascript:this.src=\'http://localhost/wordpress/wp-content/themes/feedkim/image/favicon.ico\';">';
  }
  $echoTree .= '</li>';
}

$echoTree .= '</ul></div>';

echo $echoTree;
//以下为测试结构
// echo '<pre>';
// print_r($feedsTree);
// echo '</pre>';
?>