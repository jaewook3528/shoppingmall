<?
include_once $_SERVER['DOCUMENT_ROOT']."/common.php";
require_once(TB_SHOP_PATH.'/pay2pay_pay/Pay2Pay_Pay.php');

$pay2Pay_Pay = new Pay2Pay_Pay($default);

$pay2Pay_Pay->sendVerifyCode($_POST);
