<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$gubun = trim($_POST['gubun']);
$buy_level = trim($_POST['buy_level']);
$buy_only = trim($_POST['buy_only']);

if($gubun == '1') // 선택한 상품만 적용 
{
	for($i=0; $i<count($_POST['chk']); $i++) 
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$sql = " update shop_goods
					set buy_level = '$buy_level',
					    buy_only = '$buy_only'
				  where index_no = '{$_POST['gs_id'][$k]}' ";
		sql_query($sql);
	}
} 
else if($gubun == '2') // 검색된 상품에 적용 
{ 
	foreach(explode("&", $q1) as $arr_param) {
		$param = explode("=", $arr_param);
		$$param[0] = $param[1];
	}

	if($sel_ca1) $sca = $sel_ca1;
	if($sel_ca2) $sca = $sel_ca2;
	if($sel_ca3) $sca = $sel_ca3;
	if($sel_ca4) $sca = $sel_ca4;
	if($sel_ca5) $sca = $sel_ca5;

	$sql_common = " from shop_goods a ";
	$sql_search = " where a.use_aff = 0 and a.shop_state = 0 ";
	$sql_order  = " group by a.index_no order by a.index_no desc ";

	include_once(TB_ADMIN_PATH.'/goods/goods_query.inc.php');	

	$sql = " select a.index_no $sql_common $sql_search $sql_order ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++)
	{
		$sql = " update shop_goods
					set buy_level = '$buy_level',
					    buy_only = '$buy_only'
				  where index_no = '{$row['index_no']}' ";
		sql_query($sql);
	}	
}

goto_url(TB_ADMIN_URL."/goods.php?$q1&page=$page");
?>