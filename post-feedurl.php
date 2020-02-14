<?php
/**
 * 解析$_POST['feedUrl']值
 * @since 2020-2-14
 * @param String $_POST['feedUrl']
 * @return array $feed
 */
$feedUrls = explode(',',$_POST['feedUrl']);
// 删除无效feedUrl
foreach ($feedUrls as $key => $value) {
	if (!feedkim_file_exists($value)) {
		unset($feedUrls[$key]);
	}
}

$feed = feedkim_fetch_feed($feedUrls);
foreach ($feed->get_items(0,9) as $item){
	
}
	//print_r($feed);
}
?>