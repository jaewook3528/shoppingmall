<?php
include_once('./_common.php');

if(empty($_POST))
    die('정보가 넘어오지 않았습니다.');

// 일정 기간이 경과된 임시 데이터 삭제
/*
$limit_time = date("Y-m-d H:i:s", (TB_SERVER_TIME - 86400 * 1));
$sql = " delete from shop_order_data where dt_type = '1' and dt_time < '$limit_time' ";
sql_query($sql);
*/

$od_id = get_session('ss_order_id');

$dt_data = base64_encode(serialize($_POST));

// 동일한 주문번호가 있는지 체크
$sql = " select count(*) as cnt from shop_order_data where od_id = '$od_id' ";
$row = sql_fetch($sql);
if($row['cnt'])
    sql_query(" delete from shop_order_data where od_id = '$od_id' ");

$default_pg = $default['de_pg_service'];

if( $od_settle_case == '삼성페이' ){    //현재 삼성페이인 경우에는 pg를 inicis로 처리 
    $default_pg = 'inicis';
}

$sql = " insert into shop_order_data
            set od_id	= '$od_id',
                mb_id   = '{$member['id']}',
                dt_pg   = '$default_pg',
                dt_data = '$dt_data',
                dt_time = '".TB_TIME_YMDHIS."' ";
sql_query($sql);

die('');
?>