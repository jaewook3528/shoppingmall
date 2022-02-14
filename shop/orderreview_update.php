<?php
include_once("./_common.php");

check_demo();

if(!$is_member) {
    alert("로그인 후 작성 가능합니다.");
}

if($_POST["token"] && get_session("ss_token") == $_POST["token"]) {
	// 맞으면 세션을 지워 다시 입력폼을 통해서 들어오도록 한다.
	set_session("ss_token", "");
} else {
	alert("잘못된 접근 입니다.");
	exit;
}

$gs_id = trim(strip_tags($_POST['gs_id']));
$seller_id = trim(strip_tags($_POST['seller_id']));
$score = trim(strip_tags($_POST['score']));

if(substr_count($_POST['memo'], "&#") > 50) {
    alert("내용에 올바르지 않은 코드가 다수 포함되어 있습니다.");
}

if(!get_magic_quotes_gpc()) {
	$memo = addslashes($_POST['memo']);
}

$sql = "insert into shop_goods_review 
		   set gs_id = '$gs_id', 
			   mb_id = '$member[id]',
			   memo = '$memo',
			   score = '$score',
			   reg_time = '".TB_TIME_YMDHIS."',
			   seller_id = '$seller_id',
			   pt_id = '$pt_id' ";
sql_query($sql);

sql_query("update shop_goods set m_count = m_count+1 where index_no='$gs_id'");

alert_close("정상적으로 등록 되었습니다.");
?>