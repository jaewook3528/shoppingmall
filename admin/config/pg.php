<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form name="fregform" id="fregform" action="./config/pg_update.php" method="post">
<input type="hidden" name="token" value="">

<h2>전자결제 (PG) 설정</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">결제수단</th>
		<td class="td_label">
			<input type="checkbox" name="de_bank_use" value="1" id="de_bank_use"<?php echo get_checked($default['de_bank_use'], "1"); ?>>
			<label for="de_bank_use">무통장</label>
			<input type="checkbox" name="de_card_use" value="1" id="de_card_use"<?php echo get_checked($default['de_card_use'], "1"); ?>>
			<label for="de_card_use">신용카드</label>
			<input type="checkbox" name="de_card_direct_use" value="1" id="de_card_direct_use"<?php echo get_checked($default['de_card_direct_use'], "1"); ?>>
			<label for="de_card_direct_use">신용카드(카드번호)</label>
			<input type="checkbox" name="de_iche_use" value="1" id="de_iche_use"<?php echo get_checked($default['de_iche_use'], "1"); ?>>
			<label for="de_iche_use">실시간계좌이체</label>
			<input type="checkbox" name="de_vbank_use" value="1" id="de_vbank_use"<?php echo get_checked($default['de_vbank_use'], "1"); ?>>
			<label for="de_vbank_use">가상계좌</label>
			<input type="checkbox" name="de_hp_use" value="1" id="de_hp_use"<?php echo get_checked($default['de_hp_use'], "1"); ?>>
			<label for="de_hp_use">휴대폰결제</label>
			<!-- psys 결제 추가 S-->
			<input type="checkbox" name="de_psys_rnd_use" value="1" id="de_psys_rnd_use"<?php echo get_checked($default['de_psys_rnd_use'], "1"); ?>>
			<label for="de_psys_rnd_use">연구비카드</label>
			<input type="checkbox" name="de_psys_long_use" value="1" id="de_psys_long_use"<?php echo get_checked($default['de_psys_long_use'], "1"); ?>>
			<label for="de_psys_long_use">장기무이자(인증)</label>
			<!-- psys 결제 추가 E-->
		</td>
	</tr>
	<tr>
		<th scope="row">결제 테스트</th>
		<td class="td_label">
			<input type="radio" name="de_card_test" value="0" id="de_card_test_1"
			<?php echo get_checked($default['de_card_test'], "0"); ?>>
			<label for="de_card_test_1">실결제</label>
			<input type="radio" name="de_card_test" value="1" id="de_card_test_2"
			<?php echo get_checked($default['de_card_test'], "1"); ?>>
			<label for="de_card_test_2">테스트결제</label>
			<?php echo help('PG사의 결제 테스트를 하실 경우에 체크하세요. 결제단위 최소 1,000원'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="de_pg_service">결제대행사</label></th>
		<td>
			<select name="de_pg_service" id="de_pg_service">
				<?php echo option_selected("kcp", $default['de_pg_service'], "NHN KCP"); ?>
				<?php echo option_selected("lg",  $default['de_pg_service'], "LG유플러스"); ?>
				<?php echo option_selected("inicis", $default['de_pg_service'], "KG이니시스"); ?>
				<?php echo option_selected("allat", $default['de_pg_service'], "KG올앳"); ?>
                <?php echo option_selected("kiwoom", $default['de_pg_service'], "키움페이"); ?>
                <?php echo option_selected("pay2pay_pay", $default['de_pg_service'], "페이투페이"); ?>
			</select>
			<?php echo help('쇼핑몰에서 사용할 결제대행사를 선택합니다.'); ?>
            <?php include_once TB_ADMIN_PATH.'/../plugin/kiwoompay/UfKiwoomPay.php'; UfKiwoomPay::getConfigGuideHtml(); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">복합과세 결제</th>
		<td>
			<input type="checkbox" name="de_tax_flag_use" value="1" id="de_tax_flag_use"
			<?php echo get_checked($default['de_tax_flag_use'], "1"); ?>>
			<label for="de_tax_flag_use">사용함</label>
			<?php echo help("복합과세(과세, 비과세) 결제를 사용하려면 체크하십시오.<br>복합과세 결제를 사용하기 전 PG사에 별도로 결제 신청을 해주셔야 합니다. 사용시 PG사로 문의하여 주시기 바랍니다."); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">현금영수증 발급사용</th>
		<td>
			<input type="checkbox" name="de_taxsave_use" value="1" id="de_taxsave_use"
			<?php echo get_checked($default['de_taxsave_use'], "1"); ?>>
			<label for="de_taxsave_use">사용함</label>
			<?php echo help("현금영수증 발급 취소는 PG사에서 지원하는 현금영수증 취소 기능을 사용하시기 바랍니다."); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">신용카드 무이자할부사용</th>
		<td>
			<input type="checkbox" name="de_card_noint_use" value="1" id="de_card_noint_use"
			<?php echo get_checked($default['de_card_noint_use'], "1"); ?>>
			<label for="de_card_noint_use">사용함</label>
			<?php echo help("주문시 신용카드 무이자할부를 가능하게 할것인지를 설정합니다.<br>사용함으로 설정하시면 PG사 가맹점 관리자 페이지에서 설정하신 무이자할부 설정이 적용됩니다.<br>사용안함으로 설정하시면 PG사 무이자 이벤트 카드를 제외한 모든 카드의 무이자 설정이 적용되지 않습니다."); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">PG사 간편결제 버튼사용</th>
		<td>
			<input type="checkbox" name="de_easy_pay_use" value="1" id="de_easy_pay_use"
			<?php echo get_checked($default['de_easy_pay_use'], "1"); ?>>
			<label for="de_easy_pay_use">사용함</label>
			<?php echo help("주문서 작성 페이지에 PG사 간편결제(PAYCO, PAYNOW, KPAY) 버튼의 별도 사용 여부를 설정합니다."); ?>
		</td>
	</tr>
    <tr>
        <th scope="row"><label for="de_card_installment">최대 할부가능 개월</label></th>
        <td>
            <input type="text" name="de_card_installment" value="<?php echo $default['de_card_installment']; ?>" id="de_card_installment" class="frm_input" size="5" maxlength="2">
            <?php echo help('최대 가능한 할부개월 값을 입력해 주십시오. 0 : 제한없음.<br>적용되는 PG사: 키움페이'); ?>
        </td>
    </tr>
	</tbody>
	</table>
</div>

<div class="pg_info_fld kcp_info_fld">
	<h2>NHN KCP 계약정보</h2>
	<div class="tbl_frm01">
		<table>
		<colgroup>
			<col class="w180">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row"><label for="de_kcp_mid">NHN KCP SITE CODE</label></th>
			<td>
				<input type="text" name="de_kcp_mid" value="<?php echo $default['de_kcp_mid']; ?>" id="de_kcp_mid" class="frm_input" size="10">
				<a href="http://www.kcp.co.kr/main.do" target="_blank" class="btn_small blue">NHN KCP서비스신청하기</a>
				<?php echo help('NHN KCP에서 발급받으신 사이트코드를 입력해 주세요.'); ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="de_kcp_site_key">NHN KCP SITE KEY</label></th>
			<td>
				<input type="text" name="de_kcp_site_key" value="<?php echo $default['de_kcp_site_key']; ?>" id="de_kcp_site_key" class="frm_input" size="40">
				<?php echo help("25자리 영대소문자와 숫자 - 그리고 _ 로 이루어 집니다. SITE KEY 발급 NHN KCP 전화: 1544-8660<br>예) 1Q9YRV83gz6TukH8PjH0xFf__"); ?>
			</td>
		</tr>
		<tr>
			<th scope="row">가상계좌 입금통보 URL</th>
			<td>
				<?php echo TB_SHOP_URL; ?>/settle_kcp_common.php
				<?php echo help('위 주소를 <strong><a href="http://admin.kcp.co.kr" target="_blank">NHN KCP 관리자</a> &gt; 상점정보관리 &gt; 정보변경 &gt; 공통URL 정보 &gt; 공통URL 변경</strong>후에 입력하셔야 자동으로 입금 통보됩니다.'); ?>
			</td>
		</tr>
		</tbody>
		</table>
	</div>
</div>

<div class="pg_info_fld lg_info_fld">
	<h2>LG유플러스 계약정보</h2>
	<div class="tbl_frm01">
		<table>
		<colgroup>
			<col class="w180">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row"><label for="de_lg_mid">LG유플러스 상점아이디</label></th>
			<td>
				<input type="text" name="de_lg_mid" value="<?php echo $default['de_lg_mid']; ?>" id="de_lg_mid" class="frm_input" size="10" maxlength="20">
				<a href="http://ecredit.uplus.co.kr/" target="_blank" class="btn_small blue">LG유플러스 서비스신청하기</a>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="de_lg_mert_key">LG유플러스 MertKey</label></th>
			<td>
				<input type="text" name="de_lg_mert_key" value="<?php echo $default['de_lg_mert_key']; ?>" id="de_lg_mert_key" class="frm_input" size="40" maxlength="50">
				<em>예) 95160cce09854ef44d2edb2bfb05f9f3</em>
				<?php echo help('상점MertKey는 LG유플러스 상점관리자 <strong>[계약정보 &gt; 상점정보관리 &gt; 시스템연동정보]</strong>에서 확인하실 수 있습니다.'); ?>
			</td>
		</tr>
		</tbody>
		</table>
	</div>
