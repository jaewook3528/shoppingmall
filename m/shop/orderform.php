<?php
include_once("./_common.php");

if($member['id']!='')
{
    #계정 확인
    $ss_cart_id = get_cart_info('index_no');
}else{
    #쿠키 확인
    $ss_cart_id = get_session('ss_cart_id');
}
if(!$ss_cart_id)
	alert("주문하실 상품이 없습니다.");

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

// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
add_javascript(TB_POSTCODE_JS, 0);    //다음 주소 js

$order_action_url = TB_HTTPS_MSHOP_URL.'/orderformupdate.php';
include_once(TB_MTHEME_PATH."/orderform.skin.php");

include_once("./_tail.php");
?>