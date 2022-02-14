<?php
include_once("./_common.php");

check_demo();

check_admin_token();

for($i=0; $i<count($chk); $i++)
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$sql = "update shop_member_grade
			   set gb_name = '{$_POST['gb_name'][$k]}',
				   gb_sale = '{$_POST['gb_sale'][$k]}',
				   gb_sale_unit = '{$_POST['gb_sale_unit'][$k]}',
				   gb_sale_rate = '{$_POST['gb_sale_rate'][$k]}'
			 where gb_no = '{$_POST['gb_no'][$k]}'";
	sql_query($sql);
}

goto_url(TB_ADMIN_URL."/member.php?code=level_form");
?>