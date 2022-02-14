<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

$cancelFlag = "true";

// $cancelFlag를 "ture"로 변경하는 condition 판단은 개별적으로
// 수행하여 주십시오.

if($cancelFlag == "true")
{
	include_once(TB_MSHOP_PATH.'/allat/allatutil.php');

    // Set Value
    // -------------------------------------------------------------------
	$at_cross_key	= trim($_POST["at_cross_key"]);	//가맹점 CrossKey값
	$at_shop_id		= trim($_POST["at_shop_id"]);	//ShopId값(최대  20자리)
	$at_order_no	= trim($_POST["at_order_no"]);	//주문번호(최대  80자리)
    $at_amt			= trim($_POST["at_amt"]);		//취소금액(최대  10자리)
    $at_pay_type	= trim($_POST["at_pay_type"]);	//원거래건의 결제방식[카드:CARD,계좌이체:ABANK]
    $at_seq_no		= trim($_POST["at_seq_no"]);	//거래일련번호(최대 10자리) : 옵션필드임
    $at_test_yn		= trim($_POST["at_test_yn"]);	//테스트 여부
    $at_opt_pin		= trim($_POST["at_opt_pin"]);
    $at_opt_mod		= trim($_POST["at_opt_mod"]);

    // set Enc Data
    // -------------------------------------------------------------------
    $at_enc=setValue($at_enc,"allat_shop_id",$at_shop_id);
    $at_enc=setValue($at_enc,"allat_order_no",$at_order_no);
    $at_enc=setValue($at_enc,"allat_amt",$at_amt);
    $at_enc=setValue($at_enc,"allat_pay_type",$at_pay_type);
    $at_enc=setValue($at_enc,"allat_seq_no",$at_seq_no);
    $at_enc=setValue($at_enc,"allat_test_yn",$at_test_yn);
    $at_enc=setValue($at_enc,"allat_opt_pin",$at_opt_pin);
    $at_enc=setValue($at_enc,"allat_opt_mod",$at_opt_mod);

    // Set Request Data
    //--------------------------------------------------------------------
    $at_data   = "allat_shop_id=".$at_shop_id.
                 "&allat_enc_data=".$at_enc.
                 "&allat_cross_key=".$at_cross_key;

    // 올앳과 통신 후 결과값 받기 : CancelReq->통신함수
    //-----------------------------------------------------------------
    $at_txt=CancelReq($at_data,"SSL");

    // 결과값
    //----------------------------------------------------------------
    $REPLYCD	= getValue("reply_cd",$at_txt);		//결과코드
    $REPLYMSG	= getValue("reply_msg",$at_txt);	//결과 메세지

	$res_cd		= $REPLYCD;
	$res_msg	= $REPLYMSG;

    // 결과값 처리
    //------------------------------------------------------------------
	if( !strcmp($REPLYCD,"0000") ){ // 성공
		// reply_cd "0000" 일때만 성공
		$CANCEL_YMDHMS=getValue("cancel_ymdhms",$at_txt);
		$PART_CANCEL_FLAG=getValue("part_cancel_flag",$at_txt);
		$REMAIN_AMT=getValue("remain_amt",$at_txt);
		$PAY_TYPE=getValue("pay_type",$at_txt);

		$logfile = fopen( TB_SHOP_PATH.'/allat/log/cancel_mobile_'.date("Ymd").".log", "a+" );

		fwrite( $logfile,"***************************************************************************\r\n");
		fwrite( $logfile,"[".date("Y/m/d H:i:s")."] 취소 결과값\r\n");
		fwrite( $logfile,"결과코드 : ".$REPLYCD."\r\n");
		fwrite( $logfile,"결과메세지 : ".iconv_utf8($REPLYMSG)."\r\n");
		fwrite( $logfile,"취소날짜 : ".$CANCEL_YMDHMS."\r\n");
		fwrite( $logfile,"취소구분 : ".$PART_CANCEL_FLAG."\r\n");
		fwrite( $logfile,"잔액 : ".$REMAIN_AMT."\r\n");
		fwrite( $logfile,"거래방식구분 : ".$PAY_TYPE."\r\n");
		fclose( $logfile );
	}
}
?>