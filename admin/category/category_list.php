<?php
if(!defined('_TUBEWEB_')) exit;

if(isset($sel_ca1)) $qstr .= '&sel_ca1=' . $sel_ca1;
if(isset($sel_ca2)) $qstr .= '&sel_ca2=' . $sel_ca2;
if(isset($sel_ca3)) $qstr .= '&sel_ca3=' . $sel_ca3;
if(isset($sel_ca4)) $qstr .= '&sel_ca4=' . $sel_ca4;

$query_string = "code=$code$qstr";
$q1 = $query_string;

$target_table = 'shop_cate';
include_once(TB_LIB_PATH."/categoryinfo.lib.php");
?>

<h2>카테고리 등록</h2>
<form name="fcategoryform" method="post" action="./category/category_update.php" enctype="MULTIPART/FORM-DATA">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="token" value="">

<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">카테고리 소속</th>
		<td>
			<script>multiple_select2('sel_ca');</script>
		</td>
	</tr>
	<tr>
		<th scope="row">카테고리명</th>
		<td><input type="text" name="catename" required itemname="카테고리명" class="frm_input required" size="50"></td>
	</tr>
	<tr>
		<th scope="row">카테고리 상단배너</th>
		<td><input type="file" name="img_head"></td>
	</tr>
	<tr>
		<th scope="row">카테고리 상단배너 링크</th>
		<td>
			<input type="text" name="img_head_url" class="frm_input" size="50">
			<?php echo help('예시) /shop/view.php?index_no=1'); ?>
		</td>
	</tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" class="btn_large" value="저장">
</div>
</form>

