<?php
get_header();
$feedurl = array(
	'https://www.leiue.com/feed',
	'https://zhangzifan.com/feed',
	'https://www.limiwu.com/feed'
);//FEED数组
require_once (ABSPATH.WPINC.'/class-feed.php');
// $feedin = get_stylesheet_directory().'/feed';
$feed = new SimplePie();
$feed->set_feed_url( $feedurl );
// $feed->enable_order_by_date(false);
// $feed->set_cache_location($_SERVER['DOCUMENT_ROOT'] . '/cache');//缓存文件夹
// $feed->init();
// $feed->handle_content_type();
?>
<ul>
<?php foreach ($feed->get_items(0,9) as$item)://9是文章篇数 ?>
<li>
    <a href="<?php echo $item->get_permalink()?>"><?php echo $item->get_title()?></a><!--标题链接-->
    <?php echosubstr($item->get_description(),0,180);?><!--描述-->
<?php endforeach; ?>
</li>
</ul>

<?php 
include_once(ABSPATH . WPINC . '/feed.php');
$rss = fetch_feed($feedurl);
if (!is_wp_error( $rss ) ) {
	$maxitems = $rss->get_item_quantity(20);
	$rss_items = $rss->get_items(0, $maxitems); 
}
if ($maxitems != 0){
	foreach ( $rss_items as $item ){
		echo '<a href='.$item->get_permalink().' title='.$item->get_date('j F Y | g:i a').'>'.$item->get_title().'</a><br/>';
	}
}
?>

<?php get_footer(); ?>