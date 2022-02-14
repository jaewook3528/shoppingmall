<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<div id="smb_my">
	<p id="sod_fin_no">
		<strong><?php echo $member['name']; ?></strong>님의 쿠폰내역
	</p>

	<table class="navbar">
	<colgroup>
		<col width="50%">
		<col width="50%">
	</colgroup>
	<tbody>
	<tr>
		<td<?php echo $selected1; ?>><a href="<?php echo TB_MSHOP_URL; ?>/coupon.php">사용가능쿠폰</a></td>
		<td<?php echo $selected2; ?>><a href="<?php echo TB_MSHOP_URL; ?>/coupon.php?sca=1">사용/기한만료쿠폰</a></td>
	</tr>
	</tbody>
	</table>

	<?php
	if(!$total_count) {
		echo "<p class=\"empty_list\">자료가 없습니다.</p>";
	} else {
	?>
	<div class="my_list">
		<table class="my_box">
		<colgroup>
			<col width="50%">
			<col width="50%">
		</colgroup>
		<tbody>
		<?php
		for($i=0; $row=sql_fetch_array($result); $i++) {
			// 할인금액(율)
			if($row['cp_sale_type'] == '0') {
				if($row['cp_sale_amt_max'] > 0)
					$cp_sale_amt_max = "&nbsp;(최대 ".display_price($row['cp_sale_amt_max']).")";
				else
					$cp_sale_amt_max = "";

				$sale_amt = $row['cp_sale_percent']. '%' . $cp_sale_amt_max;
			} else {
				$sale_amt = display_price($row['cp_sale_amt']);
			}

			// 쿠폰 사용기한
			if($row['cp_inv_type'] == '0') {
				if($row['cp_inv_sdate'] == '9999999999') $cp_inv_sdate = '무제한';
				else $cp_inv_sdate = $row['cp_inv_sdate'];

				if($row['cp_inv_edate'] == '9999999999') $cp_inv_edate = '무제한';
				else $cp_inv_edate = $row['cp_inv_edate'];

				if($row['cp_inv_sdate'] == '9999999999' && $row['cp_inv_edate'] == '9999999999')
					$inv_date = '무제한';
				else
					$inv_date = $cp_inv_sdate . " ~ " . $cp_inv_edate;
			} else {
				$inv_date = '다운로드 후 ' . $row['cp_inv_day']. '일간';
			}
		?>
		<tr class="tit">
			<td class="mi_dt tal bold"><?php echo $row['cp_wdate']; ?></td>
			<td class="mi_at tar"><?php echo $u_part[$row['cp_use_part']]; ?></td>
		</tr>
		<tr>
			<td class="mi_dt tal" colspan="2"><?php echo get_text($row['cp_subject']); ?></td>
		</tr>
		<tr>
			<td class="mi_dt tal">할인금액(율)</td>
			<td class="mi_at tar"><?php echo $sale_amt; ?></td>
		</tr>
		<tr>
			<td class="mi_dt tal">사용기한</td>
			<td class="mi_at tar"><?php echo $inv_date; ?></td>
		</tr>
		<?php } ?>
		</tbody>
		</table>
	</div>
	<?php } ?>

	<?php 
	echo get_paging($config['mobile_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr.'&page=');
	?>
</div>
