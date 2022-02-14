<?php
if(!defined('_TUBEWEB_')) exit;

require_once(TB_SHOP_PATH.'/settle_kakaopay.inc.php');

include_once(TB_SHOP_PATH.'/psys/PsysPay.php');

?>

<!-- 주문서작성 시작 { -->
<p><img src="<?php echo TB_IMG_URL; ?>/orderform.gif"></p>

<p class="pg_cnt mart20">
	※ 주문하실 상품 내역에 <em>수량 및 주문금액</em>이 틀리지 않는지 반드시 확인하시기 바랍니다.
</p>

<form name="buyform" id="buyform" method="post" action="<?php echo $order_action_url; ?>" onsubmit="return fbuyform_submit(this);" autocomplete="off">

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

		$it_name = stripslashes($gs['gname']);
		$it_options = print_item_options($row['gs_id']);
		if($it_options){
			$it_name .= '<div class="sod_opt">'.$it_options.'</div>';
		}

        $it_name .= PsysPay::getLongUseStr($gs);

		if($is_member) {
			$point = $sum['point'];
		}

		$supply_price = $sum['supply_price'];
		$sell_price = $sum['price'];
		$sell_opt_price = $sum['opt_price'];
		$sell_qty = $sum['qty'];
		$sell_amt = $sum['price'] - $sum['opt_price'];

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

		$href = TB_SHOP_URL.'/view.php?index_no='.$row['gs_id'];
	?>
	<tr>
		<td class="tac">
			<input type="hidden" name="gs_id[<?php echo $i; ?>]" value="<?php echo $row['gs_id']; ?>">
			<input type="hidden" name="gs_notax[<?php echo $i; ?>]" value="<?php echo $gs['notax']; ?>">
			<input type="hidden" name="gs_price[<?php echo $i; ?>]" value="<?php echo $sell_price; ?>">
			<input type="hidden" name="seller_id[<?php echo $i; ?>]" value="<?php echo $gs['mb_id']; ?>">
			<input type="hidden" name="supply_price[<?php echo $i; ?>]" value="<?php echo $supply_price; ?>">
			<input type="hidden" name="sum_point[<?php echo $i; ?>]" value="<?php echo $point; ?>">
			<input type="hidden" name="sum_qty[<?php echo $i; ?>]" value="<?php echo $sell_qty; ?>">
			<input type="hidden" name="cart_id[<?php echo $i; ?>]" value="<?php echo $row['od_no']; ?>">
			<?php echo get_it_image($row['gs_id'], $gs['simg1'], 80, 80); ?>
		</td>
		<td class="td_name"><?php echo $it_name; ?></td>
		<td class="tac"><?php echo number_format($sell_qty); ?></td>
		<td class="tar"><?php echo number_format(($sell_price/$sell_qty)); ?></td>
		<td class="tar"><?php echo number_format($sell_price); ?></td>
		<td class="tar"><?php echo number_format($point); ?></td>
		<td class="tar"><?php echo number_format($info['price']); ?></td>
	</tr>
	<?php
		$tot_point += (int)$point;
		$tot_sell_price += (int)$sell_price;
		$tot_opt_price += (int)$sell_opt_price;
		$tot_sell_qty += (int)$sell_qty;
		$tot_sell_amt += (int)$sell_amt;
	}

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
	?>
	</tbody>
	</table>
</div>

