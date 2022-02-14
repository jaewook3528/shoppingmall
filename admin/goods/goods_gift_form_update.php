<?php
include_once("./_common.php");

check_demo();

check_admin_token();

if($w == "") {
	unset($value);
	$value['gr_id']		 = $_POST['gr_id'];
	$value['gr_subject'] = $_POST['gr_subject'];
	$value['gr_explan']	 = $_POST['gr_explan'];
	$value['gr_price']	 = $_POST['gr_price'];
	$value['gr_sdate']	 = $_POST['gr_sdate'];
	$value['gr_edate']	 = $_POST['gr_edate'];
	$value['use_gift']	 = $_POST['use_gift'];
	$value['gr_wdate']	 = TB_TIME_YMD;
	insert("shop_gift_group", $value);

	for($i=0; $i<(int)$_POST['gr_quant']; $i++)
	{
		$j = 0;
		do {
			$gi_num = get_coupon_id($_POST['use_gift']);

			$sql = " select count(*) as cnt from shop_gift where gi_num = '$gi_num' ";
			$row = sql_fetch($sql);

			if(!$row['cnt'])
				break;
			else {
				if($j > 20)
					die('Coupon ID Error');
			}
		} while(1);

		$sql = " insert into shop_gift
					set gr_id = '$gr_id'
					  , gr_subject = '$_POST[gr_subject]'
					  , gr_price = '$_POST[gr_price]'
					  , gr_sdate = '$_POST[gr_sdate]'
					  , gr_edate = '$_POST[gr_edate]'
					  , gi_num = '$gi_num' ";
		sql_query($sql);
	}

	goto_url(TB_ADMIN_URL."/goods.php?code=gift_form&w=u&gr_id=$gr_id");

} else if($w == "u") {
	unset($value);
	$value['gr_subject']	= $_POST['gr_subject'];
	$value['gr_explan']		= $_POST['gr_explan'];
	$value['gr_price']		= $_POST['gr_price'];
	$value['gr_sdate']		= $_POST['gr_sdate'];
	$value['gr_edate']		= $_POST['gr_edate'];
	update("shop_gift_group", $value, "where gr_id='$gr_id'");

	unset($value);
	$value['gr_subject']	= $_POST['gr_subject'];
	$value['gr_price']		= $_POST['gr_price'];
	$value['gr_sdate']		= $_POST['gr_sdate'];
	$value['gr_edate']		= $_POST['gr_edate'];
	update("shop_gift",$value,"where gr_id='$gr_id'");

	goto_url(TB_ADMIN_URL."/goods.php?code=gift_form&w=u&gr_id=$gr_id$q1&page=$page");
}
?>