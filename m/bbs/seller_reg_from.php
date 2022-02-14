<?php
include_once("./_common.php");

if(!$config['seller_reg_yes']) {
	alert('서비스가 일시 중단 되었습니다.', TB_MURL);
}

if(!$is_member) {
	goto_url(TB_MBBS_URL.'/login.php?url='.$urlencode);
}

if(is_admin()) {
	alert('관리자는 신청을 하실 수 없습니다.');
}

$tb['title'] = '온라인 입점신청';
include_once("./_head.php");

add_javascript(TB_POSTCODE_JS, 0); //다음 주소 js

if($seller['mb_id']) {
	include_once(TB_MTHEME_PATH.'/seller_reg_result.skin.php');
} else {
	$token = md5(uniqid(rand(), true));
	set_session("ss_token", $token);

	$from_action_url = TB_HTTPS_MBBS_URL.'/seller_reg_from_update.php';
	include_once(TB_MTHEME_PATH.'/seller_reg_from.skin.php');
}

include_once("./_tail.php");
?>