<div id="sod_bsk_tot">
	<table class="wfull">
	<tr>
		<td class="w50p">
			<h2 class="anc_tit">장바구니에 담긴 상품통계</h2>
			<div class="tbl_frm01 tbl_wrap">
				<table>
				<colgroup>
					<col class="w140">
					<col class="w140">
					<col>
				</colgroup>
				<tr>
					<th scope="row">포인트</th>
					<td class="tar">적립 포인트</td>
					<td class="tar bl"><?php echo display_point($tot_point); ?></td>
				</tr>
				<tr>
					<th scope="row" rowspan="3">상품</th>
					<td class="tar">상품금액 합계</td>
					<td class="tar bl"><?php echo display_price2($tot_sell_amt); ?></td>
				</tr>
				<tr>
					<td class="tar">옵션금액 합계</td>
					<td class="tar bl"><?php echo display_price2($tot_opt_price); ?></td>
				</tr>
				<tr>
					<td class="tar">주문수량 합계</td>
					<td class="tar bl"><?php echo display_qty($tot_sell_qty); ?></td>
				</tr>
				<tr>
					<td class="list2 tac bold" colspan="2">현재 포인트 보유잔액</td>
					<td class="list2 tar bold"><?php echo display_point($member['point']); ?></td>
				</tr>
				</table>
			</div>
		</td>
		<td class="w50p">
			<h2 class="anc_tit">결제 예상금액 통계</h2>
			<div class="tbl_frm01 tbl_wrap">
				<table>
				<colgroup>
					<col class="w140">
					<col class="w140">
					<col>
				</colgroup>
				<tr>
					<th scope="row">주문</th>
					<td class="tar">(A) 주문금액 합계</td>
					<td class="tar bl"><?php echo display_price2($tot_sell_price); ?></td>
				</tr>
				<tr>
					<th scope="row" rowspan="3">배송비</th>
					<td class="tar">상품별 배송비합계</td>
					<td class="tar bl"><?php echo display_price2($send_cost); ?></td>
				</tr>
				<tr>
					<td class="tar">배송비할인</td>
					<td class="tar bl">(-) <?php echo display_price2($tot_final_sum); ?></td>
				</tr>
				<tr>
					<td class="tar">(B) 최종배송비</td>
					<td class="tar bl"><?php echo display_price2($tot_send_cost); ?></td>
				</tr>
				<tr>
					<td class="list2 tac bold" colspan="2">결제예정금액 (A+B)</td>
					<td class="list2 tar bold fc_red"><?php echo display_price2($tot_price); ?></td>
				</tr>
				</table>
			</div>
		</td>
	</tr>
	</table>
</div>

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

