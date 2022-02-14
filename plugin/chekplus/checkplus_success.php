<?php
include_once("./_common.php");

//**************************************************************************************************************
//NICE신용평가정보 Copyright(c) KOREA INFOMATION SERVICE INC. ALL RIGHTS RESERVED

//서비스명 :  체크플러스 - 안심본인인증 서비스
//페이지명 :  체크플러스 - 결과 페이지

//보안을 위해 제공해드리는 샘플페이지는 서비스 적용 후 서버에서 삭제해 주시기 바랍니다. 
//**************************************************************************************************************
$sitecode		= $default['de_checkplus_id']; // NICE로부터 부여받은 사이트 코드
$sitepasswd		= $default['de_checkplus_pw']; // NICE로부터 부여받은 사이트 패스워드
$cb_encode_path = TB_PLUGIN_PATH."/chekplus/CPClient"; // NICE로부터 받은 암호화 프로그램의 위치
	
$enc_data		= $_POST["EncodeData"]; // 암호화된 결과 데이타
$sReserved1		= $_POST['param_r1'];		
$sReserved2		= $_POST['param_r2'];
$sReserved3		= $_POST['param_r3'];

// 문자열 점검
if(preg_match("/[#\&\\-%@\\\:;,\.\'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", $enc_data, $match)) {echo "문자열 점검 : ".$match[0]; exit;}
if(preg_match("/[#\&\\+\-%@=\/\\\:;,\.\'\"\^`~\_|\!\/\?\*$#<>()\[\]\{\}]/i", $sReserved1, $match)) {echo "문자열 점검 : ".$match[0]; exit;}
if(preg_match("/[#\&\\+\-%@=\/\\\:;,\.\'\"\^`~\_|\!\/\?\*$#<>()\[\]\{\}]/i", $sReserved2, $match)) {echo "문자열 점검 : ".$match[0]; exit;}
//if(preg_match("/[#\&\\+\-%@=\/\\\:;,\.\'\"\^`~\_|\!\/\?\*$#<>()\[\]\{\}]/i", $sReserved3, $match)) {echo "문자열 점검 : ".$match[0]; exit;}
	
if ($enc_data != "") {

	$plaindata = `$cb_encode_path DEC $sitecode $sitepasswd $enc_data`;		// 암호화된 결과 데이터의 복호화
    // echo "[plaindata]  " . $plaindata . "<br>";

	if ($plaindata == -1){
		$returnMsg  = "암/복호화 시스템 오류";
	}else if ($plaindata == -4){
		$returnMsg  = "복호화 처리 오류";
	}else if ($plaindata == -5){
		$returnMsg  = "HASH값 불일치 - 복호화 데이터는 리턴됨";
	}else if ($plaindata == -6){
		$returnMsg  = "복호화 데이터 오류";
	}else if ($plaindata == -9){
		$returnMsg  = "입력값 오류";
	}else if ($plaindata == -12){
		$returnMsg  = "사이트 비밀번호 오류";
	}else{
		// 복호화가 정상적일 경우 데이터를 파싱합니다.
		$ciphertime = `$cb_encode_path CTS $sitecode $sitepasswd $enc_data`;	// 암호화된 결과 데이터 검증 (복호화한 시간획득)
		$requestnumber = GetValue($plaindata , "REQ_SEQ");
		$responsenumber = GetValue($plaindata , "RES_SEQ");
		$authtype = GetValue($plaindata , "AUTH_TYPE");
		$name = GetValue($plaindata , "NAME");
		$birthdate = GetValue($plaindata , "BIRTHDATE");
		$gender = GetValue($plaindata , "GENDER");
		$nationalinfo = GetValue($plaindata , "NATIONALINFO");	//내/외국인정보(사용자 매뉴얼 참조)
		$dupinfo = GetValue($plaindata , "DI");
		$conninfo = GetValue($plaindata , "CI");
		$mobileno = GetValue($plaindata , "MOBILE_NO");
		if(strcmp($REQ_SEQ, $requestnumber) != 0)
		{
			$requestnumber = "";
			$responsenumber = "";
			$authtype = "";
			$name = "";
			$birthdate = "";
			$gender = "";
			$nationalinfo = "";
			$dupinfo = "";
			$conninfo = "";
		}
	}
}

function GetValue($str , $name)
{
	$pos1 = 0;  //length의 시작 위치
	$pos2 = 0;  //:의 위치

	while( $pos1 <= strlen($str) )
	{
		$pos2 = strpos( $str , ":" , $pos1);
		$len = substr($str , $pos1 , $pos2 - $pos1);
		$key = substr($str , $pos2 + 1 , $len);
		$pos1 = $pos2 + $len + 1;
		if( $key == $name )
		{
			$pos2 = strpos( $str , ":" , $pos1);
			$len = substr($str , $pos1 , $pos2 - $pos1);
			$value = substr($str , $pos2 + 1 , $len);
			return $value;
		}
		else
		{
			// 다르면 스킵한다.
			$pos2 = strpos( $str , ":" , $pos1);
			$len = substr($str , $pos1 , $pos2 - $pos1);
			$pos1 = $pos2 + $len + 1;
		}            
	}
}

$sql = " update shop_joincheck 
            set j_ciphertime	 = '$ciphertime',
				j_requestnumber	 = '$requestnumber',
				j_responsenumber = '$responsenumber',
				j_authtype		 = '$authtype',
				j_name			 = '".iconv_utf8($name)."',
				j_birthdate		 = '$birthdate',
				j_sex			 = '$gender',
				j_nationalinfo	 = '$nationalinfo',
				DI				 = '$dupinfo',
				CI				 = '$conninfo' ,
				allow			 = 'Y' ,
				cell			 = '$mobileno'
				where j_key		 = '$REQ_SEQ'";
sql_query($sql);

set_session("j_key", $REQ_SEQ);
set_session("allow", 'Y');

if(is_mobile())
	$opener_href = TB_MBBS_URL.'/register_form.php';
else
	$opener_href = TB_BBS_URL.'/register_form.php';

echo '
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title>본인인증</title>
<body>
<script>
alert("정상적으로 인증 되었습니다.");
opener.location.href="'.$opener_href.'";
self.close();
</script>
</body>
</html>';
?>