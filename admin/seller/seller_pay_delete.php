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

	$sql = " update shop_order
				set sellerpay_yes = '0'
			  where index_no IN ({$_POST['order_idx'][$k]})
				and sellerpay_yes = '1' ";
	sql_query($sql, FALSE);

	$sql = " delete from shop_seller_cal where index_no = '{$_POST['index_no'][$k]}' ";
	sql_query($sql, FALSE);
}

goto_url(TB_ADMIN_URL."/seller.php?$q1&page=$page");
?>