<?php
include_once('./_common.php');
insertSettleRes('AL',$_REQUEST);

//**********************************************************************************
// 올앳페이가 전달하는 가상계좌이체의 결과를 수신하여 DB 처리 하는 부분 입니다.
// 필요한 파라메터에 대한 DB 작업을 수행하십시오.
//**********************************************************************************
$shop_id				= trim($_REQUEST["shop_id"]);					//상점ID
$order_no				= trim($_REQUEST["order_no"]);					//주문번호
$tx_seq_no				= trim($_REQUEST["tx_seq_no"]);					//거래일련번호
$account_no				= trim($_REQUEST["account_no"]);				//가상계좌 계좌번호
$bank_cd				= trim($_REQUEST["bank_cd"]);					//가상계좌 은행코드
$common_bank_cd			= trim($_REQUEST["common_bank_cd"]);			//가상계좌 공동은행코드
$apply_ymdhms			= trim($_REQUEST["apply_ymdhms"]);				//승인요청일
$approval_ymdhms		= trim($_REQUEST["approval_ymdhms"]);			//가상계좌 채번일
$income_ymdhms			= trim($_REQUEST["income_ymdhms"]);				//가상계좌 입금일
$apply_amt				= trim($_REQUEST["apply_amt"]);					//채번금액
$income_amt				= trim($_REQUEST["income_amt"]);				//입금금액
$income_account_nm		= iconv_utf8(trim($_REQUEST["income_account_nm"])); //입금자명
$receipt_seq_no			= trim($_REQUEST["receipt_seq_no"]);			//현금영수증 일련번호
$cash_approval_no		= trim($_REQUEST["cash_approval_no"]);			//현금영수증 승인번호
$noti_currenttimemillis = trim($_REQUEST["noti_currenttimemillis"]);	//Noti 통보일
$hash_value				= trim($_REQUEST["hash_value"]);				//해쉬 Data

$income_ymdhms = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3 \\4:\\5:\\6", $income_ymdhms);

$default = set_partner_value($shop_id);
$at_shop_id = $default['de_allat_mid'];
$at_cross_key = $default['de_allat_crosskey'];

$uid = md5($at_shop_id.$at_cross_key.$order_no.$noti_currenttimemillis);

if($uid != $hash_value) {
	echo iconv_euckr("9999 hash_value불일치");
	exit;
}

// 입금결과 처리
$result = false;

// 주문서 UPDATE
$sql = " update shop_order
			set receipt_time = '$income_ymdhms'
			  , deposit_name = '$income_account_nm'
			  , dan = '2'
		  where od_id = '$order_no'
			and od_app_no = '$account_no'
			and dan = '1' ";
$result = sql_query($sql, FALSE);

if($result) {
	$sql = "update shop_cart set ct_select = '1' where od_id = '$order_no' ";
	sql_query($sql, FALSE);

	echo iconv_euckr("0000 정상");
} else {
	echo iconv_euckr("9999 DB처리실패");
}
?>