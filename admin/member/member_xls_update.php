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

    $dup_mb_id = array();
    $dup_count = 0; // 중복건수
    $total_count = 0; // 총회원수
    $fail_count = 0; // 실패건수
    $succ_count = 0; // 완료건수

	for($i=4; $i<=$data->sheets[0]['numRows']; $i++) 
	{
		if(trim($data->sheets[0]['cells'][$i][1]) == '')
			continue;

		$total_count++;

        $j = 1;

		$mb_id			= addslashes(trim($data->sheets[0]['cells'][$i][$j++]));
		$mb_password	= addslashes(trim($data->sheets[0]['cells'][$i][$j++]));
		$mb_name		= addslashes(trim($data->sheets[0]['cells'][$i][$j++]));
		$mb_birth_year	= addslashes(trim(conv_number($data->sheets[0]['cells'][$i][$j++])));
		$mb_birth_month = addslashes(trim(conv_number($data->sheets[0]['cells'][$i][$j++])));
		$mb_birth_day	= addslashes(trim(conv_number($data->sheets[0]['cells'][$i][$j++])));
		$mb_birth_type	= addslashes(trim(strtoupper($data->sheets[0]['cells'][$i][$j++])));
		$mb_gender		= addslashes(trim(strtoupper($data->sheets[0]['cells'][$i][$j++])));
		$mb_recommend	= addslashes(trim($data->sheets[0]['cells'][$i][$j++]));
		$mb_email		= addslashes(trim($data->sheets[0]['cells'][$i][$j++]));
		$mb_telephone	= addslashes(trim(replace_tel($data->sheets[0]['cells'][$i][$j++])));
		$mb_cellphone	= addslashes(trim(replace_tel($data->sheets[0]['cells'][$i][$j++])));
		$mb_zip			= addslashes(trim(conv_number($data->sheets[0]['cells'][$i][$j++])));
		$mb_addr1		= addslashes(trim($data->sheets[0]['cells'][$i][$j++]));
		$mb_addr2		= addslashes(trim($data->sheets[0]['cells'][$i][$j++]));
		$mb_addr3		= addslashes(trim($data->sheets[0]['cells'][$i][$j++]));

		// id, name 값이 없다면?
		if(!$mb_id || !$mb_name) {
            $fail_count++;
            continue;
        }

		// id 형식체크
		if(preg_match("/[^0-9a-z_]+/i", $mb_id)) {
            $fail_count++;
            continue;
        }

        // id 중복체크
		$sql = " select count(*) as cnt from shop_member where id = '$mb_id' ";
        $row = sql_fetch($sql);
		if($row['cnt']) {
            $dup_mb_id[] = $mb_id;
			$dup_count++;
            $fail_count++;
            continue;
		}

		// 추천인이 없으면 최고관리자로 바꿈
		if(!$mb_recommend) $mb_recommend = 'admin';

		unset($value);
		$value['name']			= $mb_name; //회원명
		$value['id']			= $mb_id; //회원아이디
		$value['passwd']		= $mb_password; //패스워드
		$value['birth_year']	= $mb_birth_year; //생년/년
		$value['birth_month']	= sprintf('%02d', $mb_birth_month); //생년/월
		$value['birth_day']		= sprintf('%02d', $mb_birth_day); //생년/일
		$value['birth_type']	= $mb_birth_type ? $mb_birth_type : 'L'; //음력/양력
		$value['gender']		= $mb_gender ? $mb_gender : 'M'; //성별
		$value['email']			= $mb_email; //이메일
		$value['telephone']		= $mb_telephone; //전화번호
		$value['cellphone']		= $mb_cellphone; //핸드폰번호
		$value['zip']			= $mb_zip; //우편번호
		$value['addr1']			= $mb_addr1; //주소
		$value['addr2']			= $mb_addr2; //상세주소
		$value['addr3']			= $mb_addr3; //참고항목
		$value['pt_id']			= $mb_recommend; //추천인
		$value['mailser']		= 'Y'; //메일링 수신여부
		$value['smsser']		= 'Y'; //SMS 수신여부
		$value['reg_time']		= TB_TIME_YMDHIS; // 가입일
		$value['grade']			= 9; //레벨
		insert("shop_member", $value);

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
		<th scope="row">총회원수</th>
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
		<th scope="row">중복된아이디</th>
		<td><?php echo implode(', ', $dup_mb_id); ?></td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<a href="<?php echo TB_ADMIN_URL; ?>/member.php?code=xls" class="btn_large">확인</a>
</div>

<div class="information">
	<h4>도움말</h4>
	<div class="content">
		<div class="desc02">
			<p>ㆍ엑셀자료는 1회 업로드당 최대 1,000건까지 이므로 1,000건씩 나누어 업로드 하시기 바랍니다.</p>
			<p>ㆍ엑셀파일을 저장하실 때는 <strong>Excel 97 - 2003 통합문서 (*.xls)</strong>로 저장하셔야 합니다.</p>
			<p>ㆍ엑셀데이터는 4번째 라인부터 저장되므로 샘플파일 설명글과 타이틀은 지우시면 안됩니다.</p>
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
