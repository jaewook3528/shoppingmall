<?php
include_once("./_common.php");

check_demo();

check_admin_token();

unset($value);
$value['de_naverpay_mid']		 = $_POST['de_naverpay_mid'];  
$value['de_naverpay_cert_key']	 = $_POST['de_naverpay_cert_key'];
$value['de_naverpay_button_key'] = $_POST['de_naverpay_button_key'];
$value['de_naverpay_test']		 = $_POST['de_naverpay_test'];
$value['de_naverpay_mb_id']		 = $_POST['de_naverpay_mb_id'];
$value['de_naverpay_sendcost']	 = $_POST['de_naverpay_sendcost'];
update("shop_default", $value);

goto_url(TB_ADMIN_URL.'/config.php?code=naverpay');
?>