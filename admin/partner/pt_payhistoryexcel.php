<?php
include_once("./_common.php");

check_demo();

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

$sql_common = " from shop_partner_pay a, shop_member b ";
$sql_search = " where a.mb_id = b.id ";

if($sfl && $stx) {
	$sql_search .= " and $sfl like '%$stx%' ";
}

if(isset($sst) && is_numeric($sst))
	$sql_search .= " and b.grade = '$sst' ";

if($rel_field)
	$sql_search .= " and a.pp_rel_table = '{$rel_field}' ";

if($fr_date && $to_date)
    $sql_search .= " and a.pp_datetime between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
else if($fr_date && !$to_date)
	$sql_search .= " and a.pp_datetime between '$fr_date 00:00:00' and '$fr_date 23:59:59' ";
else if(!$fr_date && $to_date)
	$sql_search .= " and a.pp_datetime between '$to_date 00:00:00' and '$to_date 23:59:59' ";

if(!$orderby) {
    $filed = "a.pp_id";
    $sod = "desc";
} else {
	$sod = $orderby;
}

$sql_order = " order by {$filed} {$sod} ";

$sql = " select a.*, b.name, b.grade {$sql_common} {$sql_search} {$sql_order} ";
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
	->setCellValue($char++.'1', '회원명')
	->setCellValue($char++.'1', '아이디')
	->setCellValue($char++.'1', '레벨')
	->setCellValue($char++.'1', '수수료내용')
	->setCellValue($char++.'1', '일시')
	->setCellValue($char++.'1', '구분')
	->setCellValue($char++.'1', '수수료')
	->setCellValue($char++.'1', '수수료합');

for($i=2; $row=sql_fetch_array($result); $i++)
{
	$char = 'A';
	$excel->setActiveSheetIndex(0)
		->setCellValueExplicit($char++.$i, $row['name'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['mb_id'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, get_grade($row['grade']), PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['pp_content'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['pp_datetime'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $gw_ptype[$row['pp_rel_table']], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['pp_pay'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['pp_balance'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
}

// Rename worksheet
$excel->getActiveSheet()->setTitle('가맹점 수수료내역');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$excel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="가맹점 수수료내역-'.date("ymd", time()).'.xlsx"');
header('Cache-Control: max-age=0');

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');
?>