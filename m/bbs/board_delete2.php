<?php
include_once("./_common.php");

if($w=='d')
{
	check_demo();

	if($write['writer'] != 0) {
		if(!$is_member) {
			goto_url(TB_MBBS_URL."/login.php");
		}

		if(!is_admin()) {
			if($write['writer'] != $mb_no) {
				alert('삭제 권한이 없습니다.');
			}
		}

	} else {
		if(!is_admin()) {
			if($_POST['passwd'] != $write['passwd']) {
				alert('비밀번호가 일치하지 않습니다.');
			}
		}
	}

	$file1_dir = TB_DATA_PATH.'/board/'.$boardid.'/'.$write['fileurl1'];
	if(is_file($file1_dir) && $write['fileurl1']) {
		@unlink($file1_dir);
		delete_board_thumbnail($boardid, $write['fileurl1']);
	}

	$file2_dir = TB_DATA_PATH.'/board/'.$boardid.'/'.$write['fileurl2'];
	if(is_file($file2_dir) && $write['fileurl2']) {
		@unlink($file2_dir);
		delete_board_thumbnail($boardid, $write['fileurl2']);
	}
	
	delete_editor_image($write['memo']);
    
	$sql = " delete from shop_board_{$boardid} where index_no='$index_no' "; 
	sql_query($sql);

	$sql = " delete from shop_board_{$boardid}_tail where board_index='$index_no' ";
	sql_query($sql);

	goto_url(TB_MBBS_URL."/board_list.php?boardid=$boardid&page=$page");
}
?>