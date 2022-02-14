<?php
define('_PURENESS_', true);
include_once("./_common.php");

if(!$is_member) 
	die('로그인 후 이용하여 주십시오.');

$reg_hp = trim($_POST['reg_hp']);
$reg_price = number_format($_POST['reg_price']).'원';
$reg_bank = trim($_POST['reg_bank']);

if($reg_bank == '') {
	die('계좌번호를 선택해주세요.');
}

if($reg_hp) {
	$msg = "[{$config['company_name']}]\n결제금액 : {$reg_price}\n{$reg_bank}\n입금부탁드립니다.";
		
	// 신청자(회원)에게 문자전송
	icode_member_send($reg_hp, $msg);
	
	die('계좌정보가 문자로 발송되었습니다.');
} else {
	die('휴대폰번호를 입력해주세요.');
}
?>