<?php

// 테스트 시만 아래의 코드주석 처리.
exit;

include $_SERVER['DOCUMENT_ROOT'].'/lib/common/UfSocket.php';

function uf_socket_send($path, $req_datas) {
    $host = "pay2pay.co.kr"; // 요청주소
    $headers = '';
    $send_data = http_build_query($req_datas); // 요청 데이터
    $url = "POST {$path} HTTP/1.0\r\n";
    $sock = fsockopen("ssl://".$host, 443, $errno, $errstr);
    if($sock) {
        fwrite($sock, $url);
        fwrite($sock, "Host: " . $host . ":" . 443 . "\r\n");
        fwrite($sock, "Content-type: application/x-www-form-urlencoded\r\n");
        fwrite($sock, "Content-length: " . strlen($send_data) . "\r\n");
        fwrite($sock, "Accept: */*\r\n");
        fwrite($sock, "\r\n");
        fwrite($sock, $send_data . "\r\n");
        fwrite($sock, "\r\n");

        // 응답 데이터
        $bodys = '';
        while(!feof($sock)){
            $data =fgets($sock,4096);
            if($data=="\r\n"){
                break;
            }

            $headers .= $data;
        }
        while(!feof($sock)){
            $bodys.=fgets($sock,4096);
        }
    }

    var_dump($sock, $headers, $bodys);
}
