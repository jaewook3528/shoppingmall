<?php
include_once("./_common.php");

if(TB_IS_MOBILE) {
	goto_url(TB_MBBS_URL.'/leave_form.php');
}

if(!$is_member) {
	goto_url(TB_BBS_URL.'/login.php?url='.$urlencode);
}

$tb['title'] = '회원탈퇴';
include_once("./_head.php"); 

$form_action_url = TB_HTTPS_BBS_URL.'/leave_form_update.php';
include_once(TB_THEME_PATH.'/leave_form.skin.php');

include_once("./_tail.php");
?>