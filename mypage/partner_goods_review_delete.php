<?php
include_once("./_common.php");

check_demo();

check_admin_token();

if(!$pf_auth_good)
	alert('개별 상품판매 권한이 있어야만 이용 가능합니다.');

$count = count($_POST['chk']);
if(!$count) {
	alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

for($i=0; $i<$count; $i++)
{
    // 실제 번호를 넘김
    $k = $_POST['chk'][$i];

	$index_no = trim($_POST['index_no'][$k]);

	sql_query("delete from shop_goods_review where index_no='$index_no'");
}

goto_url(TB_MYPAGE_URL."/page.php?$q1&page=$page");
?>