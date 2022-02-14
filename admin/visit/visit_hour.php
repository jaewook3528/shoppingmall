<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TB_ADMIN_PATH.'/visit/visit.sub.php');

$max = 0;
$sum_count = 0;
$sql = " select SUBSTRING(vi_time,1,2) as vi_hour, count(vi_id) as cnt
            from shop_visit
            where vi_date between '{$fr_date}' and '{$to_date}'
            group by vi_hour
            order by vi_hour ";
$result = sql_query($sql);
for($i=0; $row=sql_fetch_array($result); $i++) {
    $arr[$row['vi_hour']] = $row['cnt'];

    if($row['cnt'] > $max) $max = $row['cnt'];

    $sum_count += $row['cnt'];
}
?>

<div class="local_ov mart30">
	총 접속자수 : <b class="fc_red"><?php echo number_format($sum_count); ?></b>건 접속
</div>

<div class="tbl_head01">
	<table>
	<colgroup>
		<col class="w60">	
		<col>
		<col class="w80">
		<col class="w80">
	</colgroup>
	<thead>
	<tr>
		<th scope="col">시간</th>
		<th scope="col">그래프</th>
		<th scope="col">접속자수</th>
		<th scope="col">비율(%)</th>
	</tr>
	</thead>
	<tfoot>
	<tr>
		<td colspan="2"><strong>합계</strong></td>
		<td><strong><?php echo number_format($sum_count); ?></strong></td>
		<td><strong>100</strong></td>
	</tr>
	</tfoot>
	<tbody class="list">
	<?php
	$k = 0;
	if($i) {
		for($i=0; $i<24; $i++) {
			$hour = sprintf("%02d", $i);
			$count = (int)$arr[$hour];

			$rate = ($count / $sum_count * 100);
			$s_rate = number_format($rate, 1);

			$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td><?php echo $hour; ?></td>
		<td class="tal">
			<div class="graph">
				<span class="bar" style="width:<?php echo $s_rate; ?>%"></span>
			</div>
		</td>
		<td><?php echo number_format($count); ?></td>
		<td><?php echo $s_rate; ?></td>
	</tr>
	<?php
		}
	} else {
		echo '<tr><td colspan="4" class="empty_table">자료가 없습니다.</td></tr>';
	}
	?>
	</tbody>
	</table>
</div>
