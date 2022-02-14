<?php
include_once("./_common.php");

$tb['title'] = "구매후기";
include_once(TB_MPATH."/head.sub.php");

$gs = get_goods($gs_id);

// 카테고리 정보
$ca = sql_fetch("select * from shop_goods_cate where gs_id='$gs_id'");

$q1 = "gs_id=$gs_id";

$sql_common = " from shop_goods_review ";
$sql_search = " where gs_id = '$gs_id' ";
if($default['de_review_wr_use']) {
	$sql_search .= " and pt_id = '$pt_id' ";
}
$sql_order  = " order by index_no desc ";

$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 10;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

include_once(TB_MTHEME_PATH."/view_user.skin.php");

include_once(TB_MPATH."/tail.sub.php");
?>