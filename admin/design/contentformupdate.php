<?php
include_once("./_common.php");

check_demo();

check_admin_token();

unset($value);
$value['co_subject'] = $_POST['co_subject'];
$value['co_content'] = $_POST['co_content'];
$value['co_mobile_content'] = $_POST['co_mobile_content'];
$value['pt_id'] = $_POST['pt_id'];
$value['page_id'] = $_POST['page_id'];

if($w == "") {
	insert("shop_content", $value);
	$co_id = sql_insert_id();

    if($_POST['pt_id']=='default')
    {
        goto_url(TB_ADMIN_URL."/design.php?code=contentdefaultform&w=u&co_id=$co_id");
    }else{
	    goto_url(TB_ADMIN_URL."/design.php?code=contentform&w=u&co_id=$co_id");
    }

} else if($w == "u") {
	update("shop_content", $value," where co_id='$co_id'");

    if($_POST['pt_id']=='default')
    {
        goto_url(TB_ADMIN_URL."/design.php?code=contentdefaultform&w=u&co_id=$co_id$qstr&page=$page");
    }else{
        goto_url(TB_ADMIN_URL."/design.php?code=contentform&w=u&co_id=$co_id$qstr&page=$page");
    }
}
?>