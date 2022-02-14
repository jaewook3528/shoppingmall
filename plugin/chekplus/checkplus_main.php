<?php
//**************************************************************************************************************
//NICE신용평가정보 Copyright(c) KOREA INFOMATION SERVICE INC. ALL RIGHTS RESERVED

//서비스명 :  체크플러스 - 안심본인인증 서비스
//페이지명 :  체크플러스 - 메인 호출 페이지

//보안을 위해 제공해드리는 샘플페이지는 서비스 적용 후 서버에서 삭제해 주시기 바랍니다. 
//**************************************************************************************************************

$sitecode		= $default['de_checkplus_id']; // NICE로부터 부여받은 사이트 코드
$sitepasswd		= $default['de_checkplus_pw']; // NICE로부터 부여받은 사이트 패스워드
$cb_encode_path = TB_PLUGIN_PATH."/chekplus/CPClient"; // NICE로부터 받은 암호화 프로그램의 위치

$authtype		= "M"; // 없으면 기본 선택화면, X: 공인인증서, M: 핸드폰, C: 카드
$popgubun 		= "Y"; //Y : 취소버튼 있음 / N : 취소버튼 없음
$customize		= is_mobile() ? "Mobile":""; //없으면 기본 웹페이지 / Mobile : 모바일페이지
$reqseq			= "REQ_0123456789"; // 요청 번호, 이는 성공/실패후에 같은 값으로 되돌려주게 되므로
	
// 업체에서 적절하게 변경하여 쓰거나, 아래와 같이 생성한다.
$reqseq			= `$cb_encode_path SEQ $sitecode`;

// CheckPlus(본인인증) 처리 후, 결과 데이타를 리턴 받기위해 다음예제와 같이 http부터 입력합니다.
$returnurl		= TB_PLUGIN_URL."/chekplus/checkplus_success.php";	// 성공시 이동될 URL
$errorurl		= TB_PLUGIN_URL."/chekplus/checkplus_fail.php"; // 실패시 이동될 URL

// reqseq값은 성공페이지로 갈 경우 검증을 위하여 세션에 담아둔다.
set_session("REQ_SEQ", $reqseq);

// 입력될 plain 데이타를 만든다.
$plaindata =  "7:REQ_SEQ" . strlen($reqseq) . ":" . $reqseq .
						  "8:SITECODE" . strlen($sitecode) . ":" . $sitecode .
						  "9:AUTH_TYPE" . strlen($authtype) . ":". $authtype .
						  "7:RTN_URL" . strlen($returnurl) . ":" . $returnurl .
						  "7:ERR_URL" . strlen($errorurl) . ":" . $errorurl .
						  "11:POPUP_GUBUN" . strlen($popgubun) . ":" . $popgubun .
						  "9:CUSTOMIZE" . strlen($customize) . ":" . $customize ;

$enc_data = `$cb_encode_path ENC $sitecode $sitepasswd $plaindata`;

if( $enc_data == -1 )
{
	$returnMsg = "암/복호화 시스템 오류입니다.";
	$enc_data = "";
}
else if( $enc_data== -2 )
{
	$returnMsg = "암호화 처리 오류입니다.";
	$enc_data = "";
}
else if( $enc_data== -3 )
{
	$returnMsg = "암호화 데이터 오류 입니다.";
	$enc_data = "";
}
else if( $enc_data== -9 )
{
	$returnMsg = "입력값 오류 입니다.";
	$enc_data = "";
}

$j_key = get_session('REQ_SEQ');
$sql = "insert into shop_joincheck set j_key = '$j_key'";
sql_query($sql);
?>