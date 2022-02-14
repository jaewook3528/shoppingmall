<?php
define('_NEWWIN_', true);
include_once('./_common.php');
include_once(TB_ADMIN_PATH."/admin_access.php");

$tb['title'] = "주문내역";
include_once(TB_ADMIN_PATH."/admin_head.php");

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

if(isset($sel_field))		 $qstr .= "&sel_field=$sel_field";
if(isset($od_settle_case))	 $qstr .= "&od_settle_case=".urlencode($od_settle_case);
if(isset($od_status))		 $qstr .= "&od_status=$od_status";
if(isset($od_final))		 $qstr .= "&od_final=$od_final";
if(isset($od_taxbill))		 $qstr .= "&od_taxbill=$od_taxbill";
if(isset($od_taxsave))		 $qstr .= "&od_taxsave=$od_taxsave";
if(isset($od_memo))			 $qstr .= "&od_memo=$od_memo";
if(isset($od_shop_memo))	 $qstr .= "&od_shop_memo=$od_shop_memo";
if(isset($od_receipt_point)) $qstr .= "&od_receipt_point=$od_receipt_point";
if(isset($od_coupon))		 $qstr .= "&od_coupon=$od_coupon";
if(isset($od_escrow))		 $qstr .= "&od_escrow=$od_escrow";

$query_string = "mb_id=$mb_id$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_order ";

$where = array();
$where[] = " dan != '0' and mb_id = '$mb_id' ";

if($sfl && $stx)
	$where[] = " $sfl like '%$stx%' ";

if($od_settle_case)
	$where[] = " paymethod = '$od_settle_case' ";

if(is_numeric($od_status))
	$where[] = " dan = '$od_status' ";

if(is_numeric($od_final))
	$where[] = " user_ok = '$od_final' ";

if($od_taxbill)
	$where[] = " taxbill_yes = 'Y' ";

if($od_taxsave)
	$where[] = " taxsave_yes IN ('Y','S') ";

if($od_memo)
	$where[] = " memo <> '' ";

if($od_shop_memo)
	$where[] = " shop_memo <> '' ";

if($od_receipt_point)
	$where[] = " use_point != 0 ";

if($od_coupon)
	$where[] = " coupon_price != 0 ";

if($od_escrow)
	$where[] = " od_escrow = 1 ";

if($fr_date && $to_date)
    $where[] = " left({$sel_field},10) between '$fr_date' and '$to_date' ";
else if($fr_date && !$to_date)
	$where[] = " left({$sel_field},10) between '$fr_date' and '$fr_date' ";
else if(!$fr_date && $to_date)
	$where[] = " left({$sel_field},10) between '$to_date' and '$to_date' ";

if($where) {
    $sql_search = ' where '.implode(' and ', $where);
}

$sql_group = " group by od_id ";
$sql_order = " order by index_no desc ";

// 테이블의 전체 레코드수만 얻음
$sql = " select od_id {$sql_common} {$sql_search} {$sql_group} ";
$result = sql_query($sql);
$total_count = sql_num_rows($result);

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select * {$sql_common} {$sql_search} {$sql_group} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

$tot_orderprice  = 0; // 총 결제금액
$sql = " select od_id {$sql_common} {$sql_search} {$sql_group} {$sql_order} ";
$res = sql_query($sql);
while($row=sql_fetch_array($res)) {
	$amount = get_order_spay($row['od_id']);
	$tot_orderprice += $amount['buyprice'];
}

