<?php
include_once("./_common.php");

check_demo();

check_admin_token();

unset($value);
$value['seller_reg_yes']		  = $_POST['seller_reg_yes'];
$value['seller_reg_auto']		  = $_POST['seller_reg_auto'];
$value['seller_mod_auto']		  = $_POST['seller_mod_auto'];
$value['seller_reg_agree']		  = $_POST['seller_reg_agree'];
$value['seller_reg_guide']		  = $_POST['seller_reg_guide'];	
$value['seller_reg_mobile_guide'] = $_POST['seller_reg_mobile_guide'];	
update("shop_config", $value);

goto_url(TB_ADMIN_URL.'/config.php?code=supply');
?>