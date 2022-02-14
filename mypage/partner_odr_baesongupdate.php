<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$count = count($_POST['chk']);
if(!$count) {
	alert('처리할 자료를 하나 이상 선택해 주십시오.');
}

for($i=0; $i<$count; $i++)
{
	// 실제 번호를 넘김
	$k = $_POST['chk'][$i];

	if(!isset($_POST['delivery'][$k]))
		continue;

	$sql = " update shop_order
			    set delivery	= '{$_POST['delivery'][$k]}'
				  , delivery_no = '{$_POST['delivery_no'][$k]}'
			  where od_no = '{$_POST['od_no'][$k]}' ";
	sql_query($sql);
}

goto_url(TB_MYPAGE_URL."/partner_odr_form.php?od_id=$od_id");
?>