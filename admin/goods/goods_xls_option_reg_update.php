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
    $update_gcode = array();
    $total_count = 0;
    $fail_count = 0;
    $succ_count = 0;

	for($i=3; $i<=$data->sheets[0]['numRows']; $i++)
	{		
		if(trim($data->sheets[0]['cells'][$i][1]) == '')
			continue;

		$total_count++;

        $j = 1;

		$gcode			= addslashes(trim($data->sheets[0]['cells'][$i][$j++])); // 상품코드
        $opt_subject	= addslashes(trim($data->sheets[0]['cells'][$i][$j++])); // 옵션명
        $io_id			= addslashes(trim($data->sheets[0]['cells'][$i][$j++])); // 옵션항목
        $io_price		= addslashes(conv_number(trim($data->sheets[0]['cells'][$i][$j++]))); // 옵션가격
        $io_stock_qty	= addslashes(conv_number(trim($data->sheets[0]['cells'][$i][$j++]))); // 재고수량
        $io_noti_qty	= addslashes(conv_number(trim($data->sheets[0]['cells'][$i][$j++]))); // 통보수량
        $io_use			= addslashes(conv_number(trim($data->sheets[0]['cells'][$i][$j++]))); // 사용여부
        $io_type		= addslashes(conv_number(trim($data->sheets[0]['cells'][$i][$j++]))); // 옵션형식

		// 상품코드 체크
		$sql = " select COUNT(*) as cnt,
					    index_no, 
					    opt_subject, 
					    spl_subject 
				   from shop_goods 
				  where gcode = '$gcode' 
				    and gcode <> '' ";
	    $gs = sql_fetch($sql);

		$gs_id = $gs['index_no'];

		if(!$gs['cnt'] || !$gcode || !$opt_subject || !$io_id || ($io_type != 0 && $io_type != 1)) { 
            $fail_gcode[] = $gcode;
			$fail_count++;
            continue;
        }

		// 상품 테이블  opt_subject 업데이트
		if($io_type == 0 && $gs['opt_subject'] != $opt_subject) {
			sql_query(" update shop_goods set opt_subject = '$opt_subject' where gcode = '$gcode' ");
		}
	
		// 상품 테이블 spl_subject 업데이트
		if($io_type == 1 && $gs['spl_subject'] != $opt_subject) {
			sql_query(" update shop_goods set spl_subject = '$opt_subject' where gcode = '$gcode' ");
		}

		// io_id
		$opt = array();
	    $opt_arr = explode(',', $io_id);
		for($k=0; $k<count($opt_arr); $k++) {
            $opt[] = $opt_arr[$k];
		}
		$io_id_chr = implode(chr(30), $opt); 

        // gs_id, io_id, io_type 중복 - 업데이트
        $sql2 = " select io_no, count(*) as cnt from shop_goods_option where gs_id = '$gs_id' and io_id = '$io_id_chr' and io_type = '$io_type' ";
        $row2 = sql_fetch($sql2);
        if($row2['cnt']) {
            $update_gcode[] = $gcode;
            $update_count++;

	        $sql = " update shop_goods_option
                        set io_id = '$io_id_chr'
						  , io_type = '$io_type'
						  , gs_id = '$gs_id'
						  , io_price = '$io_price'
						  , io_stock_qty = '$io_stock_qty'
						  , io_noti_qty = '$io_noti_qty'
						  , io_use = '$io_use'
					  where io_no = '$row2[io_no]' ";
			  sql_query($sql);

		} else { // 옵션 테이블 insert
			$sql = " insert into shop_goods_option
                        set io_id = '$io_id_chr'
						  , io_type = '$io_type'
						  , gs_id = '$gs_id'
						  , io_price = '$io_price'
						  , io_stock_qty = '$io_stock_qty'
						  , io_noti_qty = '$io_noti_qty'
						  , io_use = '$io_use' ";
	        sql_query($sql);

		    $succ_count++;
		}
	}
}
?>

<h2>엑셀일괄등록 결과</h2>
<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">총 옵션수</th>
		<td><?php echo number_format($total_count); ?>건</td>
	</tr>
	<tr>
		<th scope="row">완료건수</th>
		<td><?php echo number_format($succ_count); ?>건</td>
	</tr>
	<tr>
		<th scope="row">업데이트건수</th>
		<td><?php echo number_format($update_count); ?>건</td>
	</tr>
	<?php if($update_count > 0) { ?>
	<tr>
		<th scope="row">업데이트상품코드</th>
		<td><?php echo implode(', ', array_unique($update_gcode)); ?></td>
	</tr>
	<?php } ?>
	<tr>
		<th scope="row">실패건수</th>
		<td><?php echo number_format($fail_count); ?>건</td>
	</tr>
	<?php if($fail_count > 0) { ?>
	<tr>
		<th scope="row">실패상품코드</th>
		<td><?php echo implode(', ', array_unique($fail_gcode)); ?></td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<a href="<?php echo TB_ADMIN_URL; ?>/goods.php?code=xls_option_reg" class="btn_large">확인</a>
</div>

<div class="information">
	<h4>도움말</h4>
	<div class="content">
		<div class="desc02">
			<p>ㆍ엑셀자료는 1회 업로드당 최대 1,000건까지 이므로 1,000건씩 나누어 업로드 하시기 바랍니다.</p>
			<p>ㆍ엑셀파일을 저장하실 때는 <strong>Excel 97 - 2003 통합문서 (*.xls)</strong>로 저장하셔야 합니다.</p>
			<p>ㆍ엑셀데이터는 3번째 라인부터 저장되므로 샘플파일 설명글과 타이틀은 지우시면 안됩니다.</p>
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
