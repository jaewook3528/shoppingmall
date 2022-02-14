<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form name="fboardform" id="fboardform" method="post" action="<?php echo $from_action_url; ?>" onsubmit="return fboardform_submit(this);" autocomplete="off" enctype="MULTIPART/FORM-DATA">
<input type="hidden" name="mode" value="w">
<input type="hidden" name="index_no" value="<?php echo $index_no; ?>">
<input type="hidden" name="boardid" value="<?php echo $boardid; ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
<input type="hidden" name="stx" value="<?php echo $stx; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="token" value="<?php echo $token; ?>">

<div class="tbl_frm01 tbl_wrap">
	<table>
	<colgroup>
		<col class="w100">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">이름</th>
		<td>
			<?php
			if($is_member) {
				echo $member['name'];
				echo "<input type=\"hidden\" name=\"writer_s\" value=\"$member[name]\">";
			} else {
				echo "<input type=\"text\" name=\"writer_s\" class=\"frm_input\">";
			}
			?>
		</td>
	</tr>
	<?php if(!$is_member) { ?>
	<tr>
		<th scope="row">비밀번호</td>
		<td><input name="passwd" type="password" class="frm_input"></td>
	</tr>
	<?php } ?>
	<?php if($board['use_category'] == '1') { ?>
	<tr>
		<th scope="row">분류</th>
		<td>
			<select name="ca_name">
			<option value="">선택하세요</option>
			<?php echo get_category_option($board[usecate])?>
			</select>
			<script>document.fboardform.ca_name.value='<?php echo $write[ca_name]; ?>';</script>
		</td>
	</tr>
	<?php } ?>
	<?php
	$option = "";
	$option_hidden = "";
	if(is_admin()) {
		unset($checked);
		if($write['btype']=='1') { $checked = 'checked'; }
		$option .= "<input type=\"checkbox\" name=\"btype\" value=\"1\" $checked> 공지사항&nbsp;&nbsp;";

		unset($checked);
		if($write['issecret']=='Y') { $checked = 'checked'; }
		$option .= "<input type=\"checkbox\" name=\"issecret\" value=\"Y\" $checked> 비밀글";
	} else {

		switch($board['use_secret']){
			case '0':
				$option_hidden .= "<input type=\"hidden\" value=\"N\" name=\"issecret\">";
				break;
			case '1':
				unset($checked);
				if($write['issecret']=='Y') { $checked = 'checked'; }
				$option .= "<input type=\"checkbox\" value=\"Y\" name=\"issecret\" $checked> 비밀글";
				break;
			case '2':
				$option_hidden .= "<input type=\"hidden\" value=\"Y\" name=\"issecret\">";
			break;
		}
	}

	echo $option_hidden;
	if($option) {
	?>
	<tr>
		<th scope="row">옵션</th>
		<td><?php echo $option; ?></td>
	</tr>
	<?php } ?>
	<tr>
		<th scope="row">제목</th>
		<td><input type="text" name="subject" value="<?php echo $write['subject']; ?>" class="frm_input wfull"></td>
	</tr>
	<tr>
		<th scope="row">내용</th>
		<td>
			<?php echo editor_html('memo', get_text($write['memo'], 0)); ?>
		</td>
	</tr>
	<?php if($board['usefile']=='Y') { ?>
	<tr>
		<th scope="row">첨부파일1</th>
		<td><input type="file" name="file1"></td>
	</tr>
	<tr>
		<th scope="row">첨부파일2</th>
		<td><input type="file" name="file2"></td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="글쓰기" class="btn_lsmall">
	<a href="javascript:history.go(-1);" class="btn_lsmall bx-white">취소</a>
</div>
</form>

<script>
function fboardform_submit(f)
{
	<?php if(!$is_member) { ?>
	if(!f.writer_s.value) {
		alert('작성자명을 입력하세요.');
		f.writer_s.focus();
		return false;
	}

	if(!f.passwd.value)	{
		alert('비밀번호를 입력하세요.');
		f.passwd.focus();
		return false;
	}
	<?php } ?>

	<?php if($board['use_category'] == '1') { ?>
	if(!f.ca_name.value) {
		alert('분류를 선택하세요.');
		f.ca_name.focus();
		return false;
	}
	<?php } ?>

	if(!f.subject.value)
	{
		alert('제목을 입력하세요.');
		f.subject.focus();
		return false;
	}

	<?php echo get_editor_js('memo'); ?>
	<?php echo chk_editor_js('memo'); ?>

    return true;
}
</script>
