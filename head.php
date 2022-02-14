<?php
if(!defined('_TUBEWEB_')) exit;

if($is_member && $member['sns_id'] && is_null_time($member['sns_ptime'])) {
	if(!preg_match("/register_mod.php/", $_SERVER['PHP_SELF'])) {
		goto_url(TB_BBS_URL.'/register_mod.php');
	}
}

include_once(TB_PATH.'/head.sub.php');

include_once(TB_THEME_PATH.'/head.skin.php');
?>