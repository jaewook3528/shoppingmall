<?php
if(!defined('_TUBEWEB_')) exit;

$pg_title = "주문통계분석";
include_once("./admin_head.sub.php");

if(!$year)  $year  = TB_TIME_YEAR;
if(!$month)	$month = TB_TIME_MONTH;

$search_date = preg_replace("/([0-9]{4})([0-9]{2})/", "\\1-\\2", $year.$month);

$sql = " select COUNT(*) as cnt,
				MIN(od_time) as min_year
		   from shop_order
		  where dan IN(2,3,4,5,8)
			and left(od_time,7) = '$search_date'
			and seller_id = '{$seller['seller_code']}' ";
$row = sql_fetch($sql);

$sum_count = (int)$row['cnt']; // 총상품판매수
$min_year = substr($row['min_year'],0,4); // 가장작은 년도
if(!$min_year) $min_year = TB_TIME_YEAR; // 내역이없다면 현재 년도로
?>

<h2>통계검색</h2>
<form name="fsearch" id="fsearch" method="get">
<input type="hidden" name="code" value="<?php echo $code; ?>">
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w120">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">기간검색</th>
		<td>
			<select name="year">
				<?php
				for($i=$min_year; $i<=TB_TIME_YEAR; $i++) {
					echo "<option value=\"{$i}\"".get_selected($year, $i).">{$i}년</option>\n";
				}
				?>
			</select>
			<select name="month">
				<?php
				for($i=1;$i<=12;$i++) {
					$k = sprintf('%02d',$i);
					echo "<option value=\"{$k}\"".get_selected($month, $k).">{$k}월</option>\n";
				}
				?>
			</select>
			<input type="submit" value="검색" class="btn_small">
		</td>
	</tr>
	</tbody>
	</table>
</div>
</form>

<div class="local_ov mart20">
	<strong>총 판매상품수 : <?php echo number_format($sum_count); ?>건</strong>
	<span class="fc_red">(취소/환불/반품 내역은 제외)</span>
</div>
<div class="tbl_head01">
	<table>
	<colgroup>
		<col class="w100">
		<col>
		<col class="w60">
		<col class="w90">
		<col class="w90">
		<col class="w90">
		<col class="w90">
		<col class="w90">
	</colgroup>
	<thead>
	<tr>
		<th scope="col">날짜</th>
		<th scope="col">그래프</th>
		<th scope="col">비율%</th>
		<th scope="col">판매상품수</th>
		<th scope="col">판매가격</th>
		<th scope="col" class="th_bg">공급가격</th>
		<th scope="col" class="th_bg">배송비</th>
		<th scope="col" class="th_bg">소계</th>
	</tr>
	</thead>
	<tbody class="list">
	<?php
	$tot_count    = 0;
	$tot_price    = 0;
	$tot_supply   = 0;
	$tot_baesong  = 0;
	$tot_subprice = 0;

	if(!$sum_count) $sum_count = 1;

	for($i=1;$i<=31;$i++) {
		$day = sprintf("%02d", $i);
		$date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3", $year.$month.$day);

		$sql = " select COUNT(*) as cnt,
						SUM(goods_price) as price,
						SUM(supply_price) as supply,
						SUM(baesong_price) as baesong,
						SUM(supply_price + baesong_price) as subprice
				   from shop_order
				  where dan IN(2,3,4,5,8)
					and left(od_time,10) = '$date'
					and seller_id = '{$seller['seller_code']}' ";
		$sum = sql_fetch($sql);

		$rate = ((int)$sum['cnt'] / $sum_count * 100);
		$s_rate = number_format($rate, 1);

		$tot_count    += (int)$sum['cnt'];
		$tot_price    += (int)$sum['price'];
		$tot_supply   += (int)$sum['supply'];
		$tot_baesong  += (int)$sum['baesong'];
		$tot_subprice += (int)$sum['subprice'];

		$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td><?php echo $date; ?></td>
		<td><div class="graph"><span class="bar" style="width:<?php echo $s_rate; ?>%"></span></div></td>
		<td><?php echo $s_rate; ?></td>
		<td><?php echo number_format($sum['cnt']); ?></td>
		<td class="tar"><?php echo number_format($sum['price']); ?></td>
		<td class="tar"><?php echo number_format($sum['supply']); ?></td>
		<td class="tar"><?php echo number_format($sum['baesong']); ?></td>
		<td class="tar"><?php echo number_format($sum['subprice']); ?></td>
	</tr>
	<?php
		// 오늘날짜와 같다면 중지
		if($date == TB_TIME_YMD) break;
	}
	?>
	</tbody>
	<tfoot>
	<tr>
		<td colspan="2">합계</td>
		<td>100</td>
		<td><?php echo number_format($tot_count); ?></td>
		<td class="tar"><?php echo number_format($tot_price); ?></td>
		<td class="tar"><?php echo number_format($tot_supply); ?></td>
		<td class="tar"><?php echo number_format($tot_baesong); ?></td>
		<td class="tar"><?php echo number_format($tot_subprice); ?></td>
	</tr>
	</tfoot>
	</table>
</div>

<?php
include_once("./admin_tail.sub.php");
?>