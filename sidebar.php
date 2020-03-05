<?php 
	if(is_404() || is_category() || is_day() || is_month() ||
				is_year() || is_search() || is_paged()){
	}else{
		dynamic_sidebar(__('文章右侧栏','feedkim'));
	}
?>