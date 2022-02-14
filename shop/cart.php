<?php
include_once("./_common.php");
include_once(TB_SHOP_PATH.'/settle_naverpay.inc.php');

if(TB_IS_MOBILE) {
	goto_url(TB_MSHOP_URL.'/cart.php');
}

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

$cart_action_url = TB_SHOP_URL.'/cartupdate.php';

include_once(TB_THEME_PATH.'/cart.skin.php');

include_once("./_tail.php");
?>