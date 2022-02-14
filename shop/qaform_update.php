<?php
include_once("./_common.php");
include_once(TB_LIB_PATH."/mailer.lib.php");

check_demo();

if(!$is_member) {
	alert_close("로그인 후 작성 가능합니다.");
}

if($w == "" || $w == "u") {
	if($_POST["token"] && get_session("ss_token") == $_POST["token"]) {
		// 맞으면 세션을 지워 다시 입력폼을 통해서 들어오도록 한다.
		set_session("ss_token", "");
	} else {
		alert_close("잘못된 접근 입니다.");
		exit;
	}

	if(substr_count($_POST['iq_question'], "&#") > 50) {
		alert("내용에 올바르지 않은 코드가 다수 포함되어 있습니다.");
	}

    if(!$_POST['iq_subject']) 
		alert("제목을 입력하여 주십시오.");

    if(!$_POST['iq_question']) 
		alert("질문을 입력하여 주십시오.");
}

if($w == "")
{
    $sql = " insert into shop_goods_qa
                set gs_id		= '{$_POST['gs_id']}',					
					mb_id		= '{$_POST['mb_id']}',
					seller_id	= '{$_POST['seller_id']}',
					iq_ty		= '{$_POST['iq_ty']}',
					iq_secret	= '{$_POST['iq_secret']}',
					iq_name		= '{$_POST['iq_name']}',
                    iq_email	= '{$_POST['iq_email']}',
                    iq_hp		= '{$_POST['iq_hp']}',
                    iq_subject	= '{$_POST['iq_subject']}',
                    iq_question = '{$_POST['iq_question']}',
                    iq_time		= '".TB_TIME_YMDHIS."',
                    iq_ip		= '{$_SERVER['REMOTE_ADDR']}' ";
    sql_query($sql);

	if($_POST['gs_use_aff']) { // 가맹점상품인가?
		$mb = get_member($seller_id);
		$super_hp = $mb['cellphone'];
		$super['email'] = $mb['email'];
	}

	$wr_subject = get_text(stripslashes($iq_subject));
	$wr_content = conv_content(conv_unescape_nl(stripslashes($_POST['iq_question'])), 0);
	$wr_name = get_text($iq_name);	
	$subject = $iq_name.'님께서 상품문의에 새글을 작성하셨습니다.';

	if($iq_email) {
		ob_start();
		include_once(TB_BBS_PATH.'/write_update_mail.php');
		$content = ob_get_contents();
		ob_end_clean();

		mailer($iq_name, $iq_email, $super['email'], $subject, $content, 1);
	}
	
	icode_member_send($super_hp, $subject);

    alert("정상적으로 등록 되었습니다","thash","it_qa");
}
else if($w == "u")
{
    $sql = " update shop_goods_qa
                set iq_ty		= '{$_POST['iq_ty']}',
					iq_secret	= '{$_POST['iq_secret']}',
					iq_name		= '{$_POST['iq_name']}',
                    iq_email	= '{$_POST['iq_email']}',
                    iq_hp		= '{$_POST['iq_hp']}',
                    iq_subject	= '{$_POST['iq_subject']}',
                    iq_question = '{$_POST['iq_question']}'
			  where iq_id		= '$iq_id' ";
    sql_query($sql);

	alert("정상적으로 수정 되었습니다","thash","it_qa");
}
else if($w == "d")
{
	if(!is_admin())
    {
        $sql = " select iq_answer from shop_goods_qa where mb_id = '{$member['id']}' and iq_id = '$iq_id' ";
        $row = sql_fetch($sql);
        if(!$row)
            alert("자신의 상품문의만 삭제하실 수 있습니다.");

        if($row['iq_answer'])
            alert("답변이 있는 상품문의는 삭제하실 수 없습니다.");
    }

    $sql = " delete from shop_goods_qa where iq_id = '$iq_id' and md5(concat(iq_id,iq_time,iq_ip)) = '{$hash}' ";
    sql_query($sql);

	alert("정상적으로 삭제 되었습니다", TB_SHOP_URL."/view.php?index_no=$gs_id#it_qa");
}
?>