<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

include_once(TB_SHOP_PATH.'/psys/PsysPay.php');

?>

<script language="Javascript" src="<?=PsysPay::getCombineConst('PG_JS_URL', $od['paymethod'])?>" charset="utf-8"></script>
<script language="Javascript">

var _allat_tx_url = "<?=PsysPay::getPgDomain( $od['paymethod'] )?>";

var prev_action = null;
var prev_form = null;

// 결제페이지 호출
function ftn_approval(dfm) {

    if( ! prev_action ) {
        prev_action = dfm.action;
        prev_form = dfm;
    }

	AllatPay_Approval(dfm);
	// 결제창 자동종료 체크 시작
	AllatPay_Closechk_Start();
}

// 결과값 반환( receive 페이지에서 호출 )
function result_submit(json) {
	// 결제창 자동종료 체크 종료
	AllatPay_Closechk_End();

	if( json.Psys_resultcode != '0000' ){
		window.setTimeout(function(){alert(json.Psys_resultcode + " : " + json.Psys_resultmsg);},1000);
	} else {
		
		prev_form.Psys_enc_data.value = json.Psys_enc_data;

		prev_form.action = prev_action;
		prev_form.method = "post";
		prev_form.target = "_self";
		prev_form.submit();
		
	}
}


// 결제체크
function payment_check(f)
{
    var tot_price = parseInt(f.good_mny.value);

    if(f.od_settle_case.value == '<?=PsysPay::$card_use['r']?>') {
        if(false && tot_price < 1000) {
            alert("신용카드는 1000원 이상 결제가 가능합니다.");
            return false;
        }
    }

    if(f.od_settle_case.value == '<?=PsysPay::$card_use['l']?>') {
        if(false && tot_price < 1000) {
            alert("신용카드는 1000원 이상 결제가 가능합니다.");
            return false;
        }
    }
    return true;
}


function uf_forderform_set(f) {

    switch(f.od_settle_case.value)
    {
        case "<?=PsysPay::$card_use['l']?>":
            f.Psys_card_yn.value = "Y";
            break;
        case "<?=PsysPay::$card_use['r']?>":
            f.Psys_card_yn.value = "Y";
            break;
    }
}
</script>

