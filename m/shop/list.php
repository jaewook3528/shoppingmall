<?php
include_once("./_common.php");

$tb['title'] = get_catename($ca_id);
include_once("./_head.php");

$len = strlen($ca_id);
$sql_search = " and left(b.gcate,$len)='$ca_id' ";
$sql_common = get_sql_precompose($sql_search);
$sql_order  = " group by a.index_no ";

// 상품 정렬
if($sort && $sortodr)
	$sql_order .= " order by a.{$sort} {$sortodr}, a.rank desc, a.index_no desc ";
else
	$sql_order .= " order by a.rank desc, a.index_no desc ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(DISTINCT a.index_no) as cnt $sql_common ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$mod = 2; // 가로 출력 수
$rows = ($mod*9);
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select a.* $sql_common $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

include_once(TB_MTHEME_PATH.'/list.skin.php');

include_once("./_tail.php");
?>
