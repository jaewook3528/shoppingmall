<?php
if(!defined('_TUBEWEB_')) exit; // 개별 페이지 접근 불가

$at_shop_id = $default['de_allat_mid'];
$at_cross_key = $default['de_allat_crosskey'];

/**************************
 * 라이브러리 인클루드 *
 **************************/
include_once(TB_SHOP_PATH.'/allat/allatutil.php');

/* 기타 */
$receive_url = TB_SHOP_URL.'/allat/allat_receive.php';

$at_pay_method = array(
    '신용카드'	=> 'CARD',
    '계좌이체'	=> 'ABANK',
	'가상계좌'	=> 'VBANK',
    '휴대폰'	=> 'HP'
);
?>