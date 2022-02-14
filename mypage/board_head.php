<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TB_MYPAGE_PATH."/admin_head.php");

$pg_title = $board['boardname'];
?>

<div id="wrapper">
	<div id="snb">
		<?php
		include_once($admin_snb_file);
		?>
	</div>
	<div id="content">
		<?php
		include_once(TB_MYPAGE_PATH."/admin_head.sub.php");

		$file = TB_DATA_PATH.'/board/boardimg/'.$board['fileurl1'];
		if(is_file($file) && $board['fileurl1']) {
			$file = rpc($file, TB_PATH, TB_URL);
			echo '<p><img src="'.$file.'"></p>';
		}
		?>
