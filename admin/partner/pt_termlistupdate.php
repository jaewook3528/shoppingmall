<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$count = count($_POST['chk']);
if(!$count) {
	alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

if($_POST['act_button'] == "선택승인")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$index_no = trim($_POST['index_no'][$k]);
		$mb_id    = trim($_POST['mb_id'][$k]);

		$row = get_partner_term($index_no);

		// 이미승인된건이면 건너뜀
		if($row['state'])
			continue;	

		$mb = get_member($mb_id, 'term_date');

		if(is_null_time($mb['term_date'])) // 시간이 비어있는가?
			$new_date = date("Y-m-d", strtotime("+{$row['expire_date']} month", time()));
		else
			$new_date = date("Y-m-d", strtotime("+{$row['expire_date']} month", strtotime($mb['term_date'])));

		// 기간연장을 한다.	
		sql_query("update shop_member set term_date = '$new_date' where id = '$mb_id'");		
		sql_query("update shop_partner_term set state = '1' where index_no = '$index_no'");	
	}
} 
else if($_POST['act_button'] == "선택삭제") 
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$index_no = trim($_POST['index_no'][$k]);

		sql_query("delete from shop_partner_term where index_no = '$index_no'");
	}
}

goto_url(TB_ADMIN_URL."/partner.php?$q1&page=$page");
?>