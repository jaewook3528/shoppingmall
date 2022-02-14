<?php
include_once("./_common.php");
	
// 사용자 정보 및 CP 요청번호를 암호화한 데이타입니다. (ipin_main.php 페이지에서 암호화된 데이타와는 다릅니다.)
$sResponseData	  = $_POST['enc_data'];

// ipin_main.php 페이지에서 설정한 데이타가 있다면, 아래와 같이 확인가능합니다.
$sReservedParam1  = $_POST['param_r1'];
$sReservedParam2  = $_POST['param_r2'];
$sReservedParam3  = $_POST['param_r3'];

//////////////////////////////////////////////// 문자열 점검///////////////////////////////////////////////
if(preg_match("/[#\&\\-%@\\\:;,\.\'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", $sResponseData, $match)) {echo "문자열 점검 : ".$match[0]; exit;}
if(preg_match("/[#\&\\+\-%@=\/\\\:;,\.\'\"\^`~\_|\!\/\?\*$#<>()\[\]\{\}]/i", $sReservedParam1, $match)) {echo "문자열 점검 : ".$match[0]; exit;}
if(preg_match("/[#\&\\+\-%@=\/\\\:;,\.\'\"\^`~\_|\!\/\?\*$#<>()\[\]\{\}]/i", $sReservedParam2, $match)) {echo "문자열 점검 : ".$match[0]; exit;}
//  if(preg_match("/[#\&\\+\-%@=\/\\\:;,\.\'\"\^`~\_|\!\/\?\*$#<>()\[\]\{\}]/i", $sReservedParam3, $match)) {echo "문자열 점검 : ".$match[0]; exit;}
///////////////////////////////////////////////////////////////////////////////////////////////////////////

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

// 암호화된 사용자 정보가 존재하는 경우
if ($sResponseData != "") {
?>

<html>
<head>
<title>NICE신용평가정보 가상주민번호 서비스</title>
<script language='javascript'>
function fnLoad()
{
	// 당사에서는 최상위를 설정하기 위해 'parent.opener.parent.document.'로 정의하였습니다.
	// 따라서 귀사에 프로세스에 맞게 정의하시기 바랍니다.
	document.vnoform.enc_data.value = "<?php echo $sResponseData; ?>";			
	document.vnoform.param_r1.value = "<?php echo $sReservedParam1; ?>";
	document.vnoform.param_r2.value = "<?php echo $sReservedParam2; ?>";
	document.vnoform.param_r3.value = "<?php echo $sReservedParam3; ?>";			
	document.vnoform.target = "Parent_window";
	
	// 인증 완료시에 인증결과를 수신하게 되는 귀사 클라이언트 결과 페이지 URL
	document.vnoform.action = "ipin_result.php";
	document.vnoform.submit();

	self.close();
}
</script>
</head>
<body onLoad="fnLoad()">
<form name="vnoform" method="post">
<input type="hidden" name="enc_data">
<input type="hidden" name="param_r1" value="">
<input type="hidden" name="param_r2" value="">
<input type="hidden" name="param_r3" value="">
</form>
<?php } else { ?>
<html>
<head>
<title>NICE신용평가정보 가상주민번호 서비스</title>
<body onLoad="self.close()">
<?php } ?>
</body>
</html>