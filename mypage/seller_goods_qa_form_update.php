<?php
include_once("./_common.php");

check_demo();

check_admin_token();

if($w == "u") {
	unset($value);
	$value['iq_ty']			= $_POST['iq_ty'];
	$value['iq_secret']		= $_POST['iq_secret'];
	$value['iq_name']		= $_POST['iq_name'];
	$value['iq_email']		= $_POST['iq_email'];
	$value['iq_hp']			= $_POST['iq_hp'];
	$value['iq_subject']	= $_POST['iq_subject'];
	$value['iq_question']	= $_POST['iq_question'];
	$value['iq_answer']		= $_POST['iq_answer'];

	if(!$_POST['iq_answer'])
		$value['iq_reply'] = 0;
	else
		$value['iq_reply'] = 1;
	
	update("shop_goods_qa",$value,"where iq_id='$iq_id'");
	
	goto_url(TB_MYPAGE_URL."/page.php?code=seller_goods_qa_form&w=u&iq_id=$iq_id$qstr&page=$page");
}
?>