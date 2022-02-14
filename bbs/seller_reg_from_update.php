<?php
include_once("./_common.php");
include_once(TB_LIB_PATH."/mailer.lib.php");

check_demo();

if(!$config['seller_reg_yes']) {
	alert('서비스가 일시 중단 되었습니다.', TB_URL);
}

if(!$is_member) {
    alert("로그인 후 신청 가능합니다.");
}

if(is_admin()) {
	alert('관리자는 신청을 하실 수 없습니다.');
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
$value['seller_code'] = code_uniqid();	
$value['seller_item'] = $_POST['seller_item'];
$value['company_name'] = $_POST['company_name'];
$value['company_saupja_no'] = $_POST['company_saupja_no'];	
$value['company_item'] = $_POST['company_item'];
$value['company_service'] = $_POST['company_service'];
$value['company_owner'] = $_POST['company_owner'];
$value['company_tel'] = $_POST['company_tel'];
$value['company_fax'] = $_POST['company_fax'];
$value['company_zip'] = $_POST['company_zip'];
$value['company_addr1'] = $_POST['company_addr1'];
$value['company_addr2'] = $_POST['company_addr2'];
$value['company_addr3'] = $_POST['company_addr3'];
$value['company_addr_jibeon'] = $_POST['company_addr_jibeon'];	
$value['company_hompage'] = $_POST['company_hompage'];
$value['info_name'] = $_POST['info_name'];
$value['info_email'] = $_POST['info_email'];
$value['info_tel'] = $_POST['info_tel'];
$value['bank_name'] = $_POST['bank_name'];
$value['bank_account'] = $_POST['bank_account'];
$value['bank_holder'] = $_POST['bank_holder'];
$value['memo'] = $_POST['memo'];
$value['reg_time'] = TB_TIME_YMDHIS;
$value['update_time'] = TB_TIME_YMDHIS;
insert("shop_seller", $value);

$wr_content = conv_content(conv_unescape_nl(stripslashes($_POST['memo'])), 0);
$wr_name = get_text($member['name']);
$subject = '['.$company_name.'] '.$wr_name.'님께서 입점신청을 하셨습니다.';

if($member['email']) {	
	ob_start();
	include_once(TB_BBS_PATH.'/seller_reg_from_update_mail.php');
	$content = ob_get_contents();
	ob_end_clean();

	mailer($member['name'], $member['email'], $super['email'], $subject, $content, 1);
}

icode_member_send($super_hp, $subject);

alert('정상적으로 신청 되었습니다.', TB_BBS_URL.'/seller_reg_from.php');
?>