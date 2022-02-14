<?php
include_once("./_common.php");

check_demo();

check_admin_token();

unset($value);
$value['de_kakaopay_mid']		= $_POST['de_kakaopay_mid']; // 카카오페이 상점MID  
$value['de_kakaopay_key']		= $_POST['de_kakaopay_key']; // 카카오페이 상점키
$value['de_kakaopay_enckey']	= $_POST['de_kakaopay_enckey']; // 카카오페이 EncKey
$value['de_kakaopay_hashkey']	= $_POST['de_kakaopay_hashkey']; // 카카오페이 HashKey  
$value['de_kakaopay_cancelpwd']	= $_POST['de_kakaopay_cancelpwd']; // 카카오페이 결제취소 비밀번호
update("shop_default", $value);

goto_url(TB_ADMIN_URL.'/config.php?code=kakaopay');
?>