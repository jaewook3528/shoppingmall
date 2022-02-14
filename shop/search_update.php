<?php
include_once("./_common.php");

$ss_tx = trim(strip_tags($ss_tx));

if($_POST['hash_token'] && TB_HASH_TOKEN == $_POST['hash_token']) {		
	get_sql_search($ss_tx, $pt_id);

	goto_url(TB_SHOP_URL."/search.php?ss_tx=".urlencode($ss_tx));
} else {
	alert("잘못된 접근 입니다.", TB_URL);
}
?>