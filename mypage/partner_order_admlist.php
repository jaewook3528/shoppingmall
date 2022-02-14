<?php
if(!defined('_TUBEWEB_')) exit;

$pg_title = '본사 상품판매실적';
include_once("./admin_head.sub.php");

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

if(isset($sel_field))	   $qstr .= "&sel_field=$sel_field";
if(isset($od_settle_case)) $qstr .= "&od_settle_case=".urlencode($od_settle_case);
if(isset($od_status))	   $qstr .= "&od_status=$od_status";

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_order ";
$sql_search = " where dan != 0 and pt_id = '{$member['id']}' ";

if($sfl && $stx)
	$sql_search .= " and $sfl like '%$stx%' ";

if($od_settle_case)
	$sql_search .= " and paymethod = '$od_settle_case' ";

if(is_numeric($od_status))
	$sql_search .= " and dan = '$od_status' ";

if($fr_date && $to_date)
    $sql_search .= " and left({$sel_field},10) between '$fr_date' and '$to_date' ";
else if($fr_date && !$to_date)
	$sql_search .= " and left({$sel_field},10) between '$fr_date' and '$fr_date' ";
else if(!$fr_date && $to_date)
	$sql_search .= " and left({$sel_field},10) between '$to_date' and '$to_date' ";

$sql_group = " group by od_id ";
$sql_order = " order by index_no desc ";

// 테이블의 전체 레코드수만 얻음
$sql = " select od_id {$sql_common} {$sql_search} {$sql_group} ";
$result = sql_query($sql);
$total_count = sql_num_rows($result);

if($_SESSION['ss_page_rows'])
	$page_rows = $_SESSION['ss_page_rows'];
else
	$page_rows = 30;

$rows = $page_rows;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select * {$sql_common} {$sql_search} {$sql_group} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$tot_orderprice = 0; // 총주문액
$sql = " select od_id {$sql_common} {$sql_search} {$sql_group} {$sql_order} ";
$res = sql_query($sql);
while($row=sql_fetch_array($res)) {
	$amount = get_order_spay($row['od_id']);
	$tot_orderprice += $amount['buyprice'];
}

