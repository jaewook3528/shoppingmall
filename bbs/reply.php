<?php
include_once("./_common.php");

if(!$is_member) { $member['grade'] = 99; }

if($board['reply_priv'] < 99) {
	if($member['grade'] > $board['reply_priv'])	{
		alert('권한이 없습니다.');
	}
}

if($board['topfile']) {	
	include $board['topfile'];	
}

if($board['content_head']) {	
	echo $board['content_head'];
}

if($board['width'] <= 100) {	
	$board['width'] = $board['width'] ."%";	
}

$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$from_action_url = TB_HTTPS_BBS_URL."/reply_update.php";
include_once(TB_BBS_PATH."/skin/{$board['skin']}/reply.php");

if($board['content_tail']) {	
	echo $board['content_tail'];
}

if($board['downfile']) {	
	include $board['downfile'];	
}
?>