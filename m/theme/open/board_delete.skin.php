<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<form name="frm" action="<?php echo TB_MBBS_URL; ?>/board_delete2.php" method="post" onsubmit="return frm_submit(this);">
<input type="hidden" name="w" value="d">
<input type="hidden" name="index_no" value="<?php echo $index_no;?>">
<input type="hidden" name="boardid" value="<?php echo $boardid;?>">
<input type="hidden" name="page" value="<?php echo $page;?>">

<div class="m_bo_pop">
	<h2>게시물삭제</h2>
	<p>한번 삭제된 데이타는 복구되지 않습니다.<br>삭제 하시려면 삭제하기 버튼을 누르세요.</p>
	<?php if($is_member) { ?>
	<p class="btn_area">
		<input type='submit' value='삭제하기' class="btn_medium">
		<a href="javascript:history.go(-1);" class="btn_medium bx-white">뒤로가기</a>
	</p>
	<?php } else { ?>
	<div class="formbox" style="margin:10px 0 0 0;">
		<input type="password" name="passwd" placeholder='비밀번호'>
	</div>
	<p class="btn_area" style="margin-top:8px;">
		<input type='submit' value='삭제하기' class="btn_medium">
		<a href="javascript:history.go(-1);" class="btn_medium bx-white">뒤로가기</a>
	</p>
	<?php } ?>
</div>
</form>

<script>
function frm_submit(f)
{
	<?php if(!$is_member) { ?>
	if(!f.passwd.value)
	{
		alert('비밀번호를 입력하세요.');
		f.passwd.focus();
		return false;
	}
	<?php } ?>

	if(answer==true)
	{	return true;  }
	else
	{	return false; }
}
</script>