<section id="sod_fin_orderer">
	<h2 class="anc_tit">주문하시는 분</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<?php if(!$is_member) { // 비회원이면 ?>
		<tr>
			<th scope="row">비밀번호</th>
			<td>
				<input type="password" name="od_pwd" required itemname="비밀번호" class="frm_input required" size="20">
				<span class="frm_info">영,숫자 3~20자 (주문서 조회시 필요)</span>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<th scope="row">이름</th>
			<td><input type="text" name="name" value="<?php echo $member['name']; ?>" required itemname="이름" class="frm_input required" size="20"></td>
		</tr>
		<tr>
			<th scope="row">전화번호</th>
			<td><input type="text" name="telephone" value="<?php echo $member['telephone']; ?>" class="frm_input" size="20"></td>
		</tr>
		<tr>
			<th scope="row">핸드폰</th>
			<td><input type="text" name="cellphone" value="<?php echo $member['cellphone']; ?>" required itemname="핸드폰" class="frm_input required" size="20"></td>
		</tr>
		<tr>
			<th scope="row">주소</th>
			<td>
				<div>
					<input type="text" name="zip" value="<?php echo $member['zip']; ?>" required itemname="우편번호" class="frm_input required" maxLength="5" size="8"> <a href="javascript:win_zip('buyform', 'zip', 'addr1', 'addr2', 'addr3', 'addr_jibeon');" class="btn_small grey">주소검색</a>
				</div>
				<div class="padt5">
					<input type="text" name="addr1" value="<?php echo $member['addr1']; ?>" required itemname="주소" class="frm_input required" size="60" readonly> 기본주소
				</div>
				<div class="padt5">
					<input type="text" name="addr2" value="<?php echo $member['addr2']; ?>" class="frm_input" size="60"> 상세주소
				</div>
				<div class="padt5">
					<input type="text" name="addr3" value="<?php echo $member['addr3']; ?>" class="frm_input" size="60" readonly> 참고항목
					<input type="hidden" name="addr_jibeon" value="<?php echo $member['addr_jibeon']; ?>">
				</div>
			</td>
		</tr>
		<tr>
			<th scope="row">E-mail</th>
			<td><input type="text" name="email" value="<?php echo $member['email']; ?>" required email itemname="E-mail" class="frm_input required" size="30"></td>
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
			<th scope="row">배송지선택</th>
			<td class="td_label">
				<label><input type="radio" name="ad_sel_addr" value="1"> 주문자와 동일</label>
				<label><input type="radio" name="ad_sel_addr" value="2"> 신규배송지</label>
				<?php if($is_member) { ?>
				<label><input type="radio" name="ad_sel_addr" value="3"> 배송지목록</label>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<th scope="row">이름</th>
			<td><input type="text" name="b_name" required itemname="이름" class="frm_input required" size="20"></td>
		</tr>
		<tr>
			<th scope="row">전화번호</th>
			<td><input type="text" name="b_telephone" class="frm_input" size="20"></td>
		</tr>
		<tr>
			<th scope="row">핸드폰</th>
			<td><input type="text" name="b_cellphone" required itemname="핸드폰" class="frm_input required" size="20"></td>
		</tr>
		<tr>
			<th scope="row">주소</th>
			<td>
				<div>
					<input type="text" name="b_zip" required itemname="우편번호" class="frm_input required" maxLength="5" size="8"> <a href="javascript:win_zip('buyform', 'b_zip', 'b_addr1', 'b_addr2', 'b_addr3', 'b_addr_jibeon');" class="btn_small grey">주소검색</a>
				</div>
				<div class="padt5">
					<input type="text" name="b_addr1" required itemname="주소" class="frm_input required" size="60" readonly> 기본주소
				</div>
				<div class="padt5">
					<input type="text" name="b_addr2" class="frm_input" size="60"> 상세주소
				</div>
				<div class="padt5">
					<input type="text" name="b_addr3" class="frm_input" size="60" readonly> 참고항목
					<input type="hidden" name="b_addr_jibeon" value="">
				</div>
			</td>
		</tr>
		<tr>
			<th scope="row">전하실말씀</th>
			<td>
				<select name="sel_memo">
					<option value="">요청사항 선택하기</option>
					<option value="부재시 경비실에 맡겨주세요.">부재시 경비실에 맡겨주세요</option>
					<option value="빠른 배송 부탁드립니다.">빠른 배송 부탁드립니다.</option>
					<option value="부재시 핸드폰으로 연락바랍니다.">부재시 핸드폰으로 연락바랍니다.</option>
					<option value="배송 전 연락바랍니다.">배송 전 연락바랍니다.</option>
				</select>
				<textarea name="memo" class="frm_textbox h60 mart5" rows="3"></textarea>
				<span class="frm_info"><strong class="fc_red">"택배사원"</strong>에 전하실 말씀을 써주세요~!<br>C/S관련문의는 고객센터에 작성해주세요. 이곳에 남기시면 확인이 불가능합니다.</span>
			</td>
		</tr>
		</table>
	</div>
</section>

<section id="sod_fin_pay">
	<h2 class="anc_tit">결제정보 입력</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tr>
			<th scope="row">결제방법</th>
			<td class="td_label">
				<?php
				$escrow_title = "";
				if($default['de_escrow_use']) {
					$escrow_title = "에스크로 ";
				}

				if($is_kakaopay_use) {
					echo '<input type="radio" name="paymethod" id="paymethod_kakaopay" value="KAKAOPAY" onclick="calculate_paymethod(this.value);"> <label for="paymethod_kakaopay" class="kakaopay_icon">카카오페이</label>'.PHP_EOL;
				}
				if($default['de_bank_use']) {
					echo '<input type="radio" name="paymethod" id="paymethod_bank" value="무통장" onclick="calculate_paymethod(this.value);"> <label for="paymethod_bank">무통장입금</label>'.PHP_EOL;
				}
				if($default['de_card_use']) {
					echo '<input type="radio" name="paymethod" id="paymethod_card" value="신용카드" onclick="calculate_paymethod(this.value);"> <label for="paymethod_card">신용카드</label>'.PHP_EOL;
				}
				if($default['de_hp_use']) {
					echo '<input type="radio" name="paymethod" id="paymethod_hp" value="휴대폰" onclick="calculate_paymethod(this.value);"> <label for="paymethod_hp">휴대폰</label>'.PHP_EOL;
				}
				if($default['de_iche_use']) {
					echo '<input type="radio" name="paymethod" id="paymethod_iche" value="계좌이체" onclick="calculate_paymethod(this.value);"> <label for="paymethod_iche">'.$escrow_title.'계좌이체</label>'.PHP_EOL;
				}
				if($default['de_vbank_use']) {
					echo '<input type="radio" name="paymethod" id="paymethod_vbank" value="가상계좌" onclick="calculate_paymethod(this.value);"> <label for="paymethod_vbank">'.$escrow_title.'가상계좌</label>'.PHP_EOL;
				}
				if($is_member && $config['usepoint_yes'] && ($tot_price <= $member['point'])) {
					echo '<input type="radio" name="paymethod" id="paymethod_point" value="포인트" onclick="calculate_paymethod(this.value);"> <label for="paymethod_point">포인트결제</label>'.PHP_EOL;
				}
				PsysPay::printPaymethodForm('html', $goods_psys_long_no_use_cnt);


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
						echo '<input type="radio" name="paymethod" id="paymethod_easy_pay" value="간편결제" onclick="calculate_paymethod(this.value);"><label for="paymethod_easy_pay" class="'.$pg_easy_pay_name.'">'.$pg_easy_pay_name.'</label>'.PHP_EOL;
					}
				}
				?>
			</td>
		</tr>
		<tr>
			<th scope="row">합계</th>
			<td class="bold"><?php echo display_price($tot_price); ?></td>
		</tr>
		<tr>
			<th scope="row">추가배송비</th>
			<td>
				<strong><span id="send_cost2">0</span>원</strong>
				<span class="fc_999">(지역에 따라 추가되는 도선료 등의 배송비입니다.)</span>
			</td>
		</tr>
		<?php
		if($is_member && $config['coupon_yes']) { // 보유쿠폰
			$cp_count = get_cp_precompose($member['id']);
		?>
		<tr>
			<th scope="row">할인쿠폰</th>
			<td>(-) <strong><span id="dc_amt">0</span>원 <span id="dc_cancel" style="display:none"><a href="javascript:coupon_cancel();">X</a></span></strong>
			<span id="dc_coupon"><a href="<?php echo TB_SHOP_URL; ?>/ordercoupon.php" onclick="win_open(this,'win_coupon','670','500','yes');return false"><span class='fc_197 tu'>사용 가능 쿠폰 <?php echo $cp_count[3]; ?>장</a> </span></span></td>
		</tr>
		<?php } ?>
		<?php
		if($is_member && $config['usepoint_yes']) { ?>
		<tr>
			<th scope="row">포인트결제</th>
			<td>
				<input type="text" name="use_point" value="0" class="frm_input" size="12" onkeyup="calculate_temp_point(this.value);this.value=number_format(this.value);" style="font-weight:bold;"> 원 보유포인트 : <?php echo display_point($member['point']); ?>
				<?php if($config['usepoint']) { ?>
				(<strong><?php echo display_point($config['usepoint']); ?></strong> 부터 사용가능)
				<?php } ?>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<th scope="row">총 결제금액</th>
			<td>
				<input type="text" name="tot_price" value="<?php echo number_format($tot_price); ?>" class="frm_input" size="12" readonly style="font-weight:bold;color:#ec0e03;"> 원
			</td>
		</tr>
		</table>
	</div>
</section>

<section id="bank_section" style="display:none;">
	<h2 class="anc_tit">입금하실 계좌</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tr>
			<th scope="row">입금계좌선택</th>
			<td><?php echo get_bank_account("bank"); ?></td>
		</tr>
		<tr>
			<th scope="row">입금자명</th>
			<td><input type="text" name="deposit_name" value="<?php echo $member['name']; ?>" class="frm_input" size="12"></td>
		</tr>
		</table>
	</div>
</section>

<?php if(!$config['company_type']) { ?>
<section id="tax_section" style="display:none;">
	<h2 class="anc_tit">증빙서류발급 요청</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tr>
			<th scope="row">현금영수증발행</th>
			<td class="td_label">
				<input type="radio" id="taxsave_1" name="taxsave_yes" value="Y" onclick="tax_bill(1);">
				<label for="taxsave_1">개인 소득공제용</label>
				<input type="radio" id="taxsave_2" name="taxsave_yes" value="S" onclick="tax_bill(2);">
				<label for="taxsave_2">사업자 지출증빙용</label>
				<input type="radio" id="taxsave_3" name="taxsave_yes" value="N" onclick="tax_bill(3);" checked>
				<label for="taxsave_3">미발행</label>
			</td>
		</tr>
		<tr id="taxsave_fld_1" style="display:none">
			<th scope="row">핸드폰번호</th>
			<td>
				<input type="text" name="tax_hp" class="frm_input" size="20">
				<span class="frm_info">
					현금영수증은 1원이상 현금 구매시 발급이 가능합니다.<br>
					현금영수증은 구매대금 입금확인일 다음날 발급됩니다.<br>
					현금영수증 홈페이지 :<A href="http://taxsave.go.kr/" target="_balnk"><b>http://www.taxsave.go.kr</b></a>
				</span>
			</td>
		</tr>
		<tr id="taxsave_fld_2" style="display:none">
			<th scope="row">사업자등록번호</th>
			<td><input type="text" name="tax_saupja_no" class="frm_input" size="20"></td>
		</tr>
		<tr>
			<th scope="row">세금계산서발행</th>
			<td class="td_label">
				<input type="radio" id="taxbill_1" name="taxbill_yes" value="Y" onclick="tax_bill(4);">
				<label for="taxbill_1">발행요청</label>
				<input type="radio" id="taxbill_2" name="taxbill_yes" value="N" onclick="tax_bill(5);" checked>
				<label for="taxbill_2">미발행</label>
			</td>
		</tr>
		<tr class="taxbill_fld">
			<th scope="row">사업자등록번호</td>
			<td><input type="text" name="company_saupja_no" size="20" class="frm_input"></td>
		</tr>
		<tr class="taxbill_fld">
			<th scope="row">상호(법인명)</th>
			<td><input type="text" name="company_name" class="frm_input" size="20"> 예 : <?php echo $config['company_name']; ?></td>
		</tr>
		<tr class="taxbill_fld">
			<th scope="row">대표자</th>
			<td><input type="text" name="company_owner" class="frm_input" size="20"> 예 : 홍길동</td>
		</tr>
		<tr class="taxbill_fld">
			<th scope="row">사업장주소</th>
			<td><input type="text" name="company_addr" class="frm_input" size="60"></td>
		</tr>
		<tr class="taxbill_fld">
			<th scope="row">업태</th>
			<td><input type="text" name="company_item" class="frm_input" size="20"> 예 : 도소매</td>
		</tr>
		<tr class="taxbill_fld">
			<th scope="row">종목</th>
			<td><input type="text" name="company_service" class="frm_input" size="20"> 예 : 전자부품</td>
		</tr>
		</table>
	</div>
</section>
<?php } ?>

<?php if(!$is_member) { ?>
<section id="guest_privacy">
	<h3 class="anc_tit">개인정보 수집 및 이용</h3>
	<p>비회원으로 주문 시 포인트적립 및 추가 혜택을 받을 수 없습니다.</p>
	<div class="tbl_head02 tbl_wrap">
		<table>
		<thead>
		<tr>
			<th scope="col">목적</th>
			<th scope="col">항목</th>
			<th scope="col">보유기간</th>
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

	<fieldset id="guest_agree">
		<input type="checkbox" id="agree" value="1">
		<label for="agree">개인정보 수집 및 이용 내용을 읽었으며 이에 동의합니다.</label>
	</fieldset>
</section>
<?php } ?>

<div class="btn_confirm">
	<input type="submit" value="주문하기" class="btn_large wset">
	<a href="<?php echo TB_SHOP_URL; ?>/cart.php" class="btn_large bx-white">취소</a>
</div>

</form>

<script>
$(function() {
    $("input[name=b_addr2]").focus(function() {
        var zip = $("input[name=b_zip]").val().replace(/[^0-9]/g, "");
        if(zip == "")
            return false;

        var code = String(zip);
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
			win_open(tb_shop_url+'/orderaddress.php','win_address', 600, 600, 'yes');
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

	var min_point	= parseInt("<?php echo $config['usepoint']; ?>");
	var temp_point	= parseInt(no_comma(f.use_point.value));
	var sell_price	= parseInt(f.org_price.value);
	var send_cost2	= parseInt(f.baesong_price2.value);
	var mb_coupon	= parseInt(f.coupon_total.value);
	var mb_point	= parseInt(f.mb_point.value);
	var tot_price	= sell_price + send_cost2 - mb_coupon;

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

	var paymethod_check = false;
	for(var i=0; i<f.elements.length; i++){
		if(f.elements[i].name == "paymethod" && f.elements[i].checked==true){
			paymethod_check = true;
		}
	}

    if(!paymethod_check) {
        alert("결제방법을 선택하세요.");
        return false;
    }

    if(typeof(f.od_pwd) != 'undefined') {
        clear_field(f.od_pwd);
        if( (f.od_pwd.value.length<3) || (f.od_pwd.value.search(/([^A-Za-z0-9]+)/)!=-1) )
            error_field(f.od_pwd, "회원이 아니신 경우 주문서 조회시 필요한 비밀번호를 3자리 이상 입력해 주십시오.");
    }

	if(getRadioVal(f.paymethod) == '무통장') {
		check_field(f.bank, "입금계좌를 선택하세요");
		check_field(f.deposit_name, "입금자명을 입력하세요");
	}

	<?php if(!$config['company_type']) { ?>
	if(getRadioVal(f.paymethod) == '무통장' && getRadioVal(f.taxsave_yes) == 'Y') {
		check_field(f.tax_hp, "핸드폰번호를 입력하세요");
	}

	if(getRadioVal(f.paymethod) == '무통장' && getRadioVal(f.taxsave_yes) == 'S') {
		check_field(f.tax_saupja_no, "사업자번호를 입력하세요");
	}

	if(getRadioVal(f.paymethod) == '무통장' && getRadioVal(f.taxbill_yes) == 'Y') {
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

	if(getRadioVal(f.paymethod) == '계좌이체') {
		if(tot_price < 150) {
			alert("계좌이체는 150원 이상 결제가 가능합니다.");
			return false;
		}
	}

	if(getRadioVal(f.paymethod) == '신용카드') {
		if(false && tot_price < 1000) {
			alert("신용카드는 1000원 이상 결제가 가능합니다.");
			return false;
		}
	}

	if(getRadioVal(f.paymethod) == '휴대폰') {
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

	<?php PsysPay::printPaymethodForm('js'); ?>

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

function calculate_paymethod(type) {
    var sell_price = parseInt($("input[name=org_price]").val()); // 합계금액
	var send_cost2 = parseInt($("input[name=baesong_price2]").val()); // 추가배송비
	var mb_coupon  = parseInt($("input[name=coupon_total]").val()); // 쿠폰할인
	var mb_point   = parseInt($("input[name=mb_point]").val()); // 보유포인트
	var tot_price  = sell_price + send_cost2 - mb_coupon;

	// 포인트잔액이 부족한가?
	if( type == '포인트' && mb_point < tot_price ) {
		alert('포인트 잔액이 부족합니다.');

		$("#paymethod_bank").attr("checked", true);
		$("#bank_section").show();
		$("input[name=use_point]").val(0);
		$("input[name=use_point]").attr("readonly", false);
		calculate_order_price();
		<?php if(!$config['company_type']) { ?>
		$("#tax_section").show();
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
			$("#tax_section").show();
			<?php } ?>
			break;
		case '포인트':
			$("#bank_section").hide();
			$("input[name=use_point]").val(number_format(String(tot_price)));
			$("input[name=use_point]").attr("readonly", true);
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#tax_section").hide();
			$(".taxbill_fld").hide();
			$("#taxsave_3").attr("checked", true);
			$("#taxbill_2").attr("checked", true);
			<?php } ?>
			break;
		default: // 그외 결제수단
			$("#bank_section").hide();
			$("input[name=use_point]").val(0);
			$("input[name=use_point]").attr("readonly", false);
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#tax_section").hide();
			$(".taxbill_fld").hide();
			$("#taxsave_3").attr("checked", true);
			$("#taxbill_2").attr("checked", true);
			<?php } ?>
			break;
	}
}

function tax_bill(val) {
	switch(val) {
		case 1:
			$("#taxsave_fld_1").show();
			$("#taxsave_fld_2").hide();
			$(".taxbill_fld").hide();
			$("#taxbill_2").attr("checked", true);
			break;
		case 2:
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").show();
			$(".taxbill_fld").hide();
			$("#taxbill_2").attr("checked", true);
			break;
		case 3:
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").hide();
			break;
		case 4:
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").hide();
			$(".taxbill_fld").show();
			$("#taxsave_3").attr("checked", true);
			break;
		case 5:
			$(".taxbill_fld").hide();
			break;
	}
}

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
