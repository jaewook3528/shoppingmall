<?php
if(!defined('_TUBEWEB_')) exit;

$pg_title = "수수료 건별내역";
include_once("./admin_head.sub.php");

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

if(isset($rel_field) && $rel_field) {
	$qstr .= "&rel_field=$rel_field";
}

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_partner_pay a, shop_member b ";
$sql_search = " where a.mb_id = b.id and mb_id = '{$member['id']}' ";

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
<a href="./partner_payhistoryexcel.php?$q1" class="btn_lsmall bx-white"><i class="fa fa-file-excel-o"></i> 엑셀저장</a>
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
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="검색" class="btn_medium">
	<input type="button" value="초기화" id="frmRest" class="btn_medium grey">
</div>	
</form>

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
		<td><?php echo $num--; ?></td>
		<td class="tal"><?php echo get_text($row['name']); ?></td>
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
		echo '<tbody><tr><td colspan="9" class="empty_table">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>
<div class="local_frm02">
	<?php echo $btn_frmline; ?>
</div>

<?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=');
?>

<script>
$(function(){
	// 날짜 검색 : TODAY MAX값으로 인식 (maxDate: "+0d")를 삭제하면 MAX값 해제
	$("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});
</script>

<?php
include_once("./admin_tail.sub.php");
?>