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
  $wpdb_IDS = $wpdb->get_results($sql);
  for ($i=0; $i<count($wpdb_IDS); $i++) { 
    $wpdb_ID_array[] = $wpdb_IDS[$i]->post_id;//ID所有集合数组
  }

  var_dump($wpdb_ID_array);
}

//刷新按钮界面
function feedkim_feeds_update(){
  $updateButton = '<li>';
  $updateButton .= '<button type="submit" class="btn btn-link" name="feedsUpdate" value="feedsUpdate">'.__('刷新RSS时间','feedkim').'</button>';
  $updateButton .= '</li>';

  return $updateButton;
}
?>