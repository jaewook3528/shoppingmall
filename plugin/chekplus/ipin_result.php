<?php
include_once("./_common.php");

$sSiteCode	= $default['de_ipin_id']; // NICE신용평가정보에서 발급한 사이트코드
$sSitePw	= $default['de_ipin_pw']; // NICE신용평가정보에서 발급한 사이트패스워드
$sEncData   = $_POST['enc_data']; // ipin_process.php 에서 리턴받은 암호화 된 사용자 인증 정보
    
//////////////////////////////////////////////// 문자열 점검///////////////////////////////////////////////
if(preg_match("/[#\&\\-%@\\\:;,\.\'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", $sEncData, $match)) {echo "문자열 점검 : ".$match[0]; exit;}
//////////////////////////////////////////////////////////////////////////////////////////////////////////    
    
/*
┌ sModulePath 변수에 대한 설명  ─────────────────────────────────────────────────────
	모듈 경로설정은, '/절대경로/모듈명' 으로 정의해 주셔야 합니다.
	
	+ FTP 로 모듈 업로드시 전송형태를 'binary' 로 지정해 주시고, 권한은 755 로 설정해 주세요.
	
	+ 절대경로 확인방법
	  1. Telnet 또는 SSH 접속 후, cd 명령어를 이용하여 모듈이 존재하는 곳까지 이동합니다.
	  2. pwd 명령어을 이용하면 절대경로를 확인하실 수 있습니다.
	  3. 확인된 절대경로에 '/모듈명'을 추가로 정의해 주세요.
└────────────────────────────────────────────────────────────────────
*/
$sModulePath = TB_PLUGIN_PATH."/chekplus/IPIN2Client";
	
// ipin_main.php 에서 저장한 세션 정보를 추출합니다.
// 데이타 위변조 방지를 위해 확인하기 위함이므로, 필수사항은 아니며 보안을 위한 권고사항입니다.
$sCPRequest = $_SESSION['CPREQUEST'];
   
$sDecData	= ""; // 복호화 된 사용자 인증 정보
$sRtnMsg	= ""; // 처리결과 메세지
    
if ($sEncData != "") {

	// 사용자 정보를 복호화 합니다.
	// 실행방법은 싱글쿼터(`) 외에도, 'exec(), system(), shell_exec()' 등등 귀사 정책에 맞게 처리하시기 바랍니다.
	$sDecData = `$sModulePath RES $sSiteCode $sSitePw $sEncData`;
	
	if ($sDecData == -9) {
		$sRtnMsg = "입력값 오류 : 복호화 처리시, 필요한 파라미터값의 정보를 정확하게 입력해 주시기 바랍니다.";
	} else if ($sDecData == -12) {
		$sRtnMsg = "NICE신용평가정보에서 발급한 개발정보가 정확한지 확인해 보세요.";
	} else {
	
		/*
			- 복호화된 데이타 구성
			'데이터에 대한 byte:데이터' 형식으로 구성되어 있습니다.
		*/
		$arrData = split(":", $sDecData);
		$iCount = count($arrData);
		
		if ($iCount >= 5) {
		
			/*
				다음과 같이 사용자 정보를 추출할 수 있습니다.
				사용자에게 보여주는 정보는, '이름' 데이타만 노출 가능합니다.
			
				사용자 정보를 다른 페이지에서 이용하실 경우에는
				보안을 위하여 암호화 데이타($sEncData)를 통신하여 복호화 후 이용하실것을 권장합니다. (현재 페이지와 같은 처리방식)
				
				만약, 복호화된 정보를 통신해야 하는 경우엔 데이타가 유출되지 않도록 주의해 주세요. (세션처리 권장)
				form 태그의 hidden 처리는 데이타 유출 위험이 높으므로 권장하지 않습니다.
			*/
			
			$strResultCode		= GetValue($sDecData, "RESULT_CODE"); // 결과코드
			if ($strResultCode == 1) {
				$strCPRequest	= GetValue($sDecData, "CPREQUESTNO"); // CP 요청번호
				
				if ($sCPRequest == $strCPRequest) {
			
					$sRtnMsg = "정상적으로 인증 되었습니다";
					
					$strVnumber = GetValue($sDecData, "VNUMBER"); // 가상주민번호 (13자리, 숫자 또는 문자 포함)
					$strUserName = GetValue($sDecData, "NAME"); // 이름
					$strDupInfo = GetValue($sDecData, "DUPINFO"); // 중복가입 확인값 (DI - 64 byte 고유값)
					$strGender = GetValue($sDecData, "GENDERCODE"); // 성별 코드 (개발 가이드 참조)
					$strAgeInfo = GetValue($sDecData, "AGECODE"); // 연령대 코드 (개발 가이드 참조)
					$strBirthDate = GetValue($sDecData, "BIRTHDATE"); // 생년월일 (YYYYMMDD)
					$strNationalInfo = GetValue($sDecData, "NATIONALINFO"); // 내/외국인 정보 (개발 가이드 참조)
					$strAuthInfo = GetValue($sDecData, "AUTHMETHOD"); // 본인확인 수단 (개발 가이드 참조)
					$strCoInfo = GetValue($sDecData, "COINFO1"); // 연계정보 확인값 (CI - 88 byte 고유값)
					$strCIUpdate = GetValue($sDecData, "CIUPDATE"); // CI 갱신정보
				
					$sql = " update shop_joincheck
					            set CI				= '$strCoInfo',
									j_birthdate		= '$strBirthDate' ,
									j_sex			= '$strGender',
									j_name			= '".iconv_utf8($strUserName)."',
									j_nationalinfo  = '$strNationalInfo',
									allow			= 'Y',
									j_authtype		= '$strAuthInfo'
							  where j_key			= '$sCPRequest'";
					sql_query($sql);

					set_session("j_key", $sCPRequest);
					set_session("allow", 'Y');					

				} else {
					$sRtnMsg = "CP 요청번호 불일치 : 세션에 넣은 $sCPRequest 데이타를 확인해 주시기 바랍니다.";
				}
			} else {
				$sRtnMsg = "리턴값 확인 후, NICE신용평가정보 개발 담당자에게 문의해 주세요. [$strResultCode]";
			}
		
		} else {
			$sRtnMsg = "리턴값 확인 후, NICE신용평가정보 개발 담당자에게 문의해 주세요.";
		}
	
	}
} else {
	$sRtnMsg = "처리할 암호화 데이타가 없습니다.";
}

echo '<script>alert(\''.$sRtnMsg.'\');</script>';

if ($sCPRequest == $strCPRequest) {
	if(is_mobile())
		goto_url(TB_MBBS_URL.'/register_form.php');
	else
		goto_url(TB_BBS_URL.'/register_form.php');
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
?>