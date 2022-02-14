<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$count = count($_POST['chk']);
if(!$count) {
	alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

if($_POST['act_button'] == "선택상품감춤")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$gs = get_goods($_POST['gs_id'][$k], 'use_hide'); 
		$gs['use_hide'] = preg_replace('/\s+/', '', $gs['use_hide']);
		$s_value = "";

		$list_data = explode(",", $gs['use_hide']);
		$list_data = array_diff($list_data, array($member['id'])); // 적용 아이디 모두제거
		array_push($list_data, $member['id']);  // 적용아이디 배열에 추가
		$list_data = array_unique($list_data); //중복된 아이디 제거
		$list_data = array_filter($list_data); // 빈 배열 요소를 제거
		$list_data = array_values($list_data); // index 값 주기
		$s_value = implode(",", $list_data);

		$sql = " update shop_goods set use_hide = '$s_value' where index_no = '{$_POST['gs_id'][$k]}'"; 
		sql_query($sql);
	}
} 
else if($_POST['act_button'] == "선택상품노출") 
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$gs = get_goods($_POST['gs_id'][$k], 'use_hide'); 
		$gs['use_hide'] = preg_replace('/\s+/', '', $gs['use_hide']);
		$s_value = "";

		$list_data = explode(",", $gs['use_hide']);
		$list_data = array_diff($list_data, array($member['id'])); // 적용 아이디 모두제거
		$list_data = array_unique($list_data); //중복된 아이디 제거			
		$list_data = array_filter($list_data); // 빈 배열 요소를 제거
		$list_data = array_values($list_data); // index 값 주기
		$s_value = implode(",", $list_data);

		$sql = " update shop_goods set use_hide = '$s_value' where index_no = '{$_POST['gs_id'][$k]}'"; 
		sql_query($sql);
	}
}

goto_url(TB_MYPAGE_URL."/page.php?$q1&page=$page");
?>