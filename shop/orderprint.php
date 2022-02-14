<?php
include_once("./_common.php");

$tb['title'] = '주문내역인쇄';
include_once(TB_PATH."/head.sub.php");

$od = get_order($od_id); // 주문정보
if(!$od['od_id']) {
    alert_close("존재하는 주문이 아닙니다.");
}

$stotal = get_order_spay($od_id); // 총계
?>

<div style="margin:10px">
	<div class="tbl_head02 tbl_wrap">
		<table>
		<colgroup>
			<col>
			<col class="w100">
			<col class="w60">
			<col class="w80">
		</colgroup>
		<thead>
		<tr>
			<th scope="col">상품정보</th>
			<th scope="col">결제금액</th>
			<th scope="col">수량</th>
			<th scope="col">배송비</th>
		</tr>
		</thead>
		<tbody>
		<?php
		$sql = " select * 
				   from shop_cart 
				  where od_id = '$od_id' 
				  group by gs_id 
				  order by index_no ";	
		$result = sql_query($sql);
		for($i=0; $row=sql_fetch_array($result); $i++) {
			$rw = get_order($row['od_no']);
			$gs = unserialize($rw['od_goods']);

			$it_name = stripslashes($gs['gname']);
			$it_options = print_complete_options($row['gs_id'], $row['od_id']);
			if($it_options){
				$it_name .= '<div class="sod_opt">'.$it_options.'</div>';
			}
		?>
		<tr>
			<td><?php echo $it_name; ?></td>
			<td class="tar"><?php echo display_price($rw['use_price']); ?></td>
			<td class="tac"><?php echo display_qty($rw['sum_qty']); ?></td>
			<td class="tar"><?php echo display_price($rw['baesong_price']); ?></td>
		</tr>
		<?php } ?>
		</tbody>
		</table>
	</div>

	<dl id="sod_ws_tot">
		<dt class="bt_nolne">주문총액</dt>
		<dd class="bt_nolne"><strong><?php echo display_price($stotal['price']); ?></strong></dd>
		<?php if($stotal['coupon']) { ?>
		<dt>쿠폰할인</dt>
		<dd><strong><?php echo display_price($stotal['coupon']); ?></strong></dd>
		<?php } ?>
		<?php if($stotal['usepoint']) { ?>
		<dt>포인트결제</dt>
		<dd><strong><?php echo display_point($stotal['usepoint']); ?></strong></dd>
		<?php } ?>
		<?php if($stotal['baesong']) { ?>
		<dt>배송비</dt>
		<dd><strong><?php echo display_price($stotal['baesong']); ?></strong></dd>
		<?php } ?>
		<dt class="ws_price">총계</dt>
		<dd class="ws_price"><strong><?php echo display_price($stotal['useprice']); ?></strong></dd>
		<dt class="bt_nolne">포인트적립</dt>
		<dd class="bt_nolne"><strong><?php echo display_point($stotal['point']); ?></strong></dd>
	</dl>

	<section id="sod_fin_orderer">
		<h2 class="anc_tit">주문하신 분</h2>
		<div class="tbl_frm01 tbl_wrap">
			<table>
			<colgroup>
				<col class="w120">
				<col>
			</colgroup>
			<tr>
				<th scope="row">이 름</th>
				<td><?php echo get_text($od['name']); ?></td>
			</tr>
			<tr>
				<th scope="row">전화번호</th>
				<td><?php echo get_text($od['telephone']); ?></td>
			</tr>
			<tr>
				<th scope="row">핸드폰</th>
				<td><?php echo get_text($od['cellphone']); ?></td>
			</tr>
			<tr>
				<th scope="row">주 소</th>
				<td><?php echo get_text(sprintf("(%s)", $od['zip']).' '.print_address($od['addr1'], $od['addr2'], $od['addr3'], $od['addr_jibeon'])); ?></td>
			</tr>
			<tr>
				<th scope="row">E-mail</th>
				<td><?php echo get_text($od['email']); ?></td>
			</tr>
			</table>
		</div>
	</section>

	<section id="sod_fin_receiver">
		<h2 class="anc_tit">받으시는 분</h2>
		<div class="tbl_frm01 tbl_wrap">
			<table>
			<colgroup>
				<col class="w120">
				<col>
			</colgroup>
			<tr>
				<th scope="row">이 름</th>
				<td><?php echo get_text($od['b_name']); ?></td>
			</tr>
			<tr>
				<th scope="row">전화번호</th>
				<td><?php echo get_text($od['b_telephone']); ?></td>
			</tr>
			<tr>
				<th scope="row">핸드폰</th>
				<td><?php echo get_text($od['b_cellphone']); ?></td>
			</tr>
			<tr>
				<th scope="row">주 소</th>
				<td><?php echo get_text(sprintf("(%s)", $od['b_zip']).' '.print_address($od['b_addr1'], $od['b_addr2'], $od['b_addr3'], $od['b_addr_jibeon'])); ?></td>
			</tr>
			<?php if($od['memo']) { ?>
			<tr>
				<th scope="row">전하실 말씀</th>
				<td><?php echo conv_content($od['memo'], 0); ?></td>
			</tr>
			<?php } ?>
			</table>
		</div>
	</section>

	<div class="btn_confirm marb50">
		<a href='javascript:od_print();' class="btn_medium">인쇄</a>
		<a href='javascript:self.close();' class="btn_medium bx-white">닫기</a>
	</div>
</div>
<?php
include_once(TB_PATH."/tail.sub.php");
?>