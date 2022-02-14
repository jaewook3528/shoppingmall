<?php
/* ========================================================================== */
/* =   프로그램 명		:	Culture_library.php                   = */
/* =   프로그램 설명		:	Library 파일 		            = */
/* =   작성일			:	2009-04      		            = */
/* =   저작권			:	(주)다우기술                        = */
/* ========================================================================== */
require "./config/keycode.cfg";	
define( "PAYTYPE"	,"D" );
define( "DAOU_SOCK_ERROR"	,"-1" );
define( "VERSION"	,"1.0" );
define( "REQUEST"	,"01" );
define( "RESPONSE"	,"02" );
define( "ACK"	,"03" );

?>
<?php
function CardBinchk( $server_ip, $port, $cpid, $enckey, $timeout ) 
{
	global $CARDBINNO;
	$CARDBINNO = substr($CARDBINNO, 0,6);
	
	global $res_resultcode;
	global $res_errormessage;
	global $res_cardcd;
	
	$sendBody = $CARDBINNO;	
	$opcode = PAYTYPE."CHKBIN_";
	
  	$recvArray = array();
  	$recvArray = explode( "|", bin_do( $server_ip, $port, $opcode, $cpid, $timeout, $enckey, $sendBody));
	
	$res_resultcode		= $recvArray[0];
	$res_errormessage	= $recvArray[1];
	$res_cardcd			= $recvArray[2];

}

/* ========================================================================== */
/* =   함수 명			:	CardOrderSugi	    	            = */
/* =   함수 설명		:	카드 수기단건 결제 요청 		    = */
/* =   작성일			:	2009-04      		            = */
/* =   저작권			:	(주)다우기술      	            = */
/* ========================================================================== */
function CardOrderSugi( $server_ip, $port, $cpid, $enckey, $timeout ) 
{
    global $opcode;
	global $ORDERNO;
	global $PRODUCTTYPE;
	global $BILLTYPE;
	global $CARDCD;
	global $TAXFREECD;
	global $QUOTA;
	global $AMOUNT;
	global $IPADDRESS;
	
	global $USEREMAIL;
	global $USERID;
	global $USERNAME;
	global $PRODUCTCODE;
	global $PRODUCTNAME;
	global $TELNO1;
	global $TELNO2;
	global $RESERVEDINDEX1;
	global $RESERVEDINDEX2;
	global $RESERVEDSTRING;

	global $res_resultcode;
	global $res_errormessage;
	global $res_certtype;
	global $res_daoutrx;
	
        $sendBody = $ORDERNO.'|'.
					$PRODUCTTYPE.'|'.
					$BILLTYPE.'|'.
					$CARDCD.'|'.
					$TAXFREECD.'|'.
					$QUOTA.'|'.
					$AMOUNT.'|'.
					$IPADDRESS.'|'.
					$USERID.'|'.
					$USERNAME.'|'.
					$PRODUCTCODE.'|'.
					$PRODUCTNAME.'|'.
					$TELNO1.'|'.
					$TELNO2.'|'.
					"".'|'.
					$RESERVEDINDEX1.'|'.
					$RESERVEDINDEX2.'|'.
					$RESERVEDSTRING;					
					
		$opcode = PAYTYPE."ORDER__";
				
  	$recvArray = array();
	
  	$recvArray = explode( "|", bin_do( $server_ip, $port, $opcode, $cpid, $timeout, $enckey, $sendBody));
		
	$res_resultcode		= $recvArray[0];
	$res_errormessage	= $recvArray[1];
	$res_certtype		= $recvArray[2];
	$res_daoutrx		= $recvArray[3];
}


