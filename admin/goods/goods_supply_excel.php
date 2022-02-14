<?php
include_once("./_common.php");

check_demo();

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

if($sel_ca1) $sca = $sel_ca1;
if($sel_ca2) $sca = $sel_ca2;
if($sel_ca3) $sca = $sel_ca3;
if($sel_ca4) $sca = $sel_ca4;
if($sel_ca5) $sca = $sel_ca5;

$sql_common = " from shop_goods a ";
$sql_search = " where left(a.mb_id,3)='AP-' and a.shop_state != '0' ";

if($sca) {
	$len = strlen($sca);
    $sql_common .= " left join shop_goods_cate b on a.index_no=b.gs_id ";
    $sql_search .= " and (left(b.gcate,$len) = '$sca') ";
}

// 검색어
if($stx) {
    switch($sfl) {
        case "gname" :
		case "explan" :
		case "maker" :
		case "origin" :		
		case "model" :
            $sql_search .= " and a.$sfl like '%$stx%' ";
            break;
        default : 
            $sql_search .= " and a.$sfl like '$stx%' ";
            break;
    }
}

// 기간검색
if($fr_date && $to_date)
    $sql_search .= " and a.$q_date_field between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
else if($fr_date && !$to_date)
	$sql_search .= " and a.$q_date_field between '$fr_date 00:00:00' and '$fr_date 23:59:59' ";
else if(!$fr_date && $to_date)
	$sql_search .= " and a.$q_date_field between '$to_date 00:00:00' and '$to_date 23:59:59' ";

// 브랜드
if(isset($q_brand) && $q_brand)
	$sql_search .= " and a.brand_uid = '$q_brand' ";

// 배송가능 지역
if(isset($q_zone) && $q_zone)
	$sql_search .= " and a.zone = '$q_zone' ";

// 상품재고
if($fr_stock && $to_stock)
	$sql_search .= " and a.$q_stock_field between '$fr_stock' and '$to_stock' ";

// 상품가격
if(is_numeric($fr_price) && is_numeric($to_price))
	$sql_search .= " and a.$q_price_field between '$fr_price' and '$to_price' "; 

// 판매여부
if(isset($q_isopen) && is_numeric($q_isopen))
	$sql_search .= " and a.isopen='$q_isopen' ";

// 과세유형
if(isset($q_notax) && is_numeric($q_notax))
	$sql_search .= " and a.notax = '$q_notax' ";

// 상품 필수옵션
if(isset($q_option) && is_numeric($q_option)) {
	if($q_option)
		$sql_search .= " and a.opt_subject <> '' ";
	else
		$sql_search .= " and a.opt_subject = '' ";
}

// 상품 추가옵션
if(isset($q_supply) && is_numeric($q_supply)) {
	if($q_supply)
		$sql_search .= " and a.spl_subject <> '' ";
	else
		$sql_search .= " and a.spl_subject = '' ";
}

if(!$orderby) {
    $filed = "a.index_no";
    $sod = "desc";
} else {
	$sod = $orderby;
}

$sql_order = " group by a.index_no order by $filed $sod ";

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
	->setCellValue($char++.'1', '판매자ID')
	->setCellValue($char++.'1', '상품코드')
	->setCellValue($char++.'1', '카테고리')
	->setCellValue($char++.'1', '상품명')
	->setCellValue($char++.'1', '짧은설명')
	->setCellValue($char++.'1', '검색키워드')
	->setCellValue($char++.'1', 'A/S가능여부')
	->setCellValue($char++.'1', '모델명')
	->setCellValue($char++.'1', '브랜드')
	->setCellValue($char++.'1', '과세설정')
	->setCellValue($char++.'1', '판매가능지역')
	->setCellValue($char++.'1', '판매가능지역 추가설명')
	->setCellValue($char++.'1', '원산지')
	->setCellValue($char++.'1', '제조사')
	->setCellValue($char++.'1', '판매여부')
	->setCellValue($char++.'1', '공급가격')
	->setCellValue($char++.'1', '시중가격')
	->setCellValue($char++.'1', '판매가격')
	->setCellValue($char++.'1', '가격대체문구')
	->setCellValue($char++.'1', '재고적용타입')
	->setCellValue($char++.'1', '재고수량')
	->setCellValue($char++.'1', '재고통보수량')
	->setCellValue($char++.'1', '최소주문한도')
	->setCellValue($char++.'1', '최대주문한도')
	->setCellValue($char++.'1', '포인트')
	->setCellValue($char++.'1', '판매기간 시작일')
	->setCellValue($char++.'1', '판매기간 종료일')
	->setCellValue($char++.'1', '구매가능레벨')
	->setCellValue($char++.'1', '가격공개')
	->setCellValue($char++.'1', '배송비유형')
	->setCellValue($char++.'1', '배송비결제')
	->setCellValue($char++.'1', '기본배송비')
	->setCellValue($char++.'1', '조건배송비')
	->setCellValue($char++.'1', '이미지등록방식')
	->setCellValue($char++.'1', '소이미지')
	->setCellValue($char++.'1', '중이미지1')
	->setCellValue($char++.'1', '중이미지2')
	->setCellValue($char++.'1', '중이미지3')
	->setCellValue($char++.'1', '중이미지4')
	->setCellValue($char++.'1', '중이미지5')
	->setCellValue($char++.'1', '상세설명')
	->setCellValue($char++.'1', '관리자메모');

for($i=2; $row=sql_fetch_array($result); $i++)
{
	$comma = $gcate = "";
	$sql2 = "select * from shop_goods_cate where gs_id = '$row[index_no]'";
	$res2 = sql_query($sql2);
	while($row2=sql_fetch_array($res2)) {
		$gcate .= $comma.$row2['gcate'];
		$comma = ",";
	}

	if(is_null_time($row['sb_date'])) $row['sb_date'] = '';
	if(is_null_time($row['eb_date'])) $row['eb_date'] = '';
	
	$char = 'A';
	$excel->setActiveSheetIndex(0)
		->setCellValueExplicit($char++.$i, $row['mb_id'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['gcode'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $gcate, PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['gname'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['explan'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['keywords'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['repair'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['model'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['brand_nm'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['notax'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['zone'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['zone_msg'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['origin'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['maker'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['isopen'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['supply_price'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['normal_price'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['goods_price'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['price_msg'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['stock_mod'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['stock_qty'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['noti_qty'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['odr_min'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['odr_max'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['gpoint'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['sb_date'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['eb_date'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['buy_level'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['buy_only'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['sc_type'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['sc_method'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['sc_amt'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['sc_minimum'], PHPExcel_Cell_DataType::TYPE_NUMERIC)	
		->setCellValueExplicit($char++.$i, $row['simg_type'], PHPExcel_Cell_DataType::TYPE_NUMERIC)
		->setCellValueExplicit($char++.$i, $row['simg1'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['simg2'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['simg3'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['simg4'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['simg5'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['simg6'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['memo'], PHPExcel_Cell_DataType::TYPE_STRING)
		->setCellValueExplicit($char++.$i, $row['admin_memo'], PHPExcel_Cell_DataType::TYPE_STRING);
}

// Rename worksheet
$excel->getActiveSheet()->setTitle('공급업체 대기상품');

// Set active sheet index to the first sheet, so Excel opens this as the first sheet
$excel->setActiveSheetIndex(0);

// Redirect output to a client’s web browser (Excel2007)
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="공급업체 대기상품-'.date("ymd", time()).'.xlsx"');
header('Cache-Control: max-age=0');

$writer = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
$writer->save('php://output');
?>