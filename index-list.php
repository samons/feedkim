<?php //首页基础列表页面
	$feedUrls = array(get_bloginfo('comments_rss2_url'));
	if (isset($_POST['feedUrl'])) {
		/**
		 * 解析$_POST['feedUrl']值
		 * @since 2020-2-14
		 * @param String $_POST['feedUrl']
		 * @return array $feed
		 */
		$feedUrls = explode(',',$_POST['feedUrl']);
	}

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
		// echo('<pre>');
		// print_r($feed);
		// echo('</pre>');
		$prePage = get_option('posts_per_page');//单页显示文章数
		$paged = ($_POST['feedKimPaged']) ? $_POST['feedKimPaged'] : 0;//当前页数
		$pageCount = count($feed->get_items());//文章总数
		$pagedNum = $prePage * $paged;//文章开始第几篇

		echo ('<ul>');
		foreach ($feed->get_items($pagedNum,$prePage) as $item){
			$author = ($item->get_author()) ? $item->get_author()->get_name() : null; 
			$timeID = $item->get_date('YmdgiA');//跳窗ID
			$description = $item->get_description();//获取RSS的des
			$number = strlen($description);
			$p = strip_tags($description);
			$html_p = mb_substr($p,0,140);
			$imagesArray = feedkim_get_images($description);//[0]所有图片[1]所有图片地址
			echo "<li>";?>

			<div class="media">
				<div class="media-left">
				    <a target="_blank" rel="nofollow" href="<?php echo $item->get_permalink();?>" title="<?php echo $item->get_title();?>">
				      <img class="media-object" src="<?php echo $item->get_base();?>/favicon.ico" alt="favicon.ico" onerror="javascript:this.src='<?php bloginfo('template_url'); ?>/image/favicon.ico';">
				    </a>
				</div>
				<div class="media-body">
				    <h5 class="media-heading"><?php echo $item->get_title();?></h5>
				    <h6 class="media-about"><span class="glyphicon glyphicon-user"></span> <?php echo $author;//作者?> <span class="glyphicon glyphicon-dashboard"></span> <?php echo $item->get_date('Y-m-d g:i A');//发布时间?><span class="glyphicon glyphicon-menu-down float-right"></span></h6>
				    <?php
				    if ($number<=180) {
				    	echo '<p>'.$p.'</p>';
				    }else{
				    	//echo $description;
				    ?>
				    <p><?php echo $html_p;?>
					<button type="button" class="btn-link" data-toggle="modal" data-target="#<?php echo $timeID;?>">[...]</button>
					</p>
					<!-- 完整的descriptionn内容 -->
					<div class="modal fade" id="<?php echo $timeID;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
					  <div class="modal-dialog" role="document">
					    <div class="modal-content">
					      <div class="modal-header">
					        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					        <h4 class="modal-title" id="myModalLabel"><?php echo $item->get_title();?></h4>
					      </div>
					      <div class="modal-body">
					        <?php echo $description;?>
					      </div>
					      <div class="modal-footer">
					        <button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('关闭窗口','feedkim');?></button>
					      </div>
					    </div>
					  </div>
					</div><!-- modal end -->
					<?php }//end description
					if ($imagesArray) {
						if (count($imagesArray[0])==1) {
							echo $imagesArray[0][0];
						}else{
							echo '<div class="images-box row">';
							foreach ($imagesArray[1] as $imageUrl) {
								echo '<div class="col-md-4 col-xs-6" style="background-image:url('.$imageUrl.')"></div>';
							}
							echo '<span class="clearfix"></span></div>';
						}
					}//end $images
					?>
				</div>
			</div>
			<?php
			echo "</li>";
		}//end foreach $feed
		//分页，最后用JQ隐藏了，为了无限下拉
		$havepages = $pageCount-$prePage;
		if($havepages>0){
			$PagedAll = ceil($pageCount/$prePage) - 1;
			$PrePagedNum = $paged - 1;//前一页
			$NextPagedNum = $paged + 1;//后一页
		?>
		<li>
			<nav>
			  <ul class="pager"><form method="POST" action="" role="form">
			  	<input type="text" value="<?php echo $_POST['feedUrl']?>" name="feedUrl" class="display">
			  	
			  	<?php if($PrePagedNum >= 0):?>
			  	<li class="previous">
			  		<button type="submit" class="btn btn-default" name="feedKimPaged" value="<?php echo $PrePagedNum;?>"><span aria-hidden="true">&larr;</span> <?php _e('上一页','feedkim').',';?></button>
			  	</li>
				<?php endif?>

				<?php if($NextPagedNum <= $PagedAll):?>
				<li class="next">
			  		<button type="submit" class="btn btn-default" name="feedKimPaged" value="<?php echo $NextPagedNum;?>"><?php _e('下一页','feedkim').',';?> <span aria-hidden="true">&rarr;</span></button>
			  	</li>
    			<?php endif?>

			  </form></ul>
			</nav>
		</li>
		<?php
		}//end pages nav
		echo ('</ul>');
	}
?>