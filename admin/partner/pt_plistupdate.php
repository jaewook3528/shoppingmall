<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$count = count($_POST['chk']);
if(!$count) {
	alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

if($_POST['act_button'] == "기간연장")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$mb_id = trim($_POST['mb_id'][$k]);

		$mb = get_member($mb_id, 'term_date');

		if(is_null_time($mb['term_date'])) // 시간이 비어있는가?
			$new_date = date("Y-m-d", strtotime("+{$_POST['expire_date']} month", time()));
		else
			$new_date = date("Y-m-d", strtotime("+{$_POST['expire_date']} month", strtotime($mb['term_date'])));

		// 기간연장을 한다.	
		sql_query("update shop_member set term_date = '$new_date' where id = '$mb_id'");	
	}
} 
else if($_POST['act_button'] == "카테고리초기화") 
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$mb_id = trim($_POST['mb_id'][$k]);

		// 카테고리 테이블 DROP
		$target_table = 'shop_cate_'.$mb_id;
		sql_query(" drop table {$target_table} ", FALSE);

		// 카테고리 폴더 전체 삭제
		rm_rf(TB_DATA_PATH.'/category/'.$mb_id);

		// 카테고리 생성
		sql_member_category($mb_id);
	}
}

goto_url(TB_ADMIN_URL."/partner.php?$q1&page=$page");
?>