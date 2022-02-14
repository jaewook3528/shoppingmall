<?php
include_once("./_common.php");

check_demo();

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

$sql_common = " from shop_order ";

$where = array();

if($code == 'list') // 전체주문내역
	$where[] = " dan != '0' ";
else
	$where[] = " dan = '$code' ";

if($sfl && $stx)
	$where[] = " $sfl like '%$stx%' ";

if($od_settle_case)
	$where[] = " paymethod = '$od_settle_case' ";

if(is_numeric($od_status))
	$where[] = " dan = '$od_status' ";

if(is_numeric($od_final))
	$where[] = " user_ok = '$od_final' ";

if($od_taxbill)
	$where[] = " taxbill_yes = 'Y' ";

if($od_taxsave)
	$where[] = " taxsave_yes IN ('Y','S') ";

if($od_memo)
	$where[] = " memo <> '' ";

if($od_shop_memo)
	$where[] = " shop_memo <> '' ";

if($od_receipt_point)
	$where[] = " use_point != 0 ";

if($od_coupon)
	$where[] = " coupon_price != 0 ";

if($od_escrow)
	$where[] = " od_escrow = 1 ";

if($fr_date && $to_date)
    $where[] = " left({$sel_field},10) between '$fr_date' and '$to_date' ";
else if($fr_date && !$to_date)
	$where[] = " left({$sel_field},10) between '$fr_date' and '$fr_date' ";
else if(!$fr_date && $to_date)
	$where[] = " left({$sel_field},10) between '$to_date' and '$to_date' ";

if($where) {
    $sql_search = ' where '.implode(' and ', $where);
}

$sql_order = " order by od_time desc, index_no asc ";

$sql = " select * {$sql_common} {$sql_search} {$sql_order} ";
$result = sql_query($sql);
$cnt = @sql_num_rows($result);
if(!$cnt)
	alert("출력할 자료가 없습니다.");

define("_ORDERPHPExcel_", true);

// 주문서 PHPExcel 공통
include_once(TB_ADMIN_PATH.'/order/order_excel.sub.php');
?>