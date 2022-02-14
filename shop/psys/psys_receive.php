<?php

// PSYS 외부결제 응답 처리 - 연구비카드, 장기무이자.

header('Content-Type: text/html; charset=UTF-8');

include dirname(__FILE__) . "/PsysPay.php";

PsysPay::receive();
