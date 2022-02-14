<?php
include_once("./_common.php");

if(!$is_member) {
    alert_close("로그인 후 작성 가능합니다.");
}

$tb['title'] = '구매후기 작성';
include_once(TB_PATH."/head.sub.php");

$gs = get_goods($gs_id);

$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$form_action_url = TB_HTTPS_SHOP_URL.'/orderreview_update.php';
include_once(TB_THEME_PATH.'/orderreview.skin.php');

include_once(TB_PATH."/tail.sub.php");
?>