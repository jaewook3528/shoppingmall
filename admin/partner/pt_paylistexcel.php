<?php
include_once("./_common.php");

check_demo();

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

$sql_common = " from shop_member ";
$sql_search = " where grade between 2 and 6 ";

if($sfl && $stx) {
    $sql_search .= " and $sfl like '%$stx%' ";
}

if(isset($sst) && is_numeric($sst))
	$sql_search .= " and grade = '$sst' ";

if($fr_date && $to_date)
    $sql_search .= " and term_date between '$fr_date' and '$to_date' ";
else if($fr_date && !$to_date)
	$sql_search .= " and term_date between '$fr_date' and '$fr_date' ";
else if(!$fr_date && $to_date)
	$sql_search .= " and term_date between '$to_date' and '$to_date' ";

if(!$orderby) {
    $filed = "index_no";
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
	->setCellValue($char++.'1', '회원명')
	->setCellValue($char++.'1', '아이디')
	->setCellValue($char++.'1', '레벨')
	->setCellValue($char++.'1', '만료일')
	->setCellValue($char++.'1', '현재잔액')
	->setCellValue($char++.'1', '총적립액')
	->setCellValue($char++.'1', '총차감액')
	->setCellValue($char++.'1', '판매')
	->setCellValue($char++.'1', '추천')
	->setCellValue($char++.'1', '접속')
	->setCellValue($char++.'1', '본사');

for($i=2; $row=sql_fetch_array($result); $i++)
{
	$expire_date = '무제한';

	// 관리비를 사용중인가?
	if($config['pf_expire_use']) {			
		if($row['term_date'] < TB_TIME_YMD)
			$expire_date = '만료'.substr(conv_number($row['term_date']), 2);
		else
			$expire_date = $row['term_date'];
	}

	$info  = get_pay_sheet($row['id']); // 누적
	$sale  = get_pay_status($row['id'], 'sale'); // 판매
	$anew  = get_pay_status($row['id'], 'anew'); // 추천
	$visit = get_pay_status($row['id'], 'visit'); // 접속
	$admin = get_pay_status($row['id'], 'passive'); // 본사

	$char = 'A';
	$excel->setActiveSheetIndex(0)
		->setCellValueExplicit($char++.$i, $row['name'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['id'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, get_grade($row['grade']), PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $expire_date, PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['pay'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $info['pay'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $info['usepay'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $sale['pay'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $anew['pay'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $visit['pay'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $admin['pay'], PHPExcel_Cell_DataType::TYPE_NUMERIC);
}

// Rename worksheet
$excel->getActiveSheet()->setTitle('가맹점수수료');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$excel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="가맹점수수료-'.date("ymd", time()).'.xlsx"');
header('Cache-Control: max-age=0');

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');
?>