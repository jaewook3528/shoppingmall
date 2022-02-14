<?php
include_once("./_common.php");

$od = sql_fetch("select * from shop_order where od_id='$od_id'");
if(!$od['od_id']) {
    alert("결제할 주문서가 없습니다.");
}

$tb['title'] = '결제하기';
include_once("./_head.php");

set_session('ss_order_id', $od_id);

$stotal = get_order_spay($od_id); // 총계
$tot_price = get_session('tot_price'); // 결제금액

$order_action_url = TB_HTTPS_MSHOP_URL.'/orderformresult.php';
include_once(TB_MTHEME_PATH.'/orderkakaopay.skin.php');

include_once("./_tail.php");
?>