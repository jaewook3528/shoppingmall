<?
include dirname(__FILE__)."/../inc/head.php"; 

$result = $pay2Pay_Pay->certProc($_POST);

Pay2Pay_Pay::printData($result);

include dirname(__FILE__)."/../inc/foot.php";

