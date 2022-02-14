<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$gubun = trim($_POST['gubun']);
$price = trim($_POST['price']);
$price_field = trim($_POST['price_field']);
$price_type = trim($_POST['price_type']);
$price_both = trim($_POST['price_both']);
$price_target = trim($_POST['price_target']);
$price_unit = trim($_POST['price_unit']);
$price_cut = trim($_POST['price_cut']);

if(!$price) 
	alert("적용할 숫자가 0 이거나 값이 없습니다.");

if($gubun == '1') // 선택한 상품만 적용 
{
	for($i=0; $i<count($_POST['chk']); $i++) 
	{
		// 실제 번호를 넘김
		$k = $_POST['chk'][$i];

		$gs_id = $_POST['gs_id'][$k];

		$sql = " select goods_price, normal_price, supply_price
				   from shop_goods 
				  where index_no = '$gs_id' ";
		$gs = sql_fetch($sql);			
		
		$goods_price = 0;
		if($price_type == '%') // (%)를
			$goods_price = ($gs[$price_field] / 100) * $price;
		else // (원)을
			$goods_price = $price;
		
		if($price_both == 'up') // 할증된 가격으로
			$goods_price = $gs[$price_field] + $goods_price; 
		else // 할인된 가격으로
			$goods_price = $gs[$price_field] - $goods_price;

		if($price_cut == 'floor') // 내림
			$goods_price = floor($goods_price/$price_unit) * $price_unit; 
		else if($price_cut == 'round') // 반올림
			$goods_price = round($goods_price/$price_unit) * $price_unit; 
		else if($price_cut == 'ceil') // 올림
			$goods_price = ceil($goods_price/$price_unit) * $price_unit; 

		$sql = " update shop_goods
					set $price_target = '$goods_price'
				  where index_no = '$gs_id' ";
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

	$sql = " select a.index_no,a.goods_price,a.normal_price,a.supply_price $sql_common $sql_search $sql_order ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++)
	{
		$goods_price = 0;
		if($price_type == '%') // (%)를
			$goods_price = ($row[$price_field] / 100) * $price;
		else // (원)을 
			$goods_price = $price;
		
		if($price_both == 'up') // 할증된 가격으로
			$goods_price = $row[$price_field] + $goods_price; 
		else // 할인된 가격으로
			$goods_price = $row[$price_field] - $goods_price;

		if($price_cut == 'floor') // 내림
			$goods_price = floor($goods_price/$price_unit) * $price_unit; 
		else if($price_cut == 'round') // 반올림
			$goods_price = round($goods_price/$price_unit) * $price_unit; 
		else if($price_cut == 'ceil') // 올림
			$goods_price = ceil($goods_price/$price_unit) * $price_unit;
	
		$sql = " update shop_goods
					set $price_target = '$goods_price'
				  where index_no = '{$row['index_no']}' ";
		sql_query($sql);
	}	
}

goto_url(TB_ADMIN_URL."/goods.php?$q1&page=$page");
?>