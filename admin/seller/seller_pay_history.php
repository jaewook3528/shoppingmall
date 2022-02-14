<?php
if(!defined('_TUBEWEB_')) exit;

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_seller_cal a, shop_seller b ";
$sql_search = " where a.mb_id = b.mb_id ";
$sql_order  = " order by a.index_no desc ";

if($sfl && $stx) {
    $sql_search .= " and $sfl like '%$stx%' ";
}

// 기간검색
if($fr_date && $to_date)
    $sql_search .= " and a.reg_time between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
else if($fr_date && !$to_date)
	$sql_search .= " and a.reg_time between '$fr_date 00:00:00' and '$fr_date 23:59:59' ";
else if(!$fr_date && $to_date)
	$sql_search .= " and a.reg_time between '$to_date 00:00:00' and '$to_date 23:59:59' ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select a.*, b.seller_code, b.company_name
			$sql_common
			$sql_search
			$sql_order
			limit $from_record, $rows ";
$result = sql_query($sql);

include_once(TB_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$btn_frmline = <<<EOF
<input type="submit" name="act_button" value="선택삭제" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
<a href="./seller/seller_pay_history_excel.php?$q1" class="btn_lsmall bx-white"><i class="fa fa-file-excel-o"></i> 엑셀저장</a>
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
				<?php echo option_selected('b.company_name', $sfl, '공급사명'); ?>
				<?php echo option_selected('b.seller_code', $sfl, '업체코드'); ?>
				<?php echo option_selected('a.mb_id', $sfl, '아이디'); ?>
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
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="검색" class="btn_medium">
	<input type="button" value="초기화" id="frmRest" class="btn_medium grey">
</div>
</form>

<form name="forderlist" id="forderlist" method="post" action="./seller/seller_pay_delete.php" onsubmit="return forderlist_submit(this);">
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
		<col class="w80">
		<col>
		<col class="w130">
		<col class="w60">
		<col class="w90">
		<col class="w90">
		<col class="w90">
		<col class="w90">
		<col class="w90">
		<col class="w90">
		<col class="w90">
		<col class="w90">
		<col class="w60">
	</colgroup>
	<thead>
	<tr>
		<th scope="col"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
		<th scope="col">번호</th>
		<th scope="col">업체코드</th>
		<th scope="col">공급사명</th>
		<th scope="col">일시</th>
		<th scope="col" class="th_bg">총건수</th>
		<th scope="col" class="th_bg">주문금액</th>
		<th scope="col" class="th_bg">포인트결제</th>
		<th scope="col" class="th_bg">쿠폰할인</th>
		<th scope="col" class="th_bg">배송비</th>
		<th scope="col" class="th_bg">공급가총액</th>
		<th scope="col" class="th_bg">실정산액</th>
		<th scope="col">가맹점수수료</th>
		<th scope="col">본사마진</th>
		<th scope="col">내역</th>
	</tr>
	</thead>
	<tbody>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$order_idx = explode(',', $row['order_idx']);

		$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td>
			<input type="hidden" name="index_no[<?php echo $i; ?>]" value="<?php echo $row['index_no']; ?>">
			<input type="hidden" name="order_idx[<?php echo $i; ?>]" value="<?php echo $row['order_idx']; ?>">
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>">
		</td>
		<td><?php echo $num--; ?></td>
		<td class="tal"><?php echo get_sideview($row['mb_id'], $row['seller_code']); ?></td>
		<td class="tal"><?php echo $row['company_name']; ?></td>
		<td><?php echo $row['reg_time']; ?></td>
		<td><?php echo count($order_idx); ?></td>
		<td class="tar"><?php echo number_format($row['tot_price']); ?></td>
		<td class="tar"><?php echo number_format($row['tot_point']); ?></td>
		<td class="tar"><?php echo number_format($row['tot_coupon']); ?></td>
		<td class="tar"><?php echo number_format($row['tot_baesong']); ?></td>
		<td class="tar"><?php echo number_format($row['tot_supply']); ?></td>
		<td class="tar fc_00f bold"><?php echo number_format($row['tot_seller']); ?></td>
		<td class="tar"><?php echo number_format($row['tot_partner']); ?></td>
		<td class="tar fc_red bold"><?php echo number_format($row['tot_admin']); ?></td>
		<td><a href="<?php echo TB_ADMIN_URL; ?>/pop_sellerorder.php?mb_id=<?php echo $row['mb_id']; ?>&order_idx=<?php echo $row['order_idx']; ?>" onclick="win_open(this,'pop_sellerorder','1200','600','yes');return false;" class="btn_small">내역</a></td>
	</tr>
	<?php
	}
	if($i==0)
		echo '<tr><td colspan="15" class="empty_table">자료가 없습니다.</td></tr>';
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
function forderlist_submit(f)
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
