<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$upl_dir = TB_DATA_PATH."/banner";
$upl = new upload_files($upl_dir);

unset($value);
if($_POST['bn_file_del']) {
	$upl->del($_POST['bn_file_del']);
	$value['bn_file'] = '';
}
if($_FILES['bn_file']['name']) {
	$value['bn_file'] = $upl->upload($_FILES['bn_file']);
}

$value['mb_id']		= $member['id'];
$value['bn_device'] = 'pc';
$value['bn_theme']	= $member['theme'];
$value['bn_code']	= $_POST['bn_code'];
$value['bn_link']	= $_POST['bn_link'];
$value['bn_target'] = $_POST['bn_target'];
$value['bn_width']	= $_POST['bn_width'];
$value['bn_height'] = $_POST['bn_height'];
$value['bn_bg']		= preg_replace("/([^a-zA-Z0-9])/", "", $_POST['bn_bg']);
$value['bn_text']	= $_POST['bn_text'];
$value['bn_use']	= $_POST['bn_use'];
$value['bn_order']	= $_POST['bn_order'];

if($w == "") {
	insert("shop_banner", $value);
	$bn_id = sql_insert_id();

	goto_url(TB_MYPAGE_URL."/page.php?code=partner_banner_form&w=u&bn_id=$bn_id");
} else if($w == "u") {
	update("shop_banner", $value," where bn_id='$bn_id'");

	goto_url(TB_MYPAGE_URL."/page.php?code=partner_banner_form&w=u&bn_id=$bn_id$qstr&page=$page");
}
?>