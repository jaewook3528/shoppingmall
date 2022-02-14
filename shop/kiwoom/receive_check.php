<?php
// 키움페이 일반 결제 응답(통지) 체크 페이지

include_once('./_common.php');

//var_dump($_SESSION);
include_once(TB_PATH.'/plugin/kiwoompay/UfKiwoomPay.php');

$ufKiwoomPay = new UfKiwoomPay();

if( ! $_GET['ORDERNO'] ) {
    echo <<<END
    <script>
        var val = opener.uf_get_orderno();
        location.href = '?ORDERNO='+val;
    </script>
END;

} else {
    if( $row = $ufKiwoomPay->getReceive($_GET) ) {
        echo "<script> opener.uf_result_submit(); window.close(); </script>";
    } else {
        $key = 'times';
        $data = $_GET;
        if( $data[$key] > 5 ) {
            alert('결제가 실패하였습니다. 다시 시도해주십시오.', 'close');
        } else {
            $data[$key] += 1;
            $get_query = http_build_query($data);
            echo "<script> setTimeout(function() { location.href = '?{$get_query}'; }, 500); </script>";
        }
    }
}
