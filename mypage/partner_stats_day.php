<?php
if(!defined('_TUBEWEB_')) exit;

$pg_title = "일별 가입통계분석";
include_once("./admin_head.sub.php");

if(!$year)  $year  = TB_TIME_YEAR;
if(!$month) $month = TB_TIME_MONTH;

$sql_where  = " where pt_id = '{$member['id']}' ";

$tot_count1 = sel_count("shop_member", "{$sql_where} and grade between 2 and 9 ");
$tot_count2 = sel_count("shop_member", "{$sql_where} and grade between 7 and 9 ");
$tot_count3 = sel_count("shop_member", "{$sql_where} and grade between 2 and 6 ");

$sql = " select MIN(reg_time) as min_year
		   from shop_member
		  where grade between 2 and 9 ";
$row = sql_fetch($sql);

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
	총 회원수 : <b><?php echo number_format($tot_count1); ?></b>명
	<span class="ov_a">
		일반회원 : <b><?php echo number_format($tot_count2); ?></b>명,
		가맹점회원 : <b><?php echo number_format($tot_count3); ?></b>명
	</span>
</div>
<div class="tbl_head01">
	<table>
	<colgroup>
		<col class="w100">
		<col>
		<col class="w60">
		<col class="w80">
		<col class="w80">
		<col class="w80">
	</colgroup>
	<thead>
	<tr>
		<th scope="col">날짜</th>
		<th scope="col">그래프</th>
		<th scope="col">비율%</th>
		<th scope="col">전체</th>
		<th scope="col">일반</th>
		<th scope="col">가맹점</th>
	</tr>
	</thead>
	<tbody class="list">
	<?php
	if(!$tot_count1) $tot_count1 = 1;

	for($i=1;$i<=31;$i++) {
		$day = sprintf("%02d", $i);
		$date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3", $year.$month.$day);

		$count1 = sel_count("shop_member", "{$sql_where} and left(reg_time,10)='$date' and grade between 2 and 9 ");
		$count2 = sel_count("shop_member", "{$sql_where} and left(reg_time,10)='$date' and grade between 7 and 9 ");
		$count3 = sel_count("shop_member", "{$sql_where} and left(reg_time,10)='$date' and grade between 2 and 6 ");

		$rate = ($count1 / $tot_count1 * 100);
		$s_rate = number_format($rate, 1);

		$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td><?php echo $date; ?></td>
		<td><div class="graph"><span class="bar" style="width:<?php echo $s_rate; ?>%"></span></div></td>
		<td><?php echo $s_rate; ?></td>
		<td><?php echo number_format($count1); ?></td>
		<td><?php echo number_format($count2); ?></td>
		<td><?php echo number_format($count3); ?></td>
	</tr>
	<?php
		// 오늘날짜와 같다면 중지
		if($date == TB_TIME_YMD) break;
	}
	?>
	</tbody>
	</table>
</div>

<?php
include_once("./admin_tail.sub.php");
?>