<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TB_ADMIN_PATH.'/visit/visit.sub.php');

$max = 0;
$sum_count = 0;
$sql = " select * from shop_visit where vi_date between '{$fr_date}' and '{$to_date}' ";
$result = sql_query($sql);
while($row=sql_fetch_array($result)) {
    $str = $row['vi_referer'];
    preg_match("/^http[s]*:\/\/([\.\-\_0-9a-zA-Z]*)\//", $str, $match);
    $s = $match[1];
    $s = preg_replace("/^(www\.|search\.|dirsearch\.|dir\.search\.|dir\.|kr\.search\.|myhome\.)(.*)/", "\\2", $s);
    $arr[$s]++;

    if($arr[$s] > $max) $max = $arr[$s];

    $sum_count++;
}
?>

<div class="local_ov mart30">
	총 접속자수 : <b class="fc_red"><?php echo number_format($sum_count); ?></b>건 접속
</div>

<div class="tbl_head01">
	<table>
	<colgroup>
		<col class="w60">
		<col class="w200">
		<col>
		<col class="w80">
		<col class="w80">	
	</colgroup>
	<thead>
	<tr>
		<th scope="col">순위</th>
		<th scope="col">도메인</th>
		<th scope="col">그래프</th>
		<th scope="col">접속자수</th>
		<th scope="col">비율(%)</th>
	</tr>
	</thead>
	<tfoot>
	<tr>
		<td colspan="3"><strong>합계</strong></td>
		<td><strong><?php echo number_format($sum_count); ?></strong></td>
		<td><strong>100</strong></td>
	</tr>
	</tfoot>
	<tbody class="list">
	<?php
	$i = 0;
	$save_count = -1;
	$tot_count = 0;
	if(count($arr)) {
		arsort($arr);
		foreach($arr as $key=>$value) {
			$count = $arr[$key];
			if($save_count != $count) {
				$i++;
				$no = $i;
				$save_count = $count;
			} else {
				$no = '';
			}

			if(!$key) {
				$key = '직접';
			}

			$rate = ($count / $sum_count * 100);
			$s_rate = number_format($rate, 1);

			$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td><?php echo $no; ?></td>
		<td class="tal"><?php echo $key; ?></td>
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
		echo '<tr><td colspan="5" class="empty_table">자료가 없습니다.</td></tr>';
	}
	?>
	</tbody>
	</table>
</div>
