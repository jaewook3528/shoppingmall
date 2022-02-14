<?php
if(!defined('_TUBEWEB_')) exit;

require_once(TB_SHOP_PATH.'/settle_allat.inc.php');

// 결제대행사별 코드 include (스크립트 등)
require_once(TB_SHOP_PATH.'/allat/orderform.1.php');
?>

<!-- kg올앳 결제 시작 { -->
<p><img src="<?php echo TB_IMG_URL; ?>/orderform.gif"></p>

<p class="pg_cnt mart20">
	※ 주문하실 상품 내역에 <em>수량 및 주문금액</em>이 틀리지 않는지 반드시 확인하시기 바랍니다.
</p>

<div class="tbl_head02 tbl_wrap">
	<table>
	<colgroup>
		<col class="w120">
		<col>
		<col class="w60">
		<col class="w90">
		<col class="w90">
		<col class="w90">
		<col class="w90">
	</colgroup>
	<thead>
	<tr>
		<th scope="col">이미지</th>
		<th scope="col">상품/옵션정보</th>
		<th scope="col">수량</th>
		<th scope="col">상품금액</th>
		<th scope="col">소계</th>
		<th scope="col">포인트</th>
		<th scope="col">배송비</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$goods_nm = array();
	$goods_cd = array();

	$sql = " select *
			   from shop_cart
			  where od_id = '$od_id'
				and ct_select = '0'
			  group by gs_id
			  order by index_no ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$rw = get_order($row['od_no']);
		$gs = get_goods($row['gs_id'], 'gname,gcode,simg1');

		$goods_nm[] = preg_replace("/\'|\"|\||\,|\&|\;/", "", $gs['gname']);
		$goods_cd[] = preg_replace("/\'|\"|\||\,|\&|\;/", "", $gs['gcode']);

		$it_name = stripslashes($gs['gname']);
		$it_options = print_complete_options($row['gs_id'], $od_id);
		if($it_options){
			$it_name .= '<div class="sod_opt">'.$it_options.'</div>';
		}
	?>
	<tr>
		<td class="tac"><?php echo get_it_image($row['gs_id'], $gs['simg1'], 80, 80); ?></td>
		<td class="td_name"><?php echo $it_name; ?></td>
		<td class="tac"><?php echo number_format($rw['sum_qty']); ?></td>
		<td class="tar"><?php echo number_format($rw['goods_price']); ?></td>
		<td class="tar"><?php echo number_format($rw['use_price']); ?></td>
		<td class="tar"><?php echo number_format($rw['sum_point']); ?></td>
		<td class="tar"><?php echo number_format($rw['baesong_price']); ?></td>
	</tr>
	<?php
	}

	$product_nm = implode('||', $goods_nm);
	$product_cd = implode('||', $goods_cd);

	// 복합과세처리
	$comm_tax_mny  = 0; // 과세금액
	$comm_vat_mny  = 0; // 부가세
	$comm_free_mny = 0; // 면세금액
	if($default['de_tax_flag_use']) {
		$info = comm_tax_flag($od_id);
		$comm_tax_mny  = $info['comm_tax_mny'];
		$comm_vat_mny  = $info['comm_vat_mny'];
		$comm_free_mny = $info['comm_free_mny'];
	}
	?>
	</tbody>
	<tfoot>
	<tr>
		<td class="tar" colspan="7">
			(상품금액 : <strong><?php echo display_price($stotal['price']); ?></strong> +
			배송비 : <strong><?php echo display_price($stotal['baesong']); ?></strong>) -
			(쿠폰할인 : <strong><?php echo display_price($stotal['coupon']); ?></strong> +
			포인트결제 : <strong><?php echo display_price($stotal['usepoint']); ?></strong>) =
			총계 : <strong class="fc_red"><?php echo display_price($stotal['useprice']); ?></strong>
		</td>
	</tr>
	</tfoot>
	</table>
</div>

