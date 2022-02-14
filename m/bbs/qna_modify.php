<?php
include_once("./_common.php");

if(!$is_member) {
    alert("로그인 후 이용 가능합니다.");
}

if($_POST['mode'] == 'w') {
	check_demo();

	if($_POST["token"] && get_session("ss_token") == $_POST["token"]) {
		// 맞으면 세션을 지워 다시 입력폼을 통해서 들어오도록 한다.
		set_session("ss_token", "");
	} else {
		alert("잘못된 접근 입니다.");
		exit;
	}

	$subject = trim(strip_tags($_POST['subject']));

	if(substr_count($_POST['memo'], "&#") > 50) {
		alert("내용에 올바르지 않은 코드가 다수 포함되어 있습니다.");
	}

	if(!get_magic_quotes_gpc()) {
		$_POST['memo'] = addslashes($_POST['memo']);
	}

	unset($value);
	$value['subject']		 = $subject;
	$value['memo']			 = $_POST['memo'];	
	$value['catename']		 = $_POST['catename'];
	$value['email']			 = $_POST['email'];
	$value['email_send_yes'] = $_POST['email_send_yes'];
	$value['cellphone']		 = replace_tel($_POST['cellphone']);
	$value['sms_send_yes']	 = $_POST['sms_send_yes'];
	$value['ip']			 = $_SERVER['REMOTE_ADDR'];
	update("shop_qa", $value," where index_no='$index_no'");

	goto_url(TB_MBBS_URL."/qna_read.php?index_no=$index_no");
}

$tb['title'] = '1:1 상담문의';
include_once("./_head.php");

$qa = sql_fetch("select * from shop_qa where index_no='$index_no'");

if($qa['mb_id'] != $member['id']) {
	if(!is_admin()) {
		alert('게시글을 수정할 권한이 없습니다.');
	}
}

$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$form_action_url = TB_HTTPS_MBBS_URL.'/qna_modify.php';
include_once(TB_MTHEME_PATH.'/qna_modify.skin.php');

include_once("./_tail.php");
?>