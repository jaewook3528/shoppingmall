<?php
include_once("./_common.php");
include_once(TB_ADMIN_PATH."/admin_access.php");
include_once(TB_ADMIN_PATH."/admin_head.php");

$pg_title = ADMIN_MENU6;
$pg_num = 6;
$snb_icon = "<i class=\"ionicons ion-clipboard\"></i>";

if($member['id'] != 'admin' && !$member['auth_'.$pg_num]) {
	alert("접근권한이 없습니다.");
}

if($code == "list")				$pg_title2 = ADMIN_MENU6_01;
if($code == "1")				$pg_title2 = ADMIN_MENU6_02;
if($code == "2")				$pg_title2 = ADMIN_MENU6_03;
if($code == "3")				$pg_title2 = ADMIN_MENU6_04;
if($code == "4")				$pg_title2 = ADMIN_MENU6_05;
if($code == "5")				$pg_title2 = ADMIN_MENU6_06;
if($code == "delivery")			$pg_title2 = ADMIN_MENU6_07;
if($code == "delivery_update")	$pg_title2 = ADMIN_MENU6_07;
if($code == "6")				$pg_title2 = ADMIN_MENU6_08;
if($code == "9")				$pg_title2 = ADMIN_MENU6_09;
if($code == "7")				$pg_title2 = ADMIN_MENU6_10;
if($code == "8")				$pg_title2 = ADMIN_MENU6_11;
if($code == "memo")				$pg_title2 = ADMIN_MENU6_12;

include_once(TB_ADMIN_PATH."/admin_topmenu.php");
?>

<div class="s_wrap">
	<h1><?php echo $pg_title2; ?></h1>
	<?php
	include_once(TB_ADMIN_PATH."/order/order_{$code}.php");
	?>
</div>

<?php
include_once(TB_ADMIN_PATH."/admin_tail.php");
?>