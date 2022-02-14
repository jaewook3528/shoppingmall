<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$delivery_company = '';
$delivery_count = count($_POST['spl_name']);
if($delivery_count) {
	$arr_spl = array();
	for($i=0; $i<$delivery_count; $i++) {
		$spl_val = trim($_POST['spl_name'][$i]).'|'.trim($_POST['spl_url'][$i]);
		$arr_spl[] = $spl_val;
	}
	$delivery_company = implode(',', $arr_spl);
}

unset($value);
$value['delivery_company']	= $delivery_company;
$value['delivery_method']	= $_POST['delivery_method'];
$value['delivery_price']	= conv_number($_POST['delivery_price']);
$value['delivery_price2']	= conv_number($_POST['delivery_price2']);
$value['delivery_minimum']	= conv_number($_POST['delivery_minimum']);
$value['delivery_totmethod']	= $_POST['delivery_totmethod'];
$value['delivery_totminimum']	= conv_number($_POST['delivery_totminimum']);
$value['baesong_cont1']		= $_POST['baesong_cont1'];
$value['baesong_cont2']		= $_POST['baesong_cont2'];
update("shop_config", $value);

goto_url(TB_ADMIN_URL.'/config.php?code=baesong');
?>