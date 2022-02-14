<?php
include_once("./_common.php");

check_demo();

if($mode == 'd')
{
	if($write['writer'])
	{
		if(!$is_member) {
			goto_url(TB_BBS_URL.'/login.php?url='.$urlencode);
		}

		if(!is_admin()) {
			if($write['writer'] != $member['index_no']) {
				alert('권한이 없습니다.');
			}
		}
	} else {
		if(!is_admin()) {
			$passwd = $_POST['passwd'];
			if($passwd != $write['passwd']) {
				alert('비밀번호가 일치하지 않습니다.');
			}
		}
	}
	$savedir = TB_DATA_PATH."/board/".$boardid;
	if($write['fileurl1']) @unlink($savedir."/".$write['fileurl1']);
	if($write['fileurl1']) delete_board_thumbnail($boardid, $write['fileurl1']);
	if($write['fileurl2']) @unlink($savedir."/".$write['fileurl2']);	
	if($write['fileurl2']) delete_board_thumbnail($boardid, $write['fileurl2']);

	delete_editor_image($write['memo']);
    
	$sql = " delete from shop_board_{$boardid} where index_no='$index_no' "; 
	sql_query($sql);
	
	$sql = " delete from shop_board_{$boardid}_tail where board_index='$index_no' ";
	sql_query($sql);
	
	goto_url(TB_BBS_URL."/list.php?boardid=$boardid$qstr&page=$page");
}

if($write['writer']) {
	if(!$is_member) {
		goto_url(TB_BBS_URL.'/login.php?url='.$urlencode);
	}

	if(!is_admin()) {
		if($write['writer'] != $member['index_no']) {
			alert('권한이 없습니다.');
		}
	}
}

if($board['topfile']) {	
	@include_once($board['topfile']);
}

if($board['width'] <= 100) {	
	$board['width'] = $board['width'] ."%";	
}

include_once(TB_BBS_PATH."/skin/{$board['skin']}/del.php");

if($board['downfile']) {	
	@include_once($board['downfile']);
}
?>