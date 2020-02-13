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
?>