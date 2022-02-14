<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

require_once(TB_SHOP_PATH.'/settle_kakaopay.inc.php');

include_once(TB_SHOP_PATH.'/psys/PsysPay.php');

?>

<!-- 주문서작성 시작 { -->
<div id="sod_approval_frm">
<?php
ob_start();
?>
    <p>주문하실 상품을 확인하세요.</p>

    <ul class="sod_list">
		<?php
		$tot_point = 0;
		$tot_sell_price = 0;
		$tot_opt_price = 0;
		$tot_sell_qty = 0;
		$tot_sell_amt = 0;
		$seller_id = array();
        $sellers_price = array();

		$goods_psys_long_no_use_cnt = 0; // 장기무이자가 아닌 제품 주문 카운드

		$sql = " select *
				   from shop_cart
				  where index_no IN ({$ss_cart_id})
					and ct_select = '0'
				  group by gs_id
				  order by index_no ";
		$result = sql_query($sql);
		for($i=0; $row=sql_fetch_array($result); $i++) {
			$gs = get_goods($row['gs_id']);

        if( ! PsysPay::checkLongUse($gs) ) {
            $goods_psys_long_no_use_cnt++;
        }
            $where_add = get_cart_mode('select','','where');
			// 합계금액 계산
			$sql = " select SUM(IF(io_type = 1, (io_price * ct_qty), ((io_price + ct_price) * ct_qty))) as price,
							SUM(IF(io_type = 1, (io_price * ct_qty), ((io_price + ct_supply_price) * ct_qty))) as supply_price,
							SUM(IF(io_type = 1, (0),(ct_point * ct_qty))) as point,
							SUM(IF(io_type = 1, (0),(ct_qty))) as qty,
							SUM(io_price * ct_qty) as opt_price
					   from shop_cart
					  where gs_id = '$row[gs_id]'
				            {$where_add}
						and ct_select = '0'";
			$sum = sql_fetch($sql);

			$it_name = '<strong>'.stripslashes($gs['gname']).'</strong>';
		$it_options = print_item_options($row['gs_id']);
			if($it_options){
				$it_name .= '<div class="sod_opt">'.$it_options.'</div>';
			}

			$it_name .= PsysPay::getLongUseStr($gs);

			$point = $sum['point'];
			$supply_price = $sum['supply_price'];
			$sell_price = $sum['price'];
			$sell_opt_price = $sum['opt_price'];
			$sell_qty = $sum['qty'];
			$sell_amt = $sum['price'] - $sum['opt_price'];

			// 회원이 아니면 포인트초기화
			if(!$is_member) $point = 0;

			// 배송비
			if($gs['use_aff'])
				$sr = get_partner($gs['mb_id']);
			else
				$sr = get_seller_cd($gs['mb_id']);

			$info = get_item_sendcost($sell_price);
			$item_sendcost[] = $info['pattern'];

            $del_type = explode("|",$info['pattern']);
            if($del_type[1]==='묶음') $sellers_price[$sr['seller_code']] += $sell_price;

			$seller_id[$i] = $gs['mb_id'];

			$href = TB_MSHOP_URL.'/view.php?gs_id='.$row['gs_id'];
		?>

        <li class="sod_li">
			<input type="hidden" name="gs_id[<?php echo $i; ?>]" value="<?php echo $row['gs_id']; ?>">
			<input type="hidden" name="gs_notax[<?php echo $i; ?>]" value="<?php echo $gs['notax']; ?>">
			<input type="hidden" name="gs_price[<?php echo $i; ?>]" value="<?php echo $sell_price; ?>">
			<input type="hidden" name="seller_id[<?php echo $i; ?>]" value="<?php echo $gs['mb_id']; ?>">
			<input type="hidden" name="supply_price[<?php echo $i; ?>]" value="<?php echo $supply_price; ?>">
			<input type="hidden" name="sum_point[<?php echo $i; ?>]" value="<?php echo $point; ?>">
			<input type="hidden" name="sum_qty[<?php echo $i; ?>]" value="<?php echo $sell_qty; ?>">
			<input type="hidden" name="cart_id[<?php echo $i; ?>]" value="<?php echo $row['od_no']; ?>">

			<div class="li_name">
                <?php echo $it_name; ?>
                <div class="li_mod" style="padding-left:100px;"></div>
                <span class="total_img"><?php echo get_it_image($row['gs_id'], $gs['simg1'], 80, 80); ?></span>
            </div>
            <div class="li_prqty">
                <span class="prqty_price li_prqty_sp"><span>판매가</span>
				<?php echo number_format(($sell_price/$sell_qty)); ?></span>
                <span class="prqty_qty li_prqty_sp"><span>수량</span>
				<?php echo number_format($sell_qty); ?></span>
                <span class="prqty_sc li_prqty_sp"><span>배송비</span>
				<?php echo number_format($info['price']); ?></span>
            </div>
            <div class="li_total">
                <span class="total_price total_span"><span>소계</span>
				<strong><?php echo number_format($sell_price); ?></strong></span>
                <span class="total_point total_span"><span>적립포인트</span>
				<strong><?php echo number_format($point); ?></strong></span>
            </div>
        </li>

        <?php
			$tot_point += (int)$point;
			$tot_sell_price += (int)$sell_price;
			$tot_opt_price += (int)$sell_opt_price;
			$tot_sell_qty += (int)$sell_qty;
			$tot_sell_amt += (int)$sell_amt;
        } // for 끝

		// 배송비 검사
		$send_cost = 0;
		$com_send_cost = 0;
		$sep_send_cost = 0;
		$max_send_cost = 0;

		$k = 0;
		$condition = array();
		foreach($item_sendcost as $key) {
			list($userid, $bundle, $price) = explode('|', $key);
			$condition[$userid][$bundle][$k] = $price;
			$k++;
		}

		$com_array = array();
		$val_array = array();
		foreach($condition as $key=>$value) {
			if($condition[$key]['묶음']) {
                if(chkFreeDelivery($sellers_price[$key],$sr)) {
                    $max_send_cost += 0; // 가장 큰 배송비 합산
                    $com_array[] = ""; // max key
                    $val_array[] = "";// max value
                }else{
                    $max_send_cost += max($condition[$key]['묶음']); // 가장 큰 배송비 합산
                    $com_array[] = max(array_keys($condition[$key]['묶음'])); // max key
                    $val_array[] = max(array_values($condition[$key]['묶음']));// max value
                }
                $com_send_cost += array_sum($condition[$key]['묶음']); // 묶음배송 합산
			}
			if($condition[$key]['개별']) {
				$sep_send_cost += array_sum($condition[$key]['개별']); // 묶음배송불가 합산
				$com_array[] = array_keys($condition[$key]['개별']); // 모든 배열 key
				$val_array[] = array_values($condition[$key]['개별']); // 모든 배열 value
			}
		}

		$baesong_price = get_tune_sendcost($com_array, $val_array);

		$send_cost = $com_send_cost + $sep_send_cost; // 총 배송비합계
		$tot_send_cost = $max_send_cost + $sep_send_cost; // 최종배송비
		$tot_final_sum = $send_cost - $tot_send_cost; // 배송비할인
		$tot_price = $tot_sell_price + $tot_send_cost; // 결제예정금액

        if($i == 0) {
            alert('장바구니가 비어 있습니다.', TB_MSHOP_URL.'/cart.php');
        }
        ?>
    </ul>

    <dl id="sod_bsk_tot">
        <dt class="sod_bsk_sell"><span>주문</span></dt>
        <dd class="sod_bsk_sell"><strong><?php echo number_format($tot_sell_price); ?> 원</strong></dd>
        <dt class="sod_bsk_dvr"><span>배송비</span></dt>
        <dd class="sod_bsk_dvr"><strong><?php echo number_format($tot_send_cost); ?> 원</strong></dd>
        <dt class="sod_bsk_cnt"><span>총계</span></dt>
        <dd class="sod_bsk_cnt"><strong><?php echo number_format($tot_price); ?> 원</strong></dd>
        <dt class="sod_bsk_point"><span>포인트</span></dt>
        <dd class="sod_bsk_point"><strong><?php echo number_format($tot_point); ?> P</strong></dd>
    </dl>

<?php
$content = ob_get_contents();
ob_end_clean();
?>
</div>

<div id="sod_frm">
	<form name="buyform" id="buyform" method="post" action="<?php echo $order_action_url; ?>" onsubmit="return fbuyform_submit(this);" autocomplete="off">
	<input type="hidden" name="ss_cart_id" value="<?php echo $ss_cart_id; ?>">
	<input type="hidden" name="mb_point" value="<?php echo $member['point']; ?>">
	<input type="hidden" name="pt_id" value="<?php echo $mb_recommend; ?>">
	<input type="hidden" name="shop_id" value="<?php echo $pt_id; ?>">
	<input type="hidden" name="coupon_total" value="0">
	<input type="hidden" name="coupon_price" value="">
	<input type="hidden" name="coupon_lo_id" value="">
	<input type="hidden" name="coupon_cp_id" value="">
	<input type="hidden" name="baesong_price" value="<?php echo $baesong_price; ?>">
	<input type="hidden" name="baesong_price2" value="0">
	<input type="hidden" name="org_price" value="<?php echo $tot_price; ?>">
	<?php if(!$is_member || !$config['usepoint_yes']) { ?>
	<input type="hidden" name="use_point" value="0">
	<?php } ?>

	<?php echo $content; ?>

	<section id="sod_frm_orderer">
		<h2 class="anc_tit">주문하시는 분</h2>
		<div class="odf_tbl">
			<table>
			<tbody>
			<?php if(!$is_member) { // 비회원이면 ?>
			<tr>
				<th scope="row">비밀번호</th>
				<td>
					<input type="password" name="od_pwd" required class="frm_input required" maxlength="20">
					<span class="frm_info">영,숫자 3~20자 (주문서 조회시 필요)</span>
				</td>
			</tr>
			<?php } ?>
            <tr>
				<th scope="row">이름</th>
                <td><input type="text" name="name" value="<?php echo $member['name']; ?>" required class="frm_input required" maxlength="20"></td>
            </tr>
			<tr>
				<th scope="row">핸드폰</th>
				<td><input type="text" name="cellphone" value="<?php echo $member['cellphone']; ?>" required class="frm_input required" maxlength="20"></td>
			</tr>
			<tr>
				<th scope="row">전화번호</th>
				<td><input type="text" name="telephone" value="<?php echo $member['telephone']; ?>" class="frm_input" maxlength="20"></td>
			</tr>
			<tr>
				<th scope="row">주소</th>
				<td>
                    <input type="text" name="zip" value="<?php echo $member['zip']; ?>" required class="frm_input required" size="5" maxlength="5">
                    <button type="button" onclick="win_zip('buyform', 'zip', 'addr1', 'addr2', 'addr3', 'addr_jibeon');" class="btn_small grey">주소검색</button><br>
                    <input type="text" name="addr1" value="<?php echo $member['addr1']; ?>" required class="frm_input frm_address required"><br>
                    <input type="text" name="addr2" value="<?php echo $member['addr2']; ?>" class="frm_input frm_address"><br>
                    <input type="text" name="addr3" value="<?php echo $member['addr3']; ?>" class="frm_input frm_address" readonly><br>
                    <input type="hidden" name="addr_jibeon" value="<?php echo $member['addr_jibeon']; ?>">
				</td>
			</tr>
			<tr>
				<th scope="row">E-mail</th>
				<td><input type="text" name="email" value="<?php echo $member['email']; ?>" required class="frm_input required wfull"></td>
			</tr>
			</tbody>
			</table>
		</div>
	</section>

	<section id="sod_frm_taker">
		<h2 class="anc_tit">받으시는 분</h2>
		<div class="odf_tbl">
			<table>
			<tbody>
			<tr>
				<th scope="row">배송지선택</th>
				<td>
					<input type="radio" name="ad_sel_addr" value="1" id="sel_addr1" class="css-checkbox lrg">
					<label for="sel_addr1" class="css-label padr5">주문자와 동일</label><br>
					<input type="radio" name="ad_sel_addr" value="2" id="sel_addr2" class="css-checkbox lrg">
					<label for="sel_addr2" class="css-label">신규배송지</label>
					<?php if($is_member) { ?>
					<br><input type="radio" name="ad_sel_addr" value="3" id="sel_addr3" class="css-checkbox lrg">
					<label for="sel_addr3" class="css-label">배송지목록</label>
					<?php } ?>
				</td>
			</tr>
			<tr>
				<th scope="row">이름</th>
				<td><input type="text" name="b_name" required class="frm_input required"></td>
			</tr>
			<tr>
				<th scope="row">핸드폰</th>
				<td><input type="text" name="b_cellphone" required class="frm_input required"></td>
			</tr>
			<tr>
				<th scope="row">전화번호</th>
				<td><input type="text" name="b_telephone" class="frm_input"></td>
			</tr>
			<tr>
				<th scope="row">주소</th>
				<td>
                    <input type="text" name="b_zip" required class="frm_input required" size="5" maxlength="5">
                    <button type="button" onclick="win_zip('buyform', 'b_zip', 'b_addr1', 'b_addr2', 'b_addr3', 'b_addr_jibeon');" class="btn_small grey">주소검색</button><br>
                    <input type="text" name="b_addr1" required class="frm_input frm_address required"><br>
                    <input type="text" name="b_addr2" class="frm_input frm_address"><br>
                    <input type="text" name="b_addr3" class="frm_input frm_address" readonly><br>
					<input type="hidden" name="b_addr_jibeon" value="">
				</td>
			</tr>
			<tr>
				<th scope="row">전하실말씀</th>
				<td>
					<select name="sel_memo" class="wfull">
						<option value="">요청사항 선택하기</option>
						<option value="부재시 경비실에 맡겨주세요.">부재시 경비실에 맡겨주세요</option>
						<option value="빠른 배송 부탁드립니다.">빠른 배송 부탁드립니다.</option>
						<option value="부재시 핸드폰으로 연락바랍니다.">부재시 핸드폰으로 연락바랍니다.</option>
						<option value="배송 전 연락바랍니다.">배송 전 연락바랍니다.</option>
					</select>
					<div class="padt5">
						<textarea name="memo" id="memo" class="frm_textbox"></textarea>
					</div>
				</td>
			</tr>
			</tbody>
			</table>
		</div>
	</section>

	<?php
	$escrow_title = "";
	if($default['de_escrow_use']) {
		$escrow_title = "에스크로 ";
	}

	$multi_settle = '';
	if($is_kakaopay_use)
		$multi_settle .= "<option value='KAKAOPAY'>카카오페이</option>\n";
	if($default['de_bank_use'])
		$multi_settle .= "<option value='무통장'>무통장입금</option>\n";
	if($default['de_card_use'])
		$multi_settle .= "<option value='신용카드'>신용카드</option>\n";
	if($default['de_hp_use'])
		$multi_settle .= "<option value='휴대폰'>휴대폰</option>\n";
	if($default['de_iche_use'])
		$multi_settle .= "<option value='계좌이체'>".$escrow_title."계좌이체</option>\n";
	if($default['de_vbank_use'])
		$multi_settle .= "<option value='가상계좌'>".$escrow_title."가상계좌</option>\n";
	if($is_member && $config['usepoint_yes'] && ($tot_price <= $member['point']))
		$multi_settle .= "<option value='포인트'>포인트결제</option>\n";

    $multi_settle .= PsysPay::printPaymethodFormMo('html', $goods_psys_long_no_use_cnt);

	// PG 간편결제
	if($default['de_easy_pay_use']) {
		switch($default['de_pg_service']) {
			case 'lg':
				$pg_easy_pay_name = 'PAYNOW';
				break;
			case 'inicis':
				$pg_easy_pay_name = 'KPAY';
				break;
			case 'kcp':
				$pg_easy_pay_name = 'PAYCO';
				break;
		}
		if($pg_easy_pay_name) {
			$multi_settle .= "<option value='간편결제'>{$pg_easy_pay_name}</option>\n";
		}
	}

	// 이니시스를 사용중일때만 삼성페이 결제가능
	if($default['de_samsung_pay_use'] && ($default['de_pg_service'] == 'inicis')) {
		$multi_settle .= "<option value='삼성페이'>삼성페이</option>\n";
	}
	?>

	<section id="sod_frm_pay">
		<h2 class="anc_tit">결제정보 입력</h2>
		<div class="odf_tbl">
			<table>
			<tbody>
			<tr>
				<th scope="row">결제방법</th>
				<td>
					<select name="paymethod" onchange="calculate_paymethod(this.value);" class="wfull">
						<option value="">선택하기</option>
						<?php echo $multi_settle; ?>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row">합계</th>
				<td><strong><?php echo display_price($tot_price); ?></strong></td>
			</tr>
			<tr>
				<th scope="row">추가배송비</th>
				<td>
					<strong><span id="send_cost2">0</span>원</strong>
					<span class="fc_999">(지역에 따라 추가되는 배송비)</span>
				</td>
			</tr>
			<?php
			if($is_member && $config['coupon_yes']) { // 보유쿠폰
				$sp_count = get_cp_precompose($member['id']);
			?>
			<tr>
				<th scope="row">할인쿠폰</th>
				<td>
					<span id="dc_coupon"><a href="javascript:window.open('<?php echo TB_MSHOP_URL; ?>/ordercoupon.php');" class="btn_small bx-red">사용 가능 쿠폰 <?php echo $sp_count[3]; ?>장</a>&nbsp;</span>(-)&nbsp;&nbsp;<strong><span id="dc_amt">0</strong>원&nbsp;<span id="dc_cancel" style="display:none;"><a href="javascript:coupon_cancel();" class="btn_small grey">삭제</a></span></span>
				</td>
			</tr>
			<?php } ?>
			<?php
			if($is_member && $config['usepoint_yes']) { ?>
			<tr>
				<th scope="row">포인트결제</th>
				<td>
					<input type="text" name="use_point" value="0" onkeyup="calculate_temp_point(this.value); this.value=number_format(this.value);" class="frm_input w100"> 원
					<div>잔액 : <b><?php echo display_point($member['point']); ?></b> (<?php echo display_point($config['usepoint']); ?> 부터 사용가능)</div>
				</td>
			</tr>
			<?php } ?>
			<tr>
				<th scope="row">총 결제금액</th>
				<td>
					<input type="text" name="tot_price" value="<?php echo number_format($tot_price); ?>" class="frm_input w100" readonly style="background:#f1f1f1;color:red;font-weight:bold;"> 원
				</td>
			</tr>
			</tbody>
			</table>
		</div>
	</section>

	<section id="bank_section" style="display:none;">
		<h2 class="anc_tit">입금하실 계좌</h2>
		<div class="odf_tbl">
			<table>
			<tbody>
			<tr>
				<th scope="row">무통장계좌</th>
				<td>
					<?php echo mobile_bank_account("bank"); ?>
				</td>
			</tr>
			<tr>
				<th scope="row">입금자명</th>
				<td><input type="text" name="deposit_name" value="<?php echo $member['name']; ?>" class="frm_input w100"></td>
			</tr>
			</tbody>
			</table>
		</div>
	</section>

	<section id="taxsave_section" style="display:none;">
		<h2 class="anc_tit">증빙서류 발급</h2>
		<div class="odf_tbl">
			<table>
			<tbody>
			<tr>
				<th scope="row">현금영수증</th>
				<td>
					<select name="taxsave_yes" onchange="tax_save(this.value);" class="wfull">
						<option value="N">발행안함</option>
						<option value="Y">개인 소득공제용</option>
						<option value="S">사업자 지출증빙용</option>
					</select>
					<div id="taxsave_fld_1" style="display:none;">
						<input type="text" name="tax_hp" class="frm_input frm_address" placeholder="핸드폰번호">
					</div>
					<div id="taxsave_fld_2" style="display:none;">
						<input type="text" name="tax_saupja_no" class="frm_input frm_address" placeholder="사업자등록번호">
					</div>
				</td>
			</tr>
			<tr>
				<th scope="row">세금계산서</th>
				<td>
					<select name="taxbill_yes" onchange="tax_bill(this.value);" class="wfull">
						<option value="N">발행안함</option>
						<option value="Y">발행요청</option>
					</select>
					<div id="taxbill_section" style="display:none;">
						<input type="text" name="company_saupja_no" class="frm_input frm_address" placeholder="사업자등록번호"><br>
						<input type="text" name="company_name" class="frm_input frm_address" placeholder="상호(법인명)"><br>
						<input type="text" name="company_owner" class="frm_input frm_address" placeholder="대표자명"><br>
						<input type="text" name="company_addr" class="frm_input frm_address" placeholder="사업장주소"><br>
						<input type="text" name="company_item" class="frm_input frm_address" placeholder="업태"><br>
						<input type="text" name="company_service" class="frm_input frm_address" placeholder="종목">
					</div>
				</td>
			</tr>
			</tbody>
			</table>
		</div>
	</section>

	<?php if(!$is_member) { ?>
    <section id="guest_privacy">
		<h2 class="anc_tit">비회원 구매</h2>
		<div class="tbl_head01 tbl_wrap">
			<table>
			<thead>
			<tr>
				<th>목적</th>
				<th>항목</th>
				<th>보유기간</th>
			</tr>
			</thead>
			<tbody>
			<tr>
				<td>이용자 식별 및 본인 확인</td>
				<td>이름, 비밀번호</td>
				<td>5년(전자상거래등에서의 소비자보호에 관한 법률)</td>
			</tr>
			<tr>
				<td>배송 및 CS대응을 위한 이용자 식별</td>
				<td>주소, 연락처(이메일, 휴대전화번호)</td>
				<td>5년(전자상거래등에서의 소비자보호에 관한 법률)</td>
			</tr>
			</tbody>
			</table>
		</div>

		<div id="guest_agree">
			<input type="checkbox" id="agree" value="1" class="css-checkbox lrg">
			<label for="agree" class="css-label">개인정보 수집 및 이용 내용을 읽었으며 이에 동의합니다.</label>
		</div>
	</section>
	<?php } ?>

	<div class="btn_confirm">
		<input type="submit" value="주문하기" class="btn_medium wset">
		<a href="<?php echo TB_MSHOP_URL; ?>/cart.php" class="btn_medium bx-white">주문취소</a>
	</div>
</div>
</form>

<script>
$(function() {
    var zipcode = "";

    $("input[name=b_addr2]").focus(function() {
        var zip = $("input[name=b_zip]").val().replace(/[^0-9]/g, "");
        if(zip == "")
            return false;

        var code = String(zip);

        if(zipcode == code)
            return false;

        zipcode = code;
        calculate_sendcost(code);
    });

	// 배송지선택
	$("input[name=ad_sel_addr]").on("click", function() {
		var addr = $(this).val();

		if(addr == "1") {
			gumae2baesong(true);
		} else if(addr == "2") {
			gumae2baesong(false);
		} else {
			win_open('./orderaddress.php','win_address');
		}
	});

    $("select[name=sel_memo]").change(function() {
         $("textarea[name=memo]").val($(this).val());
    });
});

// 도서/산간 배송비 검사
function calculate_sendcost(code) {
    $.post(
        tb_shop_url+"/ordersendcost.php",
        { zipcode: code },
        function(data) {
            $("input[name=baesong_price2]").val(data);
            $("#send_cost2").text(number_format(String(data)));

            calculate_order_price();
        }
    );
}

function calculate_order_price() {
    var sell_price = parseInt($("input[name=org_price]").val()); // 합계금액
	var send_cost2 = parseInt($("input[name=baesong_price2]").val()); // 추가배송비
	var mb_coupon  = parseInt($("input[name=coupon_total]").val()); // 쿠폰할인
	var mb_point   = parseInt($("input[name=use_point]").val().replace(/[^0-9]/g, "")); //포인트결제
	var tot_price  = sell_price + send_cost2 - (mb_coupon + mb_point);

	$("input[name=tot_price]").val(number_format(String(tot_price)));
}

function fbuyform_submit(f) {

    errmsg = "";
    errfld = "";

	var min_point	 = parseInt("<?php echo $config['usepoint']; ?>");
	var temp_point   = parseInt(no_comma(f.use_point.value));
	var sell_price   = parseInt(f.org_price.value);
	var send_cost2   = parseInt(f.baesong_price2.value);
	var mb_coupon    = parseInt(f.coupon_total.value);
	var mb_point     = parseInt(f.mb_point.value);
	var tot_price    = sell_price + send_cost2 - mb_coupon;

	if(f.use_point.value == '') {
		alert('포인트사용 금액을 입력하세요. 사용을 원치 않을경우 0을 입력하세요.');
		f.use_point.value = 0;
		f.use_point.focus();
		return false;
	}

	if(temp_point > mb_point) {
		alert('포인트사용 금액은 현재 보유포인트 보다 클수 없습니다.');
		f.tot_price.value = number_format(String(tot_price));
		f.use_point.value = 0;
		f.use_point.focus();
		return false;
	}

	if(temp_point > tot_price) {
		alert('포인트사용 금액은 최종결제금액 보다 클수 없습니다.');
		f.tot_price.value = number_format(String(tot_price));
		f.use_point.value = 0;
		f.use_point.focus();
		return false;
	}

	if(temp_point > 0 && (mb_point < min_point)) {
		alert('포인트사용 금액은 '+number_format(String(min_point))+'원 부터 사용가능 합니다.');
		f.tot_price.value = number_format(String(tot_price));
		f.use_point.value = 0;
		f.use_point.focus();
		return false;
	}

	if(getSelectVal(f["paymethod"]) == ''){
		alert("결제방법을 선택하세요.");
		f.paymethod.focus();
		return false;
	}

    if(typeof(f.od_pwd) != 'undefined') {
        clear_field(f.od_pwd);
        if( (f.od_pwd.value.length<3) || (f.od_pwd.value.search(/([^A-Za-z0-9]+)/)!=-1) )
            error_field(f.od_pwd, "회원이 아니신 경우 주문서 조회시 필요한 비밀번호를 3자리 이상 입력해 주십시오.");
    }

	if(getSelectVal(f["paymethod"]) == '무통장'){
		check_field(f.bank, "입금계좌를 선택하세요");
		check_field(f.deposit_name, "입금자명을 입력하세요");
	}

	<?php if(!$config['company_type']) { ?>
	if(getSelectVal(f["paymethod"]) == '무통장' && getSelectVal(f["taxsave_yes"]) == 'Y') {
		check_field(f.tax_hp, "핸드폰번호를 입력하세요");
	}

	if(getSelectVal(f["paymethod"]) == '무통장' && getSelectVal(f["taxsave_yes"]) == 'S') {
		check_field(f.tax_saupja_no, "사업자번호를 입력하세요");
	}

	if(getSelectVal(f["paymethod"]) == '무통장' && getSelectVal(f["taxbill_yes"]) == 'Y') {
		check_field(f.company_saupja_no, "사업자번호를 입력하세요");
		check_field(f.company_name, "상호명을 입력하세요");
		check_field(f.company_owner, "대표자명을 입력하세요");
		check_field(f.company_addr, "사업장소재지를 입력하세요");
		check_field(f.company_item, "업태를 입력하세요");
		check_field(f.company_service, "종목을 입력하세요");
	}
	<?php } ?>

    if(errmsg)
    {
        alert(errmsg);
        errfld.focus();
        return false;
    }

	if(getSelectVal(f["paymethod"]) == '계좌이체') {
		if(tot_price < 150) {
			alert("계좌이체는 150원 이상 결제가 가능합니다.");
			return false;
		}
	}

	if(getSelectVal(f["paymethod"]) == '신용카드') {
		if(false && tot_price < 1000) {
			alert("신용카드는 1000원 이상 결제가 가능합니다.");
			return false;
		}
	}

	if(getSelectVal(f["paymethod"]) == '휴대폰') {
		if(tot_price < 350) {
			alert("휴대폰은 350원 이상 결제가 가능합니다.");
			return false;
		}
	}

	if(document.getElementById('agree')) {
		if(!document.getElementById('agree').checked) {
			alert("개인정보 수집 및 이용 내용을 읽고 이에 동의하셔야 합니다.");
			return false;
		}
	}

	<?php PsysPay::printPaymethodFormMo('js'); ?>

	if(!confirm("주문내역이 정확하며, 주문 하시겠습니까?"))
		return false;

	f.use_point.value = no_comma(f.use_point.value);
	f.tot_price.value = no_comma(f.tot_price.value);

    return true;
}

function calculate_temp_point(val) {
	var f = document.buyform;
	var temp_point = parseInt(no_comma(f.use_point.value));
	var sell_price = parseInt(f.org_price.value);
	var send_cost2 = parseInt(f.baesong_price2.value);
	var mb_coupon  = parseInt(f.coupon_total.value);
	var tot_price  = sell_price + send_cost2 - mb_coupon;

	if(val == '' || !checkNum(no_comma(val))) {
		alert('포인트 사용액은 숫자이어야 합니다.');
		f.tot_price.value = number_format(String(tot_price));
		f.use_point.value = 0;
		f.use_point.focus();
		return;
	} else {
		f.tot_price.value = number_format(String(tot_price - temp_point));
	}
}

// 결제방법
function calculate_paymethod(type) {
    var sell_price = parseInt($("input[name=org_price]").val()); // 합계금액
	var send_cost2 = parseInt($("input[name=baesong_price2]").val()); // 추가배송비
	var mb_coupon  = parseInt($("input[name=coupon_total]").val()); // 쿠폰할인
	var mb_point   = parseInt($("input[name=mb_point]").val()); // 보유포인트
	var tot_price  = sell_price + send_cost2 - mb_coupon;

	// 포인트잔액이 부족한가?
	if( type == '포인트' && mb_point < tot_price ) {
		alert('포인트 잔액이 부족합니다.');

		$("select[name=paymethod]").val('무통장');
		$("#bank_section").show();
		$("input[name=use_point]").val(0);
		$("input[name=use_point]").attr("readonly", false);
		calculate_order_price();
		<?php if(!$config['company_type']) { ?>
		$("#taxsave_section").show();
		<?php } ?>

		return;
	}

	switch(type) {
		case '무통장':
			$("#bank_section").show();
			$("input[name=use_point]").val(0);
			$("input[name=use_point]").attr("readonly", false);
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#taxsave_section").show();
			<?php } ?>
			break;
		case '포인트':
			$("#bank_section").hide();
			$("input[name=use_point]").val(number_format(String(tot_price)));
			$("input[name=use_point]").attr("readonly", true);
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#taxsave_section").hide();
			$("#taxbill_section").hide();
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").hide();
			<?php } ?>
			break;
		default: // 그외 결제수단
			$("#bank_section").hide();
			$("input[name=use_point]").val(0);
			$("input[name=use_point]").attr("readonly", false);
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#taxsave_section").hide();
			$("#taxbill_section").hide();
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").hide();
			<?php } ?>
			break;
	}
}

