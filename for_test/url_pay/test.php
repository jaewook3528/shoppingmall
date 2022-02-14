<meta charset="utf-8"><?php

include 'common.php';

// 아래의 작업 테스트용 파일임. 작업완료 후 삭제해야함.
// - SMS/이메일 결제와 URL결제의 링크 생성 및 결제 응답 API 작업

// 페이투페이 - 고객사 안내용 예제 코드

$now = explode("_", date("Ymd_His"));

$req_datas = array(
    'vendor_id' => 'iro36' // 계정 아이디
    , 'cross_key' => 'y0Q1dFWVbMV43cBU' // 계정 CrossKey
    , 'type' => 'M' // 타입
    , 'buyer_nm' => '테스트 '.$now[1] // 고객명
    , 'buyer_hp' => '01047490642' // 고객 휴대폰번호
    , 'buyer_email' => 'ufdevmk@gmail.com' // 이메일
    , 'product_nm' => '품명 '.implode("_", $now) // 품명
    , 'amt' => substr($now[1],-5) // 결제금액(원)
    , 'memo' => '비고'.implode("_", $now) // 비고
    , 'req_type' => 'all' // 요청방법
);

uf_socket_send('/_out/url_pay/create.php', $req_datas);

