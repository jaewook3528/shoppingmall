<?php
include_once("./_common.php");

check_demo();

if(!$is_member) {
	alert("로그인 후 작성 가능합니다.");
}

if($_REQUEST["mode"] == 'w') {
	if($_POST["token"] && get_session("ss_token") == $_POST["token"]) {
		// 맞으면 세션을 지워 다시 입력폼을 통해서 들어오도록 한다.
		set_session("ss_token", "");
	} else {
		alert("잘못된 접근 입니다.");
		exit;
	}

	$index_no = trim(strip_tags($_POST['index_no']));
	$score = trim(strip_tags($_POST['score']));
	$seller_id = trim(strip_tags($_POST['seller_id']));

	if(substr_count($_POST['memo'], "&#") > 50) {
		alert("내용에 올바르지 않은 코드가 다수 포함되어 있습니다.");
	}

	$sql = "insert into shop_goods_review
			   set gs_id = '$index_no',
				   mb_id = '$member[id]',
				   memo = '$memo',
				   score = '$score',
				   reg_time = '".TB_TIME_YMDHIS."',
				   seller_id = '$seller_id',
				   pt_id = '$pt_id' ";
	sql_query($sql);

	//상품평 카운터하기
	sql_query("update shop_goods set m_count=m_count+1 where index_no='$index_no'");

	goto_url(TB_SHOP_URL."/view.php?index_no=$index_no#it_comment");
}
else if($_REQUEST["mode"] == 'd') // 상품평 삭제
{	
	if(is_admin())
		sql_query("delete from shop_goods_review where index_no='$it_mid'");
	else
		sql_query("delete from shop_goods_review where index_no='$it_mid' and mb_id='$member[id]'");

	// 상품평 삭제시 상품테이블에 상품평 카운터를 감소한다
	sql_query("update shop_goods set m_count=m_count-1 where index_no='$index_no'");

	goto_url(TB_SHOP_URL."/view.php?index_no=$index_no#it_comment");
}
?>