<?php
include_once("./_common.php");

check_demo();

check_admin_token();

unset($value);
$value['partner_point']      = conv_number($_POST['partner_point']);  //추천인 포인트
$value['register_point']	 = conv_number($_POST['register_point']);  //회원가입 포인트
$value['register_use_hp']	 = $_POST['register_use_hp']; //핸드폰 입력
$value['register_req_hp']	 = $_POST['register_req_hp']; //핸드폰 필수입력
$value['register_use_tel']   = $_POST['register_use_tel']; //전화번호 입력
$value['register_req_tel']   = $_POST['register_req_tel']; //전화번호 필수입력
$value['register_use_addr']  = $_POST['register_use_addr']; //주소 입력
$value['register_req_addr']  = $_POST['register_req_addr']; //주소 필수입력
$value['register_use_email'] = $_POST['register_use_email']; //이메일 입력
$value['register_req_email'] = $_POST['register_req_email']; //이메일 필수입력
$value['prohibit_id']		 = $_POST['prohibit_id']; //아이디 금지단어
$value['prohibit_email']	 = $_POST['prohibit_email']; //입력 금지 메일
$value['shop_provision']	 = $_POST['shop_provision']; // 회원가입약관
$value['shop_private']		 = $_POST['shop_private']; // 개인정보 수집 및 이용
$value['shop_policy']		 = $_POST['shop_policy']; // 개인정보처리방침
update("shop_config", $value);

goto_url(TB_ADMIN_URL.'/config.php?code=register');
?>