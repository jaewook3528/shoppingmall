<?php
include_once("./_common.php");

check_demo();

check_admin_token();

unset($value);
$value['cp_type']			= $_POST['cp_type'];	
$value['cp_dlimit']			= $_POST['cp_dlimit'];
$value['cp_dlevel']			= $_POST['cp_dlevel'];    
$value['cp_subject']		= $_POST['cp_subject'];
$value['cp_explan']			= $_POST['cp_explan'];
$value['cp_use']			= $_POST['cp_use'];
$value['cp_download']		= $_POST['cp_download'];
$value['cp_overlap']		= $_POST['cp_overlap'];
$value['cp_sale_type']		= $_POST['cp_sale_type'];
$value['cp_sale_percent']	= $_POST['cp_sale_percent'];
$value['cp_sale_amt_max']	= $_POST['cp_sale_amt_max'];
$value['cp_sale_amt']		= $_POST['cp_sale_amt'];
$value['cp_dups']			= $_POST['cp_dups'];
$value['cp_pub_sdate']		= $_POST['cp_pub_sdate'];
$value['cp_pub_edate']		= $_POST['cp_pub_edate'];
$value['cp_pub_sday']		= $_POST['cp_pub_sday'];
$value['cp_pub_eday']		= $_POST['cp_pub_eday'];
$value['cp_use_sex']		= $_POST['cp_use_sex'];
$value['cp_use_sage']		= $_POST['cp_use_sage'];
$value['cp_use_eage']		= $_POST['cp_use_eage'];
$value['cp_week_day']		= get_pagecode($_POST['cp_week_day']);
$value['cp_pub_1_use']		= $_POST['cp_pub_1_use'];
$value['cp_pub_shour1']		= $_POST['cp_pub_shour1'] ? sprintf('%02d',$_POST['cp_pub_shour1']):'';
$value['cp_pub_ehour1']		= $_POST['cp_pub_ehour1'] ? sprintf('%02d',$_POST['cp_pub_ehour1']):'';
$value['cp_pub_1_cnt']		= $_POST['cp_pub_1_cnt'];
$value['cp_pub_2_use']		= $_POST['cp_pub_2_use'];
$value['cp_pub_shour2']		= $_POST['cp_pub_shour2'] ? sprintf('%02d',$_POST['cp_pub_shour2']):'';
$value['cp_pub_ehour2']		= $_POST['cp_pub_ehour2'] ? sprintf('%02d',$_POST['cp_pub_ehour2']):'';
$value['cp_pub_2_cnt']		= $_POST['cp_pub_2_cnt'];
$value['cp_pub_3_use']		= $_POST['cp_pub_3_use'];
$value['cp_pub_shour3']		= $_POST['cp_pub_shour3'] ? sprintf('%02d',$_POST['cp_pub_shour3']):'';
$value['cp_pub_ehour3']		= $_POST['cp_pub_ehour3'] ? sprintf('%02d',$_POST['cp_pub_ehour3']):'';
$value['cp_pub_3_cnt']		= $_POST['cp_pub_3_cnt'];
$value['cp_inv_type']		= $_POST['cp_inv_type'];
$value['cp_inv_sdate']		= $_POST['cp_inv_sdate'];
$value['cp_inv_edate']		= $_POST['cp_inv_edate'];
$value['cp_inv_shour1']		= sprintf('%02d',$_POST['cp_inv_shour1']);
$value['cp_inv_shour2']		= sprintf('%02d',$_POST['cp_inv_shour2']);
$value['cp_inv_day']		= $_POST['cp_inv_day'];
$value['cp_low_amt']		= $_POST['cp_low_amt'];
$value['cp_use_part']		= $_POST['cp_use_part'];
$value['cp_use_goods']		= $_POST['cp_use_goods'];
$value['cp_use_category']	= $_POST['cp_use_category'];
$value['cp_udate'] = TB_TIME_YMDHIS;

if($w == "") {		
	$value['cp_wdate'] = TB_TIME_YMDHIS;
	insert("shop_coupon",$value);
	$cp_id = sql_insert_id();

	goto_url(TB_ADMIN_URL."/goods.php?code=coupon_form&w=u&cp_id=$cp_id");

} else if($w == "u") {
	update("shop_coupon",$value,"where cp_id='$cp_id'");	
	goto_url(TB_ADMIN_URL."/goods.php?code=coupon_form&w=u&cp_id=$cp_id$qstr&page=$page");
}
?>