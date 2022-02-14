<?php
include dirname(__FILE__)."/inc/Pay2Pay_Pay.php";

$pay2Pay_Pay = new Pay2Pay_Pay(array(
    "vendor_id" => '[계정 아이디]',
    "cross_key" => '[계정 CrossKey]',
));

$title = '페이투페이(Pay2Pay) - API 결제 예제 파일';
