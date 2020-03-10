<?php
function feedKim_list($feedurls){
	//object,RSS内容集合
	$feed = feedkim_fetch_feed($feedUrls);

	if ($feed->error()) {
		echo ('<pre>');
		echo "<p>RSS源无法读取，请删除</p>";
			print_r($feed->error());
		echo ('</pre>');
	}else{
		$prePage = get_option('posts_per_page');//单页显示文章数
		$paged = ($_POST['feedKimPaged']) ? $_POST['feedKimPaged'] : 0;//当前页数
		$pageCount = count($feed->get_items());//文章总数
		$pagedNum = $prePage * $paged;//文章开始第几篇
	}
}
?>