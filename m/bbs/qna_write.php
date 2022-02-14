<?php
include_once("./_common.php");
include_once(TB_LIB_PATH."/mailer.lib.php");

if(!$is_member) {
	goto_url(TB_MBBS_URL.'/login.php?url='.$urlencode);
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
	$value['mb_id']			 = $member['id'];
	$value['subject']		 = $subject;
	$value['memo']			 = $_POST['memo'];
	$value['catename']		 = $_POST['catename'];
	$value['email']			 = $_POST['email'];
	$value['email_send_yes'] = $_POST['email_send_yes'];
	$value['cellphone']		 = replace_tel($_POST['cellphone']);
	$value['sms_send_yes']	 = $_POST['sms_send_yes'];
	$value['ip']			 = $_SERVER['REMOTE_ADDR'];
	$value['wdate']			 = TB_TIME_YMDHIS;
	insert("shop_qa", $value);

	$wr_subject = get_text(stripslashes($subject));
	$wr_content = conv_content(conv_unescape_nl(stripslashes($_POST['memo'])), 0);
	$wr_name = get_text($member['name']);
	$subject = $wr_name.'님께서 1:1상담문의에 새글을 작성하셨습니다.';

	if($email) {
		ob_start();
		include_once(TB_BBS_PATH.'/write_update_mail.php');
		$content = ob_get_contents();
		ob_end_clean();

		mailer($wr_name, $email, $super['email'], $subject, $content, 1);
	}

	icode_member_send($super_hp, $subject);

	alert("정상적으로 접수 되었습니다. 빠른 시간안에 답변드리겠습니다", TB_MBBS_URL."/qna_list.php");
}

$tb['title'] = '1:1 상담문의';
include_once("./_head.php");

$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$form_action_url = TB_HTTPS_MBBS_URL.'/qna_write.php';
include_once(TB_MTHEME_PATH.'/qna_write.skin.php');

include_once("./_tail.php");
?>