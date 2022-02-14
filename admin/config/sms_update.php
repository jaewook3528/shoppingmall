<?php
include_once("./_common.php");

check_demo();

check_admin_token();

// 회신번호 체크
if(!check_vaild_callback($_POST['cf_sms_recall']))
	alert('발신번호가 올바르지 않습니다.');

$userinfo = get_icode_userinfo($_POST['cf_icode_id'], $_POST['cf_icode_pw']);

if($userinfo['code'] == '202')
	alert('아이코드 아이디와 패스워드가 맞지 않습니다.');

unset($value);
$value['cf_sms_use'] = $_POST['cf_sms_use'];
$value['cf_sms_type'] = $_POST['cf_sms_type'];
$value['cf_sms_recall']	= $_POST['cf_sms_recall'];
$value['cf_icode_server_ip'] = $_POST['cf_icode_server_ip'];
$value['cf_icode_server_port'] = $_POST['cf_icode_server_port'];
$value['cf_icode_id'] = "gnd_".rpc(trim($_POST['cf_icode_id']), "gnd_", "");
$value['cf_icode_pw'] = $_POST['cf_icode_pw'];
$value['cf_cont1'] = $_POST['cf_cont1'];
$value['cf_cont2'] = $_POST['cf_cont2'];
$value['cf_cont3'] = $_POST['cf_cont3'];
$value['cf_cont4'] = $_POST['cf_cont4'];
$value['cf_cont5'] = $_POST['cf_cont5'];
$value['cf_cont6'] = $_POST['cf_cont6'];
$value['cf_mb_use1'] = $_POST['cf_mb_use1'];
$value['cf_ad_use1'] = $_POST['cf_ad_use1'];
$value['cf_re_use1'] = $_POST['cf_re_use1'];
$value['cf_sr_use1'] = $_POST['cf_sr_use1'];
$value['cf_mb_use2'] = $_POST['cf_mb_use2'];
$value['cf_ad_use2'] = $_POST['cf_ad_use2'];
$value['cf_re_use2'] = $_POST['cf_re_use2'];
$value['cf_sr_use2'] = $_POST['cf_sr_use2'];
$value['cf_mb_use3'] = $_POST['cf_mb_use3'];
$value['cf_ad_use3'] = $_POST['cf_ad_use3'];
$value['cf_re_use3'] = $_POST['cf_re_use3'];
$value['cf_sr_use3'] = $_POST['cf_sr_use3'];
$value['cf_mb_use4'] = $_POST['cf_mb_use4'];
$value['cf_ad_use4'] = $_POST['cf_ad_use4'];
$value['cf_re_use4'] = $_POST['cf_re_use4'];
$value['cf_sr_use4'] = $_POST['cf_sr_use4'];
$value['cf_mb_use5'] = $_POST['cf_mb_use5'];
$value['cf_ad_use5'] = $_POST['cf_ad_use5'];
$value['cf_re_use5'] = $_POST['cf_re_use5'];
$value['cf_sr_use5'] = $_POST['cf_sr_use5'];
$value['cf_mb_use6'] = $_POST['cf_mb_use6'];
$value['cf_ad_use6'] = $_POST['cf_ad_use6'];
$value['cf_re_use6'] = $_POST['cf_re_use6'];	
$value['cf_sr_use6'] = $_POST['cf_sr_use6'];
update("shop_sms", $value);

goto_url(TB_ADMIN_URL.'/config.php?code=sms');
?>