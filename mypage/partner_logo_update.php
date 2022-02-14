<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$upl_dir = TB_DATA_PATH."/banner";
$upl = new upload_files($upl_dir);

unset($value);
if($ico = $_FILES['favicon_ico']['name']) {
	if(!preg_match("/(\.ico)$/i", $ico))
		alert("파비콘 아이콘은 ico 파일만 업로드 가능합니다.");
}

$lg = sql_fetch("select * from shop_logo where mb_id = '{$member['id']}'");

if($basic_logo_del) {
	$upl->del($lg['basic_logo']);
	$value['basic_logo'] = '';
}
if($mobile_logo_del) {
	$upl->del($lg['mobile_logo']);
	$value['mobile_logo'] = '';
}
if($sns_logo_del) {
	$upl->del($lg['sns_logo']);
	$value['sns_logo'] = '';
}
if($favicon_ico_del) {
	$upl->del($lg['favicon_ico']);
	$value['favicon_ico'] = '';
}

if($_FILES['basic_logo']['name']) {
	$value['basic_logo'] = $upl->upload($_FILES['basic_logo']);
}
if($_FILES['mobile_logo']['name']) {
	$value['mobile_logo'] = $upl->upload($_FILES['mobile_logo']);
}
if($_FILES['sns_logo']['name']) {
	$value['sns_logo'] = $upl->upload($_FILES['sns_logo']);
}
if($_FILES['favicon_ico']['name']) {
	$value['favicon_ico'] = $upl->upload($_FILES['favicon_ico']);
}	

$value['mb_id'] = $member['id'];

$value['basic_logo_type'] = $_POST['basic_logo_type'];
$value['basic_logo_text'] = $_POST['basic_logo_text'];

$value['mobile_logo_type'] = $_POST['mobile_logo_type'];
$value['mobile_logo_text'] = $_POST['mobile_logo_text'];

if($lg['index_no']) {
	update("shop_logo", $value, "where mb_id = '{$member['id']}'");
} else {
	insert("shop_logo", $value);
}

goto_url(TB_MYPAGE_URL.'/page.php?code=partner_logo');
?>