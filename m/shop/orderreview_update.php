<?php
include_once("./_common.php");

check_demo();

if(!$is_member) {
	alert("로그인 후 작성 가능합니다.");
}

if($w == "" || $w == "u") {
	if($_POST["token"] && get_session("ss_token") == $_POST["token"]) {
		// 맞으면 세션을 지워 다시 입력폼을 통해서 들어오도록 한다.
		set_session("ss_token", "");
	} else {
		alert("잘못된 접근 입니다.");
		exit;
	}

	$gs_id = trim(strip_tags($_POST['gs_id']));
	$me_id = trim(strip_tags($_POST['me_id']));
	$wr_score = trim(strip_tags($_POST['wr_score']));
	$seller_id = trim(strip_tags($_POST['seller_id']));

	if(substr_count($_POST['wr_content'], "&#") > 50) {
		alert("내용에 올바르지 않은 코드가 다수 포함되어 있습니다.");
	}

	if(!get_magic_quotes_gpc()) {
		$wr_content = addslashes($_POST['wr_content']);
	}
}

if($w == "")
{
	$sql = "insert into shop_goods_review
			   set gs_id	 = '$gs_id',
				   mb_id	 = '$member[id]',
				   memo		 = '$wr_content',
				   score	 = '$wr_score',
				   reg_time	 = '".TB_TIME_YMDHIS."',
				   seller_id = '$seller_id',
				   pt_id	 = '$pt_id' ";
	sql_query($sql);

	sql_query("update shop_goods set m_count = m_count + 1 where index_no='$gs_id'");

	alert("정상적으로 등록 되었습니다.","replace");
}
else if($w == "u")
{
    $sql = " update shop_goods_review
                set memo	= '$wr_content',
					score	= '$wr_score'
			  where index_no = '$me_id' ";
    sql_query($sql);

	alert("정상적으로 수정 되었습니다.","replace");
}
else if($w == "d")
{
	if(!is_admin())
    {
        $sql = " select * from shop_goods_review where mb_id = '{$member['id']}' and index_no = '$me_id' ";
        $row = sql_fetch($sql);
        if(!$row)
            alert("자신의 글만 삭제하실 수 있습니다.");
    }

	// 구매후기 삭제
    $sql = "delete from shop_goods_review
			 where index_no='$me_id'
			    and md5(concat(index_no,reg_time,mb_id)) = '{$hash}' ";
	sql_query($sql);

	// 구매후기 삭제시 상품테이블에 상품평 카운터를 감소한다
	sql_query("update shop_goods set m_count=m_count - 1 where index_no='$gs_id'");

	if($p == "1")
		goto_url(TB_MSHOP_URL."/view_user.php?gs_id=$gs_id");
	else
		goto_url(TB_MSHOP_URL."/view.php?gs_id=$gs_id");
}
?>