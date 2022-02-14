<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$count = count($_POST['chk']);
if(!$count) {
	alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

if($_POST['act_button'] == "선택정산")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$index_no = trim($_POST['index_no'][$k]);
		$mb_id    = trim($_POST['mb_id'][$k]);

		$row = get_partner_payrun($index_no);

		// 출금요청액
		$pp_pay = $row['balance'];

		// 이미승인된건이면 건너뜀
		if($row['state'])
			continue;	

		if($pp_pay < 1) 
			continue;

		$mb = get_member($mb_id, 'id, pay');
		if(!$mb['id'])
			alert($mb_id.' 님은 존재하는 회원아이디가 아닙니다.');

		if($pp_pay > $mb['pay'])
			alert($mb_id.' 님의 지급수수료가 현재수수료잔액을 초과하였습니다.');

		insert_pay($mb_id, $pp_pay * (-1), TB_TIME_YMD.' 수수료 정산완료', 'payment', $mb_id, $member['id'].'-'.uniqid(''));
	
		$sql = " update shop_partner_payrun set state = '1' where index_no = '$index_no' ";
		sql_query($sql);
	}
} 
else if($_POST['act_button'] == "선택삭제") 
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$index_no = trim($_POST['index_no'][$k]);

		$sql = " delete from shop_partner_payrun where index_no = '$index_no' ";
		sql_query($sql);
	}
}

goto_url(TB_ADMIN_URL."/partner.php?$q1&page=$page");
?>