<?php
include_once("./_common.php");
include_once(TB_ADMIN_PATH."/admin_access.php");

check_demo();

check_admin_token();

$mb = get_member($mb_id);
if(!$mb['id'])
	alert('존재하지 않는 회원입니다.');

if($mb_id == 'admin')
	alert('관리자는 탈퇴하실 수 없습니다.');

// 회원삭제
member_delete($mb_id);

unset($value);
$value['mb_id']	   = $mb['id'];
$value['mb_name']  = $mb['name'];
$value['memo']	   = '관리자 강제 영구탈퇴처리';
$value['reg_time'] = TB_TIME_YMDHIS;
insert("shop_member_leave", $value);

alert_close('정상적으로 탈퇴 되었습니다.');
?>