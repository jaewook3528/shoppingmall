<?php
include_once("./_common.php");

$tb['title'] = get_text($board['boardname']);
include_once("./_head.php");

if(!$is_member) { $member['grade'] = 99; }

if($w == "u" || $w == "r") {
    if(!$write['index_no'])
        alert("자료가 없습니다.");

	if($w == "u") {
		if($is_member) {
			if(!is_admin()) {
				if($write['writer'] != $mb_no) {
					alert('글수정 권한이 없습니다.');
				}
			}
		}
	}

	if($w == "r") {
		if($board['reply_priv'] < 99) {
			if($member['grade'] > $board['reply_priv'])	{
				alert("댓글작성 권한이 없습니다.");
			}
		}

		$write['writer_s'] = $member['name'];
	}
} else {
	if($board['write_priv'] < 99) {
		if($member['grade'] > $board['write_priv'])
			alert("글쓰기 권한이 없습니다.");
	}

	$write['writer_s'] = $member['name'];
}

$qstr1 = "boardid=$boardid&page=$page";

$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$form_action_url = TB_HTTPS_MBBS_URL.'/board_write_update.php';
include_once(TB_MTHEME_PATH.'/board_write.skin.php');

include_once("./_tail.php");
?>