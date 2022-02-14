<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$count = count($_POST['chk']);
if(!$count) {
	alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
}

for($i=0; $i<$count; $i++)
{
    // 실제 번호를 넘김
    $k = $_POST['chk'][$i];

    // 포인트 내역정보
    $sql = " select * from shop_point where po_id = '{$_POST['po_id'][$k]}' ";
    $row = sql_fetch($sql);

    if(!$row['po_id'])
        continue;

    if($row['po_point'] < 0) {
        $mb_id = $row['mb_id'];
        $po_point = abs($row['po_point']);

		delete_use_point($mb_id, $po_point);
    } else {
        if($row['po_use_point'] > 0) {
            insert_use_point($row['mb_id'], $row['po_use_point'], $row['po_id']);
        }
    }

    // 포인트 내역삭제
    $sql = " delete from shop_point where po_id = '{$_POST['po_id'][$k]}' ";
    sql_query($sql);

    // po_mb_point에 반영
    $sql = " update shop_point
                set po_mb_point = po_mb_point - '{$row['po_point']}'
              where mb_id = '{$_POST['mb_id'][$k]}'
                and po_id > '{$_POST['po_id'][$k]}' ";
    sql_query($sql);

    // 포인트 UPDATE
    $sum_point = get_point_sum($_POST['mb_id'][$k]);
    $sql = " update shop_member set point = '$sum_point' where id = '{$_POST['mb_id'][$k]}' ";
    sql_query($sql);
}

goto_url(TB_ADMIN_URL."/member.php?$q1&page=$page");
?>