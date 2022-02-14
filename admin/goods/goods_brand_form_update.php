<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$upl_dir = TB_DATA_PATH."/brand";
$upl = new upload_files($upl_dir);

unset($value);
if($_POST['br_logo_del']) {
	$upl->del($_POST['br_logo_del']);
	$value['br_logo'] = '';
}
if($_FILES['br_logo']['name']) {
	$value['br_logo'] = $upl->upload($_FILES['br_logo']);
}

$value['br_name'] = $_POST['br_name'];
$value['br_name_eng'] = $_POST['br_name_eng'];
$value['br_updatetime'] = TB_TIME_YMDHIS;

if($w == '') {
	$value['mb_id'] = 'admin';
	$value['br_time'] = TB_TIME_YMDHIS;
	insert("shop_brand", $value);

	goto_url(TB_ADMIN_URL."/goods.php?$q1&page=$page");

} else if($w == 'u') {	
	update("shop_brand", $value, "where br_id='$br_id'");

	// 상품 정보도 동시에 수정
	$sql = "update shop_goods set brand_nm = '{$_POST['br_name']}' where brand_uid = '$br_id'";
	sql_query($sql, false);

	goto_url(TB_ADMIN_URL."/goods.php?code=brand_form&w=u&br_id=$br_id$qstr&page=$page");
}
?>