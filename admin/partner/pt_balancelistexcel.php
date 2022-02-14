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

if($fr_date && $to_date)
    $sql_search .= " and a.pp_datetime between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
else if($fr_date && !$to_date)
	$sql_search .= " and a.pp_datetime between '$fr_date 00:00:00' and '$fr_date 23:59:59' ";
else if(!$fr_date && $to_date)
	$sql_search .= " and a.pp_datetime between '$to_date 00:00:00' and '$to_date 23:59:59' ";

if(!$orderby) {
    $filed = "balance";
    $sod = "desc";
} else {
	$sod = $orderby;
}

$sql_group = " group by a.mb_id HAVING SUM(a.pp_pay) > 0 ";
$sql_order = " order by {$filed} {$sod} ";

$sql = " select a.*, 	
			    SUM(a.pp_pay) as balance,
				b.name,
				b.grade,
				b.term_date
           {$sql_common} {$sql_search} {$sql_group} {$sql_order} ";
$result = sql_query($sql);
$cnt = @sql_num_rows($result);
if(!$cnt)
	alert("출력할 내역이 없습니다.");

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
	->setCellValue($char++.'1', '수수료')
	->setCellValue($char++.'1', '세액공제')
	->setCellValue($char++.'1', '실수령액')
	->setCellValue($char++.'1', '은행명')
	->setCellValue($char++.'1', '계좌번호')
	->setCellValue($char++.'1', '예금주명');

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

	$paytax = 0;
	if($config['pf_payment_tax']) { // 세액공제
		$paytax = floor(($row['balance'] * $config['pf_payment_tax']) / 100);
	}

	$paynet = $row['balance'] - $paytax;	

	$pt = get_partner($row['mb_id'], 'bank_name, bank_account, bank_holder');

	$char = 'A';
	$excel->setActiveSheetIndex(0)
		->setCellValueExplicit($char++.$i, $row['name'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['mb_id'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, get_grade($row['grade']), PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $expire_date, PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['balance'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $paytax, PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $paynet, PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $pt['bank_name'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $pt['bank_account'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $pt['bank_holder'], PHPExcel_Cell_DataType::TYPE_STRING);
}

// Rename worksheet
$excel->getActiveSheet()->setTitle('가맹점 수수료정산');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$excel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="가맹점 수수료정산-'.date("ymd", time()).'.xlsx"');
header('Cache-Control: max-age=0');

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');
?>