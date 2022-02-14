<?php
if(!defined('_TUBEWEB_')) exit;

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

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

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

// 수수료합계
$row2 = sql_fetch(" select SUM(pay) as sum_pay {$sql_common} {$sql_search} ");
$stotal_pay = (int)$row2['sum_pay'];

include_once(TB_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>

<h2>기본검색</h2>
<form name="fsearch" id="fsearch" method="get">
<input type="hidden" name="code" value="<?php echo $code; ?>">
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w100">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">검색어</th>
		<td>
			<select name="sfl">
				<?php echo option_selected('id', $sfl, '아이디'); ?>
				<?php echo option_selected('name', $sfl, '회원명'); ?>
			</select>
			<input type="text" name="stx" value="<?php echo $stx; ?>" class="frm_input" size="30">
		</td>
	</tr>
	<tr>
		<th scope="row">만료일</th>
		<td>
			<?php echo get_search_date("fr_date", "to_date", $fr_date, $to_date); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">레벨검색</th>
		<td>				
			<?php echo get_search_level('sst', $sst, 2, 6); ?>
		</td>				
	</tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="검색" class="btn_medium">
	<input type="button" value="초기화" id="frmRest" class="btn_medium grey">
</div>
</form>

<form name="fpaylist" id="fpaylist" method="post" action="./partner/pt_plistupdate.php" onsubmit="return fpaylist_submit(this);">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<div class="local_ov mart30">
	전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 명 조회
	<strong class="ov_a">총 수수료잔액 : <?php echo number_format($stotal_pay); ?>원 </strong>
</div>
<div class="local_frm01">
	<?php if($config['pf_expire_use']) { // 관리비 사용시 ?>
	<select name="expire_date">
		<option value="0">기간선택</option>
		<?php
		for($i=1; $i<=36; $i++)
			echo "<option value=\"{$i}\">{$i}개월</option>\n";
		?>
	</select>
	<input type="submit" name="act_button" value="기간연장" class="btn_small bx-white" onclick="document.pressed=this.value">
	<?php } ?>
	<a href="./partner/pt_paylistexcel.php?<?php echo $q1; ?>" class="btn_small bx-white"><i class="fa fa-file-excel-o"></i> 엑셀저장</a>
</div>

<div class="tbl_head02">
	<table id="partner_list">
	<colgroup>
		<col class="w50">
		<col class="w130">
		<col class="w130">
		<col class="w130">
		<col class="w80">
		<col>
		<col class="w100">
		<col class="w100">
		<col class="w100">
		<col class="w100">
		<col class="w100">
		<col class="w100">
		<col class="w100">
	</colgroup>
	<thead>
	<tr>
		<th scope="col" rowspan="2"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
		<th scope="col" rowspan="2"><?php echo subject_sort_link('name',$q2); ?>회원명</a></th>
		<th scope="col" rowspan="2"><?php echo subject_sort_link('id',$q2); ?>아이디</a></th>
		<th scope="col" rowspan="2"><?php echo subject_sort_link('grade',$q2); ?>레벨</a></th>
		<th scope="col" rowspan="2"><?php echo subject_sort_link('term_date',$q2); ?>만료일</a></th>
		<th scope="col" rowspan="2">개별도메인</th>
		<th scope="col" colspan="3">누적 수수료집계</th>
		<th scope="col" colspan="4">유형별 수수료집계</th>
	</tr>
	<tr class="rows">
		<th scope="col" class="th_bg2"><?php echo subject_sort_link('pay', $q2); ?>현재잔액</a></th>
		<th scope="col" class="th_bg2">총적립액</th>
		<th scope="col" class="th_bg2">총차감액</th>
		<th scope="col" class="th_bg">판매</th>
		<th scope="col" class="th_bg">추천</th>
		<th scope="col" class="th_bg">접속</th>
		<th scope="col" class="th_bg">본사</th>
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {		

		$expr = 'txt_expired';
		$expire_date = '무제한';

		// 관리비를 사용중인가?
		if($config['pf_expire_use']) {			
			if($row['term_date'] < TB_TIME_YMD) {
				$expr = 'txt_expired';
				$expire_date = '만료'.substr(conv_number($row['term_date']), 2);
			} else {
				$expr = 'txt_active';
				$expire_date = $row['term_date'];
			}
		}
		
		$homepage = '';
		if($row['homepage']) {
			$homepage = set_http($row['homepage']);
			$homepage = '<a href="'.$homepage.'" target="_blank">'.$homepage.'</a>';
		}

		$info  = get_pay_sheet($row['id']); // 누적
		$sale  = get_pay_status($row['id'], 'sale'); // 판매
		$anew  = get_pay_status($row['id'], 'anew'); // 추천
		$visit = get_pay_status($row['id'], 'visit'); // 접속
		$admin = get_pay_status($row['id'], 'passive'); // 본사

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;

		$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td>
			<input type="hidden" name="mb_id[<?php echo $i; ?>]" value="<?php echo $row['id']; ?>">
			<label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo $row['id']; ?> 님</label>
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>" id="chk_<?php echo $i; ?>">
		</td>
		<td class="tal"><?php echo get_sideview($row['id'], $row['name']); ?></td>
		<td class="tal"><?php echo $row['id']; ?></td>
		<td><?php echo get_grade($row['grade']); ?></td>
		<td class="<?php echo $expr; ?>"><?php echo $expire_date; ?></td>
		<td class="tal"><?php echo $homepage; ?></td>
		<td class="tar"><?php echo number_format($row['pay']); ?></td>
		<td class="tar"><?php echo number_format($info['pay']); ?></td>
		<td class="tar"><?php echo number_format($info['usepay']); ?></td>
		<td class="tar"><?php echo number_format($sale['pay']); ?></td>
		<td class="tar"><?php echo number_format($anew['pay']); ?></td>
		<td class="tar"><?php echo number_format($visit['pay']); ?></td>
		<td class="tar"><?php echo number_format($admin['pay']); ?></td>
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tbody><tr><td colspan="13" class="empty_table">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>
</form>

<?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=');
?>

<h2>개별회원 수수료 증감 설정</h2>
<form name="fpaylist2" id="fpaylist2" method="post" action="./partner/pt_ppayupdate.php" autocomplete="off">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="token" value="">
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w120">
		<col>
	</colgroup>
	<tbody>	
	<tr>
		<th scope="row"><label for="mb_id">회원아이디</label></th>
		<td><input type="text" name="mb_id" value="<?php echo $mb_id; ?>" id="mb_id" required class="required frm_input"></td>
	</tr>
	<tr>
		<th scope="row"><label for="pp_content">수수료내용</label></th>
		<td><input type="text" name="pp_content" id="pp_content" required class="required frm_input" size="60"></td>
	</tr>		
	<tr>
		<th scope="row"><label for="pp_pay">수수료금액</label></th>
		<td><input type="text" name="pp_pay" id="pp_pay" required class="required frm_input" size="10"> 원</td>	
	</tr>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="수수료적용" class="btn_large red">
</div>
</form>

<div class="information">
	<h4>도움말</h4>
	<div class="content">
		<div class="desc02">
			<p>ㆍ수수료를 적립할 경우 양수만 입력하시기 바랍니다. 예) 3000</p>
			<p>ㆍ수수료를 차감할 경우 음수도 포함해 입력하시기 바랍니다. 예) -3000</p>
			<p class="fc_red">ㆍ수수료 차감액이 현재 잔액보다 클경우 차감되지 않습니다.</p>
		</div>
	</div>
</div>

<script>
function fpaylist_submit(f)
{
    if(!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "기간연장") {		
        if(f.expire_date.value == 0) {
			alert('연장하실 기간을 선택하세요.');
			f.expire_date.focus();
			return false;
		}

        if(!confirm("선택한 자료를 기간연장 하시겠습니까?")) {
            return false;
        }
    }

    return true;
}

$(function(){
	// 날짜 검색 : TODAY MAX값으로 인식 (maxDate: "+0d")를 삭제하면 MAX값 해제
	$("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99" });
});
</script>
