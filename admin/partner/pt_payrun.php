<?php
if(!defined('_TUBEWEB_')) exit;

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_partner_payrun a, shop_member b ";
$sql_search = " where a.mb_id = b.id and a.state = '0' ";

if($sfl && $stx) {
	$sql_search .= " and $sfl like '%$stx%' ";
}

if(isset($sst) && is_numeric($sst))
	$sql_search .= " and b.grade = '$sst' ";

if($fr_date && $to_date)
    $sql_search .= " and left({$spt},10) between '$fr_date' and '$to_date' ";
else if($fr_date && !$to_date)
	$sql_search .= " and left({$spt},10) between '$fr_date' and '$fr_date' ";
else if(!$fr_date && $to_date)
	$sql_search .= " and left({$spt},10) between '$to_date' and '$to_date' ";

if(!$orderby) {
    $filed = "a.index_no";
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

$sql = " select a.*, b.name, b.grade, b.term_date, b.pay
			$sql_common 
			$sql_search 
			$sql_order 
			limit $from_record, $rows ";
$result = sql_query($sql);

// 출금요청액합계
$row2 = sql_fetch(" select SUM(a.balance) as sum_pay {$sql_common} {$sql_search} ");
$stotal_pay = (int)$row2['sum_pay'];

$btn_frmline = <<<EOF
<input type="submit" name="act_button" value="선택정산" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
<input type="submit" name="act_button" value="선택삭제" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
<a href="./partner/pt_payrunexcel.php?$q1" class="btn_lsmall bx-white"><i class="fa fa-file-excel-o"></i> 엑셀저장</a>
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
			<select name="spt">					
				<?php echo option_selected('a.reg_time', $spt, "신청일"); ?>
				<?php echo option_selected('b.term_date', $spt, "만료일"); ?>
			</select>
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

<form name="fpayrun" id="fpayrun" method="post" action="./partner/pt_payrunupdate.php" onsubmit="return fpayrun_submit(this);">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<div class="local_ov mart30">
	전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회
	<strong class="ov_a">총 출금요청액 <?php echo number_format($stotal_pay); ?>원 </strong>
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
		<col class="w130">
		<col class="w80">
		<col class="w100">
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
		<th scope="col"><?php echo subject_sort_link('a.reg_time',$q2); ?>신청일시</a></th>
		<th scope="col"><?php echo subject_sort_link('b.term_date',$q2); ?>만료일</a></th>
		<th scope="col" class="th_bg"><?php echo subject_sort_link('b.pay',$q2); ?>현재잔액</a></th>
		<th scope="col" class="th_bg"><?php echo subject_sort_link('a.balance',$q2); ?>출금요청</a></th>
		<th scope="col" class="th_bg"><?php echo subject_sort_link('a.paytax',$q2); ?>세액공제</a></th>
		<th scope="col" class="th_bg"><?php echo subject_sort_link('a.paynet',$q2); ?>실수령액</a></th>
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

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;

		$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td>
			<input type="hidden" name="index_no[<?php echo $i; ?>]" value="<?php echo $row['index_no']; ?>">
			<input type="hidden" name="mb_id[<?php echo $i; ?>]" value="<?php echo $row['mb_id']; ?>">
			<label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo $row['mb_id']; ?> 님</label>
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>" id="chk_<?php echo $i; ?>">
		</td>
		<td class="tal"><?php echo get_sideview($row['mb_id'], $row['name']); ?></td>
		<td class="tal"><?php echo $row['mb_id']; ?></td>
		<td><?php echo get_grade($row['grade']); ?></td>
		<td><?php echo $row['reg_time']; ?></td>
		<td class="<?php echo $expr; ?>"><?php echo $expire_date; ?></td>
		<td class="tar"><?php echo number_format($row['pay']); ?></td>
		<td class="tar"><?php echo number_format($row['balance']); ?></td>
		<td class="tar fc_red"><?php echo number_format($row['paytax']); ?></td>
		<td class="tar fc_00f"><?php echo number_format($row['paynet']); ?></td>
		<td class="tal"><?php echo print_partner_bank2($row['bank_name'], $row['bank_account'], $row['bank_holder']); ?></td>
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tbody><tr><td colspan="11" class="empty_table">자료가 없습니다.</td></tr>';
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
function fpayrun_submit(f)
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

    if(document.pressed == "선택삭제") {
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
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
