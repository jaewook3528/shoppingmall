<?php
if(!defined('_TUBEWEB_')) exit;
?>

<div class="new_win">
    <h1 id="win_title"><?php echo $tb['title']; ?></h1>

	<div class="win_desc marb10">
		<p class="bx-danger">
			* 이 상품 구매시, 사용하실 수 있는 할인쿠폰입니다. 다운로드 받은 후 주문시 사용하세요!<br>
			* 발행된 쿠폰은 <b>[마이페이지 > 쿠폰관리]</b> 에서 확인 할 수 있습니다.
		</p>
	</div>
		
	<div class="tbl_head01 tbl_wrap bt_nolne">
		<table>
		<colgroup>
			<col class="w80">
			<col>
			<col class="w70">
		</colgroup>
		<tbody>
		<tr>
			<td class="tac"><?php echo get_it_image($gs['index_no'], $gs['simg1'], 60, 60); ?></td>
			<td class="td_name"><?php echo get_text($gs['gname']); ?></td>
			<td class="tac"><?php echo get_price($gs['index_no'])?></td>
		</tr>
		</tbody>
		</table>
	</div>	

	<div class="win_desc mart20">
		<p class="pg_cnt">
			<em>총 <?php echo number_format($total_count); ?>개</em> 조회
		</p>
	</div>

	<div class="tbl_head01 tbl_wrap">
		<table>
		<colgroup>
			<col>
			<col class="w90">
		</colgroup>
		<thead>
		<tr>
			<th scope="col">할인쿠폰명</th>
			<th scope="col">쿠폰받기</th>
		</tr>
		</thead>
		<tbody>
		<?php
		for($i=0; $row=sql_fetch_array($result); $i++) {
			$cp_id = $row['cp_id'];

			$str  = "";
			$str .= "<div>&#183; <strong>".get_text($row['cp_subject'])."</strong></div>";
			if($row['cp_explan'])
				$str .= "<div class='part5 fc_197'>&#183; ".get_text($row['cp_explan'])."</div>";

			// 동시사용 여부
			$str .= "<div class='part5 fc_eb7'>&#183; ";
			if(!$row['cp_dups']) {
				$str .= '동일한 주문건에 다른 쿠폰과 동시 사용가능';
			} else {
				$str .= '동일한 주문건에 다른 쿠폰과 동시 사용불가 (이 쿠폰만 사용가능)';
			}
			$str .= "</div>";

			// 쿠폰발행 기간
			$str .= "<div class='part5'>&#183; 다운로드 기간 : ";
			if($row['cp_type'] != '3') {
				if($row['cp_pub_sdate'] == '9999999999') $cp_pub_sdate = '';
				else $cp_pub_sdate = $row['cp_pub_sdate'];

				if($row['cp_pub_edate'] == '9999999999') $cp_pub_edate = '';
				else $cp_pub_edate = $row['cp_pub_edate'];

				if($row['cp_pub_sdate'] == '9999999999' && $row['cp_pub_edate'] == '9999999999')
					$str .= "제한없음";
				else
					$str .= $cp_pub_sdate." ~ ".$cp_pub_edate;

				// 쿠폰발행 요일
				if($row['cp_type'] == '1') {
					$str .= "&nbsp;-&nbsp;매주 (".$row['cp_week_day'].")";
				}
			} else {
				$str .= "생일 (".$row['cp_pub_sday']."일 전 ~ ".$row['cp_pub_eday']."일 이후까지)";
			}
			$str .= "</div>";


			// 쿠폰유효 기간
			$str .= "<div class='part5'>&#183; 쿠폰유효 기간 : ";
			if(!$row['cp_inv_type']) {
				// 날짜
				if($row['cp_inv_sdate'] == '9999999999') $cp_inv_sdate = '';
				else $cp_inv_sdate = $row['cp_inv_sdate'];

				if($row['cp_inv_edate'] == '9999999999') $cp_inv_edate = '';
				else $cp_inv_edate = $row['cp_inv_edate'];

				if($row['cp_inv_sdate'] == '9999999999' && $row['cp_inv_edate'] == '9999999999')
					$str .= '제한없음';
				else
					$str .= $cp_inv_sdate . " ~ " . $cp_inv_edate ;

				// 시간대
				$str .= "&nbsp;(시간대 : ";
				if($row['cp_inv_shour1'] == '99') $cp_inv_shour1 = '';
				else $cp_inv_shour1 = $row['cp_inv_shour1'] . "시부터";

				if($row['cp_inv_shour2'] == '99') $cp_inv_shour2 = '';
				else $cp_inv_shour2 = $row['cp_inv_shour2'] . "시까지";

				if($row['cp_inv_shour1'] == '99' && $row['cp_inv_shour2'] == '99') $str .= '제한없음';
				else $str .= $cp_inv_shour1 . " ~ " . $cp_inv_shour2 ;
				$str .= ")";
			} else {
				$cp_inv_day = date("Y-m-d",strtotime("+{$row[cp_inv_day]} days",time()));
				$str .= '다운로드 완료 후 ' . $row['cp_inv_day']. '일간 사용가능, 만료일('.$cp_inv_day.')';
			}
			$str .= "</div>";

			// 혜택
			$str .= "<div class='part5'>&#183; ";
			if(!$row['cp_sale_type']) {
				if($row['cp_sale_amt_max'] > 0)
					$cp_sale_amt_max = "&nbsp;(최대 ".display_price($row['cp_sale_amt_max'])."까지 할인)";
				else
					$cp_sale_amt_max = "";

				$str .= $row['cp_sale_percent']. '% 할인' . $cp_sale_amt_max;
			} else {
				$str .= display_price($row['cp_sale_amt']). ' 할인';
			}
			$str .= "</div>";

			// 최대금액
			if($row['cp_low_amt'] > 0) {
				$str .= "<div class='part5'>&#183; ".display_price($row['cp_low_amt'])." 이상 구매시</div>";
			}

			// 사용가능대상
			$str .= "<div class='part5'>&#183; ".$gw_usepart[$row['cp_use_part']]."</div>";

			$s_upd = "<a href=\"javascript:post_update('".TB_SHOP_URL."/pop_coupon_update.php', '$cp_id');\" class=\"btn_small red\">다운로드</a>";

			$bg = 'list'.($i%2);
		?>
		<tr class="<?php echo $bg; ?>">
			<td><?php echo $str; ?></td>
			<td class="tac"><?php echo $s_upd; ?></td>
		</tr>
		<?php
		}
		if($i==0)
			echo '<tr><td colspan="2" class="empty_list">자료가 없습니다.</td></tr>';
		?>
		</tbody>
		</table>
	</div>

	<?php
	echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?page=');
	?>

    <div class="win_btn">
		<a href="javascript:window.close();" class="btn_lsmall bx-white">창닫기</a>
    </div>
</div>

<script>
function post_update(action_url, val) {
	var f = document.fpost;
	f.cp_id.value = val;
	f.action = action_url;
	f.submit();
}
</script>

<form name="fpost" method="post">
<input type="hidden" name="gs_id" value="<?php echo $gs_id; ?>">
<input type="hidden" name="page"  value="<?php echo $page; ?>">
<input type="hidden" name="token" value="<?php echo $token; ?>">
<input type="hidden" name="cp_id" value="">
</form>
