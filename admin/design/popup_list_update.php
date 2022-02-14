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

		$sql = " update shop_popup
					set title = '{$_POST['title'][$k]}',
						device = '{$_POST['device'][$k]}',
						width = '{$_POST['width'][$k]}',
						height = '{$_POST['height'][$k]}',
						top = '{$_POST['top'][$k]}',
						lefts = '{$_POST['lefts'][$k]}'
				  where index_no = '{$_POST['pp_id'][$k]}' ";
		sql_query($sql);
	}
} 
else if($_POST['act_button'] == "선택삭제") 
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$pp_id = trim($_POST['pp_id'][$k]);

		// 삭제
		$sql = "select memo from shop_popup where index_no='$pp_id' ";
		$row = sql_fetch($sql);

		delete_editor_image($row['memo']);

		sql_query("delete from shop_popup where index_no='$pp_id'");	
	}
}

goto_url(TB_ADMIN_URL."/design.php?$q1&page=$page");
?>