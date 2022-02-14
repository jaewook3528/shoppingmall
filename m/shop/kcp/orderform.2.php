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

<input type="hidden" name="req_tx" value=""> <!-- 요청 구분 -->
<input type="hidden" name="res_cd" value=""> <!-- 결과 코드 -->
<input type="hidden" name="tran_cd" value=""> <!-- 트랜잭션 코드 -->
<input type="hidden" name="ordr_idxx" value=""> <!-- 주문번호 -->
<input type="hidden" name="good_mny" value="<?php echo $tot_price; ?>"> <!-- 결제금액 -->
<input type="hidden" name="good_name" value=""> <!-- 상품명 -->
<input type="hidden" name="buyr_name" value=""> <!-- 주문자명 -->
<input type="hidden" name="buyr_tel1" value=""> <!-- 주문자 전화번호 -->
<input type="hidden" name="buyr_tel2" value=""> <!-- 주문자 휴대폰번호 -->
<input type="hidden" name="buyr_mail" value=""> <!-- 주문자 E-mail -->
<input type="hidden" name="enc_info" value=""> <!-- 암호화 정보 -->
<input type="hidden" name="enc_data" value=""> <!-- 암호화 데이터 -->
<input type="hidden" name="use_pay_method" value=""> <!-- 요청된 결제 수단 -->
<input type="hidden" name="rcvr_name" value=""> <!-- 수취인 이름 -->
<input type="hidden" name="rcvr_tel1" value=""> <!-- 수취인 전화번호 -->
<input type="hidden" name="rcvr_tel2" value=""> <!-- 수취인 휴대폰번호 -->
<input type="hidden" name="rcvr_mail" value=""> <!-- 수취인 E-Mail -->
<input type="hidden" name="rcvr_zipx" value=""> <!-- 수취인 우편번호 -->
<input type="hidden" name="rcvr_add1" value=""> <!-- 수취인 주소 -->
<input type="hidden" name="rcvr_add2" value=""> <!-- 수취인 상세 주소 -->
<input type="hidden" name="param_opt_1" value="">
<input type="hidden" name="param_opt_2" value="">
<input type="hidden" name="param_opt_3" value="">
<input type="hidden" name="disp_tax_yn" value="N">
<?php if($default['de_tax_flag_use']) { ?>
<input type="hidden" name="tax_flag" value="TG03"> <!-- 변경불가 -->
<input type="hidden" name="comm_tax_mny" value="<?php echo $comm_tax_mny; ?>">  <!-- 과세금액 -->
<input type="hidden" name="comm_vat_mny" value="<?php echo $comm_vat_mny; ?>">  <!-- 부가세 -->
<input type="hidden" name="comm_free_mny" value="<?php echo $comm_free_mny; ?>"> <!-- 비과세 금액 -->
<?php } ?>

<div id="display_pay_button" class="btn_confirm">
    <input type="button" name="submitChecked" onClick="pay_approval();" value="결제등록요청" class="btn_medium wset" id="show_req_btn">
    <input type="button" onClick="forderform_check();" value="주문하기" class="btn_medium wset" id="show_pay_btn" style="display:none;">
    <a href="<?php echo TB_MURL; ?>" class="btn_medium bx-white">취소</a>
</div>

<?php
// 무통장 입금만 사용할 때는 주문하기 버튼 보이게
if(!($default['de_iche_use'] || $default['de_vbank_use'] || $default['de_hp_use'] || $default['de_card_use'])) {
?>
<script>
document.getElementById("show_req_btn").style.display = "none";
document.getElementById("show_pay_btn").style.display = "";
</script>
<?php } ?>