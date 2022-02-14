<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

if(!defined("_ORDERPHPExcel_")) exit; // 개별 페이지 접근 불가

/** Include PHPExcel */
include_once(TB_LIB_PATH.'/PHPExcel.php');

// Create new PHPExcel object
$excel = new PHPExcel();

// Add some data
$char = 'A';
$excel->setActiveSheetIndex(0)
	->setCellValue($char++.'1', '가맹점ID')
	->setCellValue($char++.'1', '판매자ID')
	->setCellValue($char++.'1', '회원ID')
	->setCellValue($char++.'1', '주문번호')
	->setCellValue($char++.'1', '일련번호')
	->setCellValue($char++.'1', '상품명')
	->setCellValue($char++.'1', '옵션')
	->setCellValue($char++.'1', '공급가')
	->setCellValue($char++.'1', '판매가')
	->setCellValue($char++.'1', '수량')
	->setCellValue($char++.'1', '쿠폰할인')
	->setCellValue($char++.'1', '포인트결제')
	->setCellValue($char++.'1', '배송비')
	->setCellValue($char++.'1', '실결제금액')
	->setCellValue($char++.'1', '총주문금액')
	->setCellValue($char++.'1', '결제방법')
	->setCellValue($char++.'1', '주문상태')
	->setCellValue($char++.'1', '주문채널')
	->setCellValue($char++.'1', '주문자명')
	->setCellValue($char++.'1', '수취인명')
	->setCellValue($char++.'1', '수취인전화번호')
	->setCellValue($char++.'1', '수취인핸드폰')
	->setCellValue($char++.'1', '수취인우편번호')
	->setCellValue($char++.'1', '수취인주소')
	->setCellValue($char++.'1', '주문시요청사항')
	->setCellValue($char++.'1', '배송회사')
	->setCellValue($char++.'1', '운송장번호')
	->setCellValue($char++.'1', '입금자명')
	->setCellValue($char++.'1', '입금일시')
	->setCellValue($char++.'1', '주문일시')
	->setCellValue($char++.'1', '거래증빙')
	->setCellValue($char++.'1', '세금계산서(상호명)')
	->setCellValue($char++.'1', '세금계산서(대표자)')
	->setCellValue($char++.'1', '세금계산서(사업자등록번호)')
	->setCellValue($char++.'1', '세금계산서(사업장주소)')
	->setCellValue($char++.'1', '세금계산서(업태)')
	->setCellValue($char++.'1', '세금계산서(종목)')
	->setCellValue($char++.'1', '현금영수증(사업자 지출증빙용)')
	->setCellValue($char++.'1', '현금영수증(개인 소득공제용)')
	->setCellValue($char++.'1', '관리자메모');

for($i=2; $row=sql_fetch_array($result); $i++)
{
	$gs = unserialize($row['od_goods']);
	$amount = get_order_spay($row['od_id']);
	$sodr = excel_order_list($row, $amount);

	$char = 'A';
	$excel->setActiveSheetIndex(0)
		->setCellValueExplicit($char++.$i, $sodr['od_pt_id'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $sodr['od_seller_id'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $sodr['od_mb_id'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['od_id'].$sodr['od_test'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['od_no'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $gs['gname'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $sodr['it_options'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['supply_price'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['goods_price'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['sum_qty'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['coupon_price'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['use_point'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['baesong_price'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['use_price'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['goods_price']+$row['baesong_price'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $sodr['od_paytype'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $gw_status[$row['dan']], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $sodr['od_mobile'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['name'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['b_name'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['b_telephone'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['b_cellphone'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['b_zip'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, print_address($row['b_addr1'], $row['b_addr2'], $row['b_addr3'], $row['b_addr_jibeon']), PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['memo'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $sodr['od_delivery_company'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['delivery_no'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['deposit_name'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $sodr['od_receipt_time'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['od_time'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $sodr['od_taxbill'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['company_name'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['company_owner'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['company_saupja_no'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['company_addr'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['company_item'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['company_service'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['tax_saupja_no'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['tax_hp'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['shop_memo'], PHPExcel_Cell_DataType::TYPE_STRING);
}

// Rename worksheet
$excel->getActiveSheet()->setTitle('주문내역');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$excel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="주문내역-'.date("ymd", time()).'.xlsx"');
header('Cache-Control: max-age=0');

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');
?>