</div>

<div class="pg_info_fld inicis_info_fld">
	<h2>KG이니시스 계약정보</h2>
	<div class="tbl_frm01">
		<table>
		<colgroup>
			<col class="w180">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row"><label for="de_inicis_mid">KG이니시스 상점아이디</label></th>
			<td>
				<input type="text" name="de_inicis_mid" value="<?php echo $default['de_inicis_mid']; ?>" id="de_inicis_mid" class="frm_input" size="10">
				<a href="https://www.inicis.com/" target="_blank" class="btn_small blue">KG이니시스 서비스신청하기</a>
				<?php echo help('KG이니시스로 부터 발급 받으신 상점아이디(MID)를 입력해 주십시오.'); ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="de_inicis_admin_key">KG이니시스 키패스워드</label></th>
			<td>
				<input type="text" name="de_inicis_admin_key" value="<?php echo $default['de_inicis_admin_key']; ?>" id="de_inicis_admin_key" class="frm_input" size="5" maxlength="4">
				<?php echo help('KG이니시스에서 발급받은 4자리 상점 키패스워드를 입력합니다.<br>KG이니시스 상점관리자 패스워드와 관련이 없습니다.<br>키패스워드 값을 확인하시려면 상점측에 발급된 키파일 안의 readme.txt 파일을 참조해 주십시오.'); ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="de_inicis_sign_key">KG이니시스 웹결제 사인키</label></th>
			<td>
				<input type="text" name="de_inicis_sign_key" value="<?php echo $default['de_inicis_sign_key']; ?>" id="de_inicis_sign_key" class="frm_input" size="40" maxlength="50">
				<?php echo help('KG이니시스에서 발급받은 웹결제 사인키를 입력합니다.<br>관리자 페이지의 상점정보 > 계약정보 > 부가정보의 웹결제 signkey생성 조회 버튼 클릭, 팝업창에서 생성 버튼 클릭 후 해당 값을 입력합니다.'); ?>
			</td>
		</tr>
		<tr>
			<th scope="row">KG이니시스 삼성페이 버튼</th>
			<td>
				<input type="checkbox" name="de_samsung_pay_use" value="1" id="de_samsung_pay_use"<?php echo get_checked($default['de_samsung_pay_use'], "1"); ?>>
				<label for="de_samsung_pay_use">사용함</label>
				<?php echo help('주문서 작성 페이지에 KG이니시스 삼성페이 버튼의 별도 사용 여부를 설정합니다.'); ?>
			</td>
		</tr>
		<tr>
			<th scope="row">가상계좌 입금통보 URL</th>
			<td>
				<?php echo TB_SHOP_URL; ?>/settle_inicis_common.php
				<?php echo help('위 주소를 <strong><a href="https://iniweb.inicis.com" target="_blank">KG이니시스 관리자</a> &gt; 거래조회 &gt; 가상계좌 &gt; 입금통보방식선택 &gt; URL 수신 설정</strong>에 입력하셔야 자동으로 입금 통보됩니다.'); ?>
			</td>
		</tr>
		</tbody>
		</table>
	</div>
