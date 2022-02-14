<?php
include_once("./_common.php");

check_demo();

check_admin_token();

if(!$pf_auth_cgy) // 권한이 없을때
	alert('카테고리 개별등록은 하실 수 없습니다.');

$target_table = 'shop_cate_'.$member['id'];

$srcfile = TB_DATA_PATH.'/category/'.$member['id'];
$upload_file = new upload_files($srcfile); 

if(!is_dir($srcfile)) {
	@mkdir($srcfile, TB_DIR_PERMISSION);
	@chmod($srcfile, TB_DIR_PERMISSION);
}

if($sel_ca1) $upcate = $sel_ca1;
if($sel_ca2) $upcate = $sel_ca2;
if($sel_ca3) $upcate = $sel_ca3;
if($sel_ca4) $upcate = $sel_ca4;

unset($value);
if($_FILES['img_head']['name'])
	$value['img_head'] = $upload_file->upload($_FILES['img_head']);

$new_code = get_ca_depth($target_table, $upcate);
$new_next = get_next_wr_num($target_table, "list_view", " where upcate='$upcate' ");

$value['catecode']		= $new_code;
$value['upcate']		= $upcate;
$value['list_view']		= $new_next;
$value['catename']		= $_POST['catename'];
$value['img_head_url']  = $_POST['img_head_url'];
insert($target_table, $value);	

goto_url(TB_MYPAGE_URL."/page.php?$q1");
?>