include_once(TB_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$btn_frmline = <<<EOF
<a href="./partner_order_admlist_excel.php?$q1" class="btn_lsmall bx-white"><i class="fa fa-file-excel-o"></i> 엑셀저장</a>
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
				<?php echo option_selected('od_id', $sfl, '주문번호'); ?>
				<?php echo option_selected('od_no', $sfl, '일련번호'); ?>
				<?php echo option_selected("mb_id", $sfl, '회원아이디'); ?>
				<?php echo option_selected('name', $sfl, '주문자명'); ?>
			</select>
			<input type="text" name="stx" value="<?php echo $stx; ?>" class="frm_input" size="30">
		</td>
	</tr>
	<tr>
		<th scope="row">기간검색</th>
		<td>
			<select name="sel_field">
				<?php echo option_selected('od_time', $sel_field, "주문일"); ?>
				<?php echo option_selected('receipt_time', $sel_field, "입금완료일"); ?>
				<?php echo option_selected('delivery_date', $sel_field, "배송일"); ?>
				<?php echo option_selected('invoice_date', $sel_field, "배송완료일"); ?>
				<?php echo option_selected('user_date', $sel_field, "구매확정일"); ?>
				<?php echo option_selected('cancel_date', $sel_field, "주문취소일"); ?>
				<?php echo option_selected('change_date', $sel_field, "교환완료일"); ?>
				<?php echo option_selected('return_date', $sel_field, "반품완료일"); ?>
				<?php echo option_selected('refund_date', $sel_field, "환불완료일"); ?>
			</select>
			<?php echo get_search_date("fr_date", "to_date", $fr_date, $to_date); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">결제방법</th>
		<td>
			<?php echo radio_checked('od_settle_case', $od_settle_case,  '', '전체'); ?>
			<?php echo radio_checked('od_settle_case', $od_settle_case, '무통장', '무통장'); ?>
			<?php echo radio_checked('od_settle_case', $od_settle_case, '가상계좌', '가상계좌'); ?>
			<?php echo radio_checked('od_settle_case', $od_settle_case, '계좌이체', '계좌이체'); ?>
			<?php echo radio_checked('od_settle_case', $od_settle_case, '휴대폰', '휴대폰'); ?>
			<?php echo radio_checked('od_settle_case', $od_settle_case, '신용카드', '신용카드'); ?>
			<?php echo radio_checked('od_settle_case', $od_settle_case, '간편결제', 'PG간편결제'); ?>
			<?php echo radio_checked('od_settle_case', $od_settle_case, 'KAKAOPAY', 'KAKAOPAY'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">주문상태</th>
		<td>
			<?php echo radio_checked('od_status', $od_status,  '', '전체'); ?>
			<?php echo radio_checked('od_status', $od_status, '1', $gw_status[1]); ?>
			<?php echo radio_checked('od_status', $od_status, '2', $gw_status[2]); ?>
			<?php echo radio_checked('od_status', $od_status, '3', $gw_status[3]); ?>
			<?php echo radio_checked('od_status', $od_status, '4', $gw_status[4]); ?>
			<?php echo radio_checked('od_status', $od_status, '5', $gw_status[5]); ?>
			<?php echo radio_checked('od_status', $od_status, '6', $gw_status[6]); ?>
			<?php echo radio_checked('od_status', $od_status, '7', $gw_status[7]); ?>
			<?php echo radio_checked('od_status', $od_status, '8', $gw_status[8]); ?>
			<?php echo radio_checked('od_status', $od_status, '9', $gw_status[9]); ?>
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
	<select id="page_rows" onchange="location='<?php echo "{$_SERVER['SCRIPT_NAME']}?{$q1}&page=1"; ?>&page_rows='+this.value;" class="marl5">
		<?php echo option_selected('30',  $page_rows, '30줄 정렬'); ?>
		<?php echo option_selected('50',  $page_rows, '50줄 정렬'); ?>
		<?php echo option_selected('100', $page_rows, '100줄 정렬'); ?>
		<?php echo option_selected('150', $page_rows, '150줄 정렬'); ?>
	</select>
	<strong class="ov_a">총주문액 : <?php echo number_format($tot_orderprice); ?>원</strong>
</div>

<div class="local_frm01">
	<?php echo $btn_frmline; ?>
</div>
<div class="tbl_head01">
	<table id="sodr_list">
	<colgroup>
		<col class="w50">
		<col class="w100">
		<col class="w150">
		<col class="w40">
		<col>
		<col class="w60">
		<col class="w90">
		<col class="w90">
		<col class="w90">
		<col class="w90">
		<col class="w90">
		<col class="w90">
		<col class="w90">
	</colgroup>
	<thead>
	<tr>
		<th scope="col">번호</th>
		<th scope="col">주문일시</th>
		<th scope="col">주문번호</th>
		<th scope="col" colspan="2">주문상품</th>
		<th scope="col">수량</th>
		<th scope="col">상품금액</th>
		<th scope="col">배송비</th>
		<th scope="col" class="th_bg">판매수수료</th>
		<th scope="col">주문상태</th>
		<th scope="col">주문자</th>
		<th scope="col">총주문액</th>
		<th scope="col">결제방법</th>
	</tr>
	</thead>
	<tbody>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$bg = 'list'.($i%2);

		$amount = get_order_spay($row['od_id']);
		$sodr = get_order_list($row, $amount);

		$sql = " select * {$sql_common} {$sql_search} and od_id = '{$row['od_id']}' order by index_no ";
		$res = sql_query($sql);
		$rowspan = sql_num_rows($res);
		for($k=0; $row2=sql_fetch_array($res); $k++) {
			$gs = unserialize($row2['od_goods']);

			$psql = " select pp_pay
						from shop_partner_pay
					   where pp_rel_table = 'sale'
						 and pp_rel_id = '{$row2['od_no']}'
						 and pp_rel_action = '{$row2['od_id']}'
						 and mb_id = '{$row2['pt_id']}' ";
			$psum = sql_fetch($psql);
			$pp_pay = (int)$psum['pp_pay'];

			$it_href = TB_SHOP_URL.'/view.php?index_no='.$row2['gs_id'];
	?>
	<tr class="<?php echo $bg; ?>">
		<?php if($k == 0) { ?>
		<td rowspan="<?php echo $rowspan; ?>"><?php echo $num--; ?></td>
		<td rowspan="<?php echo $rowspan; ?>">
			<?php echo substr($row['od_time'],2,14); ?>
			<?php echo $sodr['disp_test']; ?>
		</td>
		<td rowspan="<?php echo $rowspan; ?>">
			<?php echo $row['od_id']; ?>
			<?php echo $sodr['disp_mobile']; ?>
		</td>
		<?php } ?>
		<td class="td_img"><a href="<?php echo $it_href; ?>" target="_blank"><?php echo get_od_image($row['od_id'], $gs['simg1'], 30, 30); ?></a></td>
		<td class="td_itname"><a href="<?php echo $it_href; ?>" target="_blank"><?php echo get_text($gs['gname']); ?></a></td>
		<td><?php echo $row2['sum_qty']; ?></td>
		<td class="tar"><?php echo $row2['goods_price']; ?></td>
		<td class="tar"><?php echo $row2['baesong_price']; ?></td>
		<td class="tar"><?php echo number_format($pp_pay); ?></td>
		<td><?php echo $gw_status[$row2['dan']]; ?></td>
		<?php if($k == 0) { ?>
		<td rowspan="<?php echo $rowspan; ?>">
			<?php echo $sodr['disp_od_name']; ?>
			<?php echo $sodr['disp_mb_id']; ?>
		</td>
		<td rowspan="<?php echo $rowspan; ?>" class="td_price"><?php echo $sodr['disp_price']; ?></td>
		<td rowspan="<?php echo $rowspan; ?>"><?php echo $sodr['disp_paytype']; ?></td>
		<?php } ?>
	<?php
		}
	}
	sql_free_result($result);
	if($i==0)
		echo '<tr><td colspan="13" class="empty_table">자료가 없습니다.</td></tr>';
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

<div class="information">
	<h4>도움말</h4>
	<div class="content">
		<div class="hd">ㆍ판매수수료는 언제 적립되나요?</div>
		<div class="desc01 accent">
			<p>ㆍ배송완료가 완료되면 판매수수료가 적립됩니다. 배송완료 전 단계에서는 적립 대기 상태입니다.</p>
			<p class="fc_red">ㆍ<strong>참고!</strong> 배송완료 후 반품처리시 적립되었던 판매수수료는 환수 처리됩니다.</p>
		</div>
		<div class="hd">ㆍ주문서의 상세 정보를 확인할 수 없나요?</div>
		<div class="desc01 accent">
			<p>ㆍ주문자의 개인정보보호를 위해 주문서의 상세 정보는 열람하실 수 없습니다.</p>
		</div>
	 </div>
</div>

<script>
$(function(){
    $("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});
</script>

<?php
include_once("./admin_tail.sub.php");
?>