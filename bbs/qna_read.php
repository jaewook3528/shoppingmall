<?php
include_once("./_common.php");

if(!$is_member) {
    alert("로그인 후 이용 가능합니다.");
}

if($_REQUEST['mode'] == 'd') {
	check_demo();

	$row = sql_fetch("select * from shop_qa where index_no='$index_no'");
	if($row['mb_id'] != $member['id']) {
		if(!is_admin()) {
			alert("삭제 권한이 없습니다.");
		}
	}

	sql_query("delete from shop_qa where index_no='$index_no'");
	goto_url(TB_BBS_URL."/qna_list.php");
}

$tb['title'] = '1:1 상담문의';
include_once("./_head.php");

$qa = sql_fetch("select * from shop_qa where index_no='$index_no'");

include_once(TB_THEME_PATH.'/qna_read.skin.php');

include_once("./_tail.php");
?>