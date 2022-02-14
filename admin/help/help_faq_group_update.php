<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$count = count($_POST['chk']);
if($_POST['act_button'] != "추가" && !$count) {
	alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

if($_POST['act_button'] == "추가") {
	$sql = " insert shop_faq_cate set catename = '{$_POST['catename']}' ";
	sql_query($sql);
} 
else if($_POST['act_button'] == "선택수정")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$sql = " update shop_faq_cate 
					set catename = '{$_POST['catename'][$k]}'
				  where index_no = '{$_POST['index_no'][$k]}' ";
		sql_query($sql);
	}
} 
else if($_POST['act_button'] == "선택삭제") 
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$sql = " delete from shop_faq_cate where index_no = '{$_POST['index_no'][$k]}' ";
		sql_query($sql);
	}
}

goto_url(TB_ADMIN_URL."/help.php?$q1&page=$page");
?>