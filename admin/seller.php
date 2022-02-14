<?php
include_once("./_common.php");
include_once(TB_ADMIN_PATH."/admin_access.php");
include_once(TB_ADMIN_PATH."/admin_head.php");

$pg_title = ADMIN_MENU3;
$pg_num = 3;
$snb_icon = "<i class=\"fa fa-truck\"></i>";

if($member['id'] != 'admin' && !$member['auth_'.$pg_num]) {
	alert("접근권한이 없습니다.");
}

if($code == "list")			$pg_title2 = ADMIN_MENU3_01;
if($code == "register")		$pg_title2 = ADMIN_MENU3_02;
if($code == "xls")			$pg_title2 = ADMIN_MENU3_03;
if($code == "xls_update")	$pg_title2 = ADMIN_MENU3_03;
if($code == "pay")			$pg_title2 = ADMIN_MENU3_04;
if($code == "pay_history")	$pg_title2 = ADMIN_MENU3_05;

include_once(TB_ADMIN_PATH."/admin_topmenu.php");
?>

<div class="s_wrap">
	<h1><?php echo $pg_title2; ?></h1>
	<?php
	include_once(TB_ADMIN_PATH."/seller/seller_{$code}.php");
	?>
</div>

<?php
include_once(TB_ADMIN_PATH."/admin_tail.php");
?>