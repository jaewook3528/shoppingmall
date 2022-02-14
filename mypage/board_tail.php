<?php
if(!defined('_TUBEWEB_')) exit;

$file = TB_DATA_PATH.'/board/boardimg/'.$board['fileurl2'];
if(is_file($file) && $board['fileurl2']) {
	$file = rpc($file, TB_PATH, TB_URL);
	echo '<p><img src="'.$file.'"></p>';
}
?>
		<?php
		include_once(TB_MYPAGE_PATH."/admin_tail.sub.php"); 
		?>
	</div>
</div>

<?php
include_once(TB_MYPAGE_PATH."/admin_tail.php"); 
?>