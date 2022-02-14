<?php
if(!defined('_TUBEWEB_')) exit;

$faq = sql_fetch("select * from shop_faq where index_no='$index_no'");
?>

<form name="faqform" method="post" onsubmit="return faqform_submit(this);">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="sca" value="<?php echo $sca; ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
<input type="hidden" name="stx" value="<?php echo $stx; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="index_no" value="<?php echo $index_no; ?>">

<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">분류</th>
		<td>
			<select name="faq_cate">
				<?php
				$sql = "select * from shop_faq_cate";
				$result = sql_query($sql);
				while($row = sql_fetch_array($result)){
					echo option_selected($row['index_no'], $faq['cate'], $row['catename']);
				}
				?>
			</select>
		</td>
	</tr>
	<tr>
		<th scope="row">제목</th>
		<td>
			<input type="text" name="subject" value="<?php echo $faq['subject']; ?>" required itemname="제목" class="frm_input required" size="60">
		</td>
	</tr>
	<tr>
		<th scope="row">내용</th>
		<td>
			<?php echo editor_html('memo', get_text($faq['memo'], 0)); ?>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
	<a href="./help.php?code=faq<?php echo $qstr; ?>&page=<?php echo $page; ?>" class="btn_large bx-white">목록</a>
</div>
</form>

<script>
function faqform_submit(f) {
	<?php echo get_editor_js('memo'); ?>

	f.action = "./help/help_faq_form_update.php";
    return true;
}
</script>
