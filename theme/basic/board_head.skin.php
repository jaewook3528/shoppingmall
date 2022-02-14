<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TB_THEME_PATH.'/aside_cs.skin.php');
?>

<div id="con_lf">
	<h2 class="pg_tit">
		<span><?php echo $board['boardname']; ?></span>
		<p class="pg_nav">HOME<i>&gt;</i>고객센터<i>&gt;</i><?php echo $board['boardname']; ?></p>
	</h2>

	<?php if($board['fileurl1']) { ?>
	<p class="marb10"><img src="<?php echo TB_DATA_URL; ?>/board/boardimg/<?php echo $board['fileurl1']; ?>"></p>
	<?php } ?>