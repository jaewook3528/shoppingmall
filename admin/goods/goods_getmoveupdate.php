<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$gubun = trim($_POST['gubun']);
$new_ca_id = trim($_POST['new_ca_id']);
$act = trim($_POST['act']);

if($gubun == '1') // 선택한 상품만 적용 
{
	for($i=0; $i<count($_POST['chk']); $i++) 
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$gs_id = $_POST['gs_id'][$k];

		if($act == 1) { // 분류연결
			$sql = " insert into shop_goods_cate
						set gcate = '$new_ca_id',
							gs_id = '$gs_id' ";
			sql_query($sql);
			
		} else if($act == 2) { // 분류이동
			sql_query(" delete from shop_goods_cate where gs_id = '$gs_id' ");	

			$sql = " insert into shop_goods_cate
						set gcate = '$new_ca_id',
							gs_id = '$gs_id' ";
			sql_query($sql);
		
		} else if($act == 3) { // 모든 분류를 연결해제 
			sql_query(" delete from shop_goods_cate where gs_id = '$gs_id' ");	
		
		} else if($act == 4) { // 추가 분류만 연결해제 
			$sql = "select * from shop_goods_cate where gs_id='$gs_id' order by index_no asc limit 1 ";
			$ca = sql_fetch($sql);

			sql_query(" delete from shop_goods_cate where gs_id = '$gs_id' and index_no <> '$ca[index_no]' ");	
		}
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
		$gs_id = $row['index_no'];

		if($act == 1) { // 분류연결
			$sql = " insert into shop_goods_cate
						set gcate = '$new_ca_id',
							gs_id = '$gs_id' ";
			sql_query($sql);
			
		} else if($act == 2) { // 분류이동
			sql_query(" delete from shop_goods_cate where gs_id = '$gs_id' ");	

			$sql = " insert into shop_goods_cate
						set gcate = '$new_ca_id',
							gs_id = '$gs_id' ";
			sql_query($sql);
		
		} else if($act == 3) { // 모든 분류를 연결해제 
			sql_query(" delete from shop_goods_cate where gs_id = '$gs_id' ");	
		
		} else if($act == 4) { // 추가 분류만 연결해제 
			$sql = "select * from shop_goods_cate where gs_id='$gs_id' order by index_no asc limit 1 ";
			$ca = sql_fetch($sql);

			sql_query(" delete from shop_goods_cate where gs_id = '$gs_id' and index_no <> '$ca[index_no]' ");	
		}
	}	
}

goto_url(TB_ADMIN_URL."/goods.php?$q1&page=$page");
?>