include_once(TB_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>

<div id="memberorder_pop" class="new_win">
	<h1><?php echo $tb['title']; ?></h1>

	<section class="new_win_desc marb50">

	<ul class="anchor">
		<li><a href="./pop_memberform.php?mb_id=<?php echo $mb_id; ?>">회원정보수정</a></li>
		<?php if(is_seller($mb_id)) { ?>
		<li><a href="./pop_sellerform.php?mb_id=<?php echo $mb_id; ?>">공급사정보수정</a></li>
		<li><a href="./pop_sellerorder.php?mb_id=<?php echo $mb_id; ?>">공급사판매내역</a></li>
		<?php } ?>
		<li><a href="./pop_memberorder.php?mb_id=<?php echo $mb_id; ?>">주문내역</a></li>
		<li><a href="./pop_memberpoint.php?mb_id=<?php echo $mb_id; ?>">포인트내역</a></li>
	</ul>

	<h3 class="anc_tit">기본검색</h3>
	<form name="fsearch" id="fsearch" method="get">
	<input type="hidden" name="mb_id" value="<?php echo $mb_id; ?>">
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
					<?php echo option_selected('deposit_name', $sfl, '입금자명'); ?>
					<?php echo option_selected('bank', $sfl, '입금계좌'); ?>
					<?php echo option_selected('b_name', $sfl, '수령자명'); ?>
					<?php echo option_selected('b_telephone', $sfl, '수령자집전화'); ?>
					<?php echo option_selected('b_cellphone', $sfl, '수령자핸드폰'); ?>
					<?php echo option_selected('delivery_no', $sfl, '운송장번호'); ?>
					<?php echo option_selected('seller_id', $sfl, '판매자ID'); ?>
					<?php echo option_selected('pt_id', $sfl, '가맹점ID'); ?>
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
		<tr>
			<th scope="row">구매확정</th>
			<td>
				<?php echo radio_checked('od_final', $od_final,  '', '전체'); ?>
				<?php echo radio_checked('od_final', $od_final, '0', '구매확정'); ?>
				<?php echo radio_checked('od_final', $od_final, '1', '구매미확정'); ?>
			</td>
		</tr>
		<tr>
			<th scope="row">기타선택</th>
			<td>
				<?php echo check_checked('od_taxbill', $od_taxbill, 'Y', '세금계산서'); ?>
				<?php echo check_checked('od_taxsave', $od_taxsave, 'Y', '현금영수증'); ?>
				<?php echo check_checked('od_memo', $od_memo, 'Y', '배송메세지'); ?>
				<?php echo check_checked('od_shop_memo', $od_shop_memo, 'Y', '관리자메모'); ?>
				<?php echo check_checked('od_receipt_point', $od_receipt_point, 'Y', '포인트주문'); ?>
				<?php echo check_checked('od_coupon', $od_coupon, 'Y', '쿠폰할인'); ?>
				<?php echo check_checked('od_escrow', $od_escrow, 'Y', '에스크로'); ?>
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
		<strong class="ov_a">총주문액 : <?php echo number_format($tot_orderprice); ?>원</strong>
	</div>

	<div class="tbl_head01">
		<table id="sodr_list">
		<colgroup>
			<col class="w50">
			<col class="w150">
			<col class="w40">
			<col>
			<col class="w90">
			<col class="w90">
			<col class="w90">
			<col class="w90">
		</colgroup>
		<thead>
		<tr>
			<th scope="col">번호</th>
			<th scope="col">주문번호</th>
			<th scope="col" colspan="2">주문상품</th>
			<th scope="col">주문상태</th>
			<th scope="col">총주문액</th>
			<th scope="col">결제방법</th>
			<th scope="col">주문자명</th>
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
		?>
		<tr class="<?php echo $bg; ?>">
			<?php if($k == 0) { ?>
			<td rowspan="<?php echo $rowspan; ?>"><?php echo $num--; ?></td>
			<td rowspan="<?php echo $rowspan; ?>">
				<a href="<?php echo TB_ADMIN_URL; ?>/pop_orderform.php?od_id=<?php echo $row['od_id']; ?>" onclick="win_open(this,'pop_orderform','1200','800','yes');return false;" class="fc_197"><?php echo $row['od_id']; ?></a><br>
				<?php echo substr($row['od_time'],2,14); ?> (<?php echo get_yoil($row['od_time']); ?>)
			</td>
			<?php } ?>
			<td class="td_img"><a href="<?php echo TB_SHOP_URL; ?>/view.php?index_no=<?php echo $row2['gs_id']; ?>" target="_blank"><?php echo get_od_image($row['od_id'], $gs['simg1'], 30, 30); ?></a></td>
			<td class="td_itname"><a href="<?php echo TB_ADMIN_URL; ?>/goods.php?code=form&w=u&gs_id=<?php echo $row2['gs_id']; ?>" target="_blank"><?php echo get_text($gs['gname']); ?></a></td>
			<td><?php echo $gw_status[$row2['dan']]; ?></td>
			<?php if($k == 0) { ?>
			<td rowspan="<?php echo $rowspan; ?>" class="td_price"><?php echo $sodr['disp_price']; ?></td>
			<td rowspan="<?php echo $rowspan; ?>"><?php echo $sodr['disp_paytype']; ?></td>
			<td rowspan="<?php echo $rowspan; ?>">
				<?php echo $sodr['disp_od_name']; ?>
				<?php echo $sodr['disp_mb_id']; ?>
			</td>
			<?php } ?>
		<?php
			}
		}
		sql_free_result($result);
		if($i==0)
			echo '<tr><td colspan="8" class="empty_table">자료가 없습니다.</td></tr>';
		?>
		</tbody>
		</table>
	</div>

	<?php
	echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=');
	?>
	</section>
</div>

<script>
$(function(){
    $("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});
</script>

<?php
include_once(TB_ADMIN_PATH."/admin_tail.sub.php");
?>