<form name="fm" id="fm" method="post" action="<?php echo $order_action_url; ?>" autocomplete="off">

<?php
// 결제대행사별 코드 include (결제대행사 정보 필드)
require_once(TB_SHOP_PATH.'/allat/orderform.2.php');
?>

<section id="sod_fin_orderer">
	<h2 class="anc_tit">주문하시는 분</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col width="140">
			<col>
		</colgroup>
		<tr>
			<th scope="row">이름</th>
			<td><?php echo $od['name']; ?></td>
		</tr>
		<tr>
			<th scope="row">전화번호</th>
			<td><?php echo $od['telephone']; ?></td>
		</tr>
		<tr>
			<th scope="row">핸드폰</th>
			<td><?php echo $od['cellphone']; ?></td>
		</tr>
		<tr>
			<th scope="row">주소</th>
			<td><?php echo print_address($od['addr1'], $od['addr2'], $od['addr3'], $od['addr_jibeon']); ?></td>
		</tr>
		<tr>
			<th scope="row">E-mail</th>
			<td><?php echo $od['email']; ?></td>
		</tr>
		</table>
	</div>
</section>

<section id="sod_fin_receiver">
	<h2 class="anc_tit">받으시는 분</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tr>
			<th scope="row">이름</th>
			<td><?php echo $od['b_name']; ?></td>
		</tr>
		<tr>
			<th scope="row">전화번호</th>
			<td><?php echo $od['b_telephone']; ?></td>
		</tr>
		<tr>
			<th scope="row">핸드폰</th>
			<td><?php echo $od['b_cellphone']; ?></td>
		</tr>
		<tr>
			<th scope="row">주소</th>
			<td><?php echo print_address($od['b_addr1'], $od['b_addr2'], $od['b_addr3'], $od['b_addr_jibeon']); ?></td>
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

<section id="sod_fin_pay">
	<h2 class="anc_tit">결제정보</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tr>
			<th scope="row">결제방법</th>
			<td><?php echo $od['paymethod']; ?></td>
		</tr>
		<tr>
			<th scope="row">결제금액</th>
			<td class="fs14 bold"><?php echo display_price($tot_price); ?></td>
		</tr>
		</table>
	</div>
</section>

<div id="display_pay_button" class="btn_confirm">
	<input type="button" value="결제하기" onclick="forderform_check(this.form);" class="btn_large wset">
    <a href="<?php echo TB_URL; ?>" class="btn_large bx-white">취소</a>
</div>
<div id="display_pay_process" style="display:none">
    <img src="<?php echo TB_IMG_URL; ?>/ajax-loader.gif" alt="">
    <span>주문완료 중입니다. 잠시만 기다려 주십시오.</span>
</div>

</form>

<script>
function forderform_check(f)
{
    // 금액체크
    if(!payment_check(f))
        return false;

    switch(f.od_settle_case.value)
    {
        case "계좌이체":
            f.allat_bank_yn.value = "Y";
            break;
        case "가상계좌":
            f.allat_vbank_yn.value = "Y";
            break;
        case "휴대폰":
            f.allat_hp_yn.value = "Y";
            break;
        case "신용카드":
            f.allat_card_yn.value = "Y";
            break;
    }

	ftn_approval( f );
}

// 결제체크
function payment_check(f)
{
    var tot_price = parseInt(f.good_mny.value);

	if(f.od_settle_case.value == '계좌이체') {
		if(tot_price < 150) {
			alert("계좌이체는 150원 이상 결제가 가능합니다.");
			return false;
		}
	}

    if(f.od_settle_case.value == '신용카드') {
		if(false && tot_price < 1000) {
			alert("신용카드는 1000원 이상 결제가 가능합니다.");
			return false;
		}
    }

	if(f.od_settle_case.value == '휴대폰') {
		if(tot_price < 350) {
			alert("휴대폰은 350원 이상 결제가 가능합니다.");
			return false;
		}
    }

    return true;
}
</script>
<!-- } kg올앳 결제 끝 -->
