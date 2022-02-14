<?php
include_once("./_common.php");

$count = 0;
switch($W) {
	case "sel_ca1":
		$count = count($_POST['sel_ca1']);
		$code = $_POST['sel_ca1'];
		break;
	case "sel_ca2":
		$count = count($_POST['sel_ca2']);
		$code = $_POST['sel_ca2'];
		break;
	case "sel_ca3":
		$count = count($_POST['sel_ca3']);
		$code = $_POST['sel_ca3'];
		break;
	case "sel_ca4":
		$count = count($_POST['sel_ca4']);
		$code = $_POST['sel_ca4'];
		break;
	case "sel_ca5":
		$count = count($_POST['sel_ca5']);
		$code = $_POST['sel_ca5'];
		break;
}

if($count) {
	for($i=0;$i<=$count;$i++){
		$k = ($i+1);

		$sql = " update shop_cate set list_view = '$k' where catecode = '$code[$i]' ";
		sql_query($sql);
	}

	$msg = "정상적으로 처리 되었습니다";
} else {
	$msg = "처리할 항목이 없습니다";
}

echo "<meta charset='utf-8'>";
echo "<script>alert('".$msg."');</script>";
?> 