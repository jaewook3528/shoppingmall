<?php
define('_MINDEX_', true);
include_once("./_common.php");

// 인트로를 사용중인가? 
if($pt_id == 'admin'){

	if(!$is_member && $config['shop_intro_yes']) {
		include_once(TB_THEME_PATH.'/intro.skin.php');
		return;
	}
}else {
	if(!$is_member && $pt_shop_intro_yes) {
		include_once(TB_THEME_PATH.'/intro.skin.php');
		return;
	}

}

include_once(TB_MPATH."/_head.php"); // 상단
include_once(TB_MPATH."/popup.inc.php"); // 팝업
include_once(TB_MTHEME_PATH.'/main.skin.php'); // 팝업레이어
include_once(TB_MPATH."/_tail.php"); // 하단
?>