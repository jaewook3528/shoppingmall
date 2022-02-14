<?php
if(!defined('_TUBEWEB_')) exit;

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_gift_group ";
$sql_search = " where (1) ";

if($sfl && $stx) {
    $sql_search .= " and $sfl like '%$stx%' ";
}

if(isset($sst) && is_numeric($sst))
	$sql_search .= " and gr_ty = '$sst' ";

if($fr_date && $to_date)
	$sql_search .= " and (gr_sdate >= '$fr_date' and gr_edate <= '$to_date') ";
else if($fr_date && !$to_date)
	$sql_search .= " and (gr_sdate >= '$fr_date' and gr_sdate <= '$fr_date') ";
else if(!$fr_date && $to_date)
	$sql_search .= " and (gr_edate >= '$to_date' and gr_edate <= '$to_date') ";

if(!$orderby) {
    $filed = "gr_wdate";
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
$num = $total_count - (($page-1)*$rows);

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

include_once(TB_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$btn_frmline = <<<EOF
<input type="submit" name="act_button" value="선택삭제" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
<a href="./goods.php?code=gift_form" class="fr btn_lsmall red"><i class="ionicons ion-android-add"></i> 쿠폰등록</a>
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
				<?php echo option_selected('gr_subject', $sfl, '쿠폰명'); ?>
				<?php echo option_selected('gr_explan', $sfl, '설명'); ?>
				<?php echo option_selected('gr_id', $sfl, '일련번호'); ?>
			</select>
			<input type="text" name="stx" value="<?php echo $stx; ?>" class="frm_input" size="30">
		</td>
	</tr>
	<tr>
		<th scope="row">사용기간</th>
		<td>
			<?php echo get_search_date("fr_date", "to_date", $fr_date, $to_date); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">구분</th>
		<td>
			<select name="sst">
				<?php echo option_selected('', $sst, '구분'); ?>
				<?php echo option_selected('0', $sst, '숫자발행'); ?>
				<?php echo option_selected('1', $sst, '영문, 숫자 혼합발행'); ?>
			</select>
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

<form name="fgiftlist" id="fgiftlist" method="post" action="./goods/goods_gift_delete.php" onsubmit="return fgiftlist_submit(this);">
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
		<col class="w120">
		<col class="w80">
		<col class="w80">
		<col>
		<col class="w60">
		<col class="w60">
		<col class="w80">
		<col class="w80">
		<col class="w80">
		<col class="w60">
	</colgroup>
	<thead>
	<tr>
		<th scope="col"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
		<th scope="col">번호</th>
		<th scope="col"><?php echo subject_sort_link('gr_id',$q2); ?>일련번호</a></th>
		<th scope="col"><?php echo subject_sort_link('gr_sdate',$q2); ?>사용시작</a></th>
		<th scope="col"><?php echo subject_sort_link('gr_edate',$q2); ?>사용종료</a></th>
		<th scope="col"><?php echo subject_sort_link('gr_price',$q2); ?>금액</a></th>
		<th scope="col">매수</th>
		<th scope="col">사용</th>
		<th scope="col">발행금액</th>
		<th scope="col">사용금액</th>
		<th scope="col">조회</th>
		<th scope="col">관리</th>
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$gr_id = $row['gr_id'];

		// 발행매수
		$sql1 = " select COUNT(*) as cnt from shop_gift where gr_id='$gr_id' ";
		$row1 = sql_fetch($sql1);

		// 사용매수
		$sql2 = " select COUNT(*) as cnt from shop_gift where gr_id='$gr_id' and gi_use='1' ";
		$row2 = sql_fetch($sql2);

		// 발행금액
		$sql3 = " select SUM(gr_price) as total from shop_gift where gr_id='$gr_id' ";
		$row3 = sql_fetch($sql3);

		// 사용금액
		$sql4 = " select SUM(gr_price) as total from shop_gift where gr_id='$gr_id' and gi_use='1' ";
		$row4 = sql_fetch($sql4);

		$s_upd = "<a href='./goods.php?code=gift_form&w=u&gr_id=$gr_id$qstr&page=$page' class=\"btn_small\">수정</a>";

		$s_cert = "<a href='./goods/goods_gift_serial.php?gr_id=$gr_id' onclick=\"win_open(this,'pop_serial','700','630','yes');return false\" class=\"btn_small grey\">인증번호</a>";		

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;

		$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td>			
			<input type="hidden" name="gr_id[<?php echo $i; ?>]" value="<?php echo $gr_id; ?>">
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>">
		</td>
		<td><?php echo $num--; ?></td>
		<td><?php echo $gr_id; ?></td>
		<td><?php echo $row['gr_sdate']; ?></td>
		<td><?php echo $row['gr_edate']; ?></td>
		<td class="tar bold"><?php echo number_format($row['gr_price']); ?></td>
		<td><?php echo number_format($row1['cnt']); ?></td>
		<td><?php echo number_format($row2['cnt']); ?></td>
		<td class="tar"><?php echo number_format($row3['total']); ?></td>
		<td class="tar"><?php echo number_format($row4['total']); ?></td>
		<td><?php echo $s_cert; ?></td>
		<td><?php echo $s_upd; ?></td>
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tbody><tr><td colspan="12" class="empty_table">자료가 없습니다.</td></tr>';
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

<script>
function fgiftlist_submit(f)
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
	$("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99" });
});
</script>
