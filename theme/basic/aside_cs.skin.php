<?php
if(!defined('_TUBEWEB_')) exit;
?>

<!-- 좌측메뉴 시작 { -->
<aside id="aside">
	<div class="aside_hd">
		<p class="eng">CS CENTER</p>
		<p class="kor">고객센터</p>
	</div>
	<dl class="aside_cs">	
		<?php
		$sql = " select * from shop_board_conf where gr_id='gr_mall' order by index_no asc ";
		$res = sql_query($sql);
		for($i=0; $row=sql_fetch_array($res); $i++) {
			$bo_href = TB_BBS_URL.'/list.php?boardid='.$row['index_no'];
			echo '<dt><a href="'.$bo_href.'">'.$row['boardname'].'</a></dt>'.PHP_EOL;
		}
		?>	
		<dt><a href="<?php echo TB_BBS_URL; ?>/review.php">고객상품평</a></dt>
		<dt><a href="<?php echo TB_BBS_URL; ?>/qna_list.php">1:1 상담문의</a></dt>		
		<dt><a href="<?php echo TB_BBS_URL; ?>/faq.php?faqcate=1">자주묻는질문</a></dt>		
		<?php
		// FAQ MASTER
        $fm_sql = get_faq_table_sql($pt_id,'shop_faq_cate');
		$fm_result = sql_query($fm_sql);
		for($i=0;$row=sql_fetch_array($fm_result);$i++){
			if($i==0) echo "<dd>\n<ul>\n";
			$fm_href = TB_BBS_URL.'/faq.php?faqcate='.$row['index_no'];
			echo '<li><a href="'.$fm_href.'">'.$row['catename'].'</a></li>'.PHP_EOL;
		}
		if($i > 0) echo "</ul>\n</dd>\n";
		?>
	</dl>
</aside>
<!-- } 좌측메뉴 끝 -->
