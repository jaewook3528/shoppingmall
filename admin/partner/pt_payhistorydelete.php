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

    // 수수료 내역정보
    $sql = " select * from shop_partner_pay where pp_id = '{$_POST['pp_id'][$k]}' ";
    $row = sql_fetch($sql);

    if(!$row['pp_id'])
        continue;

    if($row['pp_pay'] < 0) {
        $mb_id = $row['mb_id'];
        $pp_pay = abs($row['pp_pay']);

		delete_use_pay($mb_id, $pp_pay);
    } else {
        if($row['pp_use_pay'] > 0) {
            insert_use_pay($row['mb_id'], $row['pp_use_pay'], $row['pp_id']);
        }
    }

    // 수수료 내역삭제
    $sql = " delete from shop_partner_pay where pp_id = '{$_POST['pp_id'][$k]}' ";
    sql_query($sql);

    // pp_balance에 반영
    $sql = " update shop_partner_pay
                set pp_balance = pp_balance - '{$row['pp_pay']}'
              where mb_id = '{$_POST['mb_id'][$k]}'
                and pp_id > '{$_POST['pp_id'][$k]}' ";
    sql_query($sql);

    // 수수료 UPDATE
    $sum_pay = get_pay_sum($_POST['mb_id'][$k]);
    $sql = " update shop_member set pay = '$sum_pay' where id = '{$_POST['mb_id'][$k]}' ";
    sql_query($sql);
}

goto_url(TB_ADMIN_URL."/partner.php?$q1&page=$page");
?>