<?php
include_once("./_common.php");

check_demo();

check_admin_token();

if(!count($_POST['chk'])) {
    alert();
}

for($i=0; $i<count($_POST['chk']); $i++) 
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$sql = " update shop_member_grade 
			    set gb_name = '{$_POST['gb_name'][$k]}'
				  , gb_anew_price = '{$_POST['gb_anew_price'][$k]}'
				  , gb_term_price = '{$_POST['gb_term_price'][$k]}'
				  , gb_visit_pay = '{$_POST['gb_visit_pay'][$k]}'
				  , gb_promotion = '{$_POST['gb_promotion'][$k]}'
			  where gb_no = '{$_POST['gb_no'][$k]}' ";
	sql_query($sql);
}

$pf_sale_benefit_2 = is_array($pf_sale_benefit_2)?implode(chr(30), $pf_sale_benefit_2) : '';
$pf_sale_benefit_3 = is_array($pf_sale_benefit_3)?implode(chr(30), $pf_sale_benefit_3) : '';
$pf_sale_benefit_4 = is_array($pf_sale_benefit_4)?implode(chr(30), $pf_sale_benefit_4) : '';
$pf_sale_benefit_5 = is_array($pf_sale_benefit_5)?implode(chr(30), $pf_sale_benefit_5) : '';
$pf_sale_benefit_6 = is_array($pf_sale_benefit_6)?implode(chr(30), $pf_sale_benefit_6) : '';
$pf_anew_benefit_2 = is_array($pf_anew_benefit_2)?implode(chr(30), $pf_anew_benefit_2) : '';
$pf_anew_benefit_3 = is_array($pf_anew_benefit_3)?implode(chr(30), $pf_anew_benefit_3) : '';
$pf_anew_benefit_4 = is_array($pf_anew_benefit_4)?implode(chr(30), $pf_anew_benefit_4) : '';
$pf_anew_benefit_5 = is_array($pf_anew_benefit_5)?implode(chr(30), $pf_anew_benefit_5) : '';
$pf_anew_benefit_6 = is_array($pf_anew_benefit_6)?implode(chr(30), $pf_anew_benefit_6) : '';

$sql = " update shop_config
			set pf_sale_benefit_dan = '{$pf_sale_benefit_dan}'
			  , pf_sale_benefit_type = '{$pf_sale_benefit_type}'
			  , pf_sale_benefit_2 = '{$pf_sale_benefit_2}'
			  , pf_sale_benefit_3 = '{$pf_sale_benefit_3}'
			  , pf_sale_benefit_4 = '{$pf_sale_benefit_4}'
			  , pf_sale_benefit_5 = '{$pf_sale_benefit_5}'
			  , pf_sale_benefit_6 = '{$pf_sale_benefit_6}'
			  , pf_anew_benefit_dan = '{$pf_anew_benefit_dan}'
			  , pf_anew_benefit_type = '{$pf_anew_benefit_type}'
			  , pf_anew_benefit_2 = '{$pf_anew_benefit_2}'
			  , pf_anew_benefit_3 = '{$pf_anew_benefit_3}'
			  , pf_anew_benefit_4 = '{$pf_anew_benefit_4}'
			  , pf_anew_benefit_5 = '{$pf_anew_benefit_5}'
			  , pf_anew_benefit_6 = '{$pf_anew_benefit_6}' ";
sql_query($sql);

goto_url(TB_ADMIN_URL.'/partner.php?code=pbasic');
?>