<?php
include_once("./_common.php");

check_demo();

check_admin_token();

unset($value);
$value['seller_code']		  = code_uniqid();
$value['mb_id']				  = $_POST['mb_id'];
$value['seller_item']		  = $_POST['seller_item'];
$value['company_name']		  = $_POST['company_name'];
$value['company_saupja_no']	  = $_POST['company_saupja_no'];
$value['company_item']		  = $_POST['company_item'];
$value['company_service']	  = $_POST['company_service'];
$value['company_owner']		  = $_POST['company_owner'];
$value['company_tel']		  = $_POST['company_tel'];
$value['company_fax']		  = $_POST['company_fax'];
$value['company_zip']		  = $_POST['company_zip'];
$value['company_addr1']		  = $_POST['company_addr1'];
$value['company_addr2']		  = $_POST['company_addr2'];
$value['company_addr3']		  = $_POST['company_addr3'];
$value['company_addr_jibeon'] = $_POST['company_addr_jibeon'];
$value['company_hompage']	  = $_POST['company_hompage'];
$value['info_name']			  = $_POST['info_name'];
$value['info_tel']			  = $_POST['info_tel'];
$value['info_email']		  = $_POST['info_email'];
$value['bank_name']			  = $_POST['bank_name'];
$value['bank_account']		  = $_POST['bank_account'];
$value['bank_holder']		  = $_POST['bank_holder'];
$value['memo']				  = $_POST['memo'];
$value['reg_time']			  = TB_TIME_YMDHIS;
$value['update_time']		  = TB_TIME_YMDHIS;
insert("shop_seller", $value);

goto_url(TB_ADMIN_URL.'/seller.php?code=register');
?>