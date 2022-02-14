<?php
include_once("./_common.php");

check_demo();

check_admin_token();

unset($value);
$value['possible_ip']  = $_POST['possible_ip']; //접근가능 IP
$value['intercept_ip'] = $_POST['intercept_ip']; //접근차단 IP
update("shop_config", $value);

goto_url(TB_ADMIN_URL.'/config.php?code=ipaccess');
?>