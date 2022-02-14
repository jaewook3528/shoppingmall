<?php
include_once("./_common.php");

check_demo();

check_admin_token();

if(!$_POST['title']) 
	alert("제목을 입력하세요.");

unset($value);
$value['device']	 = $_POST['device'];
$value['width']		 = $_POST['width'];
$value['height']	 = $_POST['height'];
$value['lefts']		 = $_POST['lefts'];
$value['top']		 = $_POST['top'];
$value['title']		 = $_POST['title'];
$value['state']		 = $_POST['state'];
$value['begin_date'] = $_POST['begin_date'];
$value['end_date']	 = $_POST['end_date'];
$value['memo']		 = $_POST['memo'];

if($w == "") {
	$value['mb_id'] = $member['id'];
	insert("shop_popup", $value);
	$pp_id = sql_insert_id();

	goto_url(TB_MYPAGE_URL."/page.php?code=partner_popup_form&w=u&pp_id=$pp_id");
} else if($w == "u") {
	update("shop_popup", $value, "where index_no='$pp_id'");

	goto_url(TB_MYPAGE_URL."/page.php?code=partner_popup_form&w=u&pp_id=$pp_id$qstr&page=$page");
}
?>