</div>

<div class="pg_info_fld allat_info_fld">
	<h2>KG올앳 계약정보</h2>
	<div class="tbl_frm01">
		<table>
		<colgroup>
			<col class="w180">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row"><label for="de_allat_mid">KG올앳 상점아이디</label></th>
			<td>
				<input type="text" name="de_allat_mid" value="<?php echo $default['de_allat_mid']; ?>" id="de_allat_mid" class="frm_input" size="10">
				<a href="https://www.allatpay.com/servlet/AllatBizV2/index/IndexMainCL" target="_blank" class="btn_small blue">KG올앳 서비스신청하기</a>
				<?php echo help('KG올앳으로 부터 발급 받으신 상점아이디(MID)를 입력해 주십시오.'); ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="de_allat_crosskey">KG올앳 크로스키</label></th>
			<td>
				<input type="text" name="de_allat_crosskey" value="<?php echo $default['de_allat_crosskey']; ?>" id="de_allat_crosskey" class="frm_input" size="40" maxlength="50">
				<?php echo help('KG올앳으로 부터 발급 받으신 크로스키(CrossKey)를 입력해 주십시오.'); ?>
			</td>
		</tr>
		<tr>
			<th scope="row">가상계좌 입금통보 URL</th>
			<td>
				<?php echo TB_SHOP_URL; ?>/settle_allat_common.php
			</td>
		</tr>
		</tbody>
		</table>
	</div>
