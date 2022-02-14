<?php
include_once("./_common.php");

$tb['title'] = get_text($board['boardname']);
include_once("./_head.php");

if(!$is_member) { $member['grade'] = 99; }

if($board['read_priv'] < 99) {
	if($member['grade'] > $board['read_priv']) {
		alert('권한이 없습니다.');
	}
}

$bo_subject = get_text($write['subject']);
$bo_wdate   = date("Y-m-d", $write['wdate']);

if($write['issecret'] == 'Y') {
	if($write['writer'] != 0) {
		if(!$is_member) {
			goto_url(TB_MBBS_URL."/login.php?url=$urlencode");
		}
	}

	if($is_member) {
		if(!is_admin()) {
			if($mb_no != $write['writer']) {
				alert("비밀글은 열람하실 수 없습니다.");
			}
		}

	} else {
		$mb_no = 0;
		if($_GET['inpasswd'] != $write['passwd']) {
			goto_url(TB_MBBS_URL."/board_secret.php?index_no=$index_no&boardid=$boardid&page=$page");
		}
	}
}

$sql = "update shop_board_{$boardid} set readcount=(readcount+1) where index_no='$index_no'";
sql_query($sql);

$bo_img_url = TB_BBS_URL.'/skin/'.$board['skin'];

$qstr1 = "boardid=$boardid&page=$page";
$qstr2 = "index_no=$index_no&boardid=$boardid&page=$page";

include_once(TB_MTHEME_PATH.'/board_read.skin.php');

include_once("./_tail.php");
?>