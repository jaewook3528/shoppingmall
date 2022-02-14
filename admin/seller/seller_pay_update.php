<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$count = count($_POST['chk']);
if(!$count) {
	alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

for($i=0; $i<$count; $i++)
{
    // 실제 번호를 넘김
    $k = $_POST['chk'][$i];

	$sql = "insert into shop_seller_cal
			   set mb_id = '{$_POST['mb_id'][$k]}'
			     , order_idx = '{$_POST['order_idx'][$k]}'
				 , tot_price = '{$_POST['tot_price'][$k]}'
				 , tot_point = '{$_POST['tot_point'][$k]}'
				 , tot_coupon = '{$_POST['tot_coupon'][$k]}'
				 , tot_baesong = '{$_POST['tot_baesong'][$k]}'
				 , tot_supply = '{$_POST['tot_supply'][$k]}'
				 , tot_seller = '{$_POST['tot_seller'][$k]}'
				 , tot_partner = '{$_POST['tot_partner'][$k]}'
				 , tot_admin = '{$_POST['tot_admin'][$k]}'
				 , reg_time = '".TB_TIME_YMDHIS."' ";
	sql_query($sql, FALSE);

	$sql = " update shop_order
				set sellerpay_yes = '1'
			  where index_no IN ({$_POST['order_idx'][$k]})
				and sellerpay_yes = '0' ";
	sql_query($sql, FALSE);
}

goto_url(TB_ADMIN_URL."/seller.php?$q1&page=$page");
?>