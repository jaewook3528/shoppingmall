<?php
include_once("./_common.php");

check_demo();

$sms = sql_fetch("select * from shop_sms ");
if(!$sms['cf_sms_use']) {
	alert_close('문자서비스를 사용가능한 설정 상태가 아닙니다.');
}

$sms_content = trim($_POST['sms_content']);

$sql = "select info_tel from shop_seller where state='1'";
$result = sql_query($sql);
while($row = sql_fetch_array($result)){	
	icode_member_send($row['info_tel'], $sms_content);
}

alert("전송되었습니다.", "./sms_seller.php");
?>