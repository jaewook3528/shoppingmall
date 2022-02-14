<?php
include_once("./_common.php");

check_demo();

check_admin_token();

unset($value);
$value['shop_provision'] = $_POST['shop_provision']; // 회원가입약관
$value['shop_private']	 = $_POST['shop_private']; // 개인정보 수집 및 이용
$value['shop_policy']	 = $_POST['shop_policy']; // 개인정보처리방침
update("shop_partner",$value,"where mb_id='$member[id]'");

goto_url(TB_MYPAGE_URL.'/page.php?code=partner_agree');
?>