<?php
include_once("./_common.php");

if(!$is_member)
    alert_close("로그인 후 작성 가능합니다.");

$tb['title'] = "구매후기 작성";
include_once(TB_MPATH."/head.sub.php");

$gs = sql_fetch("select * from shop_goods where index_no='$gs_id'");

if($w == "u") {
	$me = sql_fetch("select * from shop_goods_review where index_no='$me_id'");
	$wr_score = $me['score'];
	$wr_content = nl2br($me['memo']);
}

$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$form_action_url = TB_HTTPS_MSHOP_URL.'/orderreview_update.php';
include_once(TB_MTHEME_PATH."/orderreview.skin.php");

include_once(TB_MPATH."/tail.sub.php");
?>