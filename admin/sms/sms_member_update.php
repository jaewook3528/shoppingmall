<?php
include_once("./_common.php");

check_demo();

$sms = sql_fetch("select * from shop_sms ");
if(!$sms['cf_sms_use']) {
	alert_close('문자서비스를 사용가능한 설정 상태가 아닙니다.');
}

$send_type = trim($_POST['send_type']);
$sms_content = trim($_POST['sms_content']);

if($send_type == 'all') {
	$sql = " select cellphone 
			   from shop_member 
			  where smsser = 'Y' 
			    and id <> 'admin' 
				and supply <> 'Y' ";
	$result = sql_query($sql);
} else {
	$sql = " select cellphone
			   from shop_member 
			  where smsser = 'Y' 
			    and id <> 'admin' 
				and supply <> 'Y' 
				and grade = '$send_type' ";
	$result = sql_query($sql);
} 

while($row = sql_fetch_array($result)) {	
	icode_member_send($row['cellphone'], $sms_content);
}

alert("전송되었습니다.", "./sms_member.php");
?>