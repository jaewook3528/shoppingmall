<?php
include_once("./_common.php");

check_demo();

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

$sql_common = " from shop_seller a, shop_member b ";
$sql_search = " where a.mb_id = b.id ";

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

if(!$orderby) {
    $filed = "a.state";
    $sod = "asc, a.index_no desc";
} else {
	$sod = $orderby;
}

$sql_order = " order by $filed $sod ";

$sql = " select a.* $sql_common $sql_search $sql_order ";
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
	->setCellValue($char++.'1', '아이디')
	->setCellValue($char++.'1', '업체코드')
	->setCellValue($char++.'1', '제공상품')
	->setCellValue($char++.'1', '공급사명')
	->setCellValue($char++.'1', '사업자등록번호')
	->setCellValue($char++.'1', '전화번호')
	->setCellValue($char++.'1', '팩스번호')
	->setCellValue($char++.'1', '우편번호')
	->setCellValue($char++.'1', '사업장주소')
	->setCellValue($char++.'1', '업태')
	->setCellValue($char++.'1', '종목')
	->setCellValue($char++.'1', '대표자명')
	->setCellValue($char++.'1', '홈페이지')
	->setCellValue($char++.'1', '전달사항')
	->setCellValue($char++.'1', '은행명')
	->setCellValue($char++.'1', '계좌번호')
	->setCellValue($char++.'1', '예금주명')
	->setCellValue($char++.'1', '담당자명')
	->setCellValue($char++.'1', '담당자이메일')
	->setCellValue($char++.'1', '담당자전화번호');

for($i=2; $row=sql_fetch_array($result); $i++)
{
	$char = 'A';
	$excel->setActiveSheetIndex(0)
		->setCellValueExplicit($char++.$i, $row['mb_id'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['seller_code'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['seller_item'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['company_name'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['company_saupja_no'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['company_tel'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['company_fax'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['company_zip'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, print_address($row['company_addr1'], $row['company_addr2'], $row['company_addr3'], $row['company_addr_jibeon'], PHPExcel_Cell_DataType::TYPE_STRING))
		->setCellValueExplicit($char++.$i, $row['company_item'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['company_service'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['company_owner'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, set_http($row['company_hompage'], PHPExcel_Cell_DataType::TYPE_STRING))
		->setCellValueExplicit($char++.$i, $row['memo'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['bank_name'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['bank_account'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['bank_holder'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['info_name'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['info_email'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['info_tel'], PHPExcel_Cell_DataType::TYPE_STRING);
}

// Rename worksheet
$excel->getActiveSheet()->setTitle('공급업체');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$excel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="공급업체-'.date("ymd", time()).'.xlsx"');
header('Cache-Control: max-age=0');

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');
?>