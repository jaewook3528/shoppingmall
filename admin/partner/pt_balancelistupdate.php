<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$count = count($_POST['chk']);
if(!$count) {
	alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

for($i=0; $i<$count; $i++)
{
    // 실제 번호를 넘김
    $k = $_POST['chk'][$i];

	$mb_id = $_POST['mb_id'][$k];
	$pp_pay = $_POST['pp_pay'][$k];

	if($pp_pay < 1) 
		continue;

	$mb = get_member($mb_id, 'id, pay');
	if(!$mb['id'])
		alert($mb_id.' 님은 존재하는 회원아이디가 아닙니다.');

	if($pp_pay > $mb['pay'])
		alert($mb_id.' 님의 지급수수료가 현재수수료잔액을 초과하였습니다.');

	insert_pay($mb_id, $pp_pay * (-1), TB_TIME_YMD.' 수수료 정산완료', 'payment', $mb_id, $member['id'].'-'.uniqid(''));
}

goto_url(TB_ADMIN_URL."/partner.php?$q1&page=$page");
?>