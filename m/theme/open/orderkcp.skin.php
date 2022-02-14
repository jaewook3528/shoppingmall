<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

require_once(TB_MSHOP_PATH.'/settle_kcp.inc.php');

// 결제등록 요청시 사용할 입금마감일
$ipgm_date = date("Ymd", (TB_SERVER_TIME + 86400 * 5));
$tablet_size = "1.0"; // 화면 사이즈 조정 - 기기화면에 맞게 수정(갤럭시탭,아이패드 - 1.85, 스마트폰 - 1.0)
?>

<!-- kcp결제 시작 { -->
<div id="sod_fin">
	<section id="sod_fin_list">
        <h2>주문하실 상품</h2>
        <ul id="sod_list_inq" class="sod_list">
            <?php
			$goods = '';
			$good_info = '';
			$goods_count = -1;

			$sql = " select *
					   from shop_cart
					  where od_id = '$od_id'
					    and ct_select = '0'
					  group by gs_id
					  order by index_no ";
			$result = sql_query($sql);
            for($i=0; $row=sql_fetch_array($result); $i++) {
				$rw = get_order($row['od_no']);
				$gs = get_goods($row['gs_id'], 'gname,simg1');

				if(!$goods)
					$goods = preg_replace("/\?|\'|\"|\||\,|\&|\;/", "", $gs['gname']);

				$goods_count++;

				// 에스크로 상품정보
				if($default['de_escrow_use']) {
					if($i>0)
						$good_info .= chr(30);
					$good_info .= "seq=".($i+1).chr(31);
					$good_info .= "ordr_numb={$od_id}_".sprintf("%04d", ($i+1)).chr(31);
					$good_info .= "good_name=".addslashes($gs['gname']).chr(31);
					$good_info .= "good_cntx=".$rw['sum_qty'].chr(31);
					$good_info .= "good_amtx=".$rw['goods_price'].chr(31);
				}

				unset($it_name);
				$it_options = mobile_print_complete_options($row['gs_id'], $od_id);
				if($it_options){
					$it_name = '<div class="li_name_od">'.$it_options.'</div>';
				}
            ?>
            <li class="sod_li">
                <div class="li_opt"><?php echo get_text($gs['gname']); ?></div>
				<?php echo $it_name; ?>
                <div class="li_prqty">
                    <span class="prqty_price li_prqty_sp"><span>상품금액 </span><?php echo number_format($rw['goods_price']); ?></span>
                    <span class="prqty_qty li_prqty_sp"><span>수량 </span><?php echo number_format($rw['sum_qty']); ?></span>
                    <span class="prqty_sc li_prqty_sp"><span>배송비 </span><?php echo number_format($rw['baesong_price']); ?></span>
					<span class="prqty_stat li_prqty_sp"><span>상태 </span>주문대기</span>
                </div>
                <div class="li_total" style="padding-left:60px;height:auto !important;height:50px;min-height:50px;">
                    <span class="total_img"><?php echo get_od_image($rw['od_id'], $gs['simg1'], 50, 50); ?></span>
                    <span class="total_price total_span"><span>결제금액 </span><?php echo number_format($rw['use_price']); ?></span>
                    <span class="total_point total_span"><span>적립포인트 </span><?php echo number_format($rw['sum_point']); ?></span>
                </div>
            </li>
			<?php
			}

			if($goods_count) $goods .= ' 외 '.$goods_count.'건';

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
        </ul>

		<dl id="sod_bsk_tot">
            <dt class="sod_bsk_dvr"><span>주문총액</span></dt>
            <dd class="sod_bsk_dvr"><strong><?php echo display_price($stotal['price']); ?></strong></dd>

            <?php if($stotal['coupon']) { ?>
            <dt class="sod_bsk_dvr"><span>쿠폰할인</span></dt>
            <dd class="sod_bsk_dvr"><strong><?php echo display_price($stotal['coupon']); ?></strong></dd>
            <?php } ?>

            <?php if($stotal['usepoint']) { ?>
            <dt class="sod_bsk_dvr"><span>포인트결제</span></dt>
            <dd class="sod_bsk_dvr"><strong><?php echo display_point($stotal['usepoint']); ?></strong></dd>
            <?php } ?>

            <?php if($stotal['baesong']) { ?>
            <dt class="sod_bsk_dvr"><span>배송비</span></dt>
            <dd class="sod_bsk_dvr"><strong><?php echo display_price($stotal['baesong']); ?></strong></dd>
            <?php } ?>

            <dt class="sod_bsk_cnt"><span>총계</span></dt>
            <dd class="sod_bsk_cnt"><strong><?php echo display_price($stotal['useprice']); ?></strong></dd>

            <dt class="sod_bsk_point"><span>포인트적립</span></dt>
            <dd class="sod_bsk_point"><strong><?php echo display_point($stotal['point']); ?></strong></dd>
        </dl>
    </section>

	<?php
	// 결제대행사별 코드 include (스크립트 등)
	require_once(TB_MSHOP_PATH.'/kcp/orderform.1.php');
	?>

	<form id="forderform" name="forderform" method="post" action="<?php echo $order_action_url; ?>" autocomplete="off">

	<section id="sod_fin_orderer">
		<h3 class="anc_tit">주문하시는 분</h3>
		<div  class="odf_tbl">
			<table>
			<colgroup>
				<col class="w70">
				<col>
			</colgroup>
			<tbody>
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
			</tbody>
			</table>
		</div>
	</section>

	<section id="sod_fin_receiver">
		<h3 class="anc_tit">받으시는 분</h3>
		<div  class="odf_tbl">
			<table>
			<colgroup>
				<col class="w70">
				<col>
			</colgroup>
			<tbody>
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
			</tbody>
			</table>
		</div>
	</section>

	<?php
	// 결제대행사별 코드 include (결제대행사 정보 필드)
	require_once(TB_MSHOP_PATH.'/kcp/orderform.2.php');
	?>

    <div id="show_progress" style="display:none;">
        <img src="<?php echo TB_MSHOP_URL; ?>/img/loading.gif" alt="">
        <span>주문완료 중입니다. 잠시만 기다려 주십시오.</span>
    </div>

	</form>
</div>

<script>
/* 결제방법에 따른 처리 후 결제등록요청 실행 */
function pay_approval()
{
    var f = document.sm_form;
    var pf = document.forderform;

    // 금액체크
    if(!payment_check(pf))
        return false;

	f.buyr_name.value = pf.od_name.value;
	f.buyr_mail.value = pf.od_email.value;
	f.buyr_tel1.value = pf.od_tel.value;
	f.buyr_tel2.value = pf.od_hp.value;
	f.rcvr_name.value = pf.od_b_name.value;
	f.rcvr_tel1.value = pf.od_b_tel.value;
	f.rcvr_tel2.value = pf.od_b_hp.value;
	f.rcvr_mail.value = pf.od_email.value;
	f.rcvr_zipx.value = pf.od_b_zip.value;
	f.rcvr_add1.value = pf.od_b_addr1.value;
	f.rcvr_add2.value = pf.od_b_addr2.value;
	if(f.settle_method.value == "간편결제")
		f.payco_direct.value = "Y";
	else
		f.payco_direct.value = "";

	// 주문 정보 임시저장
	var order_data = $(pf).serialize();
	var save_result = "";
	$.ajax({
		type: "POST",
		data: order_data,
		url: tb_url+"/shop/ajax.orderdatasave.php",
		cache: false,
		async: false,
		success: function(data) {
			save_result = data;
		}
	});

	if(save_result) {
		alert(save_result);
		return false;
	}

	f.submit();

    return false;
}

function forderform_check()
{
    var f = document.forderform;

    // 금액체크
    if(!payment_check(f))
        return false;

    if(f.res_cd.value != "0000") {
        alert("결제등록요청 후 주문해 주십시오.");
        return false;
    }

    document.getElementById("display_pay_button").style.display = "none";
    document.getElementById("show_progress").style.display = "block";

    setTimeout(function() {
        f.submit();
    }, 300);
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
<!-- } kcp결제 끝 -->
