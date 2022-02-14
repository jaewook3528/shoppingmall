<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

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

<!-- 총 결제금액 : 숫자(0~9)만 사용가능 -->
<input type="hidden" name="allat_amt" value="<?php echo $tot_price; ?>">

<!-- 쇼핑몰의 회원ID : 공백,작은따옴표('),큰따옴표(") 사용 불가 -->
<input type="hidden" name="allat_pmember_id" value="<?php $t = $member['id']; echo $t?$t:'guest'; ?>">

<!-- 여러 상품의 경우 구분자 이용, 구분자('||':파이프 2개) : 공백,작은따옴표('),큰따옴표(") 사용 불가 -->
<input type="hidden" name="allat_product_cd" value="<?= cut_str($product_cd, 100); ?>">

<!-- 여러 상품의 경우 구분자 이용, 구분자('||':파이프 2개) -->
<input type="hidden" name="allat_product_nm" value="<?= cut_str($product_nm, 100) ?>">

<input type="hidden" name="allat_buyer_nm" value="<?php echo cut_str($od['name'], 8); ?>"> <!-- 결제자성명 -->
<input type="hidden" name="allat_recp_nm" value="<?php echo cut_str($od['b_name'], 8); ?>"> <!-- 수취인성명 -->
<input type="hidden" name="allat_recp_addr"	value="<?php echo print_address($od['b_addr1'], $od['b_addr2'], $od['b_addr3'], $od['b_addr_jibeon']); ?>"> <!-- 수취인주소 -->

<!-- 인증정보수신URL (Full URL 입력) -->
<input type="hidden" name="shop_receive_url" value="<?php echo $receive_url; ?>">

<!-- 주문정보암호화필드 (값은 자동으로 설정됨) -->
<!-- hidden field로 설정해야함, 결제정보가 암호화되어 설정되는 값 -->
<input type="hidden" name="allat_enc_data" value="">

<!-- 신용카드 결제 사용 여부 -->
<!-- 사용(Y),사용하지 않음(N) - Default : 올앳과 계약된 사용여부 -->
<input type="hidden" name="allat_card_yn" value="N">

<!-- 계좌이체 결제 사용 여부 -->
<!-- 사용(Y),사용하지 않음(N) - Default : 올앳과 계약된 사용여부 -->
<input type="hidden" name="allat_bank_yn" value="N">

<!-- 무통장(가상계좌) 결제 사용 여부 -->
<!-- 사용(Y),사용하지 않음(N) - Default : 올앳과 계약된 사용여부 -->
<input type="hidden" name="allat_vbank_yn"	value="N">

<!-- 휴대폰 결제 사용 여부 -->
<!-- 사용(Y),사용하지 않음(N) - Default : 올앳과 계약된 사용여부 -->
<input type="hidden" name="allat_hp_yn"	value="N">

<!-- 상품권 결제 사용 여부 -->
<!-- 사용(Y),사용하지 않음(N) - Default : 올앳과 계약된 사용여부 -->
<input type="hidden" name="allat_ticket_yn"	value="N">

<!-- utf-8 일경우 값 : U -->
<input type="hidden" name="allat_encode_type" value="U">

<!--
무통장(가상계좌) 인증 Key
계좌 채번방식이 Key별 방식일 때만 사용함
건별 채번방식일때 무시, 신청한 상점만 이용 가능하며 회원별 고유 값 필요
-->
<input type="hidden" name="allat_account_key" value="">

<!-- 과세여부 -->
<!-- Y(과세), N(비과세) - Default : Y -->
<!-- 공급가액과 부가세가 표기되며 현금영수증 사용시 Y로 설정해야 한다. -->
<input type="hidden" name="allat_tax_yn" value="Y">

<?php if($default['de_tax_flag_use']) { // 복합과세 사용시 ?>
<input type="hidden" name="allat_vat_amt" value="<?php echo $comm_vat_mny; ?>"> <!-- 부가세금액 -->
<?php } ?>

<!-- 할부 사용여부 -->
<!-- 할부사용(Y), 할부 사용않함(N) - Default : Y -->
<input type="hidden" name="allat_sell_yn" value="Y">

<!-- 일반/무이자 할부 사용여부 -->
<!-- 일반(N), 무이자 할부(Y) - Default :N -->
<input type="hidden" name="allat_zerofee_yn" value="<?php echo ($default['de_card_noint_use']?"Y":"N"); ?>">

<!-- 포인트 사용 여부 -->
<!-- 사용(Y), 사용 않음(N) - Default : N -->
<!-- 상점이 포인트 가맹점(삼성, 국민, 비씨 등) 이용시 포인트를 사용하여 결제하는 서비스 -->
<input type="hidden" name="allat_bonus_yn"	value="N">

<!-- 현금 영수증 발급 여부 -->
<!-- 사용(Y), 사용 않음(N) - Default : 올앳과 계약된 사용여부 -->
<!-- 계좌이체/무통장입금(가상계좌)를 이용하실 때, 상점이 현금영수증 사용업체로 지정 되어 있으면 사용가능 -->
<input type="hidden" name="allat_cash_yn"	value="N">

<!-- 상품이미지 URL -->
<!-- PlugIn에 보여질 상품이미지 Full URL -->
<input type="hidden" name="all_product_img"	value="">

<!-- 결제 정보 수신 E-mail -->
<!-- 에스크로 서비스 사용시에 필수 필드.(결제창에서 E-Mail주소를 넣을 수도 있음) -->
<input type="hidden" name="allat_email_addr" value="<?php echo $od['email']; ?>">

<!-- 테스트 여부 -->
<!-- 테스트(Y),서비스(N) - Default : N -->
<!-- 테스트 결제는 실결제가 나지 않으며 테스트 성공시 결과값은 "0001" 리턴 -->
<input type="hidden" name="allat_test_yn" value="<?php echo ($default['de_card_test']?"Y":"N"); ?>">

<!-- 상품 실물 여부 -->
<!-- 상품이 실물일 경우 (Y), 상품이 실물이 아닐경우 (N) - Default : N -->
<!-- 상품이 실물이고, 10만원 이상 계좌이체시 에스크로 적용여부 이용 -->
<input type="hidden" name="allat_real_yn" value="Y">

<!-- 카드 에스크로 적용여부 -->
<!-- 카드 결제에 대한 에스크로 적용여부 : 적용 (Y), 미적용 (N), 고객선택 : 값없음 - Default : 값없음 -->
<!-- 에스크로 적용 대상 결제건에 대해서만 적용됨 -->
<input type="hidden" name="allat_cardes_yn" value="N">

<!-- 계좌이체 에스크로 적용여부 -->
<!-- 계좌이체 결제에 대한 에스크로 적용여부 : 적용 (Y), 미적용 (N), 고객선택 : 없음 - Default : 없음 -->
<!-- 에스크로 적용 대상 결제건에 대해서만 적용됨 -->
<input type="hidden" name="allat_bankes_yn" value="<?php //echo ($default['de_escrow_use']?'Y':'N'); ?>">

<!-- 무통장(가상계좌) 에스크로 적용여부 -->
<!-- 가상계좌 결제에 대한 에스크로 적용여부 : 적용 (Y), 미적용 (N), 고객선택 : 없음 - Default : 없음 -->
<!-- 에스크로 적용 대상 결제건에 대해서만 적용됨 -->
<input type="hidden" name="allat_vbankes_yn" value="<?php //echo ($default['de_escrow_use']?'Y':'N'); ?>">

<!-- 휴대폰 에스크로 적용여부 -->
<!-- 휴대폰 결제에 대한 에스크로 적용여부 : 적용 (Y), 미적용 (N), 고객선택 : 없음 - Default : 없음 -->
<!-- 에스크로 적용 대상 결제건에 대해서만 적용됨 -->
<input type="hidden" name="allat_hpes_yn" value="N">

<!-- 상품권 에스크로 적용여부 -->
<!-- 상품권 결제에 대한 에스크로 적용여부 : 적용 (Y), 미적용 (N), 고객선택 : 없음 - Default : 없음 -->
<!-- 에스크로 적용 대상 결제건에 대해서만 적용됨 -->
<input type="hidden" name="allat_ticketes_yn" value="N">

<!-- 주민번호 -->
<!-- ISP - 주민번호 13자리(ISP일때는 특정 사업자만 사용함.대부분 사용하지 않음) -->
<input type="hidden" name="allat_registry_no" value="">

<!-- KB복합결제 적용여부 -->
<!-- KB복합결제 적용여부 : 적용(Y), 미적용(N) -->
<input type="hidden" name="allat_kbcon_point_yn" value="N">

<!-- 제공기간 -->
<!-- 컨텐츠 상품의 제공기간 : YYYY.MM.DD ~ YYYY.MM.DD -->
<input type="hidden" name="allat_provide_date" value="">

<!-- 성별 -->
<!-- 구매자 성별, 남자(M)/여자(F) -->
<input type="hidden" name="allat_gender" value="">

<!-- 생년월일 -->
<!-- 구매자의 생년월일 8자, YYYYMMDD형식 -->
<input type="hidden" name="allat_birth_ymd" value="">