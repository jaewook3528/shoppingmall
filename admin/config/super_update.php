<?php
include_once("./_common.php");

check_demo();

check_admin_token();

unset($value);
$value['name']			= $_POST['name'];
$value['email']			= $_POST['email'];
$value['cellphone']		= replace_tel($_POST['cellphone']);
$value['zip']			= $_POST['zip'];
$value['addr1']			= $_POST['addr1'];
$value['addr2']			= $_POST['addr2'];
$value['addr3']			= $_POST['addr3'];
$value['addr_jibeon']	= $_POST['addr_jibeon'];
$value['mailser']		= $_POST['mailser'];
$value['smsser']		= $_POST['smsser'];
if($_POST['passwd'])  $value['passwd'] = $_POST['passwd'];	
update("shop_member", $value, "where id='admin'");

goto_url(TB_ADMIN_URL.'/config.php?code=super');
?>