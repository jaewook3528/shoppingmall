<?php
include_once("./_common.php");

$pr_idx = get_session("ss_pr_idx");

// 최근 본 상품 세션 삭제
if($w == 'd') {
	check_demo();

	set_session("ss_pr_idx", "");
	$tmp_idx = explode(",", $pr_idx);
	for($i=0; $i<count($tmp_idx); $i++) {
        if($tmp_idx[$i] != $gs_id) {
            $tmp_saved .= $comma . $tmp_idx[$i];
			$comma = ',';
        }
    }

	set_session("ss_pr_idx", $tmp_saved);
	goto_url(TB_MSHOP_URL."/today.php");
}

if($pr_idx) {
	$sql_common = " from shop_goods ";
	$sql_search = " where index_no IN ({$pr_idx}) ";
	$sql_order = "order by index_no desc ";

	$sql = " select count(*) as cnt $sql_common $sql_search ";
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];

	$rows = 10;
	$total_page  = ceil($total_count / $rows); // 전체 페이지 계산
	if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
	$from_record = ($page - 1) * $rows; // 시작 열을 구함

	$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
	$result = sql_query($sql);
}

$tb['title'] = "최근본상품";
include_once("./_head.php");
include_once(TB_MTHEME_PATH."/today.skin.php");
include_once("./_tail.php");
?>