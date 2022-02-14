<?php
include_once("./_common.php");
include_once(TB_ADMIN_PATH."/admin_access.php");
include_once(TB_ADMIN_PATH."/admin_head.php");

$pg_title = ADMIN_MENU4;
$pg_num = 4;
$snb_icon = "<i class=\"fa fa-sitemap\"></i>";

if($member['id'] != 'admin' && !$member['auth_'.$pg_num]) {
	alert("접근권한이 없습니다.");
}

if($code == "list") $pg_title2 = ADMIN_MENU4_01;
if($code == "view") $pg_title2 = ADMIN_MENU4_02;

include_once(TB_ADMIN_PATH."/admin_topmenu.php");
?>

<div class="s_wrap">
	<h1><?php echo $pg_title2; ?></h1>
	<?php
	include_once(TB_ADMIN_PATH."/category/category_{$code}.php");
	?>
</div>

<?php
include_once(TB_ADMIN_PATH."/admin_tail.php");
?>