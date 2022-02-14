<?php
include_once("./_common.php");

check_demo();

$sql = " select * from shop_order where dan = '3' order by od_time desc, index_no asc ";
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
	->setCellValue($char++.'1', '판매자ID')
	->setCellValue($char++.'1', '주문번호')
	->setCellValue($char++.'1', '일련번호')
	->setCellValue($char++.'1', '상품명')
	->setCellValue($char++.'1', '주문자')
	->setCellValue($char++.'1', '주문자전화1')
	->setCellValue($char++.'1', '주문자전화2')
	->setCellValue($char++.'1', '수령자')
	->setCellValue($char++.'1', '수령지전화1')
	->setCellValue($char++.'1', '수령지전화2')
	->setCellValue($char++.'1', '수령지우편번호')
	->setCellValue($char++.'1', '수령지주소')
	->setCellValue($char++.'1', '배송회사')
	->setCellValue($char++.'1', '운송장번호');

for($i=2; $row=sql_fetch_array($result); $i++)
{	
	$gs = unserialize($row['od_goods']);

	$char = 'A';
	$excel->setActiveSheetIndex(0)
		->setCellValueExplicit($char++.$i, $row['seller_id'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['od_id'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['od_no'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $gs['gname'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['name'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['telephone'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['cellphone'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['b_name'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['b_telephone'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['b_cellphone'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['b_zip'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, print_address($row['b_addr1'], $row['b_addr2'], $row['b_addr3'], $row['b_addr_jibeon']), PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, '', PHPExcel_Cell_DataType::TYPE_STRING);
}

// Rename worksheet
$excel->getActiveSheet()->setTitle('엑셀배송처리');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$excel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="엑셀배송처리-'.date("ymd", time()).'.xlsx"');
header('Cache-Control: max-age=0');

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');
?>