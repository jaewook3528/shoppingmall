<?php
include_once("./_common.php");

$comment = sql_fetch("select * from shop_board_{$boardid}_tail where index_no='$tailindex'");

if($mode=='d') {
	if($comment['writer']) {
		if(!$is_member) {
			goto_url(TB_BBS_URL.'/login.php?url='.$urlencode);
		}

		if(!is_admin()) {
			if($comment['writer'] != $member['index_no']) {
				alert('권한이 없습니다.');
			}
		}
	} else {
		if(!is_admin()) {
			$passwd = $_POST['passwd'];
			if($passwd != $comment['passwd']) {
				alert('비밀번호가 일치하지 않습니다.');
			}
		}
	}
	
	sql_query("delete from shop_board_{$boardid}_tail where index_no='$tailindex'");
	sql_query("update shop_board_{$boardid} set tailcount=tailcount-1 where index_no='$index_no'");
	
	goto_url(TB_BBS_URL."/read.php?index_no=$index_no&boardid=$boardid$qstr&page=$page");
}

if($comment['writer'])
{
	if(!$is_member) {
		goto_url(TB_BBS_URL.'/login.php?url='.$urlencode);
	}

	if(!is_admin()) {
		if($comment['writer'] != $member['index_no']) {
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

include_once(TB_BBS_PATH."/skin/{$board['skin']}/tail_del.php");

if($board['downfile']) {	
	@include_once($board['downfile']);
}
?>