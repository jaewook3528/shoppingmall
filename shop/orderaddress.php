<?php
include_once("./_common.php");

if(!$is_member) {
	alert_close("회원 전용 서비스입니다.");
}

$tb['title'] = '배송지 목록';
include_once(TB_PATH."/head.sub.php");

$sql_common = " from shop_order ";
$sql_search = " where mb_id = '{$member['id']}' ";
$sql_order  = " group by od_id order by od_time desc ";

$sql = " select COUNT(DISTINCT od_id) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$sql = " select * $sql_common $sql_search $sql_order ";
$result = sql_query($sql);

include_once(TB_THEME_PATH.'/orderaddress.skin.php');

include_once(TB_PATH."/tail.sub.php");
?>