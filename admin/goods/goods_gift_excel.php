<?php
include_once("./_common.php");

check_demo();

$sql_common = " from shop_gift ";
$sql_search = " where gr_id = '$gr_id' ";

if($sfl && $stx) {
    $sql_search .= " and $sfl like '%$stx%' ";
}

if(!$orderby) {
    $filed = "no";
    $sod = "desc";
} else {
	$sod = $orderby;
}

$sql_order = " order by $filed $sod ";

$sql = " select * $sql_common $sql_search $sql_order ";
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
	->setCellValue($char++.'1', '일련번호')
	->setCellValue($char++.'1', '쿠폰명')
	->setCellValue($char++.'1', '설명')
	->setCellValue($char++.'1', '발행금액')
	->setCellValue($char++.'1', '시작일')
	->setCellValue($char++.'1', '종료일')
	->setCellValue($char++.'1', '등록일')
	->setCellValue($char++.'1', '인증번호')
	->setCellValue($char++.'1', '사용')
	->setCellValue($char++.'1', '사용자아이디')
	->setCellValue($char++.'1', '사용자이름')
	->setCellValue($char++.'1', '최종사용일');

for($i=2; $row=sql_fetch_array($result); $i++)
{
	$grp = sql_fetch("select * from shop_gift_group where gr_id = '$row[gr_id]'");

	if(is_null_time($row['mb_wdate'])) $row['mb_wdate'] = '';
	$row['gi_use'] = $row['gi_use'] ? 'yes' : 'no';

	$char = 'A';
	$excel->setActiveSheetIndex(0)
	->setCellValueExplicit($char++.$i, $row['gr_id'], PHPExcel_Cell_DataType::TYPE_STRING)
	->setCellValueExplicit($char++.$i, $row['gr_subject'], PHPExcel_Cell_DataType::TYPE_STRING)
	->setCellValueExplicit($char++.$i, $grp['gr_explan'], PHPExcel_Cell_DataType::TYPE_STRING)
	->setCellValueExplicit($char++.$i, $row['gr_price'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
	->setCellValueExplicit($char++.$i, $row['gr_sdate'], PHPExcel_Cell_DataType::TYPE_STRING)
	->setCellValueExplicit($char++.$i, $row['gr_edate'], PHPExcel_Cell_DataType::TYPE_STRING)
	->setCellValueExplicit($char++.$i, $grp['gr_wdate'], PHPExcel_Cell_DataType::TYPE_STRING)
	->setCellValueExplicit($char++.$i, $row['gi_num'], PHPExcel_Cell_DataType::TYPE_STRING)
	->setCellValueExplicit($char++.$i, $row['gi_use'], PHPExcel_Cell_DataType::TYPE_STRING)
	->setCellValueExplicit($char++.$i, $row['mb_id'], PHPExcel_Cell_DataType::TYPE_STRING)
	->setCellValueExplicit($char++.$i, $row['mb_name'], PHPExcel_Cell_DataType::TYPE_STRING)
	->setCellValueExplicit($char++.$i, $row['mb_wdate'], PHPExcel_Cell_DataType::TYPE_STRING);
}

// Rename worksheet
$excel->getActiveSheet()->setTitle('쿠폰(인쇄용)');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$excel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="쿠폰(인쇄용)-'.date("ymd", time()).'.xlsx"');
header('Cache-Control: max-age=0');

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');
?>