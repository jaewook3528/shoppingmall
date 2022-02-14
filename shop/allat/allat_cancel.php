<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

$cancelFlag = "true";

// $cancelFlag를 "ture"로 변경하는 condition 판단은 개별적으로
// 수행하여 주십시오.

if($cancelFlag == "true")
{
    include_once(TB_SHOP_PATH.'/allat/allatutil.php');

	//------------------------ Test Code ---------------------
	$at_cross_key = $_POST["test_cross_key"];
	$at_shop_id = $_POST["allat_shop_id"];
	//--------------------------------------------------------

	// 요청 데이터 설정
	//----------------------
	$at_data = "allat_shop_id=".$at_shop_id.
	"&allat_enc_data=".$_POST["allat_enc_data"].
	"&allat_cross_key=".$at_cross_key;

	// 올앳 결제 서버와 통신 : CancelReq->통신함수, $at_txt->결과값
	//----------------------------------------------------------------
	// PHP5 이상만 SSL 사용가능
	// $at_txt = CancelReq($at_data,"SSL");
	$at_txt = CancelReq($at_data, "NOSSL"); // PHP5 이하버전일 경우
	// 이 부분에서 로그를 남기는 것이 좋습니다.
	// (올앳 결제 서버와 통신 후에 로그를 남기면, 통신에러시 빠른 원인파악이 가능합니다.)

	// 결제 결과 값 확인
	//------------------
	$REPLYCD	= getValue("reply_cd",$at_txt);	//결과코드
	$REPLYMSG	= getValue("reply_msg",$at_txt); //결과 메세지

	// 결과값(왜 이변수가 2번이나 선언됐을까? 미스테리..)
	//----------------------------------------------------------------
	$REPLYCD	= getValue("reply_cd",$at_txt);	//결과코드
	$REPLYMSG	= getValue("reply_msg",$at_txt); //결과 메세지

	$res_msg = $REPLYMSG;

	// 결과값 처리
	//--------------------------------------------------------------------------
	// 결과 값이 '0000'이면 정상임. 단, allat_test_yn=Y 일경우 '0001'이 정상임.
	// 실제 결제   : allat_test_yn=N 일 경우 reply_cd=0000 이면 정상
	// 테스트 결제 : allat_test_yn=Y 일 경우 reply_cd=0001 이면 정상
	//--------------------------------------------------------------------------

	if($_POST["allat_test_yn"] == 'Y') {
		$allat_res_cd = '0001';
	} else {
		$allat_res_cd = '0000';
	}

	if( !strcmp($REPLYCD,$allat_res_cd) ){ // 성공
		$res_cd = '0000';

		$CANCEL_YMDHMS=getValue("cancel_ymdhms",$at_txt);
		$PART_CANCEL_FLAG=getValue("part_cancel_flag",$at_txt);
		$REMAIN_AMT=getValue("remain_amt",$at_txt);
		$PAY_TYPE=getValue("pay_type",$at_txt);

		$logfile = fopen( TB_SHOP_PATH.'/allat/log/cancel_'.date("Ymd").".log", "a+" );

		fwrite( $logfile,"***************************************************************************\r\n");
		fwrite( $logfile,"[".date("Y/m/d H:i:s")."] 취소 결과값\r\n");
		fwrite( $logfile,"결과코드 : ".$REPLYCD."\r\n");
		fwrite( $logfile,"결과메세지 : ".iconv_utf8($REPLYMSG)."\r\n");
		fwrite( $logfile,"취소날짜 : ".$CANCEL_YMDHMS."\r\n");
		fwrite( $logfile,"취소구분 : ".$PART_CANCEL_FLAG."\r\n");
		fwrite( $logfile,"잔액 : ".$REMAIN_AMT."\r\n");
		fwrite( $logfile,"거래방식구분 : ".$PAY_TYPE."\r\n");
		fclose( $logfile );

	} else { // 실패
		$res_cd = $REPLYCD;
	}
}
?>