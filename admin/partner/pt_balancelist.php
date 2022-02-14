<?php
if(!defined('_TUBEWEB_')) exit;

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

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

// 테이블의 전체 레코드수만 얻음
$sql = " select a.mb_id {$sql_common} {$sql_search} {$sql_group} ";
$result = sql_query($sql);
$total_count = sql_num_rows($result);

$rows = 30;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select a.*, 	
			    SUM(a.pp_pay) as balance,
				b.name,
				b.grade,
				b.term_date
           {$sql_common} {$sql_search} {$sql_group} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

// 수수료합계
$row2 = sql_fetch(" select SUM(a.pp_pay) as sum_pay {$sql_common} {$sql_search} ");
$stotal_pay = (int)$row2['sum_pay'];

$btn_frmline = <<<EOF
<input type="submit" name="act_button" value="선택정산" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
<a href="./partner/pt_balancelistexcel.php?$q1" class="btn_lsmall bx-white"><i class="fa fa-file-excel-o"></i> 엑셀저장</a>
EOF;

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
				<?php echo option_selected('a.mb_id', $sfl, '아이디'); ?>
				<?php echo option_selected('b.name', $sfl, '회원명'); ?>
			</select>
			<input type="text" name="stx" value="<?php echo $stx; ?>" class="frm_input" size="30">
		</td>
	</tr>
	<tr>
		<th scope="row">기간검색</th>
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

<form name="fbalancelist" id="fbalancelist" method="post" action="./partner/pt_balancelistupdate.php" onsubmit="return fbalancelist_submit(this);">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<div class="local_ov mart30">
	전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 명 조회
	<strong class="ov_a">전체 합계 <?php echo number_format($stotal_pay); ?>원 </strong>
</div>
<div class="local_frm01">
	<?php echo $btn_frmline; ?>
</div>
<div class="tbl_head01">
	<table>
	<colgroup>
		<col class="w50">
		<col class="w130">
		<col class="w130">
		<col class="w130">
		<col class="w80">
		<col class="w100">
		<col class="w100">
		<col class="w100">
		<col>
	</colgroup>
	<thead>
	<tr>
		<th scope="col"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
		<th scope="col"><?php echo subject_sort_link('b.name', $q2); ?>회원명</a></th>
		<th scope="col"><?php echo subject_sort_link('a.mb_id', $q2); ?>아이디</a></th>
		<th scope="col"><?php echo subject_sort_link('b.grade', $q2); ?>레벨</a></th>
		<th scope="col"><?php echo subject_sort_link('b.term_date',$q2); ?>만료일</a></th>
		<th scope="col" class="th_bg">수수료</th>
		<th scope="col" class="th_bg">세액공제</th>
		<th scope="col" class="th_bg">실수령액</th>
		<th scope="col">회원입금계좌</th>
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

		$paytax = 0;
		if($config['pf_payment_tax']) { // 세액공제
			$paytax = floor(($row['balance'] * $config['pf_payment_tax']) / 100);
		}

		$paynet = $row['balance'] - $paytax;		

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;

		$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td>
			<input type="hidden" name="mb_id[<?php echo $i; ?>]" value="<?php echo $row['mb_id']; ?>">
			<input type="hidden" name="pp_pay[<?php echo $i; ?>]" value="<?php echo $row['balance']; ?>">
			<label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo $row['mb_id']; ?> 님</label>
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>" id="chk_<?php echo $i; ?>">
		</td>	
		<td class="tal"><?php echo get_sideview($row['mb_id'], $row['name']); ?></td>
		<td class="tal"><?php echo $row['mb_id']; ?></td>
		<td><?php echo get_grade($row['grade']); ?></td>
		<td class="<?php echo $expr; ?>"><?php echo $expire_date; ?></td>
		<td class="tar"><?php echo number_format($row['balance']); ?></td>
		<td class="tar fc_red"><?php echo number_format($paytax); ?></td>
		<td class="tar fc_00f"><?php echo number_format($paynet); ?></td>
		<td class="tal"><?php echo print_partner_bank($row['mb_id']); ?></td>
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tbody><tr><td colspan="9" class="empty_table">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>
<div class="local_frm02">
	<?php echo $btn_frmline; ?>
</div>
</form>

<?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=');
?>

<div class="information">
	<h4>도움말</h4>
	<div class="content">
		<div class="desc02">
			<p>ㆍ정산완료를 하실 경우 DB상에 데이터값만 변경되므로 실제 본사 은행계좌에서 인출되지는 않습니다.</p>
			<p>ㆍ엑셀저장 후 인터넷뱅킹을 통한 대량 이체가 가능하므로 이체 완료 후 정산완료 하시면 됩니다.</p>
			<p class="fc_red">ㆍ정산완료를 실수로 처리하셨다면 가맹점 수수료내역에서 "선택삭제"를 통한 복원이 가능합니다.</p>
		</div>
	</div>
</div>

<script>
function fbalancelist_submit(f)
{
    if(!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택정산") {
        if(!confirm("선택한 자료를 정산하시겠습니까?")) {
            return false;
        }
    }

    return true;
}

$(function(){
	// 날짜 검색 : TODAY MAX값으로 인식 (maxDate: "+0d")를 삭제하면 MAX값 해제
	$("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});
</script>
