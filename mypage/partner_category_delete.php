<?php
include_once("./_common.php");

check_demo();

$srcfile = TB_DATA_PATH.'/category/'.$member['id'];
$upload_file = new upload_files($srcfile); 

$ca_no = trim($_POST['ca_no']);

if(!$ca_no) {
	alert('올바른 방법으로 이용해 주십시오.');
}

$target_table = 'shop_cate_'.$member['id'];
$ca = sql_fetch("select * from {$target_table} where index_no='$ca_no'");
if($ca['p_oper'] == 'y') {
	alert('본사 카테고리는 삭제 하실 수 없습니다.');
}

$len = strlen($ca['catecode']);
if($len > 0) 
{
	$sql = "select * from {$target_table} where left(upcate,$len)='$ca[catecode]'"; 
	$rst = sql_query($sql);
	while($row = sql_fetch_array($rst)) {
		if($row['index_no']) { // 대상 하위 삭제
			if($row['img_name']) { $upload_file->del($row['img_name']); }
			if($row['img_name_over']) { $upload_file->del($row['img_name_over']); }
			if($row['img_head']) { $upload_file->del($row['img_head']); }
			sql_query("delete from {$target_table} where index_no='$row[index_no]'", FALSE);
		}
	}

	if($ca['img_name']) { $upload_file->del($ca['img_name']); }
	if($ca['img_name_over']) { $upload_file->del($ca['img_name_over']); }
	if($ca['img_head']) { $upload_file->del($ca['img_head']); }
	sql_query("delete from {$target_table} where index_no='$ca_no'", FALSE);  // 삭제대상
}

goto_url(TB_MYPAGE_URL."/page.php?$q1");
?>