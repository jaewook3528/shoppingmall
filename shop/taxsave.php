<?php
include_once('./_common.php');

$tb['title'] = '주문번호 '.$od_id.' 현금영수증 발행';
include_once(TB_PATH.'/head.sub.php');

$od = sql_fetch(" select * from shop_order where od_id = '$od_id' ");
if(!$od)
	alert_close('주문서가 존재하지 않습니다.');

$goods = get_full_name($od['od_id']);
$goods_name = $goods['full_name'];
$dir = $od['od_pg'];
$od_name = $od['name'];
$od_email = get_text($od['email']);
$od_tel = get_text($od['cellphone']);

$amt_tot = (int)$od['od_tax_mny'] + (int)$od['od_vat_mny'] + (int)$od['od_free_mny'];
$amt_sup = (int)$od['od_tax_mny'] + (int)$od['od_free_mny'];
$amt_tax = (int)$od['od_vat_mny'];
$amt_svc = 0;

$trad_time = date("YmdHis");

// 신청폼
if(!$dir)
    $dir = $default['de_pg_service'];

include_once(TB_SHOP_PATH.'/'.$dir.'/taxsave_form.php');

include_once(TB_PATH.'/tail.sub.php');
?>