<?php
include_once("./_common.php");

if(TB_IS_MOBILE) {
	goto_url(TB_MSHOP_URL.'/plan.php');
}

$tb['title'] = '기획전';
include_once("./_head.php");
include_once(TB_THEME_PATH.'/plan.skin.php');
include_once("./_tail.php");
?>