<?php
include_once("./_common.php");

include_once(TB_ADMIN_PATH."/admin_head.php");

$srcfile = TB_DATA_PATH.'/category/admin';
$upload_file = new upload_files($srcfile);
$ca_no = $_REQUEST['index_no'];

if(!is_dir($srcfile)) {
	@mkdir($srcfile, TB_DIR_PERMISSION);
	@chmod($srcfile, TB_DIR_PERMISSION);
}

if($_POST['mod_type'] == 'u') {
	check_demo();

	$ca = sql_fetch("select * from shop_cate where index_no='$ca_no'");

	$sql_commend = '';

	if($img_name_del) {
		$upload_file->del($img_name_del);
		$sql_commend .= " , img_name = '' ";
	}
	if($img_name_over_del) {
		$upload_file->del($img_name_over_del);
		$sql_commend .= " , img_name_over = '' ";
	}
	if($img_head_del) {
		$upload_file->del($img_head_del);
		$sql_commend .= " , img_head = '' ";
	}
	if($_FILES['img_name']['name']) {
		$upload_file->del($ca['img_name']);
		$img_name = $upload_file->upload($_FILES['img_name']);
		$sql_commend .= " , img_name = '$img_name' ";
	}
	if($_FILES['img_name_over']['name']) {
		$upload_file->del($ca['img_name_over']);
		$img_name_over = $upload_file->upload($_FILES['img_name_over']);
		$sql_commend .= " , img_name_over = '$img_name_over' ";
	}
	if($_FILES['img_head']['name']) {
		$upload_file->del($ca['img_head']);
		$img_head = $upload_file->upload($_FILES['img_head']);
		$sql_commend .= " , img_head = '$img_head' ";
	}

	$len = strlen($ca['catecode']);
	$sql_admin  = " SUBSTRING(catecode,1,$len) = '{$ca['catecode']}' ";
	$sql_member = " SUBSTRING(p_catecode,1,$len) = '{$ca['catecode']}' ";

	// 본사 카테고리 숨김
	$sql = "update shop_cate set u_hide='$u_hide' where {$sql_admin} ";
	sql_query($sql);

	$sql = "update shop_cate
			   set catename='".trim($catename)."',
				   img_head_url = '".trim($img_head_url)."'
			      {$sql_commend}
			 where index_no='$ca_no' ";
	sql_query($sql);


	$sql = "select * from shop_cate where index_no = '$ca_no'";
	$cp = sql_fetch($sql);

	$admin_file = array(
		$_FILES['img_name']['tmp_name'],
		$_FILES['img_name_over']['tmp_name'],
		$_FILES['img_head']['tmp_name']
	);

	// 가맹점 카테고리
	$sql = "select id from shop_member where grade between 2 and 6 ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {

		$mb_id = $row['id'];

		sql_member_category($mb_id);

		$dstfile = TB_DATA_PATH.'/category/'.$mb_id;
		$sql2_member = '';

		if($img_name_del) {
			@unlink($dstfile.'/'.$img_name_del);
			$sql2_member .= " , img_name = '' ";
		}
		if($img_name_over_del) {
			@unlink($dstfile.'/'.$img_name_over_del);
			$sql2_member .= " , img_name_over = '' ";
		}
		if($img_head_del) {
			@unlink($dstfile.'/'.$img_head_del);
			$sql2_member .= " , img_head = '' ";
		}
		if($admin_file[0]) {
			@unlink($dstfile.'/'.$ca['img_name']);
			@copy($srcfile.'/'.$cp['img_name'], $dstfile.'/'.$cp['img_name']);
			$sql2_member .= " , img_name = '{$cp['img_name']}' ";
		}
		if($admin_file[1]) {
			@unlink($dstfile.'/'.$ca['img_name_over']);
			@copy($srcfile.'/'.$cp['img_name_over'], $dstfile.'/'.$cp['img_name_over']);
			$sql2_member .= " , img_name_over = '{$cp['img_name_over']}' ";
		}
		if($admin_file[2]) {
			@unlink($dstfile.'/'.$ca['img_head']);
			@copy($srcfile.'/'.$cp['img_head'], $dstfile.'/'.$cp['img_head']);
			$sql2_member .= " , img_head = '{$cp['img_head']}' ";
		}

		$target_table = 'shop_cate_'.$mb_id;
		$sql = "update {$target_table} set p_hide = '$u_hide' where {$sql_member} and p_oper = 'y' ";
		sql_query($sql);

		$sql = "update {$target_table}
				   set catename = '$cp[catename]',
					   img_head_url = '$cp[img_head_url]'
				       {$sql2_member}
				 where p_catecode = '$cp[catecode]'
				   and p_oper = 'y' ";
		sql_query($sql, FALSE);
	}

	goto_url(TB_ADMIN_URL."/category/category_mod.php?index_no=$ca_no");
}

