<?php
if(!defined('_TUBEWEB_')) exit; // 개별 페이지 접근 불가

$kw_shop_id = $default['de_kiwoom_mid'];
$kw_cross_key = $default['de_kiwoom_crosskey'];

/**************************
 * 라이브러리 인클루드 *
 **************************/
include_once(TB_PATH.'/plugin/kiwoompay/UfKiwoomPay.php');
$ufKiwoomPay = new UfKiwoomPay();

$at_pay_method = array(
    '신용카드'	=> 'CARD',
    '계좌이체'	=> 'ABANK',
	'가상계좌'	=> 'VBANK',
    '휴대폰'	=> 'HP'
);
?>