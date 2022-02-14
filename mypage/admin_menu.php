<?php
if(!defined('_TUBEWEB_')) exit;

$admin_partner = false;
$admin_seller = false;

if(in_array($code, array(
	'seller_main',
	'seller_info',
	'seller_baesong',
	'seller_goods_list',
	'seller_goods_form',
	'seller_goods_stock',
	'seller_goods_optstock',
	'seller_goods_xls_reg',
	'seller_goods_xls_reg_update',
	'seller_goods_xls_mod',
	'seller_goods_xls_mod_update',
	'seller_goods_brand_list',
	'seller_goods_brand_form',
	'seller_goods_qa',
	'seller_goods_qa_form',
	'seller_goods_review',
	'seller_odr_2',
	'seller_odr_3',
	'seller_odr_4',
	'seller_odr_5',
	'seller_odr_6',
	'seller_odr_7',
	'seller_odr_8',
	'seller_odr_9',
	'seller_odr_list',
	'seller_odr_stats',
	'seller_odr_balance',
	'seller_odr_account'))) {
	$admin_snb_file = 'admin_snb1.php';
	$admin_seller = true;
} else if(in_array($boardid, array('20','21'))) {
	$admin_snb_file = 'admin_snb1.php';
	$admin_seller = true;
} else if(in_array($code, array(
	'partner_info',
	'partner_term',
	'partner_meta',
	'partner_sns',
	'partner_agree',
	'partner_baesong',
	'partner_keyword_list',
	'partner_payhistory',
	'partner_paylist',
	'partner_pg',
	'partner_kakaopay',
	'partner_naverpay',
	'partner_logo',
	'partner_banner_list',
	'partner_banner_form',
	'partner_mbanner_list',
	'partner_mbanner_form',
	'partner_best_item',
	'partner_popup_list',
	'partner_popup_form',
	'partner_member_list',
	'partner_register_form',
	'partner_tree',
	'partner_stats_day',
	'partner_stats_month',
	'partner_visit',
	'partner_goods_admlist',
	'partner_order_admlist',
	'partner_goods_type',
	'partner_category_list',
	'partner_category_view',
	'partner_goods_brand_list',
	'partner_goods_brand_form',
	'partner_goods_form',
	'partner_goods_list',
	'partner_goods_stock',
	'partner_goods_optstock',
	'partner_goods_review',
	'partner_goods_xls_reg',
	'partner_goods_xls_reg_update',
	'partner_goods_xls_mod',
	'partner_goods_xls_mod_update',
	'partner_goods_qa',
	'partner_goods_qa_form',
	'partner_odr_1',
	'partner_odr_2',
	'partner_odr_3',
	'partner_odr_4',
	'partner_odr_5',
	'partner_odr_6',
	'partner_odr_7',
	'partner_odr_8',
	'partner_odr_9',
	'partner_odr_list',
    'partner_about_company',
    'partner_faq_list',
    'partner_faq_form',
    'partner_qa_list',
    'partner_qa_form'))) {
	$admin_snb_file = 'admin_snb2.php';
	$admin_partner = true;
} else if(in_array($boardid, array('22','36'))) {
	$admin_snb_file = 'admin_snb2.php';
	$admin_partner = true;
}
?>