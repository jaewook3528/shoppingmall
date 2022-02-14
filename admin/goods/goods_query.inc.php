<?php
// 이 파일은 새로운 파일 생성시 반드시 포함되어야 함
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가 

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

if($sca) {
	$len = strlen($sca);
    $sql_common .= " left join shop_goods_cate b on a.index_no=b.gs_id ";
    $sql_search .= " and (left(b.gcate,$len) = '$sca') ";
}

// 검색어
if($stx) {
    switch($sfl) {
        case "gname" :
		case "explan" :
		case "maker" :
		case "origin" :		
		case "model" :
            $sql_search .= " and a.$sfl like '%$stx%' ";
            break;
        default : 
            $sql_search .= " and a.$sfl like '$stx%' ";
            break;
    }
}

// 기간검색
if($fr_date && $to_date)
    $sql_search .= " and a.$q_date_field between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
else if($fr_date && !$to_date)
	$sql_search .= " and a.$q_date_field between '$fr_date 00:00:00' and '$fr_date 23:59:59' ";
else if(!$fr_date && $to_date)
	$sql_search .= " and a.$q_date_field between '$to_date 00:00:00' and '$to_date 23:59:59' ";

// 브랜드
if(isset($q_brand) && $q_brand)
	$sql_search .= " and a.brand_uid = '$q_brand' ";

// 배송가능 지역
if(isset($q_zone) && $q_zone)
	$sql_search .= " and a.zone = '$q_zone' ";

// 상품재고
if($fr_stock && $to_stock)
	$sql_search .= " and a.$q_stock_field between '$fr_stock' and '$to_stock' ";

// 상품가격
if(is_numeric($fr_price) && is_numeric($to_price))
	$sql_search .= " and a.$q_price_field between '$fr_price' and '$to_price' "; 

// 판매여부
if(isset($q_isopen) && is_numeric($q_isopen))
	$sql_search .= " and a.isopen='$q_isopen' ";

// 과세유형
if(isset($q_notax) && is_numeric($q_notax))
	$sql_search .= " and a.notax = '$q_notax' ";

// 상품 필수옵션
if(isset($q_option) && is_numeric($q_option)) {
	if($q_option)
		$sql_search .= " and a.opt_subject <> '' ";
	else
		$sql_search .= " and a.opt_subject = '' ";
}

// 상품 추가옵션
if(isset($q_supply) && is_numeric($q_supply)) {
	if($q_supply)
		$sql_search .= " and a.spl_subject <> '' ";
	else
		$sql_search .= " and a.spl_subject = '' ";
}

// 장기무이자	unltd
if(isset($psys_long_use) && $psys_long_use)
	$sql_search .= " and psys_long_use = '$psys_long_use' ";
?>