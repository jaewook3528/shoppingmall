<?php
include_once("./_common.php");
include_once(TB_ADMIN_PATH."/admin_access.php");
include_once(TB_ADMIN_PATH."/admin_head.php");

$pg_title = ADMIN_MENU5;
$pg_num = 5;
$snb_icon = "<i class=\"ionicons ion-bag\"></i>";

if($member['id'] != 'admin' && !$member['auth_'.$pg_num]) {
	alert("접근권한이 없습니다.");
}

if($code == "form")					 $pg_title2 = ($w=="u")?'상품 정보수정':'신규 상품등록';
if($code == "list")					 $pg_title2 = ADMIN_MENU5_01;
if($code == "type")					 $pg_title2 = ADMIN_MENU5_02;
if($code == "brand_list")			 $pg_title2 = ADMIN_MENU5_03;
if($code == "brand_form")			 $pg_title2 = ADMIN_MENU5_03;
if($code == "plan")					 $pg_title2 = ADMIN_MENU5_04;
if($code == "plan_form")			 $pg_title2 = ADMIN_MENU5_04;
if($code == "price")				 $pg_title2 = ADMIN_MENU5_05;
if($code == "stock")				 $pg_title2 = ADMIN_MENU5_06;
if($code == "optstock")				 $pg_title2 = ADMIN_MENU5_07;
if($code == "xls_reg")				 $pg_title2 = ADMIN_MENU5_08;
if($code == "xls_reg_update")		 $pg_title2 = ADMIN_MENU5_08;
if($code == "xls_option_reg")		 $pg_title2 = ADMIN_MENU5_09;
if($code == "xls_option_reg_update") $pg_title2 = ADMIN_MENU5_09;
if($code == "xls_mod")				 $pg_title2 = ADMIN_MENU5_10;
if($code == "xls_mod_update")		 $pg_title2 = ADMIN_MENU5_10;
if($code == "getprice")				 $pg_title2 = ADMIN_MENU5_11;
if($code == "getpoint")				 $pg_title2 = ADMIN_MENU5_12;
if($code == "getuse")				 $pg_title2 = ADMIN_MENU5_13;
if($code == "getmove")				 $pg_title2 = ADMIN_MENU5_14;
if($code == "getbrand")				 $pg_title2 = ADMIN_MENU5_15;
if($code == "getdelivery")			 $pg_title2 = ADMIN_MENU5_16;
if($code == "getbuylevel")			 $pg_title2 = ADMIN_MENU5_17;
if($code == "supply")				 $pg_title2 = ADMIN_MENU5_18;
if($code == "userlist")				 $pg_title2 = ADMIN_MENU5_19;
if($code == "qa")					 $pg_title2 = ADMIN_MENU5_20;
if($code == "qa_form")				 $pg_title2 = ADMIN_MENU5_20;
if($code == "review")				 $pg_title2 = ADMIN_MENU5_21;
if($code == "gift")					 $pg_title2 = ADMIN_MENU5_22;
if($code == "gift_form")			 $pg_title2 = ADMIN_MENU5_22;
if($code == "coupon")				 $pg_title2 = ADMIN_MENU5_23;
if($code == "coupon_form")			 $pg_title2 = ADMIN_MENU5_23;

include_once(TB_ADMIN_PATH."/admin_topmenu.php");
?>

<div class="s_wrap">
	<h1><?php echo $pg_title2; ?></h1>
	<?php
	include_once(TB_ADMIN_PATH."/goods/goods_{$code}.php");
	?>
</div>

<?php
include_once(TB_ADMIN_PATH."/admin_tail.php");
?>