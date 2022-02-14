<?php
define('_NEWWIN_', true);
include_once('./_common.php');

$sql_search = " and seller_id = '{$member['id']}' ";

$sql = " select * from shop_order where od_id = '$od_id' {$sql_search} ";
$od = sql_fetch($sql);
if(!$od['od_id']) {
    alert_close("주문서가 존재하지 않습니다.");
}

$od['mb_id'] = $od['mb_id'] ? $od['mb_id'] : "비회원";

$amount = get_order_spay($od_id, $sql_search); // 결제정보 합계
$default = set_partner_value($od['od_settle_pid']); // 가맹점 PG결제 정보

$tb['title'] = "주문내역 수정";
include_once(TB_ADMIN_PATH."/admin_head.php");

$pg_anchor = '<ul class="anchor">
<li><a href="#anc_sodr_list">주문상품 목록</a></li>
<li><a href="#anc_sodr_pay">주문결제 내역</a></li>
<li><a href="#anc_sodr_memo">관리자메모</a></li>
<li><a href="#anc_sodr_addr">주문자/배송지 정보</a></li>
</ul>';
?>

<div id="sodr_pop" class="new_win">
	<h1><?php echo $tb['title']; ?></h1>

	<section id="anc_sodr_list">
		<h4 class="anc_tit">주문상품 목록</h4>
		<?php echo $pg_anchor; ?>
		<div class="local_desc02 local_desc">
			<p>
				주문일시 <strong><?php echo substr($od['od_time'],0,16); ?> (<?php echo get_yoil($od['od_time']); ?>)</strong> <span class="fc_214">|</span>
				주문총액 <strong><?php echo number_format($amount['buyprice']); ?></strong>원
				<a href="javascript:win_open('<?php echo TB_MYPAGE_URL; ?>/partner_odr_print.php?od_id=<?php echo $od_id; ?>','order_print','670','600','yes');" class="btn_small blue fr"><i class="fa fa-print"></i> 주문서출력</a>
			</p>
		</div>

		<form name="frmorderform" method="post" action="./partner_odr_statusupdate.php" onsubmit="return form_submit(this);">
		<input type="hidden" name="od_id" value="<?php echo $od_id; ?>">
		<input type="hidden" name="od_hp" value="<?php echo $od['cellphone']; ?>">
		<input type="hidden" name="od_email" value="<?php echo $od['email']; ?>">
		<input type="hidden" name="mb_id" value="<?php echo $od['mb_id']; ?>">
		<input type="hidden" name="pg_cancel" value="0">

		<div class="tbl_head01">
			<table id="sodr_list">
			<colgroup>
				<col class="w40">
				<col class="w60">
				<col>
				<col class="w90">
				<col class="w60">
				<col class="w70">
				<col class="w70">
				<col class="w70">
				<col class="w70">
				<col class="w70">
				<col class="w70">
			</colgroup>
			<thead>
			<tr>
				<th scope="col">
					<label for="chkall" class="sound_only">주문 전체</label>
					<input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form);">
				</th>
				<th scope="col">이미지</th>
				<th scope="col">주문상품</th>
				<th scope="col">주문상태</th>
				<th scope="col">수량</th>
				<th scope="col">공급가격</th>
				<th scope="col">상품금액</th>
				<th scope="col">배송비</th>
				<th scope="col">쿠폰할인</th>
				<th scope="col">포인트결제</th>
				<th scope="col">실결제액</th>
			</tr>
			</thead>
			<tbody class="list">
			<?php
			$chk_cnt	= 0; // 전체 배열
			$chk_cancel = 0; // 클래임 수
			$sum_point  = 0; // 포인트적립

			$sql = " select *
					   from shop_order
					  where od_id = '$od_id'
						{$sql_search}
					  order by od_time desc, index_no asc ";
			$result = sql_query($sql);
			for($i=0; $row=sql_fetch_array($result); $i++) {
				$gs = unserialize($row['od_goods']);

				$it_options = print_complete_options($row['gs_id'], $row['od_id']);
				if($it_options){
					$it_options = '<div class="sod_opt">'.$it_options.'</div>';
				}

				// 취소.반품.환불 외
				if(!in_array($row['dan'], array(6,7,9))) {
					$sum_point += (int)$row['sum_point'];
				}

				$bg = 'list'.($i%2);
			?>
			<tr class="<?php echo $bg; ?>">
				<td>
					<input type="hidden" name="od_no[<?php echo $i; ?>]" value="<?php echo $row['od_no']; ?>">
					<input type="hidden" name="current_status[<?php echo $i; ?>]" value="<?php echo $row['dan']; ?>">
					<label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo $row['od_id']; ?></label>
					<input type="checkbox" name="chk[]" value="<?php echo $i; ?>" id="chk_<?php echo $i; ?>">
				</td>
				<td>
					<a href="<?php echo TB_SHOP_URL; ?>/view.php?index_no=<?php echo $row['gs_id']; ?>" target="_blank"><?php echo get_od_image($row['od_id'], $gs['simg1'], 40, 40); ?></a>
				</td>
				<td class="tal">
					<a href="<?php echo TB_MYPAGE_URL; ?>/page.php?code=partner_goods_form&w=u&gs_id=<?php echo $row['gs_id']; ?>" target="_blank"><?php echo get_text($gs['gname']); ?></a>
					<?php if($row['od_tax_flag'] && !$gs['notax']) echo '[비과세상품]'; ?>
					<?php echo $it_options; ?>
					<?php
					// 배송준비.배송중.배송완료
					$baesong_run = 0;
					if(in_array($row['dan'], array(3,4,5))) {
						$baesong_run++;
					?>
					<div class="frm_info">
						<?php echo get_delivery_select("delivery[".$i."]", $row['delivery']); ?>
						<input type="text" name="delivery_no[<?php echo $i; ?>]" value="<?php echo $row['delivery_no']; ?>" class="frm_input w130" placeholder="개별 운송장번호">
						<?php echo get_delivery_inquiry($row['delivery'], $row['delivery_no'], 'btn_ssmall'); ?>
					</div>
					<?php } ?>
				</td>
				<td>
					<?php echo get_change_select("change_status[".$i."]", $row['dan']); ?>
					<?php if(in_array($row['dan'], array(7,9)) && $row['refund_price'] == 0 && in_array($row['paymethod'], array('신용카드', '계좌이체', 'KAKAOPAY'))) { ?>
					<p class="padt3"><a href="<?php echo TB_ADMIN_URL; ?>/pop_orderpartcancel.php?od_id=<?php echo $row['od_id']; ?>&od_no=<?php echo $row['od_no']; ?>" class="btn_ssmall orderpartcancel red">PG부분취소</a></p>
					<?php } ?>
				</td>
				<td><?php echo number_format($row['sum_qty']); ?></td>
				<td class="tar"><?php echo number_format($row['supply_price']); ?></td>
				<td class="tar"><?php echo number_format($row['goods_price']); ?></td>
				<td class="tar"><?php echo number_format($row['baesong_price']); ?></td>
				<td class="tar"><?php echo number_format($row['coupon_price']); ?></td>
				<td class="tar"><?php echo number_format($row['use_point']); ?></td>
				<td class="td_price"><?php echo number_format($row['use_price']); ?></td>
			</tr>
			<?php
				$chk_cnt++;

				// 취소.반품.교환.환불 수
				if(in_array($row['dan'], array(6,7,8,9))) {
					$chk_cancel++;
				}
			}
			?>
			</tbody>
			</table>
		</div>
		<div class="local_frm02">
			<i class="ionicons ion-alert-circled fc_red"></i> 환불, 반품완료 후 <b>"PG부분취소"</b>를 통해 신용카드 및 계좌이체 결제취소를 해주셔야 <b>"PG 신용카드 승인취소 처리"</b>가 완료됩니다.
		</div>

		<?php
		$sql = " select COUNT(*) as od_count1,
						SUM(IF(dan = '1', 1, 0)) as od_count2
				   from shop_order
				  where od_id = '$od_id' ";
		$res = sql_fetch($sql);
		?>

		<?php if($chk_cnt != $chk_cancel) { // 정상 주문건만 노출 ?>
		<div class="btn_list marb20">
			<input type="hidden" name="chk_cnt" value="<?php echo $chk_cnt; ?>">
			<strong class="marr5">선택한 상품을</strong>
		<?php if($res['od_count1'] == $res['od_count2']) { // 모두 입금대기 상태인가? ?>
			<?php if($pf_auth_pg && ($od['od_settle_pid'] == $member['id'])) { ?>
			<input type="submit" name="act_button" value="입금완료" class="btn_lsmall red" onclick="document.pressed=this.value">
			<?php } ?>
			<input type="submit" name="act_button" value="주문취소" class="btn_lsmall white" onclick="document.pressed=this.value">
		<?php } else { ?>
			<input type="submit" name="act_button" value="주문상태저장" class="btn_lsmall red" onclick="document.pressed=this.value">
			<?php if($baesong_run) { ?>
			<input type="submit" name="act_button" value="운송장번호수정" class="btn_lsmall white" onclick="document.pressed=this.value">
			<?php } ?>
		<?php } ?>
		</div>
		<?php } ?>
		</form>

		<?php if($od['od_test']) { ?>
		<div class="od_test_caution">주의) 이 주문은 테스트용으로 실제 결제가 이루어지지 않았으므로 절대 배송하시면 안됩니다.</div>
		<?php } ?>

		<?php if($od['od_mod_history']) { ?>
		<section id="sodr_qty_log">
			<h3>주문 전체취소 처리 내역</h3>
			<div>
				<?php echo conv_content($od['od_mod_history'], 0); ?>
			</div>
		</section>
		<?php } ?>
	</section>

	<?php
	// 결제방법
	$s_receipt_way = $od['paymethod'];

	if($od['paymethod'] == '간편결제') {
		if($od['od_pg'] == 'lg')
			$s_receipt_way = 'PAYNOW';
		else if($od['od_pg'] == 'inicis')
			$s_receipt_way = 'KPAY';
		else if($od['od_pg'] == 'kcp')
			$s_receipt_way = 'PAYCO';
		else
			$s_receipt_way = $od['paymethod'];
	}

	if($amount['usepoint'] > 0)
		$s_receipt_way .= "+포인트";
	?>

	<section id="anc_sodr_pay" class="new_win_desc mart30">
		<h3 class="anc_tit">주문결제 내역</h3>
		<?php echo $pg_anchor; ?>
		<form name="frmorderreceiptform" action="./partner_odr_formupdate.php" method="post" autocomplete="off">
		<input type="hidden" name="od_id" value="<?php echo $od_id; ?>">
		<input type="hidden" name="mod_type" value="receipt">

		<div class="compare_wrap">
			<section id="anc_sodr_chk" class="compare_left">
				<h3>결제상세정보 확인</h3>

				<div class="tbl_frm01">
					<table>
					<colgroup>
						<col class="w150">
						<col>
					</colgroup>
					<tbody>
					<tr>
						<th scope="row">총 주문금액</th>
						<td class="td_price"><?php echo display_price($amount['buyprice']); ?></td>
					</tr>
					<tr>
						<th scope="row">총 상품금액</th>
						<td class="td_price bg0"><?php echo display_price($amount['price']); ?></td>
					</tr>
					<tr>
						<th scope="row">총 배송비</th>
						<td class="td_price fc_197">(+) <?php echo display_price($amount['baesong']); ?></td>
					</tr>
					<tr>
						<th scope="row">총 쿠폰할인</th>
						<td class="td_price fc_red">(-) <?php echo display_price($amount['coupon']); ?></td>
					</tr>
					<tr>
						<th scope="row">포인트결제</th>
						<td class="td_price fc_red">(-) <?php echo display_price($amount['usepoint']); ?></td>
					</tr>
					<tr>
						<th scope="row">실 결제금액</th>
						<td class="td_price bg0"><?php echo display_price($amount['useprice']); ?></td>
					</tr>
					<tr>
						<th scope="row" class="fc_red">환불액(PG승인취소)</th>
						<td class="td_price bg1 fc_red">(-) <?php echo display_price($amount['refund']); ?></td>
					</tr>
					<tr>
						<th scope="row">포인트적립</th>
						<td class="tar"><?php echo display_price($sum_point); ?></td>
					</tr>
					</tbody>
					</table>
				</div>
			</section>

			<section id="anc_sodr_paymo" class="compare_right">
				<h3>결제상세정보 수정</h3>

				<div class="tbl_frm01">
					<table>
					<colgroup>
						<col class="w150">
						<col>
					</colgroup>
					<tbody>
					<tr>
						<th scope="row">주문번호</th>
						<td><?php echo $od['od_id']; ?></td>
					</tr>
					<tr>
						<th scope="row">주문일시</th>
						<td><?php echo $od['od_time']; ?> (<?php echo get_yoil($od['od_time']); ?>)</td>
					</tr>
					<tr>
						<th scope="row">주문채널</th>
						<td><?php echo $od['od_mobile']?'모바일':'PC'; ?> 쇼핑몰에서 주문</td>
					</tr>
					<tr>
						<th scope="row">결제방법</th>
						<td><?php echo $s_receipt_way; ?></td>
					</tr>
					<?php if(in_array($od['paymethod'], array('무통장', '가상계좌', '계좌이체'))) { ?>
					<?php
					if($od['paymethod'] == '무통장')
						$bank_account = get_bank_account("bank", $od['bank']);
					else if($od['paymethod'] == '가상계좌')
						$bank_account = $od['bank'].'<input type="hidden" name="bank" value="'.$od['bank'].'">';
					else if($od['paymethod'] == '계좌이체')
						$bank_account = $od['paymethod'];
					?>
					<?php if(in_array($od['paymethod'], array('무통장', '가상계좌'))) { ?>
					<tr>
						<th scope="row"><label for="bank">계좌번호</label></th>
						<td><?php echo $bank_account; ?></td>
					</tr>
					<?php } ?>
					<tr>
						<th scope="row"><label for="deposit_name">입금자명</label></th>
						<td><input type="text" name="deposit_name" value="<?php echo get_text($od['deposit_name']); ?>" id="deposit_name" class="frm_input" placeholder="실 입금자명"></td>
					</tr>
					<tr>
						<th scope="row"><?php echo $od['paymethod']; ?> 입금액</th>
						<td><?php echo display_price($amount['useprice']); ?></td>
					</tr>
					<tr>
						<th scope="row">입금확인일시</th>
						<td>
							<?php
							if(is_null_time($od['receipt_time']))
								echo '입금 확인일시 정보가 없습니다.';
							else
								echo $od['receipt_time'].' ('.get_yoil($od['receipt_time']).')';
							?>
						</td>
					</tr>
					<?php } ?>

					<?php if($od['paymethod'] == '휴대폰') { ?>
					<tr>
						<th scope="row">휴대폰번호</th>
						<td><?php echo get_text($od['bank']); ?></td>
					</tr>
					<tr>
						<th scope="row">휴대폰 결제액</th>
						<td><?php echo display_price($amount['useprice']); ?></td>
					</tr>
					<tr>
						<th scope="row">결제 확인일시</th>
						<td>
							<?php
							if(is_null_time($od['receipt_time']))
								echo '결제 확인일시 정보가 없습니다.';
							else
								echo $od['receipt_time'].' ('.get_yoil($od['receipt_time']).')';
							?>
						</td>
					</tr>
					<?php } ?>

					<?php if($od['paymethod'] == '신용카드') { ?>
					<tr>
						<th scope="row" class="sodr_sppay">신용카드 결제금액</th>
						<td>
							<?php
							if(is_null_time($od['receipt_time']))
								echo '0원';
							else
								echo display_price($amount['useprice']);
							?>
						</td>
					</tr>
					<tr>
						<th scope="row" class="sodr_sppay">카드 승인일시</th>
						<td>
							<?php
							if(is_null_time($od['receipt_time']))
								echo '신용카드 결제 일시 정보가 없습니다.';
							else
								echo substr($od['receipt_time'], 0, 20);
							?>
						</td>
					</tr>
					<?php } ?>

					<?php if($od['paymethod'] == 'KAKAOPAY') { ?>
					<tr>
						<th scope="row" class="sodr_sppay">KAKOPAY 결제금액</th>
						<td>
							<?php
							if(is_null_time($od['receipt_time']))
								echo '0원';
							else
								echo display_price($amount['useprice']);
							?>
						</td>
					</tr>
					<tr>
						<th scope="row" class="sodr_sppay">KAKAOPAY 승인일시</th>
						<td>
							<?php
							if(is_null_time($od['receipt_time']))
								echo '신용카드 결제 일시 정보가 없습니다.';
							else
								echo substr($od['receipt_time'], 0, 20);
							?>
						</td>
					</tr>
					<?php } ?>

					<?php if($od['paymethod'] == '간편결제' || ($od['od_pg'] == 'inicis' && $od['paymethod'] == '삼성페이') ) { ?>
					<tr>
						<th scope="row" class="sodr_sppay"><?php echo $s_receipt_way; ?> 결제금액</th>
						<td>
							<?php
							if(is_null_time($od['receipt_time']))
								echo '0원';
							else
								echo display_price($amount['useprice']);
							?>
						</td>
					</tr>
					<tr>
						<th scope="row" class="sodr_sppay"><?php echo $s_receipt_way; ?> 승인일시</th>
						<td>
							<?php
							if(is_null_time($od['receipt_time']))
								echo $s_receipt_way. ' 결제 일시 정보가 없습니다.';
							else
								echo substr($od['receipt_time'], 0, 20);
							?>
						</td>
					</tr>
					<?php } ?>

					<?php if($pf_auth_pg && ($od['od_settle_pid'] == $member['id']) && !in_array($od['paymethod'], array('무통장', '포인트'))) { ?>
					<tr>
						<th scope="row">결제대행사 링크</th>
						<td>
							<?php
							switch($od['od_pg']) {
								case 'lg':
									$pg_url  = 'http://pgweb.uplus.co.kr';
									$pg_test = 'LG유플러스 ';
									if($od['od_test']) {
										$pg_url = 'http://pgweb.uplus.co.kr/tmert';
										$pg_test .= '테스트 ';
									}
									break;
								case 'inicis':
									$pg_url  = 'https://iniweb.inicis.com/';
									$pg_test = 'KG이니시스 ';
									break;
								case 'KAKAOPAY':
									$pg_url  = 'https://mms.cnspay.co.kr';
									$pg_test = 'KAKAOPAY ';
									break;
								case 'kcp':
									$pg_url  = 'http://admin8.kcp.co.kr';
									$pg_test = 'NHN KCP ';
									if($od['od_test']) {
										// 로그인 아이디 / 비번
										// 일반 : test1234 / test12345
										// 에스크로 : escrow / escrow913
										$pg_url = 'http://testadmin8.kcp.co.kr';
										$pg_test .= '테스트 ';
									}

							}
							echo "<a href=\"{$pg_url}\" target=\"_blank\" class=\"btn_small blue\">{$pg_test}바로가기</a>";
							//------------------------------------------------------------------------------
							?>
						</td>
					</tr>
					<?php } ?>
					<?php if($od['taxsave_yes'] == 'S' || $od['taxsave_yes'] == 'Y') { ?>
					<tr>
						<th scope="row">현금영수증 신청여부</th>
						<td class="lh4">
							<?php if($od['taxsave_yes'] == 'S') { ?>
							사업자 지출증빙용<br>
							사업자번호 : <?php echo $od['tax_saupja_no']; ?>
							<?php } else if($od['taxsave_yes'] == 'Y') { ?>
							개인 소득공제용<br>
							핸드폰 : <?php echo $od['tax_hp']; ?>
							<?php } ?>
						</td>
					</tr>
					<?php } ?>
					<?php if($od['taxbill_yes'] == 'Y') { ?>
					<tr>
						<th scope="row">세금계산서 신청여부</th>
						<td class="lh4">
							회사명 : <?php echo $od['company_name']; ?><br>
							대표자명 : <?php echo $od['company_owner']; ?><br>
							사업자번호 : <?php echo $od['company_saupja_no']; ?><br>
							사업장주소 : <?php echo $od['company_addr']; ?><br>
							업태 : <?php echo $od['company_item']; ?><br>
							종목 : <?php echo $od['company_service']; ?>
						</td>
					</tr>
					<?php } ?>
					</tbody>
					</table>
				</div>
			</section>
		</div>

		<div class="btn_confirm">
			<input type="submit" value="결제정보 수정" class="btn_medium">
			<a href="javascript:window.close();" class="btn_medium bx-white">닫기</a>
		</div>
		</form>
	</section>

	<section id="anc_sodr_memo">
		<h3 class="anc_tit">관리자메모</h3>
		<?php echo $pg_anchor; ?>
		<div class="local_desc02 local_desc">
			<p>현재 열람 중인 주문에 대한 내용을 메모하는곳입니다.</p>
		</div>

		<form name="frmorderform3" action="./partner_odr_formupdate.php" method="post">
		<input type="hidden" name="od_id" value="<?php echo $od_id; ?>">
		<input type="hidden" name="mod_type" value="memo">

		<label for="shop_memo" class="sound_only">관리자메모</label>
		<textarea name="shop_memo" id="shop_memo" rows="8" class="frm_textbox"><?php echo stripslashes($od['shop_memo']); ?></textarea>

		<div class="btn_confirm">
			<input type="submit" value="관리자메모 수정" class="btn_medium">
			<a href="javascript:window.close();" class="btn_medium bx-white">닫기</a>
		</div>
		</form>
	</section>

	<section id="anc_sodr_addr">
		<h3 class="anc_tit">주문자/배송지 정보</h3>
		<?php echo $pg_anchor; ?>

		<form name="frmorderform2" action="./partner_odr_formupdate.php" method="post">
		<input type="hidden" name="od_id" value="<?php echo $od_id; ?>">
		<input type="hidden" name="mod_type" value="info">

		<div class="compare_wrap">
			<section id="anc_sodr_orderer" class="compare_left">
				<h3>주문하신 분</h3>

				<div class="tbl_frm01">
					<table>
					<colgroup>
						<col class="w100">
						<col>
					</colgroup>
					<tbody>
					<tr>
						<th scope="row"><label for="od_name">이름</label></th>
						<td><input type="text" name="name" value="<?php echo get_text($od['name']); ?>" id="od_name" required class="frm_input required"></td>
					</tr>
					<tr>
						<th scope="row"><label for="telephone">전화번호</label></th>
						<td><input type="text" name="telephone" value="<?php echo get_text($od['telephone']); ?>" id="telephone" class="frm_input"></td>
					</tr>
					<tr>
						<th scope="row"><label for="cellphone">핸드폰</label></th>
						<td><input type="text" name="cellphone" value="<?php echo get_text($od['cellphone']); ?>" id="cellphone" required class="frm_input required"></td>
					</tr>
					<tr>
						<th scope="row">주소</th>
						<td>
							<label for="zip" class="sound_only">우편번호</label>
							<input type="text" name="zip" value="<?php echo $od['zip']; ?>" id="zip" required class="frm_input required" size="5" maxlength="5">
							<button type="button" class="btn_small grey" onclick="win_zip('frmorderform2', 'zip', 'addr1', 'addr2', 'addr3', 'addr_jibeon');">주소검색</button><br>
							<span id="od_win_zip" style="display:block"></span>
							<input type="text" name="addr1" value="<?php echo get_text($od['addr1']); ?>" id="addr1" required class="frm_input required" size="35">
							<label for="addr1">기본주소</label><br>
							<input type="text" name="addr2" value="<?php echo get_text($od['addr2']); ?>" id="addr2" class="frm_input" size="35">
							<label for="addr2">상세주소</label><br>
							<input type="text" name="addr3" value="<?php echo get_text($od['addr3']); ?>" id="addr3" class="frm_input" size="35">
							<label for="addr3">참고항목</label><br>
							<input type="hidden" name="addr_jibeon" value="<?php echo get_text($od['addr_jibeon']); ?>">
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="od_email">E-mail</label></th>
						<td><input type="text" name="email" value="<?php echo $od['email']; ?>" id="od_email" required class="frm_input required" size="30"></td>
					</tr>
					<tr>
						<th scope="row"><span class="sound_only">주문하신 분 </span>IP Address</th>
						<td><?php echo $od['od_ip']; ?></td>
					</tr>
					</tbody>
					</table>
				</div>
			</section>

			<section id="anc_sodr_taker" class="compare_right">
				<h3>받으시는 분</h3>

				<div class="tbl_frm01">
					<table>
					<colgroup>
						<col class="w100">
						<col>
					</colgroup>
					<tbody>
					<tr>
						<th scope="row"><label for="b_name">이름</label></th>
						<td><input type="text" name="b_name" value="<?php echo get_text($od['b_name']); ?>" id="b_name" required class="frm_input required"></td>
					</tr>
					<tr>
						<th scope="row"><label for="b_telephone">전화번호</label></th>
						<td><input type="text" name="b_telephone" value="<?php echo get_text($od['b_telephone']); ?>" id="b_telephone" class="frm_input"></td>
					</tr>
					<tr>
						<th scope="row"><label for="b_cellphone">핸드폰</label></th>
						<td><input type="text" name="b_cellphone" value="<?php echo get_text($od['b_cellphone']); ?>" id="b_cellphone" required class="frm_input required"></td>
					</tr>
					<tr>
						<th scope="row">주소</th>
						<td>
							<label for="b_zip" class="sound_only">우편번호</label>
							<input type="text" name="b_zip" value="<?php echo $od['b_zip']; ?>" id="b_zip" required class="frm_input required" size="5" maxlength="5">
							<button type="button" class="btn_small grey" onclick="win_zip('frmorderform2', 'b_zip', 'b_addr1', 'b_addr2', 'b_addr3', 'b_addr_jibeon');">주소검색</button><br>
							<input type="text" name="b_addr1" value="<?php echo get_text($od['b_addr1']); ?>" id="b_addr1" required class="frm_input required" size="35">
							<label for="b_addr1">기본주소</label><br>
							<input type="text" name="b_addr2" value="<?php echo get_text($od['b_addr2']); ?>" id="b_addr2" class="frm_input" size="35">
							<label for="b_addr2">상세주소</label><br>
							<input type="text" name="b_addr3" value="<?php echo get_text($od['b_addr3']); ?>" id="b_addr3" class="frm_input" size="35">
							<label for="b_addr3">참고항목</label><br>
							<input type="hidden" name="b_addr_jibeon" value="<?php echo get_text($od['b_addr_jibeon']); ?>">
						</td>
					</tr>
					<tr>
						<th scope="row">전달 메세지</th>
						<td><?php if($od['memo']) echo get_text($od['memo'], 1);else echo "없음"; ?></td>
					</tr>
					</tbody>
					</table>
				</div>
			</section>

		</div>

		<div class="btn_confirm">
			<input type="submit" value="주문자/배송지 정보 수정" class="btn_medium">
			<a href="javascript:window.close();" class="btn_medium bx-white">닫기</a>
		</div>
		</form>
	</section>

</div>

<script>
$(function() {
	// 부분취소창
	$(".orderpartcancel").on("click", function() {
		var href = this.href;
		window.open(href, "partcancelwin", "left=100, top=100, width=600, height=350, scrollbars=yes");
		return false;
	});
});

function form_submit(f)
{
	var status = document.pressed;

	if(status == "주문상태저장" || status == "운송장번호수정") {
		if(!is_checked("chk[]")) {
			alert("처리할 자료를 하나 이상 선택해 주십시오.");
			return false;
		}
	}

	if(status == "운송장번호수정") {
		f.action = "./partner_odr_baesongupdate.php";
		return true;
	}

	if(confirm("주문상태를 변경하시겠습니까?")) {
		return true;
	} else {
		return false;
	}
}
</script>

<?php
include_once(TB_ADMIN_PATH."/admin_tail.sub.php");
?>