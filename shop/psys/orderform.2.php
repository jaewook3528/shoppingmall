<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

?>
<!-- Psys  결제 시작 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->
<input type="hidden" name="Psys_shopid" value="<?=($t = PsysPay::getPgInfo( $od['paymethod'] )) ? $t['shop_id'] : '' ?>"> <!-- PSYS 가맹점ID  [필수] -->


	<!--개인 정보 암호화 시작 ##################################################################-->
<input type="hidden" name="Psys_buyername" value="<?=$od['name']?>"> <!-- 구매자명 -->
<input type="hidden" name="Psys_handphone" value="<?=$od['cellphone']?>"> <!-- 구매자 인증용 핸드폰 번 -->
<input type="hidden" name="Psys_email" value="<?=$od['email']?>"> <!-- 이메일주소 -->
<input type="hidden" name="Psys_recp_nm" value="<?=$od['b_name']?>"> <!-- 수신자 -->
<input type="hidden" name="Psys_recp_addr" value="<?=$od['addr1'].$od['addr2'].$od['addr3']?>"> <!-- 수신 주소 -->
<input type="hidden" name="Psys_rcvr_name" value="<?=$od['b_name']?>"> <!-- 수신자명 -->
<input type="hidden" name="Psys_rcvr_add" value=""> <!-- 수신 주소 -->
<input type="hidden" name="Psys_pmember_id" value="<?= ( $t = $member['id'] ) ? $t : 'guest'; ?>"> <!-- 구매자 ID -->
	<!--개인 정보 암호화 끝 ###################################################################-->


<input type="hidden" name="Psys_totalamt" value="<?php echo $tot_price; ?>">  <!--  결제금액 [필수] -->
<input type="hidden" name="ReturnURL" value="<?php echo PsysPay::getCombineConst('PG_RECEIVE_URL'); ?>"> <!-- //리턴 주소[필수] -->

<input type="hidden" name="Psys_product_ea" value="1">    <!-- // 상품수 기본=1 -->
<input type="hidden" name="Psys_goods_code" value="<?=cut_str($product_cd, 100); ?>">    <!-- // 상점상품코드 -->
<input type="hidden" name="Psys_title" value="<?=cut_str($product_nm, 100); ?>">  <!-- // 상품명 (한글시 인코딩처리) [필수] -->
<!--input type="hidden" name="Psys_title" value="<?php echo base64_encode($product_nm); ?>"-->  <!-- // 상품명 (한글시 인코딩처리) [필수] -->
<input type="hidden" name="Psys_shopingmall_order_no" value="<?php echo $od_id; ?>"> <!-- // 상품 오더번호 -->
<input type="hidden" name="Psys_card_type" value="<?php echo PsysPay::getCardType($od['paymethod']); ?>"> <!-- // 연구비카드 구분 : 미입력시 카드 구분 전체 가능<br>1: 연구비카드(신한,BC,삼성), 3: 일반카드, 4: 키인(현장)결제, 5.키인결제(전화승인) ,6:장기무이자,7:장기무이자(인증),9: 연구비카드(신한,BC,삼성)+일반카드 -->
<input type="hidden" name="Psys_test_yn" value="Y"> <!-- // 테스트(Y/N) -->

<input type="hidden" name="Psys_real_yn" value="Y"> <!-- 상품 실물 여부(Y/N) -->

<input type="hidden" name="Psys_card_yn" value="Y"> <!-- 카드결제사용(Y/N) -->
<input type="hidden" name="Psys_bank_yn" value="N"> <!-- 계좌이체사용(Y/N) -->
<input type="hidden" name="Psys_vbank_yn" value="N"> <!-- 가상계좌사용(Y/N) -->
<input type="hidden" name="Psys_hp_yn'] = 'N"> <!-- 핸드폰사용(Y/N) -->
<input type="hidden" name="Psys_ticket_yn" value="Y"> <!-- 상품권사용(Y/N) -->
<input type="hidden" name="Psys_cardes_yn" value="N"> <!-- 에스크로사용-카드 (Y/N) -->
<input type="hidden" name="Psys_bankes_yn'] = 'N"> <!-- 에스크로사용-계좌이체 (Y/N) -->
<input type="hidden" name="Psys_vbankes_yn'] = 'N"> <!-- 에스크로사용-가상계좌 (Y/N) -->
<input type="hidden" name="Psys_hpes_yn" value="N"> <!-- 에스크로사용-핸드폰(Y/N) -->
<input type="hidden" name="Psys_ticketes_yn'] = 'N"> <!-- 에스크로사용-상품권(Y/N) -->

<input type="hidden" name="Psys_cash_yn" value="Y"> <!-- 현금영수증 사용(Y/N) -->
<input type="hidden" name="Psys_pay_type" value="<?php echo $od['paymethod']; ?>"> <!-- 결제방식 카드(3D, ISP, NOR), 계좌이체(ABANK), 가상계좌(VBANK), 휴대폰(HP), 상품권(TICKET) -->