// 현금영수증
function tax_save(val) {
	switch(val) {
		case 'Y': // 개인 소득공제용
			$("#taxsave_fld_1").show();
			$("#taxsave_fld_2").hide();
			$("#taxbill_section").hide();
			$("select[name=taxbill_yes]").val('N');
			break;
		case 'S': // 지출증빙용
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").show();
			$("#taxbill_section").hide();
			$("select[name=taxbill_yes]").val('N');
			break;
		default: // 발행안함
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").hide();
			break;
	}
}

// 세금계산서
function tax_bill(val) {
	switch(val) {
		case 'Y':  // 발행함
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").hide();
			$("select[name=taxsave_yes]").val('N');
			$("#taxbill_section").show();
			break;
		case 'N': //미발행
			$("#taxbill_section").hide();
			break;
	}
}

// 할인쿠폰 삭제
function coupon_cancel() {
	var f = document.buyform;
	var sell_price = parseInt(no_comma(f.tot_price.value)); // 최종 결제금액
	var mb_coupon  = parseInt(f.coupon_total.value); // 쿠폰할인
	var tot_price  = sell_price + mb_coupon;

	$("#dc_amt").text(0);
	$("#dc_cancel").hide();
	$("#dc_coupon").show();

	$("input[name=tot_price]").val(number_format(String(tot_price)));
	$("input[name=coupon_total]").val(0);
	$("input[name=coupon_price]").val("");
	$("input[name=coupon_lo_id]").val("");
	$("input[name=coupon_cp_id]").val("");
}

// 구매자 정보와 동일합니다.
function gumae2baesong(checked) {
    var f = document.buyform;

    if(checked == true) {
		f.b_name.value			= f.name.value;
		f.b_cellphone.value		= f.cellphone.value;
		f.b_telephone.value		= f.telephone.value;
		f.b_zip.value			= f.zip.value;
		f.b_addr1.value			= f.addr1.value;
		f.b_addr2.value			= f.addr2.value;
		f.b_addr3.value			= f.addr3.value;
		f.b_addr_jibeon.value	= f.addr_jibeon.value;

        calculate_sendcost(String(f.b_zip.value));
    } else {
		f.b_name.value			= '';
		f.b_cellphone.value		= '';
		f.b_telephone.value		= '';
		f.b_zip.value			= '';
		f.b_addr1.value			= '';
		f.b_addr2.value			= '';
		f.b_addr3.value			= '';
		f.b_addr_jibeon.value	= '';

		calculate_sendcost('');
    }
}

gumae2baesong(true);
</script>
<!-- } 주문서작성 끝 -->
