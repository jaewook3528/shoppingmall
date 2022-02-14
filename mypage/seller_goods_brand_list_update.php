<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$count = count($_POST['chk']);
if(!$count) {
	alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

if($_POST['act_button'] == "선택수정")
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$sql = "update shop_brand 
				   set br_name = '{$_POST['br_name'][$k]}',
					   br_name_eng = '{$_POST['br_name_eng'][$k]}'
				 where br_id = '{$_POST['br_id'][$k]}'";
		sql_query($sql);

		// 상품 정보도 동시에 수정
		$sql = "update shop_goods 
				   set brand_nm = '{$_POST['br_name'][$k]}' 
				 where brand_uid = '{$_POST['br_id'][$k]}'";
		sql_query($sql);
	}
} 
else if($_POST['act_button'] == "선택삭제") 
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$br_id = trim($_POST['br_id'][$k]);

		$row = sql_fetch("select br_logo from shop_brand where br_id = '$br_id'");
		@unlink(TB_DATA_PATH."/brand/".$row['br_logo']);

		sql_query("delete from shop_brand where br_id='$br_id' ");
	}
}

goto_url(TB_MYPAGE_URL."/page.php?$q1&page=$page");
?>