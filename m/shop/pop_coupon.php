<?php
include_once("./_common.php");

if(!$config['coupon_yes']) {
    alert_close("쿠폰사용이 중지 되었습니다.");
}

if(!$is_member) {
	alert_close('회원만 이용가능한 서비스 입니다');
}

$tb['title'] = "쿠폰 다운받기";
include_once(TB_MPATH."/head.sub.php");

$gs = get_goods($gs_id);
$cp_list = is_used_coupon('0', $gs_id);

if(!$cp_list) $cp_list = "NULL";

$sql_common = " from shop_coupon ";
$sql_search = " where cp_id in ({$cp_list}) ";
$sql_order  = " order by cp_id ";

$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 5;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

$token = md5(uniqid(rand(), true));
set_session('ss_token', $token);

include_once(TB_MTHEME_PATH.'/pop_coupon.skin.php');

include_once(TB_MPATH."/tail.sub.php");
?>