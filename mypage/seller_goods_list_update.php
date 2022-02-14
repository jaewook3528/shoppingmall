<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$count = count($_POST['chk']);
if(!$count) {
	alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

if($_POST['act_button'] == "선택삭제")
{
    // _GOODS_DELETE_ 상수를 선언해야 goods_delete.inc.php 가 정상 작동함
    define('_GOODS_DELETE_', true);

	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		// include 전에 $gs_id 값을 반드시 넘겨야 함
		$gs_id = trim($_POST['gs_id'][$k]);
		include(TB_ADMIN_PATH.'/goods/goods_delete.inc.php');
	}
} 
else if($_POST['act_button'] == "선택상품복사") 
{
    // _GOODS_COPY_ 상수를 선언해야 goods_copy.inc.php 가 정상 작동함
    define('_GOODS_COPY_', true);

	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		// include 전에 $gs_id 값을 반드시 넘겨야 함
		$gs_id = trim($_POST['gs_id'][$k]);
		include(TB_ADMIN_PATH.'/goods/goods_copy.inc.php');
	}
} 
else if($_POST['act_button'] == "선택재고수정") 
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$gs_id = trim($_POST['gs_id'][$k]);
		$stock_qty = conv_number($_POST['stock_qty']);

		$sql = " update shop_goods set stock_qty = '$stock_qty' where index_no = '$gs_id' ";
		sql_query($sql);
	}
} 
else if($_POST['act_button'] == "선택옵션재고수정") 
{
	for($i=0; $i<$count; $i++)
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$io_no = trim($_POST['io_no'][$k]);
		$io_stock_qty = conv_number($_POST['stock_qty']);

		$sql = " update shop_goods_option set io_stock_qty = '$io_stock_qty' where io_no = '$io_no' ";
		sql_query($sql);
	}
} 
else {
	alert();
}

goto_url(TB_MYPAGE_URL."/page.php?$q1&page=$page");
?>