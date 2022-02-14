<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$_POST = array_map('trim', $_POST);

$pf_stipulation = substr($_POST['pf_stipulation'],0,65536);
$pf_stipulation = preg_replace("#[\\\]+$#", "", $pf_stipulation);

$pf_regulation = substr($_POST['pf_regulation'],0,65536);
$pf_regulation = preg_replace("#[\\\]+$#", "", $pf_regulation);

$pf_basedomain = preg_replace("/\s+/", "", $_POST['pf_basedomain']);

unset($value);
$value['partner_reg_yes']		= $_POST['partner_reg_yes'];
$value['pf_payment_yes']		= $_POST['pf_payment_yes'];
$value['pf_sale_use']			= $_POST['pf_sale_use'];
$value['pf_sale_flag']			= $_POST['pf_sale_flag'];
$value['pf_anew_use']			= $_POST['pf_anew_use'];
$value['pf_visit_use']			= $_POST['pf_visit_use'];
$value['pf_payment_type']		= $_POST['pf_payment_type']; 
$value['pf_payment']			= conv_number($_POST['pf_payment']);
$value['pf_payment_unit']		= $_POST['pf_payment_unit'];
$value['pf_payment_tax']		= $_POST['pf_payment_tax'];
$value['pf_stipulation_subj']	= $_POST['pf_stipulation_subj'];
$value['pf_stipulation']		= $pf_stipulation;
$value['pf_regulation_subj']	= $_POST['pf_regulation_subj'];
$value['pf_regulation']			= $pf_regulation;
$value['pf_basedomain']			= $_POST['pf_basedomain'];
$value['pf_auth_good']			= $_POST['pf_auth_good'];
$value['pf_auth_cgy']			= $_POST['pf_auth_cgy'];
$value['pf_auth_pg']			= $_POST['pf_auth_pg'];
$value['pf_expire_use']			= $_POST['pf_expire_use'];
$value['pf_expire_term']		= $_POST['pf_expire_term'];
$value['pf_login_no']			= $_POST['pf_login_no']; 
$value['pf_account_no']			= $_POST['pf_account_no'];
$value['pf_session_no']			= $_POST['pf_session_no'];
update("shop_config", $value);

goto_url(TB_ADMIN_URL."/partner.php?code=pform");
?>