</div>

<div class="pg_info_fld kiwoom_info_fld">
    <? $t_title = '키움페이'; ?>
    <h2><?=$t_title?> 계약정보</h2>
    <div class="tbl_frm01">
        <table>
            <colgroup>
                <col class="w180">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <th scope="row"><label for="de_kiwoom_mid"><?=$t_title?> 상점아이디</label></th>
                <td>
                    <input type="text" name="de_kiwoom_mid" value="<?php echo $default['de_kiwoom_mid']; ?>" id="de_kiwoom_mid" class="frm_input" size="10">
                    <a href="https://www.kiwoompay.co.kr" target="_blank" class="btn_small blue"><?=$t_title?> 서비스신청하기</a>
                    <?php echo help($t_title.'으로 부터 발급 받으신 상점아이디를 입력해 주십시오.'); ?>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="de_kiwoom_crosskey"><?=$t_title?> 암화화키</label></th>
                <td>
                    <input type="text" name="de_kiwoom_crosskey" value="<?php echo $default['de_kiwoom_crosskey']; ?>" id="de_kiwoom_crosskey" class="frm_input" size="40" maxlength="50">
                    <?php echo help($t_title.'으로 부터 발급 받으신 암화화키를 입력해 주십시오.'); ?>
                </td>
            </tr>
            <tr>
                <th scope="row"><?=$t_title?> 변경통지 URL</th>
                <td>
                    <?=TB_SHOP_URL?>/kiwoom/receive.php
                    <?php echo help($t_title.' 상점 관리자에 위 값을 넣어 주십시오.'); ?>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="pg_info_fld pay2pay_pay_info_fld">
    <? $t_title = '페이투페이'; ?>
    <h2><?=$t_title?> 계약정보</h2>
    <div class="tbl_frm01">
        <table>
            <colgroup>
                <col class="w180">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <th scope="row"><label for="de_pay2pay_pay_mid"><?=$t_title?> 상점아이디</label></th>
                <td>
                    <input type="text" name="de_pay2pay_pay_mid" value="<?php echo $default['de_pay2pay_pay_mid']; ?>" id="de_pay2pay_pay_mid" class="frm_input" size="10">
                    <a href="https://www.pay2pay.co.kr" target="_blank" class="btn_small blue"><?=$t_title?> 서비스신청하기</a>
                    <?php echo help($t_title.'으로 부터 발급 받으신 상점아이디를 입력해 주십시오.'); ?>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="de_pay2pay_pay_crosskey"><?=$t_title?> 크로스키</label></th>
                <td>
                    <input type="text" name="de_pay2pay_pay_crosskey" value="<?php echo $default['de_pay2pay_pay_crosskey']; ?>" id="de_pay2pay_pay_crosskey" class="frm_input" size="40" maxlength="50">
                    <?php echo help($t_title.'으로 부터 발급 받으신 크로스키를 입력해 주십시오.'); ?>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

<h2>에스크로 설정</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">에스크로 사용</th>
		<td>
			<input type="radio" name="de_escrow_use" value="0" id="de_escrow_use_no"<?php echo get_checked($default['de_escrow_use'], "0"); ?>>
			<label for="de_escrow_use_no">일반결제 사용</label>
			<input type="radio" name="de_escrow_use" value="1" id="de_escrow_use_yes"<?php echo get_checked($default['de_escrow_use'], "1"); ?>>
			<label for="de_escrow_use_yes"> 에스크로결제 사용</label>
			<?php echo help('에스크로 결제를 사용하시려면, 반드시 결제대행사 상점 관리자 페이지에서 에스크로 서비스를 신청하신 후 사용하셔야 합니다.<br>에스크로 사용시 배송과의 연동은 되지 않으며 에스크로 결제만 지원됩니다.'); ?>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<h2>무통장입금계좌</h2>
