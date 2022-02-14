<?php
include_once("./_common.php");

if(!$is_member)
	goto_url(TB_MBBS_URL.'/login.php?url='.$urlencode);

$tb['title'] = '포인트조회';
include_once("./_head.php");

$sql_common = " from shop_point ";
$sql_search = " where mb_id = '{$member['id']}' ";
$sql_order  = " order by po_id desc ";

$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 15;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

include_once(TB_MTHEME_PATH.'/point.skin.php');

include_once("./_tail.php");
?>