<?php //首页基础列表页面
	if (isset($_POST['feedUrl'])) {
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

		//object,RSS内容集合
		$feed = feedkim_fetch_feed($feedUrls);

		if ($feed->error()) {
			echo ('<pre>');
			echo "<p>RSS源无法读取，请删除</p>";
				print_r($feed->error());
			echo ('</pre>');
		}else{
			echo ('<ul>');
			foreach ($feed->get_items(0,9) as $item){//9是文章篇数
				echo "<li><a href=".$item->get_permalink().">".$item->get_title()."</a><br>".substr($item->get_description(),0,180)."</li>";
			}
			echo ('</ul>');
		}
	}
?>