$ca = sql_fetch("select * from shop_cate where index_no='$ca_no'");
?>

<form name="fcgyform" method="post" action="./category_mod.php" enctype="MULTIPART/FORM-DATA">
<input type="hidden" name="mod_type" value="u">
<input type="hidden" name="index_no" value="<?php echo $ca_no; ?>">
<input type="hidden" name="upcate" value="<?php echo $ca['catecode']; ?>">

<div class="tbl_frm02 mart10">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">카테고리 소속</th>
		<td>
			<?php
			$str = '';
			$ca_len = strlen($ca['catecode']);
			for($i=1;$i<=($ca_len/3);$i++) {
				$tmp = substr($ca['catecode'],0,($i*3));
				$row = sql_fetch("select * from shop_cate where catecode='$tmp' ");
				$len = strlen($row['catecode']);
				if($len == 3) {
					$str .= $row['catename'];
				} else {
					$str .= " > ".$row['catename'];
				}
			}
			echo '<b>'.$str.'</b>';
			?>
		</td>
	</tr>
	<tr>
		<th scope="row">카테고리명</th>
		<td>
			<input type="text" name="catename" value="<?php echo $ca['catename']; ?>" required itemname="카테고리명" class="frm_input required" size="50">
			<input type="checkbox" name="u_hide" value="1" id="u_hide"<?php echo ($ca['u_hide'])?" checked='checked'":""; ?>> <label for="u_hide">카테고리 숨김</label>
		</td>
	</tr>
	<?php /* ?>
	<tr>
		<th scope="row">카테고리 아이콘</th>
		<td>
			<input type="file" name="img_name">
			<?php
			$mimg_str = "";
			$mimg = $srcfile.'/'.$ca['img_name'];
			if(is_file($mimg) && $ca['img_name']) {
				$size = @getimagesize($mimg);
				if($size[0] && $size[0] > 300)
					$width = 300;
				else
					$width = $size[0];

				$mimg = rpc($mimg, TB_PATH, TB_URL);

				echo '<input type="checkbox" name="img_name_del" value="'.$ca['img_name'].'" id="img_name_del"> <label for="img_name_del">삭제</label>';
				$mimg_str = '<img src="'.$mimg.'" width="'.$width.'">';
			}
			if($mimg_str) {
				echo '<div class="banner_or_img">'.$mimg_str.'</div>';
			}
			?>
		</td>
	</tr>
	<tr>
		<th scope="row">카테고리 아이콘 (ON)</th>
		<td>
			<input type="file" name="img_name_over">
			<?php
			$timg_str = "";
			$timg = $srcfile.'/'.$ca['img_name_over'];
			if(is_file($timg) && $ca['img_name_over']) {
				$size = @getimagesize($timg);
				if($size[0] && $size[0] > 300)
					$width = 300;
				else
					$width = $size[0];

				$timg = rpc($timg, TB_PATH, TB_URL);

				echo '<input type="checkbox" name="img_name_over_del" value="'.$ca['img_name_over'].'" id="img_name_over_del"> <label for="img_name_over_del">삭제</label>';
				$timg_str = '<img src="'.$timg.'" width="'.$width.'">';
			}
			if($timg_str) {
				echo '<div class="banner_or_img">'.$timg_str.'</div>';
			}
			?>
		</td>
	</tr>
	<?php */ ?>
	<?php if(strlen($ca['catecode']) == 3) { ?>
	<tr>
		<th scope="row">카테고리 상단배너</th>
		<td>
			<input type="file" name="img_head">
			<?php
			$himg_str = "";
			$himg = $srcfile.'/'.$ca['img_head'];
			if(is_file($himg) && $ca['img_head']) {
				$size = @getimagesize($himg);
				if($size[0] && $size[0] > 300)
					$width = 300;
				else
					$width = $size[0];

				$himg = rpc($himg, TB_PATH, TB_URL);

				echo '<input type="checkbox" name="img_head_del" value="'.$ca['img_head'].'" id="img_head_del"> <label for="img_head_del">삭제</label>';
				$himg_str = '<img src="'.$himg.'" width="'.$width.'">';
			}
			if($himg_str) {
				echo '<div class="banner_or_img">'.$himg_str.'</div>';
			}
			?>
		</td>
	</tr>
	<tr>
		<th scope="row">카테고리 상단배너 링크</th>
		<td><input type="text" name="img_head_url" value="<?php echo $ca['img_head_url']; ?>" class="frm_input" size="50"></td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="확인" class="btn_lsmall">
	<button type="button" onClick="cancel('<?php echo $ca_no; ?>')" class="btn_lsmall bx-white">닫기</button>
</div>
</form>

<script>
function cancel(no){
	parent.document.all['co'+no].style.display='none';
}
</script>

<?php
include_once(TB_ADMIN_PATH.'/admin_tail.sub.php');
?>