<?php
if(!defined('_TUBEWEB_')) exit; // 개별 페이지 접근 불가

if($is_kakaopay_use) {
    $remoteaddr = $_SERVER['REMOTE_ADDR'];
    $serveraddr = $_SERVER['SERVER_ADDR'];
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
<?php
}
?>

<div id="kakaopay_request">
<input type="hidden" name="merchantTxnNum" value="<?php echo $od_id; ?>">
<input type="hidden" name="GoodsName" value="<?php echo $goods; ?>">
<input type="hidden" name="Amt" value="<?php echo $tot_price; ?>">
<input type="hidden" name="GoodsCnt" value="<?php echo ($goods_count + 1); ?>">
<input type="hidden" name="BuyerEmail" value="">
<input type="hidden" name="BuyerName" value="">
<input type="hidden" name="prType" value="<?php echo (is_mobile() ? 'MPM' : 'WPM'); ?>">
<input type="hidden" name="channelType" value="4">
<input type="hidden" name="TransType" value="0">
<input type="hidden" name="resultCode" value="" id="resultCode">
<input type="hidden" name="resultMsg" value="" id="resultMsg">
<input type="hidden" name="txnId" value="" id="txnId">
<input type="hidden" name="prDt" value="" id="prDt">
<input type="hidden" name="SPU" value="">
<input type="hidden" name="SPU_SIGN_TOKEN" value="">
<input type="hidden" name="MPAY_PUB" value="">
<input type="hidden" name="NON_REP_TOKEN" value="">
<input type="hidden" name="EdiDate" value="<?php echo($ediDate); ?>">
<input type="hidden" name="EncryptData" value="">
<?php if($default['de_tax_flag_use']) { ?>
<input type="hidden" name="SupplyAmt" value="<?php echo ((int)$comm_tax_mny + (int)$comm_free_mny); ?>">
<input type="hidden" name="GoodsVat" value="<?php echo $comm_vat_mny; ?>">
<input type="hidden" name="ServiceAmt" value="0">
<?php } ?>
</div>
<?php
}
?>