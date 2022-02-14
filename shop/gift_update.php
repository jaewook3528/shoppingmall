<?php
include_once("./_common.php");

check_demo();

if(!$config['gift_yes']) {
    alert("쿠폰사용이 중지 되었습니다.");
}

if(!$is_member) {
	alert("로그인 후 이용 가능합니다.");
}

// 세션에 저장된 토큰과 폼값으로 넘어온 토큰을 비교.
if($_POST["token"] && get_session("ss_token") == $_POST["token"]) {
	// 맞으면 세션을 지워 다시 입력폼을 통해서 들어오도록 한다.
	set_session("ss_token", "");
} else {
	alert("잘못된 접근 입니다.");
	exit;
}

$str  = trim($_POST['gi_num1']);
$str .= trim($_POST['gi_num2']);
$str .= trim($_POST['gi_num3']);
$str .= trim($_POST['gi_num4']);

$gi_num = preg_replace("/([0-9A-Z]{4})([0-9A-Z]{4})([0-9A-Z]{4})([0-9A-Z]{4})/", "\\1-\\2-\\3-\\4", $str);

$cp = sql_fetch(" select * from shop_gift where gi_num = '$gi_num' ");

if(!$cp['no'])
	alert("쿠폰번호가 존재하지 않습니다. 확인 후 다시 등록 바랍니다.");

if($cp['gr_edate'] < TB_TIME_YMD)
	alert("현재 쿠폰은 사용기간이 만료 되었습니다. \\n\\n만료날짜 : ".$cp['gr_edate']);

if($cp['gr_sdate'] > TB_TIME_YMD)
	alert("현재 쿠폰은 ".$cp['gr_sdate']."일 이후부터 사용 가능하십니다.");

if($cp['gi_use'])
	alert("현재 쿠폰은 이미 등록 된 상태입니다.");

unset($value);
$value['mb_id']		= $member['id'];
$value['mb_name']	= $member['name'];
$value['mb_wdate']	= TB_TIME_YMDHIS;
$value['gi_use']	= 1;
update("shop_gift", $value, "where gi_num = '$gi_num' ");

// 포인트적립
insert_point($member['id'], $cp['gr_price'], $cp['gr_subject']." 쿠폰", "@gift", $member['id'], $gi_num);

alert("정상적으로 처리 되었습니다.", TB_SHOP_URL."/gift.php");
?>