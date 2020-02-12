<?php
//https://zhangzifan.com/wordpress-fetch_feed.html
//https://zhangzifan.com/wordpress-feed-rss.html
get_header();
$feedurl = array(
	'https://www.leiue.com/feed',
	//'https://www.limiwu.com/feed',
	//'https://www.txcstx.cn/feed.php'
);//FEED数组
require_once (ABSPATH.WPINC.'/class-feed.php');
// $feedin = get_stylesheet_directory().'/feed';
$feed = new SimplePie();

//$feed->set_sanitize_class( 'WP_SimplePie_Sanitize_KSES' );
//$feed->sanitize = new WP_SimplePie_Sanitize_KSES();
$feed->set_cache_class( 'WP_Feed_Cache' );
//$feed->set_file_class( 'WP_SimplePie_File' );

$feed->set_feed_url( $feedurl );
$feed->enable_order_by_date(false);
$feed->set_cache_location(get_template_directory_uri().'/feed');//缓存文件夹
$feed->init();
$feed->handle_content_type();
echo('<pre>');
print_r($feed);
echo('</pre>');
?>
<ul>
<?php 
	foreach ($feed->get_items(0,9) as $item){//9是文章篇数
		echo "<li><a href=".$item->get_permalink().">".$item->get_title()."</a><br>".substr($item->get_description(),0,180)."</li><br>".$item->get_Author().$item->get_date('Y-m-d | g:i a');
}?>
</ul>

<?php 


include_once(ABSPATH . WPINC . '/feed.php');
$rss = ANZ_fetch_feed($feedurl);
if (!is_wp_error( $rss ) ) {
	$maxitems = $rss->get_item_quantity(200);
	$rss_items = $rss->get_items(10, $maxitems); 
}
if ($maxitems != 0){
	foreach ( $rss_items as $item ){
		// echo '<a href='.$item->get_permalink().' title='.$item->get_date('j F Y | g:i a').'>'.$item->get_title().'</a>.'.$item->get_author().$item->get_date('j F Y | g:i a').'.<br/>'.$item->get_description().'<br>';
		//echo '<a href='.$item->get_permalink().' title='.$item->get_date('j F Y | g:i a').'>'.$item->get_title().'</a><br/>'.substr($item->get_description(),0,180).'<br>';
	}
}
?>

<?php get_footer(); ?>