<?php
/**
 * feed.kim functions and definitions
 * 本主题所用到的相关函数
 * 所有自定义函数请使用feedkim_为前缀
 *
 * @package feedkim
 * @author feedkim/910109610@qq.com
 */
/**
 * 去除window._wpemojiSettings
 * WordPress版本 zhangwenbao.com/wordpress-window-wpemojisettings.html
 * 查看Wordpress源文件的时候，会看到head头部加载了一大片window._wpemojiSettings开头的JS和CSS代码，这是用于支持emjo表情的脚本。对于大部分国内站长来说，这个是十分鸡肋的功能
**/
remove_action( 'admin_print_scripts', 'print_emoji_detection_script');
remove_action( 'admin_print_styles', 'print_emoji_styles');
remove_action( 'wp_head', 'print_emoji_detection_script', 7);
remove_action( 'wp_print_styles', 'print_emoji_styles');
remove_filter( 'the_content_feed', 'wp_staticize_emoji');
remove_filter( 'comment_text_rss', 'wp_staticize_emoji');
remove_filter( 'wp_mail', 'wp_staticize_emoji_for_email');
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
 * @since  2020-2-12
 * @param  String  $file 文件路径
 * @return Boolean
 */
function feedkim_file_exists($file){
    if(strtolower(substr($file, 0, 4))=='http'){// 远程文件
        $header = get_headers($file, true);
        return isset($header[0]) && (strpos($header[0], '200') || strpos($header[0], '304'));
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
        the_author_meta('user_nicename',$ID);
        echo '</a>';
    }elseif(get_the_author_meta('user_email',$ID) && $email == 'email'){
        echo '<a target="_blank" rel="nofollow" href="mailto:';
        the_author_meta('user_email',$ID);
        echo '">';
        the_author_meta('user_nicename',$ID);
        echo '</a>';
    }else{
        the_author_meta('user_nicename',$ID);
    }
}
/**
 * WordPress 修改时间的显示格式为XXX秒、分钟、小时、天前
 * form www.chendexin.com/archives/137.html
 */
function past_date(){
    $suffix=__('前','limiwu');
    $endtime='2419200';
    $day = __('天','limiwu');
    $hour = __('小时','limiwu');
    $minute = __('分钟','limiwu');
    $second = __('秒','limiwu');
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