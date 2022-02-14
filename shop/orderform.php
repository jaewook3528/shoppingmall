<?php
include_once("./_common.php");

if(TB_IS_MOBILE) {
	goto_url(TB_MSHOP_URL.'/orderform.php');
}

$ss_cart_id = get_session('ss_cart_id');
if(!$ss_cart_id)
//	alert("주문하실 상품이 없습니다.");

set_session('tot_price', '');
set_session('use_point', '');

$tb['title'] = '주문서작성';
include_once("./_head.php");

if($is_member) { // 회원일때
	// 주문자가 가맹점이면 추천인을 자신으로 변경
	$mb_recommend = $member['pt_id'];
	if(is_partner($member['id'])) {
		$mb_recommend = $member['id'];
	}
} else {
	$mb_recommend = $pt_id;
	$member['point'] = 0;
}

$order_action_url = TB_HTTPS_SHOP_URL.'/orderformupdate.php';
include_once(TB_THEME_PATH.'/orderform.skin.php');

include_once("./_tail.php");
?>