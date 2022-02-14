<?php
include_once("./_common.php");
include_once(TB_LIB_PATH."/mailer.lib.php");

check_demo();

check_admin_token();

if($w == "u") {
	$qa = sql_fetch("select * from shop_qa where index_no='$index_no'");	
	if(!$qa['result_yes']) { // 답변전
		if($qa['email_send_yes'] && $_POST['reply']) {			
			
			$subject = '[1:1문의 답변 알림 메일] '.$config['company_name'];
			mailer($config['company_name'], $super['email'], $qa['email'], $subject, $_POST['reply'], 1);
		}

		if($qa['sms_send_yes'] && $qa['cellphone']) {
			$content = '1:1문의에 답변이 등록되었습니다. '.$config['company_name'];
			icode_member_send($qa['cellphone'], $content);
		}
	}

	unset($value);
	$value['reply'] = $_POST['reply'];
	$value['replyer'] = $_POST['replyer'];
	$value['result_yes'] = 1;
	$value['result_date'] = TB_TIME_YMDHIS;
	update("shop_qa", $value, "where index_no='$index_no'");


    if($_SESSION['ss_mb_id']!='admin')
    {
        goto_url(TB_MYPAGE_URL."/page.php?code=partner_qa_form&w=u&index_no=$index_no$qstr&page=$page");
    }else{
        goto_url(TB_ADMIN_URL."/help.php?code=qa_form&w=u&index_no=$index_no$qstr&page=$page");
    }
}
?>