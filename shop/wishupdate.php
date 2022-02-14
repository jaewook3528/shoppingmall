<?php
include_once("./_common.php");

if(!$is_member) {
    alert('회원 전용 서비스 입니다.');
}

if($w == "d")
{
    $wi_id = trim($_GET['wi_id']);
    $sql = " delete from shop_wish where wi_id = '$wi_id' and mb_id = '{$member['id']}' ";
    sql_query($sql);
}
else
{
	$gs_id = $_POST['gs_id'][0];

    $sql = " select wi_id from shop_wish where mb_id = '{$member['id']}' and gs_id = '$gs_id' ";
    $row = sql_fetch($sql);
    if($row['wi_id']) { // 이미 있다면 삭제함
        $sql = " delete from shop_wish where wi_id = '{$row['wi_id']}' ";
        sql_query($sql);
    }

    $sql = " insert shop_wish
                set mb_id = '{$member['id']}',
                    gs_id = '$gs_id',
                    wi_time = '".TB_TIME_YMDHIS."',
                    wi_ip = '{$_SERVER['REMOTE_ADDR']}' ";
    sql_query($sql);
}

goto_url(TB_SHOP_URL."/wish.php");
?>