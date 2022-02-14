<?php
include_once("./_common.php");

ajax_admin_token();

$mb = get_member($mb_id, 'id');
if(!$mb['id']) {
	alert('회원아이디가 존재하지 않습니다.');
}

$target_table = 'shop_cate_'.$mb_id;

$ca = sql_fetch("select * from {$target_table} where index_no = '$ca_no' "); 
if($ca['p_hide'] == '0')
	$t_value = '1';
else
	$t_value = '0';

$len = strlen($ca['catecode']);
$sql_where = " where SUBSTRING(catecode,1,$len) = '{$ca['catecode']}' ";

$sql = "update {$target_table} set p_hide = '$t_value' {$sql_where} ";
$result = sql_query($sql);

if($result) 
	die("{\"error\":\"처리 되었습니다.\"}"); // 정상
else 
	die("{\"error\":\"일시적인 오류\"}");
?>