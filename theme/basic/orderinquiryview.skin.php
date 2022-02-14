<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TB_THEME_PATH.'/aside_my.skin.php');
?>

<div id="con_lf">
	<h2 class="pg_tit">
		<span><?php echo $tb['title']; ?></span>
		<p class="pg_nav">HOME<i>&gt;</i>마이페이지<i>&gt;</i><?php echo $tb['title']; ?></p>
	</h2>

	<p class="pg_cnt">주문번호 <em><?php echo $od_id; ?></em> 의 상세내역입니다.</p>

	<div class="tbl_head02 tbl_wrap">
		<table>
		<colgroup>
			<col class="w90">
			<col>
			<col class="w100">
			<col class="w140">
		</colgroup>
		<thead>
		<tr>
			<th scope="col">주문일자</th>
			<th scope="col">상품정보</th>
			<th scope="col">결제금액</th>
			<th scope="col">상태</th>
		</tr>
		</thead>
		<tbody>
		<?php
        $st_count1 = $st_count2 = $st_cancel_price = 0;
        $custom_cancel = false;

		$sql = " select * from shop_order where od_id = '$od_id' group by od_id order by index_no desc ";
		$result = sql_query($sql);
		for($i=0; $row=sql_fetch_array($result); $i++) {
			$sql = " select * from shop_cart where od_id = '$od_id' ";
			$sql.= " group by gs_id order by io_type asc, index_no asc ";
			$res = sql_query($sql);
			$rowspan = sql_num_rows($res) + 1;

			for($k=0; $ct=sql_fetch_array($res); $k++) {
				$rw = get_order($ct['od_no']);
				$gs = unserialize($rw['od_goods']);

				$dlcomp = explode('|', trim($rw['delivery']));

				$href = TB_SHOP_URL.'/view.php?index_no='.$rw['gs_id'];

				unset($it_name);
				$it_options = print_complete_options($ct['gs_id'], $ct['od_id']);
				if($it_options){
					$it_name = '<div class="sod_opt">'.$it_options.'</div>';
				}

				if($k == 0) {
		?>
		<tr>
			<td class="tac" rowspan="<?php echo $rowspan; ?>" style="padding: 0;">
				<p class="bold"><?php echo substr($rw['od_time'],0,10);?></p>
				<p class="padt5"><a href="<?php echo TB_SHOP_URL; ?>/orderprint.php?od_id=<?php echo $rw['od_id']; ?>" onclick="win_open(this,'winorderprint','670','600','yes');return false;" class="btn_small bx-white"><i class="fa fa-print fs14 vam marb2 marr3"></i> 인쇄</a></p>
                <?php if($rw['dan']>=2){ ?>
				<p class="padt5"><a href="<?php echo TB_COMMON_URL; ?>/popup/statement.php?od_id=<?php echo $rw['od_id']; ?>" onclick="win_open(this,'winorderstatement','670','600','yes');return false;" class="btn_small bx-white"><i class="fa fa-file-text fs14 vam marb2 marr3"></i> 거래명세서</a></p>
				<?php } ?>
			</td>
		</tr>
		<?php } ?>
		<tr class="rows">
			<td>
				<div class="ini_wrap">
					<table class="wfull">
					<colgroup>
						<col class="w70">
						<col>
					</colgroup>
					<tr>
						<td class="vat tal"><a href="<?php echo $href; ?>"><?php echo get_od_image($rw['od_id'], $gs['simg1'], 60, 60); ?></a></td>
						<td class="vat tal">
							<a href="<?php echo $href; ?>"><?php echo get_text($gs['gname']); ?></a>
							<?php echo $it_name; ?>
							<p class="padt3 fc_999">주문번호 : <?php echo $rw['od_id']; ?> / 수량 : <?php echo display_qty($rw['sum_qty']); ?> / 배송비 : <?php echo display_price($rw['baesong_price']); ?></p>
							<?php if($rw['dan'] == 5) { ?>
							<p class="padt3"><a href="<?php echo TB_SHOP_URL; ?>/orderreview.php?gs_id=<?php echo $rw['gs_id']; ?>&od_id=<?php echo $rw['od_id']; ?>" onclick="win_open(this, 'winorderreview', '650', '530','yes');return false;" class="btn_ssmall bx-white">구매후기 작성</a></p>
							<?php } ?>
						</td>
					</tr>
					</table>
				</div>
			</td>
			<td class="tar"><?php echo display_price($rw['use_price']); ?></td>
			<td class="tac">
				<p><?php echo $gw_status[$rw['dan']]; ?></p>
				<?php if($dlcomp[0] && $rw['delivery_no']) { ?>
				<p class="padt3 fc_90"><?php echo $dlcomp[0]; ?><br><?php echo $rw['delivery_no']; ?></p>
				<?php } ?>
				<?php if($dlcomp[1] && $rw['delivery_no']) { ?>
				<p class="padt3"><?php echo get_delivery_inquiry($rw['delivery'], $rw['delivery_no'], 'btn_ssmall'); ?></p>
				<?php } ?>
			</td>
		</tr>
		<?php
				$st_count1++;
				if(in_array($rw['dan'], array('1','2')))
					$st_count2++;

				$st_cancel_price += $rw['cancel_price'];
			}
		}

		// 주문상태가 배송중 이전 단계이면 고객 취소 가능
		if($st_count1 > 0 && $st_count1 == $st_count2)
			$custom_cancel = true;
		?>
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

	<section id="sod_fin_pay">
		<h2 class="anc_tit">결제정보</h2>
		<div class="tbl_frm01 tbl_wrap">
			<table>
			<colgroup>
				<col class="w140">
				<col>
			</colgroup>
			<tbody>
			<tr>
				<th scope="row">주문번호</th>
				<td><?php echo $od_id; ?></td>
			</tr>
			<tr>
				<th scope="row">주문일시</th>
				<td><?php echo $od['od_time']; ?></td>
			</tr>
			<tr>
				<th scope="row">결제방식</th>
				<td>
					<?php echo ($easy_pay_name ? $easy_pay_name.'('.$od['paymethod'].')' : $od['paymethod']); ?>
					<?php echo ($od['od_card_nm'] ? ', 카드명('.$od['od_card_nm'].')' : ''); ?>
					<?php echo ($od['od_sell_mm'] ? ', 할부개월('.$od['od_sell_mm'].')' : ''); ?>
				</td>
			</tr>
			<tr>
				<th scope="row">결제금액</th>
				<td><?php echo display_price($stotal['useprice']); ?></td>
			</tr>
			<?php
			if(!is_null_time($od['receipt_time'])) {
			?>
			<tr>
				<th scope="row">결제일시</th>
				<td><?php echo $od['receipt_time']; ?></td>
			</tr>
			<?php
			}

			// 승인번호, 휴대폰번호, 거래번호
			if($app_no_subj) {
			?>
			<tr>
				<th scope="row"><?php echo $app_no_subj; ?></th>
				<td><?php echo $app_no; ?></td>
			</tr>
			<?php
			}

			// 계좌정보
			if($disp_bank) {
			?>
			<tr>
				<th scope="row">입금자명</th>
				<td><?php echo get_text($od['deposit_name']); ?></td>
			</tr>
			<tr>
				<th scope="row">입금계좌</th>
				<td><?php echo get_text($od['bank']); ?></td>
			</tr>
			<?php
			}

			if($disp_receipt) {
			?>
			<tr>
				<th scope="row">영수증</th>
				<td>
					<?php

if($_SERVER['REMOTE_ADDR']=='211.118.5.14'){
	//print_r($od);
 }
 
					if($od['paymethod'] == '휴대폰')
					{
						if($od['od_pg'] == 'lg') {
							require_once TB_SHOP_PATH.'/settle_lg.inc.php';
							$LGD_TID      = $od['od_tno'];
							$LGD_MERTKEY  = $default['de_lg_mid'];
							$LGD_HASHDATA = md5($LGD_MID.$LGD_TID.$LGD_MERTKEY);

							$hp_receipt_script = 'showReceiptByTID(\''.$LGD_MID.'\', \''.$LGD_TID.'\', \''.$LGD_HASHDATA.'\');';
						} else if($od['od_pg'] == 'inicis') {
							$hp_receipt_script = 'window.open(\'https://iniweb.inicis.com/DefaultWebApp/mall/cr/cm/mCmReceipt_head.jsp?noTid='.$od['od_tno'].'&noMethod=1\',\'receipt\',\'width=430,height=700\');';
						} else if($od['od_pg'] == 'kcp') {
							$hp_receipt_script = 'window.open(\''.TB_BILL_RECEIPT_URL.'mcash_bill&tno='.$od['od_tno'].'&order_no='.$od['od_id'].'&trade_mony='.$stotal['useprice'].'\', \'winreceipt\', \'width=500,height=690,scrollbars=yes,resizable=yes\');';
						} else if($od['od_pg'] == 'allat') {
							$hp_receipt_script = 'window.open(\'http://www.allatpay.com/servlet/AllatBizPop/member/pop_card_receipt.jsp?shop_id='.$default['de_allat_mid'].'&order_no='.$od['od_id'].'\',\'app\',\'width=410,height=650,scrollbars=0\');';
						}
					?>
					<a href="javascript:;" onclick="<?php echo $hp_receipt_script; ?>" class="btn_small">영수증 출력</a>
					<?php
					}

					if( in_array($od['paymethod'], array('신용카드', '신용카드(카드번호)', '연구비카드')) )
					{
						if($od['od_pg'] == 'lg') {
							require_once TB_SHOP_PATH.'/settle_lg.inc.php';
							$LGD_TID      = $od['od_tno'];
							$LGD_MERTKEY  = $default['de_lg_mid'];
							$LGD_HASHDATA = md5($LGD_MID.$LGD_TID.$LGD_MERTKEY);

							$card_receipt_script = 'showReceiptByTID(\''.$LGD_MID.'\', \''.$LGD_TID.'\', \''.$LGD_HASHDATA.'\');';
						} else if($od['od_pg'] == 'inicis') {
							$card_receipt_script = 'window.open(\'https://iniweb.inicis.com/DefaultWebApp/mall/cr/cm/mCmReceipt_head.jsp?noTid='.$od['od_tno'].'&noMethod=1\',\'receipt\',\'width=430,height=700\');';
						} else if($od['od_pg'] == 'kcp') {
							$card_receipt_script = 'window.open(\''.TB_BILL_RECEIPT_URL.'card_bill&tno='.$od['od_tno'].'&order_no='.$od['od_id'].'&trade_mony='.$stotal['useprice'].'\', \'winreceipt\', \'width=470,height=815,scrollbars=yes,resizable=yes\');';
						} else if($od['od_pg'] == 'allat') {
							$card_receipt_script = 'window.open(\'http://www.allatpay.com/servlet/AllatBizPop/member/pop_card_receipt.jsp?shop_id='.$default['de_allat_mid'].'&order_no='.$od['od_id'].'\',\'app\',\'width=410,height=650,scrollbars=0\');';
                        } else if($od['od_pg'] == 'kiwoom') {
                            $card_receipt_script = UfKiwoomPay::getReceiptJs($od);
                        } else if($od['od_pg'] == 'pay2pay_pay') {
                            $card_receipt_script = Pay2Pay_Pay::getReceiptJs($od);
						} else if($od['od_pg'] == 'psys') {
                            $card_receipt_script = PsysPay::getReceiptJs($od);
						}
					?>
					<a href="javascript:;" onclick="<?php echo $card_receipt_script; ?>" class="btn_small">영수증 출력</a>
					<?php
					}

					if($od['paymethod'] == 'KAKAOPAY')
					{
						$card_receipt_script = 'window.open(\'https://mms.cnspay.co.kr/trans/retrieveIssueLoader.do?TID='.$od['od_tno'].'&type=0\', \'popupIssue\', \'toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,width=420,height=540\');';
					?>
					<a href="javascript:;" onclick="<?php echo $card_receipt_script; ?>" class="btn_small">영수증 출력</a>
					<?php
					}
					?>
				</td>
			</tr>
			<?php
			}

			// 현금영수증 발급을 사용하는 경우에만
			if($default['de_taxsave_use'] && ($od['od_pg'] != 'allat')) {
				// 미수금이 없고 현금일 경우에만 현금영수증을 발급 할 수 있습니다.
				if(!is_null_time($od['receipt_time']) && ($od['paymethod'] == '무통장' || $od['paymethod'] == '계좌이체' || $od['paymethod'] == '가상계좌')) {
			?>
			<tr>
				<th scope="row">현금영수증</th>
				<td>
				<?php
				if($od['od_cash'])
				{
					if($od['od_pg'] == 'lg') {
						require_once TB_SHOP_PATH.'/settle_lg.inc.php';

						switch($od['paymethod']) {
							case '계좌이체':
								$trade_type = 'BANK';
								break;
							case '가상계좌':
								$trade_type = 'CAS';
								break;
							default:
								$trade_type = 'CR';
								break;
						}
						$cash_receipt_script = 'javascript:showCashReceipts(\''.$LGD_MID.'\',\''.$od['od_id'].'\',\''.$od['od_casseqno'].'\',\''.$trade_type.'\',\''.$CST_PLATFORM.'\');';
					} else if($od['od_pg'] == 'inicis') {
						$cash = unserialize($od['od_cash_info']);
						$cash_receipt_script = 'window.open(\'https://iniweb.inicis.com/DefaultWebApp/mall/cr/cm/Cash_mCmReceipt.jsp?noTid='.$cash['TID'].'&clpaymethod=22\',\'showreceipt\',\'width=380,height=540,scrollbars=no,resizable=no\');';
					} else if($od['od_pg'] == 'kcp') {
						require_once TB_SHOP_PATH.'/settle_kcp.inc.php';

						$cash = unserialize($od['od_cash_info']);
						$cash_receipt_script = 'window.open(\''.TB_CASH_RECEIPT_URL.$default['de_kcp_mid'].'&orderid='.$od_id.'&bill_yn=Y&authno='.$cash['receipt_no'].'\', \'taxsave_receipt\', \'width=360,height=647,scrollbars=0,menus=0\');';
					}
				?>
					<a href="javascript:;" onclick="<?php echo $cash_receipt_script; ?>" class="btn_small">현금영수증 확인하기</a>
				<?php
				}
				else {
				?>
					<a href="javascript:;" onclick="window.open('<?php echo TB_SHOP_URL; ?>/taxsave.php?od_id=<?php echo $od_id; ?>', 'taxsave', 'width=550,height=400,scrollbars=1,menus=0');" class="btn_small">현금영수증 발급하기</a>
				<?php } ?>
				</td>
			</tr>
			<?php
				}
			}
			?>
			</tbody>
			</table>
		</div>
	</section>

	<section id="sod_fin_orderer">
		<h2 class="anc_tit">주문하신 분</h2>
		<div class="tbl_frm01 tbl_wrap">
			<table>
			<colgroup>
				<col class="w140">
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
				<col class="w140">
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

	<?php
	// 취소한 내역이 없다면
	if($st_cancel_price == 0 && $custom_cancel) {
	?>
	<?php if($od['od_pg'] == 'allat') { // 올앳페이일때 ?>
	<?php
	require_once(TB_SHOP_PATH.'/settle_allat.inc.php');
	?>
	<script language="Javascript" src="https://tx.allatpay.com/common/NonAllatPayREPlus.js" charset="euc-kr"></script>
	<script language="Javascript">
		// 결제페이지 호출
		function ftn_approval(dfm) {
			Allat_Plus_Api(dfm);
		}

		// 결과값 반환( receive 페이지에서 호출 )
		function result_submit(result_cd,result_msg,enc_data) {

			if( result_cd != '0000' ){
				window.setTimeout(function(){alert(result_cd + " : " + result_msg);},1000);
			} else {
				fm.allat_enc_data.value = enc_data;
				fm.action = tb_shop_url+"/orderinquirycancel.php";
				fm.method = "post";
				fm.target = "_self";
				fm.submit();
			}
		}
	</script>
	<?php } ?>
	<section id="sod_fin_cancel">
		<h2>주문취소</h2>
		<button type="button" onclick="document.getElementById('sod_fin_cancelfrm').style.display='block';" class="btn_medium wset">주문 취소하기</button>

		<div id="sod_fin_cancelfrm">
			<form name="fm" method="post" action="<?php echo TB_SHOP_URL; ?>/orderinquirycancel.php" onsubmit="return fcancel_check(this);">
			<input type="hidden" name="od_id" value="<?php echo $od_id; ?>">
			<input type="hidden" name="token" value="<?php echo $token; ?>">

			<?php if($od['od_pg'] == 'allat') { // 올앳페이일때 ?>
			<input type="hidden" name="test_cross_key" value="<?php echo $at_cross_key; ?>">
			<input type="hidden" name="allat_shop_id" value="<?php echo $at_shop_id; ?>">
			<input type="hidden" name="allat_order_no" value="<?php echo $od_id; ?>">
			<input type="hidden" name="allat_amt" value="<?php echo $stotal['useprice']; ?>">
			<input type="hidden" name="allat_pay_type" value="<?php echo $at_pay_method[$od['paymethod']]; ?>">
			<input type="hidden" name="shop_receive_url" value="<?php echo $receive_url; ?>">
			<input type="hidden" name="allat_enc_data" value="">
			<input type="hidden" name="allat_opt_pin" value="NOUSE">
			<input type="hidden" name="allat_opt_mod" value="WEB">
			<input type="hidden" name="allat_seq_no" value="<?php echo $od['od_tno']; ?>">
			<input type="hidden" name="allat_test_yn" value="<?php echo ($default['de_card_test']?"Y":"N"); ?>">
			<?php
			} else if( $od['od_pg'] == PsysPay::PG_IDEN ) {
                echo PsysPay::getCancelFormHtml($od, $stotal);
            } ?>

			<label for="cancel_memo">취소사유</label>
			<input type="text" name="cancel_memo" id="cancel_memo" required class="frm_input required" size="40" maxlength="100">
			<input type="submit" value="확인" class="btn_small">
			</form>
		</div>
	</section>
	<?php } ?>
</div>

<script>
var fcancel_f = null;
function fcancel_check(f)
{
    fcancel_f = f;

    if(!confirm("주문을 정말 취소하시겠습니까?"))
        return false;

    var memo = f.cancel_memo.value;
    if(memo == "") {
        alert("취소사유를 입력해 주십시오.");
        return false;
    }

	<?php if($od['od_pg'] == 'allat') { // 올앳페이일때 ?>
	ftn_approval(f);
	<?php
	}  else if( $od['od_pg'] == PsysPay::PG_IDEN ) {
        echo PsysPay::getCancelFormJs($od);
    } else { // 그외 결제사 ?>
    return true;
	<?php } ?>
}

// pg 승인 취소 응답
function fcancel_receive(json) {

    <?=PsysPay::getCancelReceiveJs('fcancel_f', 'json')?>
}

</script>
