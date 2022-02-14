<?php
include_once("./_common.php");

if(TB_IS_MOBILE) {
	goto_url(TB_MBBS_URL.'/faq.php');
}

$tb['title'] = '자주묻는 질문';
include_once("./_head.php"); 
include_once(TB_THEME_PATH.'/faq.skin.php');
include_once("./_tail.php");
?>