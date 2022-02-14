<?php
include_once("./_common.php");

check_demo();

check_admin_token();

unset($value);
$value['head_title']		= $_POST['head_title']; //웹브라우져 타이틀
$value['meta_author']		= $_POST['meta_author']; //Author : 메타태그 1
$value['meta_description']	= $_POST['meta_description']; //description : 메타태그 2
$value['meta_keywords']		= $_POST['meta_keywords']; //keywords : 메타태그 3
$value['add_meta']			= $_POST['add_meta']; //추가 메타태그
$value["head_script"]		= $_POST["head_script"]; //<HEAD> 내부 태그
$value["tail_script"]		= $_POST["tail_script"]; //<BODY> 내부 태그
update("shop_partner",$value,"where mb_id='$member[id]'");

goto_url(TB_MYPAGE_URL.'/page.php?code=partner_meta');
?>