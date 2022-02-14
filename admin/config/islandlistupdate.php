<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$count = count($_POST['chk']);
if($_POST['act_button'] != "추가" && !$count) {
	alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

if($_POST['act_button'] == "추가") {

    $is_name  = trim($_POST['is_name']);
    $is_zip1  = conv_number($_POST['is_zip1']);
    $is_zip2  = conv_number($_POST['is_zip2']);
    $is_price = conv_number($_POST['is_price']);

    if(!$is_name)
        alert('지역명을 입력해 주십시오.');
    if(!$is_zip1)
        alert('우편번호 시작을 입력해 주십시오.');
    if(!$is_zip2)
        alert('우편번호 끝을 입력해 주십시오.');
    if(!$is_price)
        alert('추가배송비를 입력해 주십시오.');

	$sql = " insert shop_island
				set is_name  = '$is_name',
					is_zip1  = '$is_zip1',
					is_zip2  = '$is_zip2',
					is_price = '$is_price' ";
	sql_query($sql);
} 
else if($_POST['act_button'] == "선택수정")
{
	for($i=0; $i<$count; $i++)
	{
		$k = $_POST['chk'][$i];

		$sql = " update shop_island
					set is_name  = '{$_POST['is_name'][$k]}',
						is_zip1  = '".conv_number($_POST['is_zip1'][$k])."',
						is_zip2  = '".conv_number($_POST['is_zip2'][$k])."',
						is_price = '".conv_number($_POST['is_price'][$k])."'
				  where is_id = '{$_POST['is_id'][$k]}' ";
		sql_query($sql);
	}
} 
else if($_POST['act_button'] == "선택삭제") 
{
	for($i=0; $i<$count; $i++)
	{
		$k = $_POST['chk'][$i];

		$sql = " delete from shop_island where is_id = '{$_POST['is_id'][$k]}' ";
		sql_query($sql);
    }
} 
else {
	alert();
}

goto_url(TB_ADMIN_URL."/config.php?$q1&page=$page");
?>