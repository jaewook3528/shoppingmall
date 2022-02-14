<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$srcfile = TB_DATA_PATH.'/category/admin';
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

$new_code = get_ca_depth("shop_cate", $upcate);
$new_next = get_next_wr_num("shop_cate", "list_view", " where upcate='$upcate' ");

$value['catecode']		= $new_code;
$value['upcate']		= $upcate;
$value['list_view']		= $new_next;
$value['catename']		= $_POST['catename'];
$value['img_head_url']  = $_POST['img_head_url'];	
insert("shop_cate", $value);
$ca_id = sql_insert_id();

$sql = "select * from shop_cate where index_no = '$ca_id'";
$cp = sql_fetch($sql);

$admin_file = array($cp['img_head']);
$admin_file_count = count($admin_file);

// 가맹점모두 신규생성
$sql = "select id from shop_member where grade between 2 and 6 ";
$result = sql_query($sql);
for($i=0; $row=sql_fetch_array($result); $i++) {

	$mb_id = $row['id'];

	sql_member_category($mb_id);

	$target_table = 'shop_cate_'.$mb_id;
	$dstfile = TB_DATA_PATH.'/category/'.$mb_id;

	for($g=0; $g<$admin_file_count; $g++) {
		$file = $srcfile."/".$admin_file[$g];
		if(is_file($file) && $admin_file[$g]) {
			$dstfile = $dstfile.'/'.$admin_file[$g];
			@copy($file, $dstfile);
			@chmod($dstfile, TB_FILE_PERMISSION);
		}
	}

	$sql_common = "";
	$fields = sql_field_names($target_table);
	foreach($fields as $fld) {
		if (in_array($fld, array('index_no','catecode','upcate','p_catecode','p_upcate','list_view')))
			continue;

		$sql_common .= " , $fld = '".addslashes($cp[$fld])."' ";
	}

	if ($cp['upcate'] == "") {
		$new_code = get_ca_depth($target_table, '');
		$new_upcate = '';
	} else {
		$new_code = get_up_depth($target_table, $cp['upcate']);
		$new_upcate = substr($new_code, 0, -3);
	}

	$new_next = get_next_wr_num($target_table, "list_view", " where upcate='$new_upcate' ");

	$sql2 = " insert {$target_table}
				 set catecode	= '$new_code',
					 upcate		= '$new_upcate',
					 list_view  = '$new_next',
					 p_catecode	= '{$cp['catecode']}',
					 p_upcate	= '{$cp['upcate']}'
					 $sql_common ";
	sql_query($sql2, FALSE);
}

goto_url(TB_ADMIN_URL."/category.php?$q1");
?>