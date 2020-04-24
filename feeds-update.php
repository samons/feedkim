<?php
/**
 * 管理员通过wp_postmeta表找到对应的post_ID；
 * 通过对应ID刷新结构wp_posts表中的时间
 * 
 * @since  2020-4-23
 * @param  $wpdb
 * @return html
 */

if ($_POST['feedsUpdate']=='feedsUpdate') {
  global $wpdb;

  $table_wp_posts = $wpdb->prefix . "posts";
  $table_wp_postmeta = $wpdb->prefix . "postmeta";

  $sql = "SELECT `post_id` FROM ".$table_wp_postmeta." WHERE `meta_value` LIKE 'custom' AND `meta_key` LIKE '_menu_item_type'";
  $results = $wpdb->get_results($sql);
  for ($i=0; $i<count($results); $i++) { 
    $wpdb_IDs[] = $results[$i]->post_id;//单数组，结构{[]=>ID}
  }

  foreach ($wpdb_IDs as $key) {
    $sql = "SELECT `meta_value` FROM ".$table_wp_postmeta." WHERE `meta_key` = '_menu_item_url' AND `post_id`=".$key;
    $results = $wpdb->get_results($sql);
    $value = $results[0]->meta_value;
    if ($value == home_url('/') || $value == home_url()) {
      continue;
    }else{
      $feedkim_IDtoUrl[$key] = $value;//单数组，结构{ID=>URL}
    }
  }

  foreach ($feedkim_IDtoUrl as $ID => $URL) {
    $feed = feedkim_fetch_feed($URL);
    if($feed->error()){
      continue;
    }else{
      foreach ($feed->get_items(0,1) as $item){
        $update = $item->get_date('Y-m-d H:i:s');
        //$feedkim_IDtoUpdate[$ID] = $update;单数组，结构{ID=>update}
        //var_dump($feedkim_IDtoUpdate);
        //跟新数据表中wp_Posts中post-date信息
        $sql = "UPDATE ".$table_wp_posts." SET `post_date_gmt` = '".$update."' WHERE `ID` = ".$ID;
        $results = $wpdb->get_results($sql);
      }
    }
  }

  //关闭数据库
  $wpdb->flush();
  
}

//刷新按钮界面
function feedkim_feeds_update(){
  $updateButton = '<li>';
  $updateButton .= '<button type="submit" class="btn btn-link" name="feedsUpdate" value="feedsUpdate"><i class="glyphicon glyphicon-repeat"></i> '.__('刷新FEED时间','feedkim').'</button>';
  $updateButton .= '</li>';

  return $updateButton;
}
?>