/* ========================================================================== */
/* =   함수 명			:	CardAuthSugi	    	            = */
/* =   함수 설명		:	카드 수기단건 결제과금 요청 		    = */
/* =   작성일			:	2009-04      		            = */
/* =   저작권			:	(주)다우기술      	            = */
/* ========================================================================== */
function CardAuthSugi( $server_ip, $port, $cpid, $enckey, $timeout ) 
{
    global $opcode;
	global $DAOUTRX;
	global $CERTTYPE;		
	global $CERTRESULTCODE;
	global $CERTRESULTMSG;
	global $USEREMAIL;
	global $USERMOBILENO;
	global $AMOUNT;	
	global $QUOTA;		
	global $NOINTFLAG;
	global $ENCDATA1;	
	global $ENCDATA2;		
	global $ENCDATA3;		
	global $ENCDATA4;
	global $AUTHRESERVED;
	
	global $res_resultcode;
	global $res_errormessage;
	global $res_daoutrx;
	global $res_amount;
	global $res_orderno;
	global $res_authdate;
	global $res_authno;
	global $res_nointflag;
	global $res_quota;
	global $res_cpname;
	global $res_cpurl;
	global $res_cptelno;
	global $res_daoureserved1;
	global $res_daoureserved2;
	
	$ENCDATA1 = _packet_encrypt( $ENCDATA1, $enckey);
	$ENCDATA2 = _packet_encrypt( $ENCDATA2, $enckey);
	$ENCDATA3 = _packet_encrypt( $ENCDATA3, $enckey);
	$ENCDATA4 = _packet_encrypt( $ENCDATA4, $enckey);
	
        $sendBody = $DAOUTRX.'|'.
					$CERTTYPE.'|'.
					$CERTRESULTCODE.'|'.
					$CERTRESULTMSG.'|'.
					$USEREMAIL.'|'.
					$USERMOBILENO.'|'.
					$AMOUNT.'|'.
					$QUOTA.'|'.
					$NOINTFLAG.'|'.
					$ENCDATA1.'|'.
					$ENCDATA2.'|'.
					$ENCDATA3.'|'.
					$ENCDATA4.'|'.
					$AUTHRESERVED;					
					
		$opcode = PAYTYPE."AUTH___";
		
  	$recvArray = array();
  	$recvArray = explode( "|", bin_do( $server_ip, $port, $opcode, $cpid, $timeout, $enckey, $sendBody));

  	$res_resultcode  	= $recvArray[0];
  	$res_errormessage	= $recvArray[1];
  	$res_daoutrx      	= $recvArray[2];
  	$res_amount       	= $recvArray[3];
	
	$res_orderno		= $recvArray[4];
	$res_authdate		= $recvArray[5];
	$res_authno		= $recvArray[6];
	$res_nointflag		= $recvArray[7];
	$res_quota			= $recvArray[8];
	$res_cpname		= $recvArray[9];
	$res_cpurl			= $recvArray[10];
	$res_cptelno		= $recvArray[11];
	$res_daoureserved1	= $recvArray[12]; //카드사 코드
	$res_daoureserved2	= $recvArray[13];
}


/* ========================================================================== */
/* =   함수 명			:	CardCancel                           = */
/* =   함수 설명		:	신용카드취소		            = */
/* =   작성일			:	2014-09      		            = */
/* =   저작권			:	(주)다우기술      	            = */
/* ========================================================================== */
function CardCancel(  $server_ip, $port, $cpid, $enckey, $timeout ) {
	
	global $opcode;
	global $DAOUTRX;
	global $AMOUNT;
	global $IPADDRESS;
	global $CANCELMEMO;
	
	global $res_resultcode;
	global $res_errormessage;
	global $res_daoutrx;
	global $res_amount;
	global $res_canceldate;

	
	$sendBody = 
    		_MaxLength($DAOUTRX,20).'|'.
    		_MaxLength($AMOUNT,10).'|'.
			_MaxLength($IPADDRESS,40).'|'.
    		_MaxLength($CANCELMEMO,50);


		//opcode 추가
		$opcode = PAYTYPE."CANCEL_";
		
	  	$recvArray	= array();

	  	$recvArray	= explode( "|", bin_do(  $server_ip, $port, $opcode, $cpid, $timeout, $enckey, $sendBody));

	  	$res_resultcode    	= $recvArray[0];
	  	$res_errormessage  	= $recvArray[1];
	  	$res_daoutrx       	= $recvArray[2];
	  	$res_amount        	= $recvArray[3];
	  	$res_canceldate      = $recvArray[4];
}

/* ========================================================================== */
/* =   함수 명			:	bin_do		   	  	    = */
/* =   함수 설명		:	bin 실행 			    = */
/* =   작성일			:	2009-02      	                    = */
/* =   저작권			:	(주)다우기술                        = */
/* ========================================================================== */
function bin_do( $argv1="", $argv2="", $argv3="", $argv4="", $argv5="", $argv6="", $argv7="" )
{
	
		//body 부분 encrypt 암호화
		$daou_packet_body = _packet_encrypt( $argv7, $argv6);

		//소캣 통신
		$result = _requestImpayUnit($daou_packet_body, $argv3, $argv4);

		//result 부분 decrypt
		$rt = _packet_decrypt( $result, $argv6);
	
		echo "rt:".$rt;
	
		return $rt;

}


function _encutment($fbi) {         
	$fbi = substr($fbi,46,2).substr($fbi,12,1).substr($fbi,268,2).substr($fbi,7,1).substr($fbi,169,2).substr($fbi,46,1).substr($fbi,305,1).substr($fbi,188,1).substr($fbi,103,1).substr($fbi,402,1).substr($fbi,246,2).substr($fbi,193,1);
	return $fbi;
}

