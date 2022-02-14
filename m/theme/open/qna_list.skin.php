<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<div class="m_bo_bg">
	<ul>
		<?php
		for($i=0; $row=sql_fetch_array($result); $i++) {
			$bo_date = $row['mb_id']."<span class='padl10'>".substr($row['wdate'],0,10);
		?>
		<li class="list">
			<a href="<?php echo TB_MBBS_URL; ?>/qna_read.php?index_no=<?php echo $row['index_no']; ?>">
			<p class="subj"><b class="cate">[ <?php echo $row['catename']; ?> ]</b> <?php echo cut_str($row['subject'],60); ?></p>
			<p class="date"><?php echo $bo_date; ?></p>
			</a>
		</li>
		<?php
		}
		if($i==0) echo '<li class="empty_list">자료가 없습니다.</li>';
		?>
	</ul>

	<?php 
	echo get_paging($config['mobile_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?page='); 
	?>

	<div class="btn_confirm">
		<p><a href="<?php echo TB_MBBS_URL; ?>/qna_write.php" class="btn_medium wset">상담문의하기</a></p>
	</div>

</div>