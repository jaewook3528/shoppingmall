<?php
include_once("./_common.php");

// 자료가 많을 경우 대비 설정변경
set_time_limit ( 0 );
ini_set('memory_limit', '1000M');

check_demo();

check_admin_token();

if($_FILES['excelfile']['tmp_name']) {
    $file = $_FILES['excelfile']['tmp_name'];

    include_once(TB_LIB_PATH.'/Excel/reader.php');

    $data = new Spreadsheet_Excel_Reader();

    // Set output Encoding.
    $data->setOutputEncoding('UTF-8');

    /***
    * if you want you can change 'iconv' to mb_convert_encoding:
    * $data->setUTFEncoder('mb');
    *
    **/

    /***
    * By default rows & cols indeces start with 1
    * For change initial index use:
    * $data->setRowColOffset(0);
    *
    **/

    /***
    * Some function for formatting output.
    * $data->setDefaultFormat('%.2f');
    * setDefaultFormat - set format for columns with unknown formatting
    *
    * $data->setColumnFormat(4, '%.3f');
    * setColumnFormat - set format for column (apply only to number fields)
    *
    **/

    $data->read($file);

    /*
	$data->sheets[0]['numRows'] - count rows
	$data->sheets[0]['numCols'] - count columns
	$data->sheets[0]['cells'][$i][$j] - data from $i-row $j-column

	$data->sheets[0]['cellsInfo'][$i][$j] - extended info about cell

	$data->sheets[0]['cellsInfo'][$i][$j]['type'] = "date" | "number" | "unknown"
	if 'type' == "unknown" - use 'raw' value, because  cell contain value with format '0.00';
	$data->sheets[0]['cellsInfo'][$i][$j]['raw'] = value if cell without format
	$data->sheets[0]['cellsInfo'][$i][$j]['colspan']
	$data->sheets[0]['cellsInfo'][$i][$j]['rowspan']
    */

    error_reporting(E_ALL ^ E_NOTICE);

    $fail_gcode = array();
    $fail_count = 0;
	$total_count = 0;
    $succ_count = 0;

	for($i=2; $i<=$data->sheets[0]['numRows']; $i++) 
	{
		if(trim($data->sheets[0]['cells'][$i][1]) == '')
			continue;

		$total_count++;

        $j = 1;

		$mb_id		= addslashes(trim($data->sheets[0]['cells'][$i][$j++])); // 판매자ID
		$gcode		= addslashes(trim($data->sheets[0]['cells'][$i][$j++])); // 상품코드
		$gcate		= addslashes(trim($data->sheets[0]['cells'][$i][$j++])); // 카테고리
		$gname		= addslashes(trim($data->sheets[0]['cells'][$i][$j++])); // 상품명
		$explan		= addslashes(trim($data->sheets[0]['cells'][$i][$j++])); // 짧은설명
		$keywords	= addslashes(trim($data->sheets[0]['cells'][$i][$j++])); // 검색키워드
		$repair		= addslashes(trim($data->sheets[0]['cells'][$i][$j++])); // A/S가능여부
		$model		= addslashes(trim($data->sheets[0]['cells'][$i][$j++])); // 모델명
		$brand_nm	= addslashes(trim($data->sheets[0]['cells'][$i][$j++])); // 브랜드
		$notax		= addslashes(trim(conv_number($data->sheets[0]['cells'][$i][$j++]))); // 과세설정
		$zone		= addslashes(trim($data->sheets[0]['cells'][$i][$j++])); // 판매가능지역
		$zone_msg	= addslashes(trim($data->sheets[0]['cells'][$i][$j++])); // 판매가능지역 추가설명
		$origin		= addslashes(trim($data->sheets[0]['cells'][$i][$j++])); // 원산지
		$maker		= addslashes(trim($data->sheets[0]['cells'][$i][$j++])); // 제조사
		$isopen		= addslashes(trim(conv_number($data->sheets[0]['cells'][$i][$j++]))); // 판매여부
		$supply_price = addslashes(trim(conv_number($data->sheets[0]['cells'][$i][$j++]))); // 공급가격
		$normal_price = addslashes(trim(conv_number($data->sheets[0]['cells'][$i][$j++]))); // 시중가격
		$goods_price = addslashes(trim(conv_number($data->sheets[0]['cells'][$i][$j++]))); // 판매가격
		$price_msg	= addslashes(trim($data->sheets[0]['cells'][$i][$j++])); // 가격대체문구
		$stock_mod	= addslashes(trim(conv_number($data->sheets[0]['cells'][$i][$j++]))); // 재고적용타입
		$stock_qty	= addslashes(trim(conv_number($data->sheets[0]['cells'][$i][$j++]))); // 재고수량
		$noti_qty	= addslashes(trim(conv_number($data->sheets[0]['cells'][$i][$j++]))); // 재고통보수량
		$odr_min	= addslashes(trim(conv_number($data->sheets[0]['cells'][$i][$j++]))); // 최소주문한도
		$odr_max	= addslashes(trim(conv_number($data->sheets[0]['cells'][$i][$j++]))); // 최대주문한도
		$gpoint		= addslashes(trim(conv_number($data->sheets[0]['cells'][$i][$j++]))); // 포인트
		$sb_date	= addslashes(trim($data->sheets[0]['cells'][$i][$j++])); // 판매기간 시작일
		$eb_date	= addslashes(trim($data->sheets[0]['cells'][$i][$j++])); // 판매기간 종료일
		$buy_level	= addslashes(trim(conv_number($data->sheets[0]['cells'][$i][$j++]))); // 구매가능레벨
		$buy_only	= addslashes(trim(conv_number($data->sheets[0]['cells'][$i][$j++]))); // 가격공개
		$sc_type	= addslashes(trim(conv_number($data->sheets[0]['cells'][$i][$j++]))); // 배송비유형
		$sc_method	= addslashes(trim(conv_number($data->sheets[0]['cells'][$i][$j++]))); // 배송비결제
		$sc_amt		= addslashes(trim(conv_number($data->sheets[0]['cells'][$i][$j++]))); // 기본배송비
		$sc_minimum	= addslashes(trim(conv_number($data->sheets[0]['cells'][$i][$j++]))); // 조건배송비
		$simg_type	= addslashes(trim(conv_number($data->sheets[0]['cells'][$i][$j++]))); // 이미지등록방식
		$simg1		= addslashes(trim($data->sheets[0]['cells'][$i][$j++])); // 소이미지
		$simg2		= addslashes(trim($data->sheets[0]['cells'][$i][$j++])); // 중이미지1
		$simg3		= addslashes(trim($data->sheets[0]['cells'][$i][$j++])); // 중이미지2
		$simg4		= addslashes(trim($data->sheets[0]['cells'][$i][$j++])); // 중이미지3
		$simg5		= addslashes(trim($data->sheets[0]['cells'][$i][$j++])); // 중이미지4
		$simg6		= addslashes(trim($data->sheets[0]['cells'][$i][$j++])); // 중이미지5
		$memo		= addslashes(trim($data->sheets[0]['cells'][$i][$j++])); // 상세설명
		$admin_memo	= addslashes(trim($data->sheets[0]['cells'][$i][$j++])); // 관리자메모

        if(!$mb_id || !$gcode || !$gname) {
            $fail_count++;
            continue;
        }

        // 상품코드로 상품이 있는지 먼저 검사
		$sql2 = " select count(*) as cnt from shop_goods where gcode = '$gcode' ";
        $row2 = sql_fetch($sql2);
        if(!$row2['cnt']) {
            $fail_gcode[] = $gcode;
            $fail_count++;
            continue;
        }

		unset($value);
		$value['mb_id']			= $mb_id; // 판매자ID
		$value['gcode']			= $gcode; // 상품코드
		$value['gname']			= $gname; // 상품명
		$value['explan']		= $explan; // 짧은설명
		$value['keywords']		= $keywords; // 검색키워드
		$value['repair']		= $repair ? $repair : '상품상세설명참조'; // A/S가능여부
		$value['model']			= $model; // 모델명
		$value['brand_uid']		= get_brand_chk($brand_nm); // 브랜드주키
		$value['brand_nm']		= $brand_nm; // 브랜드명
		$value['notax']			= $notax; // 과세설정
		$value['zone']			= $zone; // 판매가능지역
		$value['zone_msg']		= $zone_msg; // 판매가능지역 추가설명
		$value['origin']		= $origin; // 원산지
		$value['maker']			= $maker; // 제조사
		$value['isopen']		= $isopen; // 판매여부
		$value['supply_price']	= $supply_price; // 공급가격
		$value['normal_price']	= $normal_price; // 시중가격
		$value['goods_price']	= $goods_price; // 판매가격
		$value['price_msg']		= $price_msg; // 가격대체문구
		$value['stock_mod']		= $stock_mod; // 재고적용타입
		$value['stock_qty']		= $stock_qty; // 재고수량
		$value['noti_qty']		= $noti_qty; // 재고통보수량
		$value['odr_min']		= $odr_min; // 최소주문한도
		$value['odr_max']		= $odr_max; // 최대주문한도
		$value['gpoint']		= $gpoint; // 포인트
		$value['sb_date']		= $sb_date; // 판매기간 시작일
		$value['eb_date']		= $eb_date; // 판매기간 종료일
		$value['buy_level']		= $buy_level; // 구매가능레벨
		$value['buy_only']		= $buy_only; // 가격공개
		$value['sc_type']		= $sc_type; // 배송비유형
		$value['sc_method']		= $sc_method; // 배송비결제
		$value['sc_amt']		= $sc_amt; // 기본배송비
		$value['sc_minimum']	= $sc_minimum; // 조건배송비
		$value['simg_type']		= $simg_type; // 이미지등록방식
		$value['simg1']			= $simg1; // 소이미지
		$value['simg2']			= $simg2; // 중이미지1
		$value['simg3']			= $simg3; // 중이미지2
		$value['simg4']			= $simg4; // 중이미지3
		$value['simg5']			= $simg5; // 중이미지4
		$value['simg6']			= $simg6; // 중이미지5
		$value['memo']			= $memo; // 상세설명
		$value['admin_memo']	= $admin_memo; // 관리자메모
		$value['update_time']	= TB_TIME_YMDHIS; //수정일시
		update("shop_goods", $value," where gcode='$gcode'");

		// 상품주키값을 불러온다
		$gs = sql_fetch("select index_no from shop_goods where gcode = '$gcode'");
		$gs_id = $gs['index_no'];

		// 기존 카테고리 자료를 모두 삭제
		sql_query("delete from shop_goods_cate where gs_id = '$gs_id'");

		$arr = explode(",", trim($gcate));
		for($g=0; $g<sizeof($arr); $g++) {
			$new_gcate = trim($arr[$g]);
			if($new_gcate) {
				sql_query("insert into shop_goods_cate (gcate, gs_id) VALUES ('$new_gcate', '$gs_id')");
			}
		}

		$succ_count++;
	}
}
?>