function _decutment($swx) {         
	$swx = substr($swx,46,2).substr($swx,140,1).substr($swx,268,2).substr($swx,110,1).substr($swx,169,2).substr($swx,212,1).substr($swx,305,1).substr($swx,312,1).substr($swx,318,1).substr($swx,232,1).substr($swx,246,2).substr($swx,193,1).substr($swx,12,1).substr($swx,103,1).substr($swx,269,2).substr($swx,46,1).substr($swx,200,1).substr($swx,46,1).substr($swx,509,2).substr($swx,79,2).substr($swx,136,1).substr($swx,80,1).substr($swx,178,1).substr($swx,80,1).substr($swx,300,1);
	return $swx;
} 

function hextobin($hexdata) {                
	$bindata="";                
	for ($i=0;$i<strlen($hexdata);$i+=2) {                        
		$bindata.=chr(hexdec(substr($hexdata,$i,2)));                
	}                
	return $bindata;        
}                

function _packet_encrypt($param_input_string, $enckey)
{
	$desend =  "";
	$desend2 =  "";
	$desend3 =  "";
	
	$belief = array();
	$belief[0] = "0f1e2d34cb5a6978";
	$belief[1] = "57930f21a38ec6b4";
	$belief[2] = "1d72eb0f39ac8465";
	$belief[3] = "c3ab69720514df8e";
	$belief[4] = "a35c97b261d04fe8";
	$belief[5] = "905373c2f41a8e6b";
	$belief[6] = "1d72eb09fc38a465";
	$belief[7] = "5793201f3aec86b4";
	$belief[8] = "c5ab36917024dfe8";
	$belief[9] = "395730c8f142abe6";
	$belief[10] = "d1e7209bcf38a645";
	$belief[11] = "903537cf2a481e6b";
	$belief[12] = "c5a3b97261d40f8e";
	$belief[13] = "1d2e7fb03c9a8465";
	$belief[14] = "a53c7b926d014e8f";
	$belief[15] = "10efd234cab56987";
	
	$hope = array();
	$hope[0] = "sjhwvbsb";
	$hope[1] = "hwcsghea";
	$hope[2] = "gncabknd";
	$hope[3] = "nitenksw";
	$hope[4] = "tegnlsdd";
	$hope[5] = "mdmehddp";
	$hope[6] = "leokfsdk";
	$hope[7] = "qawsxawy";
	$hope[8] = "unversty";
	$hope[9] = "bananaqq";
	$hope[10] = "internet";
	$hope[11] = "menglong";
	$hope[12] = "poiukjhg";
	$hope[13] = "qwsxashj";
	$hope[14] = "wordwide";
	$hope[15] = "tgbyhnrf";
	
	$webdate = array();
	$webdate[0] = "20120321232312aa";
	$webdate[1] = "20120223223312bb";
	$webdate[2] = "20120509223312aa";
	$webdate[3] = "20120302223312bb";
	$webdate[4] = "20110418223312aa";
	$webdate[5] = "20120426223312bb";
	$webdate[6] = "20120312223312aa";
	$webdate[7] = "20100723223312bb";
	$webdate[8] = "20091211223312aa";
	$webdate[9] = "20080625223312bb";
	$webdate[10] = "20120508223312aa";
	$webdate[11] = "20120104223312bb";
	$webdate[12] = "20111103223312aa";
	$webdate[13] = "20110209223312bb";
	$webdate[14] = "20120323223312aa";
	$webdate[15] = "20090217223312bb";
	
//	for($i = 0 ; $i < 16 ; $i++){
//		$desend.= $belief[$i].$webdate[$i].$hope[$i] % 16;
//	}
	
	//USE PHP7.1
	$desend = "0f1e2d34cb5a697820120321232312aa057930f21a38ec6b420120223223312bb01d72eb0f39ac846520120509223312aa0c3ab69720514df8e20120302223312bb0a35c97b261d04fe820110418223312aa0905373c2f41a8e6b20120426223312bb01d72eb09fc38a46520120312223312aa05793201f3aec86b420100723223312bb0c5ab36917024dfe820091211223312aa0395730c8f142abe620080625223312bb0d1e7209bcf38a64520120508223312aa0903537cf2a481e6b20120104223312bb0c5a3b97261d40f8e20111103223312aa01d2e7fb03c9a846520110209223312bb0a53c7b926d014e8f20120323223312aa010efd234cab5698720090217223312bb0";
		
	$suq = base64_encode(_encutment($desend));
	$xst = base64_encode(_decutment($desend));

	$key2 = bin2hex($enckey);
	
	//USE PHP7.1
	//$padding_string = _toPkcs7($param_input_string);
	$padding_string = pkcs5_pad($param_input_string);
	
	//USE PHP7.1
	//$cipher_txt = @mcrypt_encrypt(MCRYPT_RIJNDAEL_128, hextobin(_hash($suq).$key2), $padding_string, MCRYPT_MODE_CBC,_unhash($xst));	
	$cipher_txt = @openssl_encrypt($padding_string, 'AES-128-CBC', hextobin(_hash($suq).$key2), OPENSSL_ZERO_PADDING|OPENSSL_RAW_DATA, _unhash($xst));
	
	$encrypttext = bin2hex($cipher_txt);
			
	return $encrypttext;	
}


	
function _packet_decrypt($param_input_string, $enckey)
{  
	$desend =  "";
	
	$belief = array();
	$belief[0] = "0f1e2d34cb5a6978";
	$belief[1] = "57930f21a38ec6b4";
	$belief[2] = "1d72eb0f39ac8465";
	$belief[3] = "c3ab69720514df8e";
	$belief[4] = "a35c97b261d04fe8";
	$belief[5] = "905373c2f41a8e6b";
	$belief[6] = "1d72eb09fc38a465";
	$belief[7] = "5793201f3aec86b4";
	$belief[8] = "c5ab36917024dfe8";
	$belief[9] = "395730c8f142abe6";
	$belief[10] = "d1e7209bcf38a645";
	$belief[11] = "903537cf2a481e6b";
	$belief[12] = "c5a3b97261d40f8e";
	$belief[13] = "1d2e7fb03c9a8465";
	$belief[14] = "a53c7b926d014e8f";
	$belief[15] = "10efd234cab56987";
	
	$hope = array();
	$hope[0] = "sjhwvbsb";
	$hope[1] = "hwcsghea";
	$hope[2] = "gncabknd";
	$hope[3] = "nitenksw";
	$hope[4] = "tegnlsdd";
	$hope[5] = "mdmehddp";
	$hope[6] = "leokfsdk";
	$hope[7] = "qawsxawy";
	$hope[8] = "unversty";
	$hope[9] = "bananaqq";
	$hope[10] = "internet";
	$hope[11] = "menglong";
	$hope[12] = "poiukjhg";
	$hope[13] = "qwsxashj";
	$hope[14] = "wordwide";
	$hope[15] = "tgbyhnrf";
	
	$webdate = array();
	$webdate[0] = "20120321232312aa";
	$webdate[1] = "20120223223312bb";
	$webdate[2] = "20120509223312aa";
	$webdate[3] = "20120302223312bb";
	$webdate[4] = "20110418223312aa";
	$webdate[5] = "20120426223312bb";
	$webdate[6] = "20120312223312aa";
	$webdate[7] = "20100723223312bb";
	$webdate[8] = "20091211223312aa";
	$webdate[9] = "20080625223312bb";
	$webdate[10] = "20120508223312aa";
	$webdate[11] = "20120104223312bb";
	$webdate[12] = "20111103223312aa";
	$webdate[13] = "20110209223312bb";
	$webdate[14] = "20120323223312aa";
	$webdate[15] = "20090217223312bb";
	
	//for($i = 0 ; $i < 16 ; $i++){
		//$desend.= $belief[$i].$webdate[$i].$hope[$i] % 16;
	//}	
	
	//USE PHP7.1
	$desend = "0f1e2d34cb5a697820120321232312aa057930f21a38ec6b420120223223312bb01d72eb0f39ac846520120509223312aa0c3ab69720514df8e20120302223312bb0a35c97b261d04fe820110418223312aa0905373c2f41a8e6b20120426223312bb01d72eb09fc38a46520120312223312aa05793201f3aec86b420100723223312bb0c5ab36917024dfe820091211223312aa0395730c8f142abe620080625223312bb0d1e7209bcf38a64520120508223312aa0903537cf2a481e6b20120104223312bb0c5a3b97261d40f8e20111103223312aa01d2e7fb03c9a846520110209223312bb0a53c7b926d014e8f20120323223312aa010efd234cab5698720090217223312bb0";
	
	$qus = base64_encode(_encutment($desend));
	$edf = base64_encode(_decutment($desend));

	$key2 = bin2hex($enckey);
	
		//USE PHP7.1
    	//$decrypttext = @mcrypt_decrypt(MCRYPT_RIJNDAEL_128, hextobin(_hash($qus).$key2), hextobin($param_input_string), MCRYPT_MODE_CBC,_unhash($edf));
		$decrypttext = openssl_decrypt(hextobin($param_input_string), 'AES-128-CBC', hextobin(_hash($qus).$key2), OPENSSL_ZERO_PADDING|OPENSSL_RAW_DATA, _unhash($edf));
		
	
	//USE PHP7.1
	//return 	_fromPkcs7($decrypttext);
	return 	pkcs5_unpad($decrypttext);
}

