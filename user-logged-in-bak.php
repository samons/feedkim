<?php 
	/**
	 * 用户登录后可在前台直接发文
	 * //segmentfault.com/q/1010000005920615
	 * @author 碎石头 //sheng.iteye.com
	 * @since 2020-3-20
	 */
?>

<?php
if( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'post' ) {
  if (!is_user_logged_in() ) auth_redirect();
  if(!current_user_can( 'publish_posts' ) ) {
    wp_redirect( get_bloginfo( 'url' ) . '/' );
    exit;
  }
  check_admin_referer( 'new-post' );
  $user_id  = $current_user->user_id;

  if (isset ($_POST['title'])) {
    $title =  $_POST['title'];
  } else {
    echo '请输入文章标题！';
  }
  if (isset ($_POST['content'])) {
    $content = $_POST['content'];
  } else {
    echo '请输入文章内容！';
  }
  $tags = $_POST['post_tags'];
  $post = array(
    'post_author'   => $user_id,
    'post_title'    => $title,
    'post_content'  => $content,
    'post_category' => array($_POST['cat']),
    'tags_input'    => $tags,
    'post_status'   => 'publish',
    'post_type'     => $_POST['post_type']
  );
  wp_insert_post($post);
  wp_redirect( home_url() );
}
do_action('wp_insert_post', 'wp_insert_post');
?>

<div style="width:100%; text-align: center;">
  <div style="width:96%; text-align: left; padding: 0 20px 0 20px;">
<?php
if( current_user_can( 'publish_posts' ) ) {
?>
<div style="width: 100%; font-size: 20px; font-weight: bold; text-align: center;">发布新文章</div><br/>
<!--以下为发表文章的表单-->

<form id="new_post" name="new_post" method="post" action="">
  <p><label for="title">文章标题：</label><input type="text" id="title" value="输入文章标题"  tabindex="1" size="80" name="title" /></p>
  <p><label for="post_tags">文章标签：</label><input type="text" value="输入文章TAGS，用单引号隔开" onfocus="this.value==this.defaultValue?this.value='':null;" onblur="this.value==''?this.value='输入文章TAGS，用单引号隔开':null;" tabindex="5" size="80" name="post_tags" id="post_tags" /></p>
  <p><label for="cat">文章分类：</label><?php /*wp_dropdown_categories( 'show_option_none=选择文章分类&tab_index=4&taxonomy=category' );*/wp_dropdown_categories( 'tab_index=4&taxonomy=category' ); ?></p>
  <p><label for="content">文章内容：</label>
<?php wp_editor( '', content, $settings = array(
                    'quicktags'=>1,
                    'tinymce'=>0,
                    'media_buttons'=>0,
                    'textarea_rows'=>4,
                    'editor_class'=>"textareastyle"
) ); ?></p>
  <input type="hidden" name="post_type" id="post_type" value="post" />
  <input type="hidden" name="action" value="post" />
  <p style="width: 100%; text-align: center;">
  <input type="submit" value="发  布" tabindex="6" id="submit" name="submit" class="inputy"/>
  <input type="reset" value="重  置" id="reset" name="" class="inputn"/>
  </p>
  <?php wp_nonce_field( 'new-post' ); ?>
</form>
<?php
}else{
?>
对不起，您没有发布文章的权限！
<?php
}
?>
  </div>
</div>