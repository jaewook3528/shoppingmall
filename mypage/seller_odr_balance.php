<?php
if(!defined('_TUBEWEB_')) exit;

$pg_title = "정산대기목록";
include_once("./admin_head.sub.php");

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_order ";
$sql_search = " where seller_id = '{$seller['seller_code']}' and sellerpay_yes = 0 and user_ok = 1 ";

if($sfl && $stx)
	$sql_search .= " and $sfl like '%$stx%' ";

if($fr_date && $to_date)
    $sql_search .= " and left(od_time,10) between '$fr_date' and '$to_date' ";
else if($fr_date && !$to_date)
	$sql_search .= " and left(od_time,10) between '$fr_date' and '$fr_date' ";
else if(!$fr_date && $to_date)
	$sql_search .= " and left(od_time,10) between '$to_date' and '$to_date' ";

$sql_order = " order by od_time desc, index_no asc ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt {$sql_common} {$sql_search} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$result = sql_query($sql);

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
				<?php echo option_selected('od_id', $sfl, '주문번호'); ?>
				<?php echo option_selected('od_no', $sfl, '일련번호'); ?>
				<?php echo option_selected('name', $sfl, '주문자명'); ?>
			</select>
			<input type="text" name="stx" value="<?php echo $stx; ?>" class="frm_input" size="30">
		</td>
	</tr>
	<tr>
		<th scope="row">주문일</th>
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

<div class="local_ov mart30">
	전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회
</div>
<div class="tbl_head01">
	<table id="sodr_list">
	<colgroup>
		<col class="w50">
		<col class="w100">
		<col class="w150">
		<col class="w90">
		<col class="w40">
		<col>
		<col class="w60">
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
		<th scope="col">주문자</th>
		<th scope="col" colspan="2">주문상품</th>
		<th scope="col">수량</th>
		<th scope="col">판매가</th>
		<th scope="col" class="th_bg">공급가</th>
		<th scope="col" class="th_bg">배송비</th>
		<th scope="col" class="th_bg">정산액</th>
		<th scope="col">주문총액</th>
		<th scope="col">결제방법</th>
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$gs = unserialize($row['od_goods']);

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;

		$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td><?php echo $num--; ?></td>
		<td><?php echo substr($row['od_time'],2,14); ?></td>
		<td><a href="<?php echo TB_MYPAGE_URL; ?>/seller_odr_form.php?od_id=<?php echo $row['od_id']; ?>" onclick="win_open(this,'seller_odr_form','1200','800','yes');return false;" class="fc_197"><?php echo $row['od_id']; ?></a></td>
		<td><?php echo get_text($row['name']); ?></td>
		<td class="td_img"><a href="<?php echo TB_SHOP_URL; ?>/view.php?index_no=<?php echo $row['gs_id']; ?>" target="_blank"><?php echo get_od_image($row['od_id'], $gs['simg1'], 30, 30); ?></a></td>
		<td class="td_itname"><a href="<?php echo TB_MYPAGE_URL; ?>/page.php?code=seller_goods_form&w=u&gs_id=<?php echo $row['gs_id']; ?>" target="_blank"><?php echo get_text($gs['gname']); ?></a></td>
		<td><?php echo number_format($row['sum_qty']); ?></td>
		<td class="tar"><?php echo number_format($row['goods_price']); ?></td>
		<td class="tar"><?php echo number_format($row['supply_price']); ?></td>
		<td class="tar"><?php echo number_format($row['baesong_price']); ?></td>
		<td class="td_price"><?php echo number_format($row['supply_price']+$row['baesong_price']); ?></td>
		<td class="td_price"><?php echo number_format($row['goods_price']+$row['baesong_price']); ?></td>
		<td><?php echo $row['paymethod']; ?></td>
	</tr>
	<?php
	}
	if($i==0)
		echo '<tbody><tr><td colspan="13" class="empty_table">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>

<?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=');
?>

<div class="information">
	<h4>도움말</h4>
	<div class="content">
		<div class="hd">ㆍ정산대기목록에서 주문건은 언제 노출되나요?</div>
		<div class="desc01 accent">
			<p>ㆍ배송완료 후 "구매확정"이 되어야만 정산대기목록에 노출됩니다. 정산처리가 완료되면 "정산완료목록"에서 확인하실 수 있습니다.</p>
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