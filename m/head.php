<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

if($is_member && $member['sns_id'] && is_null_time($member['sns_ptime'])) {
	if(!preg_match("/register_form.php/", $_SERVER['PHP_SELF'])) {
		goto_url(TB_MBBS_URL.'/register_form.php?w=u');
	}
}

include_once(TB_MPATH."/head.sub.php");

include_once(TB_MTHEME_PATH.'/head.skin.php');
?>