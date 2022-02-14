<?php
include_once("./_common.php");
include_once(TB_LIB_PATH."/mailer.lib.php");

check_demo();

if(!$config['partner_reg_yes']) {
	alert('서비스가 일시 중단 되었습니다.', TB_URL);
}

if(!$is_member) {
    alert("로그인 후 신청 가능합니다.");
}

if(is_admin()) {
	alert('관리자는 신청을 하실 수 없습니다.');
}

if($partner['mb_id']) {
    alert('중복된 신청은 하실 수 없습니다.');
}

if($_POST["token"] && get_session("ss_token") == $_POST["token"]) {
	// 맞으면 세션을 지워 다시 입력폼을 통해서 들어오도록 한다.
	set_session("ss_token", "");
} else {
	alert("잘못된 접근 입니다.");
	exit;
}

unset($value);
$value['mb_id'] = $member['id'];
$value['bank_holder'] = $_POST['bank_holder'];
$value['bank_account'] = $_POST['bank_account'];
$value['bank_name'] = $_POST['bank_name'];
$value['memo'] = $_POST['memo'];
$value['anew_grade'] = $_POST['anew_grade'];
$value['receipt_price'] = $_POST['receipt_price'];
$value['deposit_name'] = $_POST['deposit_name'];
$value['pay_settle_case'] = $_POST['pay_settle_case'];
$value['bank_acc'] = $_POST['bank_acc'];
$value['reg_signature_json'] = $_POST['signatureJSON'];
$value['reg_ip'] = $_SERVER['REMOTE_ADDR'];
$value['reg_time'] = TB_TIME_YMDHIS;
$value['update_time'] = TB_TIME_YMDHIS;
insert("shop_partner", $value);

$wr_content = conv_content(conv_unescape_nl(stripslashes($_POST['memo'])), 0);
$wr_name = get_text($member['name']);
$subject = $wr_name.'님께서 분양신청을 하셨습니다.';

if($member['email']) {
	ob_start();
	include_once(TB_BBS_PATH.'/partner_reg_update_mail.php');
	$content = ob_get_contents();
	ob_end_clean();

	mailer($member['name'], $member['email'], $super['email'], $subject, $content, 1);
}

icode_member_send($super_hp, $subject);

alert('정상적으로 신청 되었습니다.', TB_BBS_URL.'/partner_reg.php');
?>