<?php
include_once("./_common.php");

check_demo();

check_admin_token();

unset($value);
$value['cate']	  = $_POST['faq_cate'];
$value['subject'] = $_POST['subject'];
$value['memo']	  = $_POST['memo'];
$value['pt_id']	  = $_POST['pt_id'];

if($w == "") {
	$value['wdate'] = TB_TIME_YMDHIS;
	insert("shop_faq",$value);
	$index_no = sql_insert_id();

    if($_SESSION['ss_mb_id']!='admin')
    {
        goto_url(TB_MYPAGE_URL."/page.php?code=partner_faq_form&w=u&index_no=$index_no");
    }else{
	    goto_url(TB_ADMIN_URL."/help.php?code=faq_from&w=u&index_no=$index_no");
    }

} else if($w == "u") {
	update("shop_faq",$value," where index_no='$index_no'");

    if($_SESSION['ss_mb_id']!='admin')
    {
        goto_url(TB_MYPAGE_URL."/page.php?code=partner_faq_form&w=u&index_no=$index_no$qstr&page=$page");
    }else{
        goto_url(TB_ADMIN_URL."/help.php?code=faq_from&w=u&index_no=$index_no$qstr&page=$page");
    }

}
?>