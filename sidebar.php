<?php 
if (is_single()){
	dynamic_sidebar(__('文章右侧栏','feedkim'));
}else{
	dynamic_sidebar(__('文章列表右侧栏','feedkim'));
}
?>