<?php
include_once("./_common.php");

if(!$od_id)
	alert_close("주문번호가 넘어오지 않았습니다.");

$sql = " select * from shop_order where od_id IN ({$od_id}) group by od_id order by index_no desc ";
$result = sql_query($sql);
$cnt = @sql_num_rows($result);
if(!$cnt)
	alert_close("출력할 자료가 없습니다.");

$tb['title'] = "주문내역";
include_once(TB_ADMIN_PATH."/admin_head.php");
?>

<div id="sodr_print_pop" class="new_win">
    <h1>주문서출력</h1>

	<?php
	for($i=0; $row=sql_fetch_array($result); $i++)
	{
		$amount = get_order_spay($row['od_id']); // 주문별 합계

		$od_addr = sprintf("(%s)", $row['zip']).' '.print_address($row['addr1'], $row['addr2'], $row['addr3'], $row['addr_jibeon']);
		$od_b_addr = sprintf("(%s)", $row['b_zip']).' '.print_address($row['b_addr1'], $row['b_addr2'], $row['b_addr3'], $row['b_addr_jibeon']);

		$od_addr  = ($od_addr) ? $od_addr : '입력안함';
		$od_tel   = ($row['telephone']) ? $row['telephone'] : '입력안함';
		$od_hp    = ($row['cellphone']) ? $row['cellphone'] : '입력안함';
		$od_b_tel = ($row['b_telephone']) ? $row['b_telephone'] : '입력안함';
		$od_b_hp  = ($row['b_cellphone']) ? $row['b_cellphone'] : '입력안함';

		// 보내는 사람과 받는 사람이 완전 일치하면 간단하게 출력
		// 보내는 사람과 받는 사람이 부분 일치하더라도 원래 내용을 모두 출력
        if($row['name'] == $row['b_name'] && $od_addr == $od_b_addr && $od_tel == $od_b_tel &&  $od_hp == $od_b_hp && $od_hp != "&nbsp;") $samesamesame = 1;
        else $samesamesame = '';

		$od_memo = ($row['memo']) ? get_text(stripslashes($row['memo'])) : '';
		$od_shop_memo = ($row['shop_memo']) ? get_text(stripslashes($row['shop_memo'])) : '';
	?>
	<!-- 반복시작 -->
	<div class="sodr_print_pop_list">
		<h2>주문번호 <?php echo $row['od_id']; ?></h2>
		<h3>주문하신 분 : <?php echo get_text($row['name']); ?></h3>
		<dl>
			<dt>주소</dt>
			<dd><?php echo get_text($od_addr); ?></dd>
			<dt>휴대폰</dt>
			<dd><?php echo get_text($od_hp); ?></dd>
			<dt>전화번호</dt>
			<dd><?php echo get_text($od_tel); ?></dd>
		</dl>
		<?php if($samesamesame) { ?>
		<p class="sodr_print_pop_same">보내는 사람과 받는 사람이 동일합니다.</p>
		<?php } else { ?>
		<h3>받으시는 분 : <?php echo get_text($row['b_name']); ?></h3>
		<dl>
			<dt>주소</dt>
			<dd><?php echo get_text($od_b_addr); ?></dd>
			<dt>휴대폰</dt>
			<dd><?php echo get_text($od_b_hp); ?></dd>
			<dt>전화번호</dt>
			<dd><?php echo get_text($od_b_tel); ?></dd>
		</dl>
		<?php } ?>

		<h3>주문 목록</h3>
		<div class="tbl_head01">
			<table id="sodr_list">
			<colgroup>
				<col>
				<col class="w90">
				<col class="w60">
				<col class="w90">
				<col class="w90">
			</colgroup>
			<thead>
			<tr>
				<th scope="col">주문상품</th>
				<th scope="col">판매가</th>
				<th scope="col">수량</th>
				<th scope="col">소계</th>
				<th scope="col">상태</th>
			</tr>
			</thead>
			<tbody>
			<?php
			$sub_tot_price1 = $sub_tot_price2 = $sub_tot_qty = 0;

			$sql2 = " select *
					    from shop_cart
					   where od_id = '{$row['od_id']}'
					   group by gs_id
					   order by io_type, index_no ";
			$res2 = sql_query($sql2);
			while($row2=sql_fetch_array($res2)) {
				$sodr = get_order($row2['od_no'], 'od_goods, dan');
				$gs = unserialize($sodr['od_goods']);

				$it_name = stripslashes($gs['gname']);
				$it_options = print_complete_options($row2['gs_id'], $row2['od_id']);
				if($it_options){
					$it_name .= '<div class="sod_opt">'.$it_options.'</div>';
				}

				// 합계금액 계산
				$sql = " select SUM(IF(io_type = 1, (io_price), (io_price + ct_price))) as price,
								SUM(IF(io_type = 1, (io_price * ct_qty), ((io_price + ct_price) * ct_qty))) as sell_price,
								SUM(IF(io_type = 1, 0, ct_qty)) as qty
						   from shop_cart
					      where gs_id = '{$row2['gs_id']}'
						    and od_id = '{$row2['od_id']}' ";
				$sum = sql_fetch($sql);

				$sub_tot_qty	+= $sum['qty'];
				$sub_tot_price1 += $sum['price'];
                $sub_tot_price2 += $sum['sell_price'];
			?>
			<tr>
				<td class="tal"><?php echo $it_name; ?></td>
				<td class="tar"><?php echo number_format($sum['price']); ?></td>
				<td class="tac"><?php echo number_format($sum['qty']); ?></td>
				<td class="tar"><?php echo number_format($sum['sell_price']); ?></td>
				<td class="tac"><?php echo $gw_status[$sodr['dan']]; ?></td>
			</tr>
			<?php
			}
			?>
            <tr>
                <td class="tac">합계</td>
                <td class="tar"><?php echo number_format($sub_tot_price1); ?></td>
                <td class="tac"><?php echo number_format($sub_tot_qty); ?></td>
				<td class="tar"><?php echo number_format($sub_tot_price2); ?></td>
                <td></td>
            </tr>
			</tbody>
            <tfoot>
            <tr>
                <td class="tar" colspan="5">
					(상품금액 : <?php echo display_price($amount['price']); ?> +
					배송비 : <?php echo display_price($amount['baesong']); ?>) -
					(쿠폰할인 : <?php echo display_price($amount['coupon']); ?> +
					포인트결제 : <?php echo display_price($amount['usepoint']); ?>) =
					총계 : <strong class="fc_red"><?php echo display_price($amount['useprice']); ?></strong>
				</td>
            </tr>
            </tfoot>
			</table>
		</div>
		<?php
        $tot_tot_qty     += $amount['qty'];
        $tot_tot_price   += $amount['buyprice'];
		$tot_tot_price2  += $amount['useprice'];
		$tot_tot_baesong += $amount['baesong'];

        if($od_memo)
			echo "<p class=\"padt10\"><strong>비고</strong> $od_memo</p>";

		if($od_shop_memo)
			echo "<p class=\"padt10\"><strong>메모</strong> $od_shop_memo</p>";
		?>
    </div>
    <!-- 반복 끝 -->
    <?php } ?>

    <div id="sodr_print_pop_total">
        <span>
            총 수량 <strong><?php echo number_format($tot_tot_qty); ?></strong>개,
            총 주문액 <strong><?php echo number_format($tot_tot_price); ?></strong>원,
			총 결제액 <strong><?php echo number_format($tot_tot_price2); ?></strong>원,
			총 배송비 <strong><?php echo number_format($tot_tot_baesong); ?></strong>원
        </span>
        &lt;출력 끝&gt;
    </div>
</div>

<script>print();</script>

</body>
</html>