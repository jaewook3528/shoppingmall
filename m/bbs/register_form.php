<?php
include_once("./_common.php");

if($w == "") {
	if($is_member) {
		goto_url(TB_MURL);
	}

	// 본사쇼핑몰에서 회원가입을 받지 않을때
	$config['admin_reg_msg'] = str_replace("\r\n", "\\r\\n", $config['admin_reg_msg']);
	if($config['admin_reg_yes'] && $pt_id == 'admin') {
		alert($config['admin_reg_msg'], TB_MURL);
	}

	// 실명인증 사용중일때
	if($default['de_certify_use']) {
		if(get_session("allow") != 'Y') {
			alert('정상적인 접근이 아닙니다.', TB_MBBS_URL.'/register.php');
		}

		$sql = " select * from shop_joincheck where j_key='".get_session('j_key')."' ";
		$cert = sql_fetch($sql);

		$member['name']		    = $cert['j_name'];
		$member['birth_year']   = substr($cert['j_birthdate'],0,4);
		$member['birth_month']  = substr($cert['j_birthdate'],4,2);
		$member['birth_day']	= substr($cert['j_birthdate'],6,2);
		$member['smsser']		= 'Y';
		$member['mailser']		= 'Y';

		if(isset($cert['j_sex']) && $cert['j_sex'] == 1)
			$member['gender'] = 'M';
		else if(isset($cert['j_sex']) && $cert['j_sex'] == 0)
			$member['gender'] = 'F';
	}

	$tb['title'] = "회원가입";

} else if($w == 'u') {
	if(!$is_member) {
		alert('로그인 후 이용하여 주십시오.', TB_MURL);
	}

	$tb['title'] = "정보수정";
}

include_once("./_head.php");

// 불법접근을 막도록 토큰생성
$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

// add_javascript('js 구문', 출력순서); 숫자가 작을 수록 먼저 출력됨
if($config['register_use_addr'])
    add_javascript(TB_POSTCODE_JS, 0); //다음 주소 js

$readonly = ' readonly style="background-color:#ddd"';

$register_action_url = TB_HTTPS_MBBS_URL.'/register_form_update.php';
include_once(TB_MTHEME_PATH.'/register_form.skin.php');

include_once("./_tail.php");
?>