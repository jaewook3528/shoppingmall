<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$count = count($_POST['chk']);
if(!$count) {
	alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

if($_POST['act_button'] == "선택승인")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$mb_id = trim($_POST['mb_id'][$k]);
		$seller_code = trim($_POST['seller_code'][$k]);

		sql_query("update shop_seller set state='1', seller_open='1' where mb_id='$mb_id'");
		sql_query("update shop_member set supply='Y' where id='$mb_id'");	
		sql_query("update shop_goods set isopen='1' where mb_id='$seller_code'");	
	}
} 
else if($_POST['act_button'] == "선택삭제") 
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$sql = " delete from shop_seller where mb_id = '{$_POST['mb_id'][$k]}' ";
		sql_query($sql);
	}
}

goto_url(TB_ADMIN_URL."/seller.php?$q1&page=$page");
?>