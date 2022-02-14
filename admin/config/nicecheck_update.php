<?php
include_once("./_common.php");

check_demo();

check_admin_token();

unset($value);
$value['de_certify_use']	= $_POST['de_certify_use']; // 실명인증 사용여부
$value['de_certify_nm']		= $_POST['de_certify_nm']; // 실명인증 회사
$value['de_checkplus_id']	= $_POST['de_checkplus_id']; // [본인인증] 사이트 코드
$value['de_checkplus_pw']	= $_POST['de_checkplus_pw']; // [본인인증] 사이트 패스워드
$value['de_ipin_id']		= $_POST['de_ipin_id']; // [아이핀] 사이트 코드
$value['de_ipin_pw']		= $_POST['de_ipin_pw']; // [아이핀] 사이트 패스워드 
update("shop_default", $value);

goto_url(TB_ADMIN_URL.'/config.php?code=nicecheck');
?>