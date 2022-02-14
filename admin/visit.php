<?php
include_once("./_common.php");
include_once(TB_ADMIN_PATH."/admin_access.php");
include_once(TB_ADMIN_PATH."/admin_head.php");

$pg_title = ADMIN_MENU7;
$pg_num = 7;
$snb_icon = "<i class=\"fa fa-bar-chart\"></i>";

if($member['id'] != 'admin' && !$member['auth_'.$pg_num]) {
	alert("접근권한이 없습니다.");
}

if($code == "hour")		$pg_title2 = ADMIN_MENU7_01;
if($code == "date")		$pg_title2 = ADMIN_MENU7_02;
if($code == "week")		$pg_title2 = ADMIN_MENU7_03;
if($code == "month")	$pg_title2 = ADMIN_MENU7_04;
if($code == "year")		$pg_title2 = ADMIN_MENU7_05;
if($code == "browser")	$pg_title2 = ADMIN_MENU7_06;
if($code == "os")		$pg_title2 = ADMIN_MENU7_07;
if($code == "domain")	$pg_title2 = ADMIN_MENU7_08;
if($code == "search")	$pg_title2 = ADMIN_MENU7_09;
if($code == "order1")	$pg_title2 = ADMIN_MENU7_10;
if($code == "order2")	$pg_title2 = ADMIN_MENU7_11;
if($code == "cancel")	$pg_title2 = ADMIN_MENU7_12;
if($code == "return")	$pg_title2 = ADMIN_MENU7_13;
if($code == "change")	$pg_title2 = ADMIN_MENU7_14;
if($code == "refund")	$pg_title2 = ADMIN_MENU7_15;

include_once(TB_ADMIN_PATH."/admin_topmenu.php");
?>

<div class="s_wrap">
	<h1><?php echo $pg_title2; ?></h1>
	<?php	
	include_once(TB_ADMIN_PATH."/visit/visit_{$code}.php");
	?>
</div>

<?php 
include_once(TB_ADMIN_PATH."/admin_tail.php");
?>