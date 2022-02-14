<?php
include_once("./_common.php");

check_demo();

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

$sql_common = " from shop_seller_cal a, shop_seller b ";
$sql_search = " where a.mb_id = b.mb_id ";
$sql_order  = " order by a.index_no desc ";

if($sfl && $stx) {
    $sql_search .= " and $sfl like '%$stx%' ";
}

// 기간검색
if($fr_date && $to_date)
    $sql_search .= " and a.reg_time between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
else if($fr_date && !$to_date)
	$sql_search .= " and a.reg_time between '$fr_date 00:00:00' and '$fr_date 23:59:59' ";
else if(!$fr_date && $to_date)
	$sql_search .= " and a.reg_time between '$to_date 00:00:00' and '$to_date 23:59:59' ";

$sql = " select a.*, b.seller_code, b.company_name
			$sql_common
			$sql_search
			$sql_order ";
$result = sql_query($sql);
$cnt = @sql_num_rows($result);
if(!$cnt)
	alert("출력할 자료가 없습니다.");

/** Include PHPExcel */
include_once(TB_LIB_PATH.'/PHPExcel.php');

// Create new PHPExcel object
$excel = new PHPExcel();

// Add some data
$char = 'A';
$excel->setActiveSheetIndex(0)
	->setCellValue($char++.'1', '업체코드')
	->setCellValue($char++.'1', '공급사명')
	->setCellValue($char++.'1', '아이디')
	->setCellValue($char++.'1', '총건수')
	->setCellValue($char++.'1', '주문금액')
	->setCellValue($char++.'1', '포인트결제')
	->setCellValue($char++.'1', '쿠폰할인')
	->setCellValue($char++.'1', '배송비')
	->setCellValue($char++.'1', '공급가총액')
	->setCellValue($char++.'1', '실정산액')
	->setCellValue($char++.'1', '가맹점수수료')
	->setCellValue($char++.'1', '본사마진')
	->setCellValue($char++.'1', '일시');

for($i=2; $row=sql_fetch_array($result); $i++)
{
	$order_idx = explode(',', $row['order_idx']);

	$char = 'A';
	$excel->setActiveSheetIndex(0)
		->setCellValueExplicit($char++.$i, $row['seller_code'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['company_name'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['mb_id'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, count($order_idx), PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['tot_price'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['tot_point'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['tot_coupon'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['tot_baesong'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['tot_supply'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['tot_seller'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['tot_partner'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['tot_admin'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['reg_time'], PHPExcel_Cell_DataType::TYPE_STRING);
}

// Rename worksheet
$excel->getActiveSheet()->setTitle('공급업체정산내역');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$excel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="공급업체정산내역-'.date("ymd", time()).'.xlsx"');
header('Cache-Control: max-age=0');

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');
?>