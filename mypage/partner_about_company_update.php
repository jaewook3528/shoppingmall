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

if($_POST['w'] == "") {
    insert("shop_content", $value);
    sql_insert_id();
} else if($_POST['w'] == "u") {
    update("shop_content", $value," where co_id='{$_POST['co_id']}'");
}
goto_url(TB_MYPAGE_URL."/page.php?code=partner_about_company");
?>