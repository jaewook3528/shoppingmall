<?php
if(!defined('_TUBEWEB_')) exit;

if($w == "") {
	$readonly = "";
} else if($w == "u") {
	$group = sql_fetch("select * from shop_board_group where gr_id = '$gr_id'");
    if(!$group['gr_id'])
        alert("존재하지 않은 게시판그룹 입니다.");

	$readonly = ' readonly style="background-color:#ddd"';
}
?>

<form name="fboardgroup" method="post" action="./config/board_group_form_update.php" autocomplete="off">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">그룹 ID</th>
		<td><input type="text" name="gr_id" value="<?php echo $group['gr_id']; ?>" required alphanumericunderline itemname="그룹 아이디" class="frm_input required"<?php echo $readonly; ?>> 영문자, 숫자, _ 만 가능 (공백없이)</td>
	</tr>
	<tr>
		<th scope="row">그룹 제목</th>
		<td><input type="text" name="gr_subject" value="<?php echo get_text($group['gr_subject']); ?>" required itemname="그룹 제목" class="frm_input required" size="50"></td>
	</tr>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
	<a href="./config.php?code=board_group_list&page=<?php echo $page; ?>" class="btn_large bx-white">목록</a>
</div>
</form>
