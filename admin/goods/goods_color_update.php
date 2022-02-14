<?php
include_once("./_common.php");

check_demo();

check_admin_token();

if($w == '') {
	$sql = " insert into shop_goods_color (gd_color, gd_b_use) values('{$gd_color}', '{$gd_b_use}') ";
	sql_query($sql);
} else if($w == 'd') {
	$sql = " delete from shop_goods_color where index_no='{$index_no}' ";
	sql_query($sql);
}

goto_url(TB_ADMIN_URL."/goods/goods_color.php")
?>