function _hash($qaz) {  
	return codefunction($qaz);        
} 
function _unhash($aws) {  
	return aesrtfunction($aws);
} 

//USE PHP7.1
function pkcs5_pad($text, $blocksize = 16) {
  $pad = $blocksize - (strlen($text) % $blocksize);
  return $text . str_repeat(chr($pad), $pad);
}

//USE PHP7.1
function pkcs5_unpad($text) {
  $pad = ord($text{strlen($text)-1});
  if ($pad > strlen($text)) {
    return $text;
  }
  if (!strspn($text, chr($pad), strlen($text) - $pad)) {
    return $text;
  }
  return substr($text, 0, -1 * $pad);
}

function _toPkcs7 ($value)      
    {                
    	if ( is_null ($value) )	$value = "" ;                
    	$padSize = 16 - (strlen ($value) % 16) ;                
    	return $value . str_repeat (chr($padSize), $padSize) ;        
    }                
    
function _fromPkcs7 ($value)        
    {   
    	$valueLen = strlen ($value) ;
		
    	if ( $valueLen % 16 > 0 )
    		$value = "";
				
    	$padSize = ord ($value{$valueLen - 1}) ;
    	if ( ($padSize < 1) or ($padSize > 16) )
    		$value = "";                // Check padding.                
    	for ($i = 0; $i < $padSize; $i++)                
    	{                        
    		if ( ord ($value{$valueLen - $i - 1}) != $padSize )
    			$value = "";                
    	}               
		return substr ($value, 0, $valueLen - $padSize) ;        
    }

