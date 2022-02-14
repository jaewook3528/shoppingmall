<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<div class="m_bo_bg mart10">
	<div class="title"><?php echo $bo_subject; ?></div>
	<div class="wr_name"><?php echo $write['writer_s']; ?><span class="wr_day"><?php echo $bo_wdate; ?></span></div>
	<div class="wr_txt">
		<?php
		$file1 = TB_DATA_PATH."/board/{$boardid}/{$write['fileurl1']}";
		if(is_file($file1) && preg_match("/\.(gif|jpg|png)$/i", $write['fileurl1'])) {
			$file1 = rpc($file1, TB_PATH, TB_URL);
		?>
		<img src="<?php echo $file1; ?>" class="img_fix">
		<?php } ?>
		<?php
		$file2 = TB_DATA_PATH."/board/{$boardid}/{$write['fileurl2']}";
		if(is_file($file2) && preg_match("/\.(gif|jpg|png)$/i", $write['fileurl2'])) {
			$file2 = rpc($file2, TB_PATH, TB_URL);
		?>
		<img src="<?php echo $file2; ?>" class="img_fix">
		<?php } ?>
		<p><?php echo get_image_resize($write['memo']); ?></p>
	</div>
</div>

<div class="btn_confirm">
	<a href="<?php echo TB_MBBS_URL; ?>/board_list.php?<?php echo $qstr1;?>" class="btn_medium bx-white">목록</a>
	<?php if($member['grade']<=$board['reply_priv'] && $board['usereply']=='Y') { ?>
	<a href="<?php echo TB_MBBS_URL; ?>/board_write.php?<?php echo $qstr2;?>&w=r" class="btn_medium bx-white">답변</a>
	<?php } if(($mb_no == $write['writer']) || is_admin()) { ?>
	<a href="<?php echo TB_MBBS_URL; ?>/board_write.php?<?php echo $qstr2;?>&w=u" class="btn_medium bx-white">수정</a>
	<a href="<?php echo TB_MBBS_URL; ?>/board_delete.php?<?php echo $qstr2;?>" class="btn_medium bx-white">삭제</a>
	<?php } ?>
</div>
