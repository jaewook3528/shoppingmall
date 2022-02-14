<?php
include_once("./_common.php");

check_demo();

check_admin_token();

unset($value);
$value['mb_id']			= $member['id'];
$value['expire_date']	= $_POST['expire_date'];
$value['term_price']	= $_POST['term_price'];
$value['pay_method']	= $_POST['pay_method'];
$value['deposit_name']	= $_POST['deposit_name'];
$value['bank_account']	= $_POST['bank_account'];
$value['reg_time']		= TB_TIME_YMDHIS;
insert("shop_partner_term", $value);

goto_url(TB_MYPAGE_URL.'/page.php?code=partner_term');
?>