<?php
include_once("./_common.php");

if($board['topfile']) {	
	@include_once($board['topfile']);
}

if($board['width'] <= 100) {	
	$board['width'] = $board['width'] ."%";	
}

$bo_img_url = TB_BBS_URL.'/skin/'.$board['skin'];

include_once(TB_BBS_PATH."/skin/{$board['skin']}/secret.php");

if($board['downfile']) {	
	@include_once($board['downfile']);
}
?>