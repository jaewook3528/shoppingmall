<?php
include_once("./_common.php");

check_demo();

check_admin_token();

if(!$pf_auth_pg)
	alert('개별 PG결제 사용 권한이 없습니다.');

if(!get_session('admin_ss_mb_id'))
	alert('최고관리자만 접근하실 수 있습니다.');

unset($value);
$value['de_kakaopay_mid']		= $_POST['de_kakaopay_mid']; // 카카오페이 상점MID
$value['de_kakaopay_key']		= $_POST['de_kakaopay_key']; // 카카오페이 상점키
$value['de_kakaopay_enckey']	= $_POST['de_kakaopay_enckey']; // 카카오페이 EncKey
$value['de_kakaopay_hashkey']	= $_POST['de_kakaopay_hashkey']; // 카카오페이 HashKey
$value['de_kakaopay_cancelpwd']	= $_POST['de_kakaopay_cancelpwd']; // 카카오페이 결제취소 비밀번호
update("shop_partner",$value,"where mb_id='$member[id]'");

goto_url(TB_MYPAGE_URL.'/page.php?code=partner_kakaopay');
?>