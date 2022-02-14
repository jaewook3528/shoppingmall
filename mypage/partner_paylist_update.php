<?php
include_once("./_common.php");

check_demo();

check_admin_token();

// 관리비를 사용중일때 기간이 만료되었다면 출금신청 차단
if($config['pf_expire_use'] && $config['pf_account_no']) {
	if(!is_null_time($member['term_date'])) {
		if($member['term_date'] < TB_TIME_YMD) {
			alert("회원님께서는 관리비 미납으로 출금신청을 하실 수 없습니다.");
		}
	}
}

$reg_price = conv_number($_POST['reg_price']);

$paytax = 0;
if($config['pf_payment_tax']) { // 세액공제
	$paytax = floor(($reg_price * $config['pf_payment_tax']) / 100);
}

$paynet = $reg_price - $paytax;	

unset($value);
$value['mb_id']			= $member['id'];
$value['balance']		= $reg_price;
$value['paytax']		= $paytax;
$value['paynet']		= $paynet;
$value['bank_name']		= $bank_name;
$value['bank_account']	= $bank_account;
$value['bank_holder']	= $bank_holder;
$value['reg_time']		= TB_TIME_YMDHIS;
insert("shop_partner_payrun", $value);

goto_url(TB_MYPAGE_URL.'/page.php?code=partner_paylist');
?>