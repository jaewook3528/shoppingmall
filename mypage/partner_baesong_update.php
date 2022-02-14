<?php
include_once("./_common.php");

check_demo();

check_admin_token();

if(!$pf_auth_good)
	alert('개별 상품판매 권한이 있어야만 이용 가능합니다.');

unset($value);
$value['delivery_method']	= $_POST['delivery_method'];
$value['delivery_price']	= conv_number($_POST['delivery_price']);
$value['delivery_price2']	= conv_number($_POST['delivery_price2']);
$value['delivery_minimum']	= conv_number($_POST['delivery_minimum']);	
$value['baesong_cont1']		= $_POST['baesong_cont1'];
$value['baesong_cont2']		= $_POST['baesong_cont2'];
update("shop_partner",$value,"where mb_id='$member[id]'");

goto_url(TB_MYPAGE_URL.'/page.php?code=partner_baesong');
?>