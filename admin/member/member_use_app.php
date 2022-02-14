<?php
include_once("./_common.php");

ajax_admin_token();

$mb = get_member($mb_id, 'use_app');
if($mb['use_app'] == '0')
	$t_value = '1';
else
	$t_value = '0';

$sql = " update shop_member set use_app='$t_value' where id='$mb_id' ";
$result = sql_query($sql);

if($result) 
	die("{\"error\":\"\"}"); // 정상
else 
	die("{\"error\":\"일시적인 오류\"}");
?>