function codefunction($aes) {              
	return base64_decode($aes);        
} 

function aesrtfunction($edc) {              
	return hextobin(base64_decode($edc));        
} 

/*private*/ function _requestImpayUnit($param_packet_body, $opcode, $cpid ) 
	{
		$impay_fd = fsockopen(SERVER_IP,
                          CARD_PORT);
        if (!$impay_fd)
        {
	   		$rtn = DAOU_SOCK_ERROR;
			error_log("socket cenenct fail.... ");
        }
		else
		{

			$daou_packet_lenght = strlen($param_packet_body) + 1 ;

			//요청헤더 생성
			$daou_packet_head = _makeHead($opcode,$cpid, REQUEST, $daou_packet_lenght );
			

			//요청헤더 + body + tail
			$daou_packet =  $daou_packet_head.$param_packet_body.chr(10);

			//daou_packet 보냄
			$rtn = fwrite($impay_fd, $daou_packet);
			error_log("packet send... ");

			if($rtn<=0){
				$rtn = DAOU_SOCK_ERROR;
				error_log("packet send fail... ");
			}
			
			//daou_packet 받음
			$cipher_read = fread($impay_fd, 1024);
			error_log("packet read... ");

			//ack헤더 생성	
			$daou_packet_ack =  _makeHead($opcode,$cpid, ACK, "1");

			//ack헤더 + tail
			$daou_packet_ack = $daou_packet_ack.chr(10);

			
			$rtn = fwrite($impay_fd, $daou_packet_ack);
			error_log("ACK packet send... ");

			if($rtn<=0){
				$rtn = DAOU_SOCK_ERROR;
				error_log("ACK packet send fail... ");
			}

			//소켓 닫기
			fclose($impay_fd);
			
	
			//head 와 tail 제거
			$rtn = substr($cipher_read,40,strlen($cipher_read)-41);
			
			
		}

		return $rtn;
	}

/*private*/ function _makeHead($opcode, $cpid, $opcodeState, $daou_packet_lenght ) {
		$daou_head = 
			str_pad($opcode.$opcodeState,10,STR_PAD_RIGHT).
			str_pad(VERSION,3,STR_PAD_RIGHT).
			str_pad($cpid,20," ",STR_PAD_RIGHT).
			str_pad($daou_packet_lenght,7,"0",STR_PAD_LEFT);
			
			return $daou_head;
}

function _MaxLength($strValue, $strValueLen){
		
		if(strlen($strValue) > $strValueLen){
			$strValue = substr($strValue,0,strValueLen);
		}
		return$strValue;
}

/* public */ function printline($paramString)
{
   printf("[DEBUG]%s\n", $paramString);
}
	
?>
