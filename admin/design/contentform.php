<?php
if(!defined('_TUBEWEB_')) exit;

if($w == "u") {
	$co	= sql_fetch("select * from shop_content where co_id='$co_id'");
    if(!$co['co_id'])
        alert('등록된 자료가 없습니다.');
}

$frm_submit = '<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
	<a href="./design.php?code=contentlist'.$qstr.'&page='.$page.'" class="btn_large bx-white">목록</a>'.PHP_EOL;
if($w == 'u') {
	$frm_submit .= '<a href="./design.php?code=contentform" class="btn_large bx-red">추가</a>'.PHP_EOL;
}
$frm_submit .= '</div>';
?>

<form name="frmcontentform" method="post" action="./design/contentformupdate.php" onsubmit="return frmcontentform_check(this);">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
<input type="hidden" name="stx" value="<?php echo $stx; ?>">
<input type="hidden" name="page"  value="<?php echo $page; ?>">
<input type="hidden" name="co_id" value="<?php echo $co_id; ?>">
<input type="hidden" name="pt_id" value="<?=$pt_id?>">

<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
    <tr>
        <th scope="row">페이지 이름(영어로)</th>
        <td><input type="text" name="page_id" value="<?php echo $co['page_id']; ?>" required itemname="페이지 이름" class="required frm_input" size="60"></td>
    </tr>
	<tr>
		<th scope="row">제목</th>
		<td><input type="text" name="co_subject" value="<?php echo $co['co_subject']; ?>" required itemname="제목" class="required frm_input" size="60"></td>
	</tr>
	<?php if($w == 'u') { ?>
	<tr>
		<th scope="row">페이지 URL</th>
		<td><input type="text" value="/bbs/content.php?page_id=<?=$co['page_id']?>&pt_id=<?=$co['pt_id']?>" readonly class="frm_input list2" size="60"> <a href="/bbs/content.php?page_id=<?=$co['page_id']?>&pt_id=<?=$co['pt_id']?>" target="_blank" class="btn_small grey">페이지 바로가기</a></td>
	</tr>
	<?php } ?>
	<tr>
		<th scope="row">PC 내용</th>
		<td>
			<?php echo editor_html('co_content', get_text($co['co_content'], 0)); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">모바일 내용</th>
		<td>
			<?php echo editor_html('co_mobile_content', get_text($co['co_mobile_content'], 0)); ?>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<?php echo $frm_submit; ?>
</form>

<script>
function frmcontentform_check(f) {
	<?php echo get_editor_js('co_content'); ?>
	<?php echo get_editor_js('co_mobile_content'); ?>

    return true;
}
</script>