<?php
include_once("./_common.php");

$ss_cart_id = get_session('ss_cart_id');

if(!$config['coupon_yes'])
    alert_close("쿠폰사용이 중지 되었습니다.");

if(!$is_member)
	alert_close("회원만 이용가능한 서비스 입니다");

if(!$ss_cart_id)
	alert_close("장바구니에 담긴 상품이 없습니다.");

$tb['title'] = "쿠폰적용 및 조회";
include_once(TB_MPATH."/head.sub.php");

$is_possible = array();
$cp_tmp = array();

$query = get_cp_precompose($member['id']);
$sql_common  = $query[0];
$sql_search  = $query[1];
$sql_order	 = $query[2];
$total_count = $query[3];

$rows = 10;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

$sql2 = " select * from shop_cart where index_no IN ({$ss_cart_id}) group by gs_id order by index_no ";
$result2 = sql_query($sql2);
$cart_count = sql_num_rows($result2);

include_once(TB_MTHEME_PATH.'/ordercoupon.skin.php');

include_once(TB_MPATH."/tail.sub.php");
?>