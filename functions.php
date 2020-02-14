<?php
/**
 * feed.kim functions and definitions
 * 本主题所用到的相关函数
 * 所有自定义函数请使用feedkim_为前缀
 *
 * @package annanzi
 * @author annanzi/910109610@qq.com
 */
// 开启主题的小工具
if( function_exists('register_sidebar') ) {
    register_sidebar(array(
        'name' => __('文章右侧栏','feedkim'),
        'description'   => __('放置在文章页面右侧，随滚动','feedkim'),
        'before_widget' => '<section class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));
    register_sidebar(array(
        'name' => __('普通列表右侧栏','feedkim'),
        'description'   => __('放置在列表右侧','feedkim'),
        'before_widget' => '<section class="widget %2$s">',
        'after_widget' => '</section>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));
    register_sidebar(array(
        'name' => __('首页AD广告位','feedkim'),
        'description'   => __('建议用250PX宽的，只在大屏显示','feedkim'),
        'before_widget' => '<div class="col-lg-wu1 visible-lg-inline masonrybox" style="overflow:hidden">',
        'after_widget' => '</div>',
        'before_title' => '<h2 style="display:none">',
        'after_title' => '</h2>'
    ));
    register_nav_menus( array(
    'primary' => __('顶部导航', 'feedkim'),
    'link' => __('友情链接', 'feedkim'),
    'feeds' => 'feeds'
    ));
}
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