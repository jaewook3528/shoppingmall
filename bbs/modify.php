<?php
include_once("./_common.php");

if(!$is_member) { $member['grade'] = 99; }

if($is_member) {
	if(!is_admin()) {
		if($write['writer']!=$member['index_no']) {
			alert('권한이 없습니다.');
		}
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

$bo_img_url = TB_BBS_URL.'/skin/'.$board['skin'];
$from_action_url = TB_HTTPS_BBS_URL."/modify_update.php";

include_once(TB_BBS_PATH."/skin/{$board['skin']}/modify.php");

if($board['content_tail']) {	
	echo $board['content_tail'];
}

if($board['downfile']) {	
	include $board['downfile'];
}
?>