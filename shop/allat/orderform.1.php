<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

// 전자결제를 사용할 때만 실행
if($default['de_iche_use'] || $default['de_vbank_use'] || $default['de_hp_use'] || $default['de_card_use']) {
?>

<script language="Javascript" src="https://tx.allatpay.com/common/NonAllatPayRE.js" charset="euc-kr"></script>

<script language="Javascript">
// 결제페이지 호출
function ftn_approval(dfm) {
	AllatPay_Approval(dfm);
	// 결제창 자동종료 체크 시작
	AllatPay_Closechk_Start();
}

// 결과값 반환( receive 페이지에서 호출 )
function result_submit(result_cd,result_msg,enc_data) {

	// 결제창 자동종료 체크 종료
	AllatPay_Closechk_End();

	if( result_cd != '0000' ){
		window.setTimeout(function(){alert(result_cd + " : " + result_msg);},1000);
	} else {
		fm.allat_enc_data.value = enc_data;
		fm.action = tb_shop_url+"/orderformresult.php";
		fm.method = "post";
		fm.target = "_self";
		fm.submit();
	}
}
</script>
<?php } ?>