<?php
include_once("./_common.php");

check_demo();

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

$sql_common = " from shop_order a, shop_seller b ";
$sql_search = " where a.seller_id = b.seller_code
				  and left(a.seller_id,3)='AP-'
				  and a.dan IN(5,8)
				  and a.sellerpay_yes = '0'
				  and a.user_ok = '1' ";

if($sfl && $stx) {
    $sql_search .= " and $sfl like '%$stx%' ";
}

if($fr_date && $to_date) {
	$sql_search .= " and left(a.od_time,10) between '$fr_date' and '$to_date' ";
}

$sql_order = " group by a.seller_id order by a.index_no desc ";

$sql = " select a.*, b.mb_id, b.company_name, b.bank_name, b.bank_account, b.bank_holder
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
	->setCellValue($char++.'1', '은행명')
	->setCellValue($char++.'1', '계좌번호')
	->setCellValue($char++.'1', '예금주명');

for($i=2; $row=sql_fetch_array($result); $i++)
{
	$tot_price	 = 0;
	$tot_point	 = 0;
	$tot_coupon	 = 0;
	$tot_baesong = 0;
	$tot_supply	 = 0;
	$tot_seller	 = 0;
	$tot_partner = 0;
	$tot_admin	 = 0;
	$order_idx	 = array();
	$order_arr	 = array();

	$sql2 = " select *
				from shop_order
			   where seller_id = '{$row['seller_id']}'
				 and dan IN(5,8)
				 and sellerpay_yes = '0'
				 and user_ok = '1' ";
	if($fr_date && $to_date) {
		$sql2 .= " and left(od_time,10) between '$fr_date' and '$to_date' ";
	}
	$res2 = sql_query($sql2);
	while($row2 = sql_fetch_array($res2)) {
		$psql = " select SUM(pp_pay) as sum_pay
					from shop_partner_pay
				   where pp_rel_table = 'sale'
					 and pp_rel_id = '{$row2['od_no']}'
					 and pp_rel_action = '{$row2['od_id']}' ";
		$psum = sql_fetch($psql);

		$tot_point   += (int)$row2['use_point']; // 포인트결제
		$tot_supply  += (int)$row2['supply_price']; // 공급가
		$tot_price   += (int)$row2['goods_price']; // 판매가
		$tot_baesong += (int)$row2['baesong_price']; // 배송비
		$tot_coupon  += (int)$row2['coupon_price']; // 쿠폰할인
		$tot_partner += (int)$psum['sum_pay']; // 가맹점수수료
		$order_idx[] = $row2['index_no'];
		$order_arr['od_id'] = $row2['od_id'];
	}

	/*
	// 반품.환불건에 포함된 배송비도 합산
	foreach($order_arr as $key) {
		$sql3 = " select baesong_price
					from shop_order
				   where seller_id = '{$row['seller_id']}'
					 and dan IN(7,9)
					 and sellerpay_yes = '0'
					 and od_id = '$key' ";
		$res3 = sql_query($sql3);
		while($row3 = sql_fetch_array($res3)) {
			$tot_baesong += (int)$row3['baesong_price']; // 배송비
		}
	}
	*/

	// 정산액 = (공급가합 + 배송비)
	$tot_seller = ($tot_supply + $tot_baesong);

	// 본사마진 = (판매가 - 공급가 - 가맹점수수료 - 포인트결제 - 쿠폰할인)
	$tot_admin = ($tot_price - $tot_supply - $tot_partner - $tot_point - $tot_coupon);

	$char = 'A';
	$excel->setActiveSheetIndex(0)
		->setCellValueExplicit($char++.$i, $row['seller_id'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['company_name'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['mb_id'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, count($order_idx), PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $tot_price, PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $tot_point, PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $tot_coupon, PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $tot_baesong, PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $tot_supply, PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $tot_seller, PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $tot_partner, PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $tot_admin, PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['bank_name'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['bank_account'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['bank_holder'], PHPExcel_Cell_DataType::TYPE_STRING);
}

// Rename worksheet
$excel->getActiveSheet()->setTitle('공급업체정산');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$excel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="공급업체정산-'.date("ymd", time()).'.xlsx"');
header('Cache-Control: max-age=0');

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');
?>