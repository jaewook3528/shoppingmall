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
    $total_count = 0; // 총업체수
    $fail_count = 0; // 실패건수
    $succ_count = 0; // 완료건수

	for($i=4; $i<=$data->sheets[0]['numRows']; $i++) 
	{
		if(trim($data->sheets[0]['cells'][$i][1]) == '')
			continue;

		$total_count++;

        $j = 1;

		$mb_id = addslashes(trim(strtolower($data->sheets[0]['cells'][$i][$j++]))); //아이디
		$passwd = addslashes(trim($data->sheets[0]['cells'][$i][$j++])); //비밀번호
		$seller_item = addslashes(trim($data->sheets[0]['cells'][$i][$j++])); //제공상품
		$company_name = addslashes(trim($data->sheets[0]['cells'][$i][$j++])); //업체(법인)명
		$company_saupja_no = addslashes(trim($data->sheets[0]['cells'][$i][$j++])); //사업자등록번호
		$company_tel = addslashes(trim($data->sheets[0]['cells'][$i][$j++])); //전화번호
		$company_fax = addslashes(trim($data->sheets[0]['cells'][$i][$j++])); //팩스번호
		$company_zip = addslashes(trim($data->sheets[0]['cells'][$i][$j++])); //우편번호
		$company_addr1 = addslashes(trim($data->sheets[0]['cells'][$i][$j++])); //주소
		$company_item = addslashes(trim($data->sheets[0]['cells'][$i][$j++])); //업태
		$company_service = addslashes(trim($data->sheets[0]['cells'][$i][$j++])); //종목
		$company_owner = addslashes(trim($data->sheets[0]['cells'][$i][$j++])); //대표자명
		$company_hompage = addslashes(trim($data->sheets[0]['cells'][$i][$j++])); //홈페이지
		$bank_name = addslashes(trim($data->sheets[0]['cells'][$i][$j++])); //은행명
		$bank_account = addslashes(trim($data->sheets[0]['cells'][$i][$j++])); //계좌번호
		$bank_holder = addslashes(trim($data->sheets[0]['cells'][$i][$j++])); //예금주명
		$info_name = addslashes(trim($data->sheets[0]['cells'][$i][$j++])); //담당자명
		$info_email = addslashes(trim($data->sheets[0]['cells'][$i][$j++])); //담당자이메일
		$info_tel = addslashes(trim($data->sheets[0]['cells'][$i][$j++])); //담당자핸드폰

		// id, passwd, company_name 값이 없다면?
		if(!$mb_id || !$passwd || !$company_name) {
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

		unset($mfrm);
		$mfrm['id'] = $mb_id; //회원아이디
		$mfrm['name'] = $company_name; //회원명		
		$mfrm['passwd'] = $passwd; //비밀번호
		$mfrm['gender'] = 'M'; //성별
		$mfrm['email'] = $info_email; //이메일		
		$mfrm['cellphone'] = $info_tel; //핸드폰번호
		$mfrm['telephone'] = $company_tel; //전화번호
		$mfrm['zip'] = $company_zip; //우편번호
		$mfrm['addr1'] = $company_addr1; //주소
		$mfrm['mailser'] = 'Y'; //메일링 수신여부
		$mfrm['smsser'] = 'Y'; //SMS 수신여부
		$mfrm['supply'] = 'Y'; //공급사 여부
		$mfrm['pt_id'] = 'admin'; //추천인
		$mfrm['reg_time'] = TB_TIME_YMDHIS; // 가입일
		$mfrm['grade'] = 9; //레벨
		insert("shop_member", $mfrm);

		unset($sfrm);
		$sfrm['mb_id'] = $mb_id;
		$sfrm['seller_code'] = code_uniqid();		
		$sfrm['seller_item'] = $seller_item;
		$sfrm['company_name'] = $company_name;
		$sfrm['company_saupja_no'] = $company_saupja_no;		
		$sfrm['company_item'] = $company_item;
		$sfrm['company_service'] = $company_service;
		$sfrm['company_owner'] = $company_owner;
		$sfrm['company_tel'] = $company_tel;
		$sfrm['company_fax'] = $company_fax;
		$sfrm['company_zip'] = $company_zip;
		$sfrm['company_addr1'] = $company_addr1;		
		$sfrm['company_hompage'] = $company_hompage;
		$sfrm['info_name'] = $info_name;
		$sfrm['info_tel'] = $info_tel;
		$sfrm['info_email'] = $info_email;		
		$sfrm['bank_name'] = $bank_name;
		$sfrm['bank_account'] = $bank_account;	
		$sfrm['bank_holder'] = $bank_holder;
		$sfrm['reg_time'] = TB_TIME_YMDHIS;
		$sfrm['update_time'] = TB_TIME_YMDHIS;
		$sfrm['state'] = 1;
		$sfrm['seller_open'] = 1;
		insert("shop_seller", $sfrm);

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
		<th scope="row">총업체수</th>
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
	<a href="<?php echo TB_ADMIN_URL; ?>/seller.php?code=xls" class="btn_large">확인</a>
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
