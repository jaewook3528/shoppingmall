<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TB_THEME_PATH.'/aside_my.skin.php');
?>

<div id="con_lf">
	<h2 class="pg_tit">
		<span><?php echo $tb['title']; ?></span>
		<p class="pg_nav">HOME<i>&gt;</i>마이페이지<i>&gt;</i><?php echo $tb['title']; ?></p>
	</h2>

	<p class="pg_cnt">
		<em>총 <?php echo number_format($total_count); ?>건</em>의 포인트내역이 있습니다.
	</p>

	<div class="tbl_head02 tbl_wrap">
		<table>
		<colgroup>
			<col width="140">
			<col>
			<col width="100">
			<col width="100">
		</colgroup>
		<thead>
		<tr>
			<th scope="col">일시</th>
			<th scope="col">내용</th>
			<th scope="col">지급포인트</th>
			<th scope="col">사용포인트</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$sum_point1 = $sum_point2 = $sum_point3 = 0;

		for($i=0; $row=sql_fetch_array($result); $i++) {
			$point1 = $point2 = 0;
			if($row['po_point'] > 0) {
				$point1 = '+' .number_format($row['po_point']);
				$sum_point1 += $row['po_point'];
			} else {
				$point2 = number_format($row['po_point']);
				$sum_point2 += $row['po_point'];
			}
		?>
		<tr>
			<td class="tac"><?php echo $row['po_datetime']; ?></td>
			<td><?php echo $row['po_content']; ?></td>
			<td class="td_num"><?php echo $point1; ?></td>
			<td class="td_num"><?php echo $point2; ?></td>
		</tr>
		<?php
		}
		if($i == 0)
			echo '<tr><td colspan="4" class="empty_table">자료가 없습니다.</td></tr>';
		else {
			if($sum_point1 > 0)
				$sum_point1 = "+" . number_format($sum_point1);
			$sum_point2 = number_format($sum_point2);
		}
		?>
		</tbody>
		<tfoot>
		<tr>
			<th scope="row" colspan="2">소계</th>
			<td class="td_num fc_red"><?php echo $sum_point1; ?></td>
			<td class="td_num fc_red"><?php echo $sum_point2; ?></td>
		</tr>
		<tr>
			<th scope="row" colspan="2">보유포인트</th>
			<td class="td_num fc_red" colspan="2"><?php echo number_format($member['point']); ?></td>
		</tr>
		</tfoot>
		</table>
	</div>

	<?php
	echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?page=');
	?>
</div>
