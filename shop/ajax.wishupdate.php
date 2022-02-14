<?php
define('_PURENESS_', true);
include_once("./_common.php");

$gs_id = trim($_POST['gs_id']);

if(!$is_member) {
	die('회원 전용 서비스입니다.');
}

if(!$gs_id) {
	die('잘못된 접근입니다.');
}

$sql = " select count(*) as cnt from shop_wish where mb_id = '{$member['id']}' and gs_id = '{$gs_id}' ";
$row = sql_fetch($sql);

if($row['cnt']) {
	$sql = " delete from shop_wish where mb_id = '{$member['id']}' and gs_id = '{$gs_id}' ";
	sql_query($sql);
	die('DELETE');
} else {
	$sql = " insert shop_wish set mb_id = '{$member[id]}', gs_id = '{$gs_id}', wi_time = '".TB_TIME_YMDHIS."', wi_ip = '{$_SERVER[REMOTE_ADDR]}' ";
	sql_query($sql);
	die('INSERT');
}
?>