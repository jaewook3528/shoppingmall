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

		$sql = " update shop_board_group
					set gr_subject = '{$_POST['gr_subject'][$k]}'
				  where gr_id = '{$_POST['gr_id'][$k]}' ";
		sql_query($sql);
	}
} 
else if($_POST['act_button'] == "선택삭제") 
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$gr_id = trim($_POST['gr_id'][$k]);

		$row = sql_fetch(" select count(*) as cnt from shop_board_conf where gr_id = '$gr_id' ");
		if($row['cnt'])
			alert("이 그룹에 속한 게시판이 존재하여 게시판 그룹을 삭제할 수 없습니다.\\n\\n이 그룹에 속한 게시판을 먼저 삭제하여 주십시오.", TB_ADMIN_URL."/config.php?code=board_list&sfl=gr_id&stx=$gr_id");

		// 그룹 삭제
		sql_query(" delete from shop_board_group where gr_id = '$gr_id' ");
	}
}

goto_url(TB_ADMIN_URL."/config.php?$q1&page=$page");
?>