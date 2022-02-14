<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$count = count($_POST['chk']);
if(!$count) {
	alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

$chk = array_reverse($_POST['chk']);

if($_POST['act_button'] == "선택승인")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
        $k = $chk[$i];

		$mb_id = trim($_POST['mb_id'][$k]);

		$pt = get_partner($mb_id, 'state, anew_grade');
		
		// 이미승인된 회원이면 건너뜀
		if($pt['state'])
			continue;			

		// 승인
		$sql = " update shop_partner set state = '1' where mb_id = '$mb_id' ";		
		sql_query($sql);

		$term_date = get_term_date($config['pf_expire_term']); // 만료일

		$sql = " update shop_member 
					set grade = '{$pt['anew_grade']}'
					  , anew_date = '".TB_TIME_YMD."'
					  , term_date = '$term_date'
				  where id = '$mb_id' ";
		sql_query($sql);

		// 카테고리 생성
		sql_member_category($mb_id);	

		// 추천 소개수수료
		insert_anew_pay($mb_id);
	}
} 
else if($_POST['act_button'] == "선택삭제") 
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
        $k = $chk[$i];

		$mb_id = trim($_POST['mb_id'][$k]);

		// 삭제
		$sql = " delete from shop_partner where mb_id = '$mb_id' ";
		sql_query($sql);

		$sql = " update shop_member 
					set grade = '9'
					  , anew_date = '0000-00-00'
					  , term_date = '0000-00-00'
				  where id = '$mb_id' ";
		sql_query($sql, FALSE);
	}
}

goto_url(TB_ADMIN_URL."/partner.php?$q1&page=$page");
?>