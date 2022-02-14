<?php
include_once("./_common.php");

check_demo();

if(!$is_member) {
    alert('회원만 접근하실 수 있습니다.');
}

$mb_password = trim($_POST['mb_password']);
if(!($mb_password && check_password($mb_password, $member['passwd'])))
    alert('비밀번호가 틀립니다.');

if($member['id'] == 'admin') {
	alert('최고 관리자는 탈퇴하실 수 없습니다.', TB_MURL);
}

// 회원삭제
member_delete($member['id']);

$sql = "insert into shop_member_leave
		   set mb_id = '{$member['id']}'
		     , mb_name = '{$member['name']}'
			 , memo = '$memo'
			 , other = '$other'
			 , reg_time = '".TB_TIME_YMDHIS."' ";
sql_query($sql);

// 최고 관리자에게 문자전송
icode_member_send($super_hp, $member['name'].'님께서 회원에서 탈퇴 하셨습니다.');

// 3.09 수정 (로그아웃)
unset($_SESSION['ss_mb_id']);

alert($member['name'].'님께서는 '. date("Y년 m월 d일") .'에 회원에서 탈퇴 하셨습니다.', TB_MURL);
?>