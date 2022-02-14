<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

// 전자결제를 사용할 때만 실행
if($default['de_iche_use'] || $default['de_vbank_use'] || $default['de_hp_use'] || $default['de_card_use']) {
?>

<script type="text/javascript" src="https://tx.allatpay.com/common/AllatPayM.js"></script>

<script language="Javascript">
// 결제페이지 호출
function approval(sendFm) {
	Allat_Mobile_Approval(sendFm,0,0); /* 포지션 지정 (결제창 크기, 320*360) */
	$("body").scrollTop(0);
}

// 결과값 반환( receive 페이지에서 호출 )
function approval_submit(result_cd,result_msg,enc_data) {

	Allat_Mobile_Close();

	if( result_cd != '0000' ){
		alert(result_cd + " : " + result_msg);
	} else {
		sendFm.allat_enc_data.value = enc_data;
		sendFm.action = tb_mobile_shop_url+"/orderformresult.php";
		sendFm.method = "post";
		sendFm.target = "_self";
		sendFm.submit();
	}
}
</script>
<?php } ?>