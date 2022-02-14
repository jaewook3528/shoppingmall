<?php
include_once("./_common.php");
include_once(TB_ADMIN_PATH."/admin_access.php");
include_once(TB_ADMIN_PATH."/admin_head.php");

$pg_title = ADMIN_MENU9;
$pg_num = 9;
$snb_icon = "<i class=\"ionicons ion-compose\"></i>";

if($member['id'] != 'admin' && !$member['auth_'.$pg_num]) {
	alert("접근권한이 없습니다.");
}

if($code == "banner_list")		$pg_title2 = ADMIN_MENU9_01;
if($code == "banner_form")		$pg_title2 = ADMIN_MENU9_01;
if($code == "mbanner_list")		$pg_title2 = ADMIN_MENU9_02;
if($code == "mbanner_form")		$pg_title2 = ADMIN_MENU9_02;
if($code == "logo")				$pg_title2 = ADMIN_MENU9_03;
if($code == "contentlist")		$pg_title2 = ADMIN_MENU9_04;
if($code == "contentform")		$pg_title2 = ADMIN_MENU9_04;
if($code == "contentdefaultlist") $pg_title2 = ADMIN_MENU9_07;
if($code == "contentdefaultform") $pg_title2 = ADMIN_MENU9_07;
if($code == "best_item")		$pg_title2 = ADMIN_MENU9_05;
if($code == "popup_list")		$pg_title2 = ADMIN_MENU9_06;
if($code == "popup_form")		$pg_title2 = ADMIN_MENU9_06;

include_once(TB_ADMIN_PATH."/admin_topmenu.php");
?>

<div class="s_wrap">
	<h1><?php echo $pg_title2; ?></h1>
	<?php
	include_once(TB_ADMIN_PATH."/design/{$code}.php");
	?>
</div>

<?php
include_once(TB_ADMIN_PATH."/admin_tail.php");
?>