<?php
include_once("./_common.php");

check_demo();

// 세션에 저장된 토큰과 폼값으로 넘어온 토큰을 비교.
if($_POST["token"] && get_session("ss_token") == $_POST["token"]) {
    // 맞으면 세션을 지워 다시 들어오도록 한다.
    set_session("ss_token", "");
} else {
    alert_close("정상적인 접근이 아닌것 같습니다.");
    exit;
}

$cp_id = trim($_POST['cp_id']);
$cp = sql_fetch("select * from shop_coupon where cp_id='$cp_id'");

insert_used_coupon($member['id'], $member['name'], $cp);

alert("쿠폰발행이 정상적으로 처리 되었습니다\\n\\n발급 된 쿠폰은 마이페이서 확인 가능합니다",  TB_SHOP_URL."/pop_coupon.php?gs_id=$gs_id&page=$page");
?>