<?php
if(!defined('_TUBEWEB_')) exit;
?>

<div><img src="<?php echo TB_IMG_URL; ?>/seller_reg.gif"></div>
<div class="mart20">
	<?php echo get_view_thumbnail(conv_content($config['seller_reg_guide'], 1), 1000); ?>
</div>
<div class="btn_confirm">
	<a href="<?php echo TB_BBS_URL; ?>/seller_reg_from.php" class="btn_large wset">확인</a>
	<a href="<?php echo TB_URL; ?>" class="btn_large bx-white">취소</a>
</div>