<h2>총 건수</h2>
<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">총상품수</th>
		<td><?php echo number_format($total_count); ?>건</td>
	</tr>
	<tr>
		<th scope="row">완료건수</th>
		<td><?php echo number_format($succ_count); ?>건</td>
	</tr>
	<tr>
		<th scope="row">실패건수</th>
		<td><?php echo number_format($fail_count); ?>건</td>
	</tr>
	<?php if($fail_count > 0) { ?>
	<tr>
		<th scope="row">실패상품코드</th>
		<td><?php echo implode(', ', $fail_gcode); ?></td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<a href="<?php echo TB_ADMIN_URL; ?>/goods.php?code=xls_mod" class="btn_large">확인</a>
</div>

<div class="information">
	<h4>도움말</h4>
	<div class="content">
		<div class="desc02">
			<p>ㆍ엑셀자료는 1회 업로드당 최대 1,000건까지 이므로 1,000건씩 나누어 업로드 하시기 바랍니다.</p>
			<p>ㆍ엑셀파일을 저장하실 때는 <strong>Excel 97 - 2003 통합문서 (*.xls)</strong>로 저장하셔야 합니다.</p>	
			<p>ㆍ상품관리에서 엑셀다운로드 하시고 수정 후 그대로 업로드 하시면 됩니다.</p>
			<p>ㆍ상품관리에서 다운로드 받으신 엑셀데이터는 2번째 라인부터 저장되므로 타이틀은 지우시면 안됩니다.</p>
		</div>
	 </div>
</div>

<script>
$(function() {
	// 새로고침(F5) 막기
	$(document).keydown(function (e) {
		if(e.which === 116) {
			if(typeof event == "object") {
				event.keyCode = 0;
			}
			return false;
		} else if(e.which === 82 && e.ctrlKey) {
			return false;
		}
	});
});
</script>
