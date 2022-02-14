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

<input type="hidden" name="good_mny" value="<?php echo $tot_price; ?>">
<input type="hidden" name="res_cd" value=""> <!-- 결과 코드 -->

<input type="hidden" name="P_HASH" value="">
<input type="hidden" name="P_TYPE" value="">
<input type="hidden" name="P_UNAME" value="">
<input type="hidden" name="P_AUTH_DT" value="">
<input type="hidden" name="P_AUTH_NO" value="">
<input type="hidden" name="P_HPP_CORP" value="">
<input type="hidden" name="P_APPL_NUM" value="">
<input type="hidden" name="P_VACT_NUM" value="">
<input type="hidden" name="P_VACT_NAME" value="">
<input type="hidden" name="P_VACT_BANK" value="">
<input type="hidden" name="P_CARD_ISSUER" value="">

<?php if($default['de_tax_flag_use']) { ?>
<input type="hidden" name="comm_tax_mny" value="<?php echo $comm_tax_mny; ?>">  <!-- 과세금액 -->
<input type="hidden" name="comm_vat_mny" value="<?php echo $comm_vat_mny; ?>">  <!-- 부가세 -->
<input type="hidden" name="comm_free_mny" value="<?php echo $comm_free_mny; ?>"> <!-- 비과세 금액 -->
<?php } ?>

<div id="display_pay_button" class="btn_confirm">
    <input type="button" name="submitChecked" onClick="pay_approval();" value="결제등록" class="btn_medium wset" id="show_req_btn">
    <input type="button" onClick="forderform_check();" value="주문하기" class="btn_medium wset" id="show_pay_btn" style="display:none;">
    <a href="<?php echo TB_MURL; ?>" class="btn_medium bx-white">취소</a>
</div>