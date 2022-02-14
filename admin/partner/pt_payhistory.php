<?php
if(!defined('_TUBEWEB_')) exit;

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

if(isset($rel_field) && $rel_field) {
	$qstr .= "&rel_field=$rel_field";
}

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

if($rel_field)
	$sql_search .= " and a.pp_rel_table = '{$rel_field}' ";

if($fr_date && $to_date)
    $sql_search .= " and a.pp_datetime between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
else if($fr_date && !$to_date)
	$sql_search .= " and a.pp_datetime between '$fr_date 00:00:00' and '$fr_date 23:59:59' ";
else if(!$fr_date && $to_date)
	$sql_search .= " and a.pp_datetime between '$to_date 00:00:00' and '$to_date 23:59:59' ";

if(!$orderby) {
    $filed = "a.pp_id";
    $sod = "desc";
} else {
	$sod = $orderby;
}

$sql_order = " order by {$filed} {$sod} ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt {$sql_common} {$sql_search} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select a.*, b.name, b.grade {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

include_once(TB_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$btn_frmline = <<<EOF
<input type="submit" name="act_button" value="선택삭제" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
<a href="./partner/pt_payhistoryexcel.php?$q1" class="btn_lsmall bx-white"><i class="fa fa-file-excel-o"></i> 엑셀저장</a>
EOF;
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
		<th scope="row">구분</th>
		<td>
			<?php echo radio_checked('rel_field', $rel_field, '', '전체'); ?>
			<?php echo radio_checked('rel_field', $rel_field, 'sale', $gw_ptype['sale']); ?>
			<?php echo radio_checked('rel_field', $rel_field, 'anew', $gw_ptype['anew']); ?>
			<?php echo radio_checked('rel_field', $rel_field, 'visit', $gw_ptype['visit']); ?>
			<?php echo radio_checked('rel_field', $rel_field, 'passive', $gw_ptype['passive']); ?>	
			<?php echo radio_checked('rel_field', $rel_field, 'payment', $gw_ptype['payment']); ?>
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

<form name="fpayhistory" id="fpayhistory" method="post" action="./partner/pt_payhistorydelete.php" onsubmit="return fpayhistory_submit(this);">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<div class="local_ov mart30">
	전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회
</div>
<div class="local_frm01">
	<?php echo $btn_frmline; ?>
</div>
<div class="tbl_head01">
	<table>
	<colgroup>
		<col class="w50">
		<col class="w50">
		<col class="w130">
		<col class="w130">
		<col class="w130">
		<col>			
		<col class="w130">		
		<col class="w90">
		<col class="w90">
		<col class="w90">
	</colgroup>
	<thead>
	<tr>
		<th scope="col"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
		<th scope="col">번호</th>		
		<th scope="col"><?php echo subject_sort_link('b.name', $q2); ?>회원명</a></th>
		<th scope="col"><?php echo subject_sort_link('a.mb_id', $q2); ?>아이디</a></th>
		<th scope="col"><?php echo subject_sort_link('b.grade', $q2); ?>레벨</a></th>	
		<th scope="col"><?php echo subject_sort_link('a.pp_content', $q2); ?>수수료내용</a></th>
		<th scope="col"><?php echo subject_sort_link('a.pp_datetime', $q2); ?>일시</a></th>	
		<th scope="col" class="th_bg"><?php echo subject_sort_link('a.pp_rel_table', $q2); ?>구분</a></th>	
		<th scope="col" class="th_bg"><?php echo subject_sort_link('a.pp_pay', $q2); ?>수수료</a></th>			
		<th scope="col"><?php echo subject_sort_link('a.pp_balance', $q2); ?>수수료합</a></th>
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;

		$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td>
			<input type="hidden" name="mb_id[<?php echo $i; ?>]" value="<?php echo $row['mb_id']; ?>">
			<input type="hidden" name="pp_id[<?php echo $i; ?>]" value="<?php echo $row['pp_id']; ?>">
			<label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo $row['pp_content']; ?> 내역</label>
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>" id="chk_<?php echo $i; ?>">
		</td>
		<td><?php echo $num--; ?></td>
		<td class="tal"><?php echo get_sideview($row['mb_id'], $row['name']); ?></td>
		<td class="tal"><?php echo $row['mb_id']; ?></td>
		<td><?php echo get_grade($row['grade']); ?></td>
		<td class="tal"><?php echo $row['pp_content']; ?></td>
		<td><?php echo $row['pp_datetime']; ?></td>	
		<td><?php echo $gw_ptype[$row['pp_rel_table']]; ?></td>	
		<td class="tar"><?php echo number_format($row['pp_pay']); ?></td>							
		<td class="tar"><?php echo number_format($row['pp_balance']); ?></td>
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tbody><tr><td colspan="10" class="empty_table">자료가 없습니다.</td></tr>';
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
			<p>ㆍ수수료가 음수인 내역을 삭제할 경우 수수료가 다시 복원처리됩니다.</p>
			<p>ㆍ수수료가 양수인 내역을 삭제할 경우 수수료가 다시 차감처리됩니다.</p>
		</div>
	</div>
</div>

<script>
function fpayhistory_submit(f)
{
    if(!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
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
	$("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});
</script>
