<?php
// 키움페이 일반 결제 응답(통지) 페이지

include_once('./_common.php');

include_once(TB_PATH.'/plugin/kiwoompay/UfKiwoomPay.php');

$ufKiwoomPay = new UfKiwoomPay();

if( $ufKiwoomPay->checkReceive() ) {

    // 결제 성공시에만 호출되기에 성공값 셋팅
    $_GET['RESULTCODE'] = '0000';
    
    $ufKiwoomPay->procReceive($_GET);

    echo "<html><body><RESULT>SUCCESS</RESULT></body></html>";
}