<input type="hidden" name="Psys_etc_data1" value=""> <!-- 추가데이타1 -->
<input type="hidden" name="Psys_etc_data2" value=""> <!-- 추가데이타2 -->
<input type="hidden" name="Psys_etc_data3" value=""> <!-- 추가데이타3 -->
<input type="hidden" name="Psys_etc_data4" value=""> <!-- 추가데이타4 -->
<input type="hidden" name="Psys_etc_data5" value=""> <!-- 추가데이타5 -->
<input type="hidden" name="Psys_etc_data6" value=""> <!-- 추가데이타6 -->
<input type="hidden" name="Psys_etc_data7" value=""> <!-- 추가데이타7 -->

<input type="hidden" name="Psys_autoscreen_yn" value="Y"> <!-- 모바일결제창 fullscreen -->

<input type="hidden" name="Psys_enc_yn" value="Y"> <!-- 암호화 여부(Y/N) 디폴트 N //  Y 일때만 AES256 적용-->

<!-- Psys  결제 요청 끝  리터데이터 추가 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->

<input type="hidden" name="Psys_enc_data" value=""> <!--결제요청 응답 데이터-->

<!-- Psys  결제 요청 끝 ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////-->

<input type="hidden" name="od_id" value="<?php echo $od_id; ?>">
<input type="hidden" name="od_settle_case" value="<?php echo $od['paymethod']; ?>">
<input type="hidden" name="od_name" value="<?php echo $od['name']; ?>">
<input type="hidden" name="od_tel" value="<?php echo $od['telephone']; ?>">
<input type="hidden" name="od_hp" value="<?php echo $od['cellphone']; ?>">
<input type="hidden" name="od_zip" value="<?php echo $od['zip']; ?>">
<input type="hidden" name="od_addr1" value="<?php echo $od['addr1']; ?>">
<input type="hidden" name="od_addr2" value="<?php echo $od['addr2']; ?>">
<input type="hidden" name="od_addr3" value="<?php echo $od['addr3']; ?>">
<input type="hidden" name="od_addr_jibeon" value="<?php echo $od['addr_jibeon']; ?>">
<input type="hidden" name="od_email" value="<?php echo $od['email']; ?>">
<input type="hidden" name="od_b_name" value="<?php echo $od['b_name']; ?>">
<input type="hidden" name="od_b_tel" value="<?php echo $od['b_telephone']; ?>">
<input type="hidden" name="od_b_hp" value="<?php echo $od['b_cellphone']; ?>">
<input type="hidden" name="od_b_zip" value="<?php echo $od['b_zip']; ?>">
<input type="hidden" name="od_b_addr1" value="<?php echo $od['b_addr1']; ?>">
<input type="hidden" name="od_b_addr2" value="<?php echo $od['b_addr2']; ?>">
<input type="hidden" name="od_b_addr3" value="<?php echo $od['b_addr3']; ?>">
<input type="hidden" name="od_b_addr_jibeon" value="<?php echo $od['b_addr_jibeon']; ?>">

<?php /* 주문폼 자바스크립트 에러 방지를 위해 추가함 */ ?>
<input type="hidden" name="good_mny" value="<?php echo $tot_price; ?>">
<?php if($default['de_tax_flag_use']) { ?>
<input type="hidden" name="comm_tax_mny" value="<?php echo $comm_tax_mny; ?>"> <!-- 과세금액 -->
<input type="hidden" name="comm_vat_mny" value="<?php echo $comm_vat_mny; ?>"> <!-- 부가세 -->
<input type="hidden" name="comm_free_mny" value="<?php echo $comm_free_mny; ?>"> <!-- 비과세 금액 -->
<?php } ?>

<!-- Allat에서 발급한 고유 상점 ID -->
<input type="hidden" name="allat_shop_id" value="<?php echo $at_shop_id; ?>">

<!-- 쇼핑몰에서 사용하는 고유 주문번호 : 공백,작은따옴표('),큰따옴표(") 사용 불가 -->
<input type="hidden" name="allat_order_no" value="<?php echo $od_id; ?>">

<!-- 주문정보암호화필드 (값은 자동으로 설정됨) -->
<!-- hidden field로 설정해야함, 결제정보가 암호화되어 설정되는 값 -->
<input type="hidden" name="allat_enc_data" value="">

<!-- 신용카드 결제 사용 여부 -->
<!-- 사용(Y),사용하지 않음(N) - Default : 올앳과 계약된 사용여부 -->
<input type="hidden" name="allat_card_yn" value="N">

<?
// 상품 옵션의 할부 개월값 얻기
$sql = "
  select op.* 
  from shop_cart c 
    join shop_goods_option op
      on c.gs_id = op.gs_id and c.io_id = op.io_id
  where od_id = '$od_id' 
  order by index_no ";
$result = sql_query($sql);
$opt_row = sql_fetch_array($result);
?>
<input type="hidden" name="sell_mm" value="<?=$opt_row['sell_mm']?>">
