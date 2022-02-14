<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$count = count($_POST['chk']);
if(!$count) {
	alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

if($_POST['act_button'] == "선택수정")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$sql = "update shop_plan 
				   set pl_name = '{$_POST['pl_name'][$k]}',
					   pl_use = '{$_POST['pl_use'][$k]}'
				 where pl_no = '{$_POST['pl_no'][$k]}'";
		sql_query($sql);
	}
} 
else if($_POST['act_button'] == "선택삭제") 
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$pl_no = trim($_POST['pl_no'][$k]);

		$row = sql_fetch("select * from shop_plan where pl_no = '$pl_no'");
		@unlink(TB_DATA_PATH."/plan/".$row['pl_limg']);
		@unlink(TB_DATA_PATH."/plan/".$row['pl_bimg']);

		sql_query("delete from shop_plan where pl_no='$pl_no' ");
	}
}

goto_url(TB_ADMIN_URL."/goods.php?$q1&page=$page");
?>