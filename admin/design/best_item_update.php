<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$arr_best = array();
for($i=0; $i<count($_POST['maintype_subj']); $i++) {
	if(!trim($_POST['maintype_subj'][$i])) 
		continue;

	$arr_best[$i]['subj'] = trim($_POST['maintype_subj'][$i]);
	$arr_best[$i]['code'] = preg_replace("/\s+/", "", $_POST['maintype_code'][$i]);
}

$de_maintype_best = base64_encode(serialize($arr_best));

unset($value);
$value['de_maintype_title'] = $_POST['de_maintype_title'];
$value['de_maintype_best'] = $de_maintype_best;
update('shop_default', $value);

goto_url(TB_ADMIN_URL.'/design.php?code=best_item');
?>