<div class="local_cmd01">
	<p>※ 무통장입금계좌는 최대 5개까지 등록 가능합니다.</p>
</div>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<?php
	$bank = unserialize($default['de_bank_account']);
	for($i=0; $i<5; $i++) {
		$k = ($i+1);
	?>
	<tr>
		<th scope="row">은행계좌번호 <?php echo $k; ?></th>
		<td>
			<input type="text" name="bank_name[]" value="<?php echo $bank[$i]['name']; ?>" class="frm_input" size="15" placeholder="은행명 <?php echo $k; ?>">
			<input type="text" name="bank_account[]" value="<?php echo $bank[$i]['account']; ?>" class="frm_input" size="30" placeholder="계좌번호 <?php echo $k; ?>">
			<input type="text" name="bank_holder[]" value="<?php echo $bank[$i]['holder']; ?>" class="frm_input" size="15" placeholder="예금주명 <?php echo $k; ?>">
		</td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
</div>

<? include_once(TB_SHOP_PATH.'/psys/PsysPay.php'); ?>
<h2>외부연동 정보</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w170">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">연구비카드</th>
		<td>

            <? PsysPay::getSelServiceHtml('de_psys_rnd_service', $default) ?>

            &nbsp;

			<input type="text" name="de_psys_rnd_mid" value="<?php echo $default['de_psys_rnd_mid']; ?>" class="frm_input" size="15" placeholder="상점 아이디">
			<input type="text" name="de_psys_rnd_crosskey" value="<?php echo $default['de_psys_rnd_crosskey']; ?>" class="frm_input" size="15" placeholder="크로스키">
		</td>
	</tr>
	<tr>
		<th scope="row">장기무이자</th>
		<td>

            <? PsysPay::getSelServiceHtml('de_psys_long_service', $default) ?>

            &nbsp;

			<input type="text" name="de_psys_long_mid" value="<?php echo $default['de_psys_long_mid']; ?>" class="frm_input" size="15" placeholder="상점 아이디">
			<input type="text" name="de_psys_long_crosskey" value="<?php echo $default['de_psys_long_crosskey']; ?>" class="frm_input" size="15" placeholder="크로스키">
		</td>
	</tr>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
</div>
</form>

<div class="information">
	<h4>도움말</h4>
	<div class="content">
		<div class="desc02">
			<p>ㆍPG사와 계약을 맺은 이후에는 메일로 받으신 실제 PG 계약정보를 입력하시면 됩니다.</p>
			<p>ㆍPG사의 결제정보 설정후 고객님께서 카드결제 테스트를 꼭 해보시기 바랍니다.</p>
			<p>ㆍ간혹 PG사를 통해 카드승인된 값을 받지못하여 주문관리페이지에서 입금확인으로 자동변경되지 않을수 있습니다.</p>
			<p>ㆍ반드시 주문관리페이지의 주문상태와 PG사에서 제공하는 관리자화면내의 카드승인내역도 동시에 확인해 주십시요.</p>
		</div>
	 </div>
</div>

<script>
$(function() {
    $(".pg_info_fld").hide();
    <?php if($default['de_pg_service']) { ?>
    $(".<?php echo $default['de_pg_service']; ?>_info_fld").show();
    <?php } else { ?>
    $(".kcp_info_fld").show();
    <?php } ?>
	$("#de_pg_service").on("change", function() {
		$(".pg_info_fld:visible").hide();
        var pg = $(this).val();
		$("."+pg+"_info_fld").show();
    });
});
</script>

<?php
// 결제모듈 실행권한 체크
if($default['de_iche_use'] || $default['de_vbank_use'] || $default['de_hp_use'] || $default['de_card_use']) {
    // kcp의 경우 pp_cli 체크
    if($default['de_pg_service'] == 'kcp') {
        if(!extension_loaded('openssl')) {
            echo '<script>'.PHP_EOL;
            echo 'alert("PHP openssl 확장모듈이 설치되어 있지 않습니다.\n모바일 쇼핑몰 결제 때 사용되오니 openssl 확장 모듈을 설치하여 주십시오.");'.PHP_EOL;
            echo '</script>'.PHP_EOL;
        }

        if(!extension_loaded('soap') || !class_exists('SOAPClient')) {
            echo '<script>'.PHP_EOL;
            echo 'alert("PHP SOAP 확장모듈이 설치되어 있지 않습니다.\n모바일 쇼핑몰 결제 때 사용되오니 SOAP 확장 모듈을 설치하여 주십시오.");'.PHP_EOL;
            echo '</script>'.PHP_EOL;
        }

        $is_linux = true;
        if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
            $is_linux = false;

        $exe = '/kcp/bin/';
        if($is_linux) {
            if(PHP_INT_MAX == 2147483647) // 32-bit
                $exe .= 'pp_cli';
            else
                $exe .= 'pp_cli_x64';
        } else {
            $exe .= 'pp_cli_exe.exe';
        }

        echo module_exec_check(TB_SHOP_PATH.$exe, 'pp_cli');

        // shop/kcp/log 디렉토리 체크 후 있으면 경고
        if(is_dir(TB_SHOP_PATH.'/kcp/log') && is_writable(TB_SHOP_PATH.'/kcp/log')) {
            echo '<script>'.PHP_EOL;
            echo 'alert("웹접근 가능 경로에 log 디렉토리가 있습니다.\nlog 디렉토리를 웹에서 접근 불가능한 경로로 변경해 주십시오.")'.PHP_EOL;
            echo '</script>'.PHP_EOL;
        }
    }

    // LG의 경우 log 디렉토리 체크
    if($default['de_pg_service'] == 'lg') {
        $log_path = TB_LGXPAY_PATH.'/lgdacom/log';

        if(!is_dir($log_path)) {
            echo '<script>'.PHP_EOL;
            echo 'alert("'.str_replace(TB_PATH.'/', '', TB_LGXPAY_PATH).'/lgdacom 폴더 안에 log 폴더를 생성하신 후 쓰기권한을 부여해 주십시오.\n> mkdir log\n> chmod 707 log");'.PHP_EOL;
            echo '</script>'.PHP_EOL;
        } else {
            if(!is_writable($log_path)) {
                echo '<script>'.PHP_EOL;
                echo 'alert("'.str_replace(TB_PATH.'/', '',$log_path).' 폴더에 쓰기권한을 부여해 주십시오.\n> chmod 707 log");'.PHP_EOL;
                echo '</script>'.PHP_EOL;
            }
        }
    }

    // 이니시스의 경우 log 디렉토리 체크
    if($default['de_pg_service'] == 'inicis') {
        if(!function_exists('xml_set_element_handler')) {
            echo '<script>'.PHP_EOL;
            echo 'alert("XML 관련 함수를 사용할 수 없습니다.\n서버 관리자에게 문의해 주십시오.");'.PHP_EOL;
            echo '</script>'.PHP_EOL;
        }

        if(!function_exists('openssl_get_publickey')) {
            echo '<script>'.PHP_EOL;
            echo 'alert("OPENSSL 관련 함수를 사용할 수 없습니다.\n서버 관리자에게 문의해 주십시오.");'.PHP_EOL;
            echo '</script>'.PHP_EOL;
        }

        if(!function_exists('socket_create')) {
            echo '<script>'.PHP_EOL;
            echo 'alert("SOCKET 관련 함수를 사용할 수 없습니다.\n서버 관리자에게 문의해 주십시오.");'.PHP_EOL;
            echo '</script>'.PHP_EOL;
        }

        if(!function_exists('mcrypt_module_open')) {
            echo '<script>'.PHP_EOL;
            echo 'alert("MCRYPT 관련 함수를 사용할 수 없습니다.\n서버 관리자에게 문의해 주십시오.");'.PHP_EOL;
            echo '</script>'.PHP_EOL;
        }

        $log_path = TB_SHOP_PATH.'/inicis/log';

        if(!is_dir($log_path)) {
            echo '<script>'.PHP_EOL;
            echo 'alert("'.str_replace(TB_PATH.'/', '', TB_SHOP_PATH).'/inicis 폴더 안에 log 폴더를 생성하신 후 쓰기권한을 부여해 주십시오.\n> mkdir log\n> chmod 707 log");'.PHP_EOL;
            echo '</script>'.PHP_EOL;
        } else {
            if(!is_writable($log_path)) {
                echo '<script>'.PHP_EOL;
                echo 'alert("'.str_replace(TB_PATH.'/', '',$log_path).' 폴더에 쓰기권한을 부여해 주십시오.\n> chmod 707 log");'.PHP_EOL;
                echo '</script>'.PHP_EOL;
            }
        }
    }
}
?>