<div class="sho_cate_bx mart30">
	<div class="local_frm02">
		<a href="<?php echo TB_ADMIN_URL; ?>/category/category_excel.php" class="btn_lsmall bx-white"><i class="fa fa-file-excel-o"></i> 카테고리 엑셀저장</a>
		<a href="<?php echo TB_ADMIN_URL; ?>/category/category_def.php?mod_type=R" class="btn_lsmall bx-red" onclick="return default_confirm(this, '한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 초기화하시겠습니까?');"><i class="fa fa-refresh fa-spin"></i> 본사와 동일하게 가맹점 설정값 초기화</a>
	</div>
	<ul>
	<?php
	$sql = "select * from shop_cate where length(catecode)='3' order by list_view asc";
	$res = sql_query($sql);
	while($row=sql_fetch_array($res)) {
		$count1 = sel_count("shop_cate", "where upcate='{$row['catecode']}'");
		$href1 = "?code=list&sel_ca1={$row['catecode']}";
		echo "<li>\n";
	?>
		<div>
			<img src="<?php echo TB_IMG_URL; ?>/icon/no_01_over.gif" class="vam" alt="1차">
			<b><?php echo $row['catecode']; ?></b>
			<a href="javascript:post_delete('<?php echo TB_ADMIN_URL; ?>/category/category_delete.php', '<?php echo $row['index_no']; ?>');" class="btn_ssmall red">삭제</a>
			<a href="javascript:modok('<?php echo $row['index_no']; ?>');" class="btn_ssmall">수정</a>
			<a href="<?php echo $href1; ?>"><b><?php echo $row['catename']; ?></b></a> <b class="fc_255">(<?php echo $count1; ?>)</b>
			<div id="co<?php echo $row['index_no']; ?>" style="display:none;"><iframe id="cos<?php echo $row['index_no']; ?>" frameborder="0" width="100%" height="350"></iframe></div>
		</div>
	<?php
	if($sel_ca1 && $sel_ca1==$row['catecode']) { // 2차
		echo "<dl class=\"cate2_bx\">\n";
		$sql2 = "select * from shop_cate where upcate='$sel_ca1' order by list_view asc ";
		$res2 = sql_query($sql2);
		while($row2=sql_fetch_array($res2)) {
			$count2 = sel_count("shop_cate", "where upcate='{$row2['catecode']}'");
			$href2 = "{$href1}&sel_ca2={$row2['catecode']}";
	?>
		<dt>
			<img src="<?php echo TB_IMG_URL; ?>/icon/no_02.gif" class="vam" alt="2차">
			<b><?php echo $row2['catecode']; ?></b>
			<a href="javascript:post_delete('<?php echo TB_ADMIN_URL; ?>/category/category_delete.php', '<?php echo $row2['index_no']; ?>');" class="btn_ssmall red">삭제</a>
			<a href="javascript:modok('<?php echo $row2['index_no']; ?>');" class="btn_ssmall">수정</a>
			<a href="<?php echo $href2; ?>"><b><?php echo $row2['catename']; ?></b></a> <b class="fc_255">(<?php echo $count2; ?>)</b>
			<div style="display:none;" id="co<?php echo $row2['index_no']; ?>"><iframe id="cos<?php echo $row2['index_no']; ?>" frameborder="0" width="100%" height="270"></iframe></div>
		</dt>
	<?php
	if($sel_ca2 && $sel_ca2==$row2['catecode']) { // 3차
		echo "<dd>\n<dl class=\"cate3_bx\">\n";
		$sql3 = "select * from shop_cate where upcate='$sel_ca2' order by list_view asc";
		$res3 = sql_query($sql3);
		while($row3=sql_fetch_array($res3)) {
			$count3 = sel_count("shop_cate", "where upcate='{$row3['catecode']}'");
			$href3 = "{$href2}&sel_ca3={$row3['catecode']}";
	?>
		<dd>
			<img src="<?php echo TB_IMG_URL; ?>/icon/no_03.gif" class="vam" alt="3차">
			<b><?php echo $row3['catecode']; ?></b>
			<a href="javascript:post_delete('<?php echo TB_ADMIN_URL; ?>/category/category_delete.php', '<?php echo $row3['index_no']; ?>');" class="btn_ssmall red">삭제</a>
			<a href="javascript:modok('<?php echo $row3['index_no']; ?>');" class="btn_ssmall">수정</a>
			<a href="<?php echo $href3; ?>"><b><?php echo $row3['catename']; ?></b></a> <b class="fc_255">(<?php echo $count3; ?>)</b>
			<div style="display:none;" id="co<?php echo $row3['index_no']; ?>"><iframe id="cos<?php echo $row3['index_no']; ?>" frameborder="0" width="100%" height="270"></iframe></div>
		</dd>
	<?php
	if($sel_ca3 && $sel_ca3==$row3['catecode']) { // 4차
		echo "<dd>\n<dl class=\"cate4_bx\">\n";
		$sql4 = "select * from shop_cate where upcate='$sel_ca3' order by list_view asc";
		$res4 = sql_query($sql4);
		while($row4=sql_fetch_array($res4)) {
			$count4 = sel_count("shop_cate", "where upcate='{$row4['catecode']}'");
			$href4 = "{$href3}&sel_ca4={$row4['catecode']}";
	?>
		<dd>
			<img src="<?php echo TB_IMG_URL; ?>/icon/no_04.gif" class="vam" alt="4차">
			<b><?php echo $row4['catecode']; ?></b>
			<a href="javascript:post_delete('<?php echo TB_ADMIN_URL; ?>/category/category_delete.php', '<?php echo $row4['index_no']; ?>');" class="btn_ssmall red">삭제</a>
			<a href="javascript:modok('<?php echo $row4['index_no']; ?>');" class="btn_ssmall">수정</a>
			<a href="<?php echo $href4; ?>"><b><?php echo $row4['catename']; ?></b></a> <b class="fc_255">(<?php echo $count4; ?>)</b>
			<div style="display:none;" id="co<?php echo $row4['index_no']; ?>"><iframe id="cos<?php echo $row4['index_no']; ?>" frameborder="0" width="100%" height="270"></iframe></div>
		</dd>
	<?php
	if($sel_ca4 && $sel_ca4==$row4['catecode']) { // 5차
		echo "<dd>\n<dl class=\"cate5_bx\">\n";
		$sql5 = "select * from shop_cate where upcate='$sel_ca4' order by list_view asc";
		$res5 = sql_query($sql5);
		while($row5=sql_fetch_array($res5)) {
	?>
		<dd>
			<img src="<?php echo TB_IMG_URL; ?>/icon/no_05.gif" class="vam" alt="5차">
			<b><?php echo $row5['catecode']; ?></b>
			<a href="javascript:post_delete('<?php echo TB_ADMIN_URL; ?>/category/category_delete.php', '<?php echo $row5['index_no']; ?>');" class="btn_ssmall red">삭제</a>
			<a href="javascript:modok('<?php echo $row5['index_no']; ?>');" class="btn_ssmall">수정</a>
			<b><?php echo $row5['catename']; ?></b>
			<div style="display:none;" id="co<?php echo $row5['index_no']; ?>"><iframe id="cos<?php echo $row5['index_no']; ?>" frameborder="0" width="100%" height="270"></iframe></div>
		</dd>
	<?php
									} //while 5
									echo "</dl>\n</dd>\n";
								} //if
							} //while 4
							echo "</dl>\n</dd>\n";
						} //if
					} //while 3
					echo "</dl>\n</dd>\n";
				} //if

			} //while 2
			echo "</dl>\n";
		} //if
		echo "</li>\n";
	} //while 1
	?>
	</ul>
</div>

<script>
$(function(){
	<?php if($sel_ca1) { ?>
	$("select#sel_ca1").val('<?php echo $sel_ca1; ?>');
	categorychange('<?php echo $sel_ca1; ?>', 'sel_ca2');
	<?php } ?>
	<?php if($sel_ca2) { ?>
	$("select#sel_ca2").val('<?php echo $sel_ca2; ?>');
	categorychange('<?php echo $sel_ca2; ?>', 'sel_ca3');
	<?php } ?>
	<?php if($sel_ca3) { ?>
	$("select#sel_ca3").val('<?php echo $sel_ca3; ?>');
	categorychange('<?php echo $sel_ca3; ?>', 'sel_ca4');
	<?php } ?>
	<?php if($sel_ca4) { ?>
	$("select#sel_ca4").val('<?php echo $sel_ca4; ?>');
	categorychange('<?php echo $sel_ca4; ?>', 'sel_ca5');
	<?php } ?>
});

// POST 방식으로 삭제
function post_delete(action_url, val)
{
	var f = document.fpost;

	if(confirm("한번 삭제한 자료는 복구할 방법이 없습니다.\n\n정말 삭제하시겠습니까?")) {
        f.ca_no.value = val;
		f.action = action_url;
		f.submit();
	}
}

function modok(no)
{
	document.all['cos'+no].src = "<?php echo TB_ADMIN_URL; ?>/category/category_mod.php?index_no="+no;
	document.all['co'+no].style.display = "";
}
</script>

<form name="fpost" method="post">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="ca_no" value="">
</form>
