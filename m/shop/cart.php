<?php
include_once("./_common.php");
include_once(TB_SHOP_PATH.'/settle_naverpay.inc.php');

$tb['title'] = '장바구니';
include_once("./_head.php");

$where_add = get_cart_mode('select','','where');

$sql = " select * 
               from shop_cart 
              where ct_select = '0' 
                    {$where_add}
              group by gs_id 
              order by index_no ";
$result = sql_query($sql);
$cart_count = sql_num_rows($result);

$cart_action_url = TB_MSHOP_URL.'/cartupdate.php';

include_once(TB_MTHEME_PATH.'/cart.skin.php');

include_once("./_tail.php");
?>