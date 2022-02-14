<?php
include_once("./_common.php");

$where = " pt_id = '{$_GET['pt_id']}' AND page_id='{$_GET['page_id']}' ";
$co	= sql_fetch("select * from shop_content WHERE {$where} ");

if($_GET['pt_id'] && !$co['co_id'])
{
    $co	= sql_fetch("select * from shop_content WHERE pt_id='default' && page_id='{$_GET['page_id']}' ");
}

$tb['title'] = $co['co_subject'];
include_once("./_head.php");

if(!$co['co_mobile_content']) {
	$co['co_mobile_content'] = $co['co_content'];
}

include_once(TB_MTHEME_PATH.'/content.skin.php');

include_once("./_tail.php");
?>