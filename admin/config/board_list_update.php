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

		$sql = " update shop_board_conf
					set gr_id = '{$_POST['gr_id'][$k]}',
						boardname = '{$_POST['bo_subject'][$k]}'
				  where index_no = '{$_POST['bo_table'][$k]}' ";
		sql_query($sql);
	}
} 
else if($_POST['act_button'] == "선택삭제") 
{
	$board_dir = TB_DATA_PATH."/board/boardimg";
	$board_def = array(36,22,21,20,13);

	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$bo_table = trim($_POST['bo_table'][$k]);
		
		//기본게시판의 삭제를 막는다.
		if(in_array($_POST['bo_table'][$k], $board_def)) {
			alert('기본게시판은 삭제 할 수 없습니다.');
		}

		$tmpData = sql_query("select * from shop_board_{$bo_table}");
		while($board=sql_fetch_array($tmpData)) {
			if($board['fileurl1']) @unlink(TB_DATA_PATH."/board/".$bo_table."/".$board['fileurl1']);
			if($board['fileurl2']) @unlink(TB_DATA_PATH."/board/".$bo_table."/".$board['fileurl2']);
			delete_editor_image($board['memo']);
		}

		if(is_dir(TB_DATA_PATH."/board/".$bo_table)) 
			zRmDir(TB_DATA_PATH."/board/".$bo_table);

		$tmpData1 = sql_query("select * from shop_board_conf where index_no='$bo_table'");
		while($conf=sql_fetch_array($tmpData1)) {
			if($conf['fileurl1']) @unlink($board_dir."/".$conf['fileurl1']);
			if($conf['fileurl2']) @unlink($board_dir."/".$conf['fileurl2']);
		}

		sql_query("drop table shop_board_{$bo_table}");
		sql_query("drop table shop_board_{$bo_table}_tail");
		sql_query("delete from shop_board_conf where index_no='$bo_table'");
	}
}

function zRmDir($path) { 
	$directory = dir($path); 
	while($entry = $directory->read()) { 
		if($entry != "." && $entry != "..") { 
			if(Is_Dir($path."/".$entry)) { 
				zRmDir($path."/".$entry); 
			} else { 
				@UnLink ($path."/".$entry); 
			} 
		} 
	} 

	$directory->close(); 
	@RmDir($path); 
}

goto_url(TB_ADMIN_URL."/config.php?$q1&page=$page");
?>