<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form name="fboardform" action="read.php" method="get" onsubmit="return fboardform_submit(this);">
<input type="hidden" name="index_no" value="<?php echo $index_no; ?>">
<input type="hidden" name="boardid" value="<?php echo $boardid; ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
<input type="hidden" name="stx" value="<?php echo $stx; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<table class="wfull marb20">
<tr> 
	<td valign="top" align="center">
	<table width="680">
	<tr>
		<td height="300" align="center">
		<table width="400" height="110" class="bd">
		<tr>
			<td width="90" align="center"><img src="<?php echo $bo_img_url; ?>/img/icon3_3.gif"></td>
			<td align="center">
			<table width="300">
			<tr>
				<td class="tac padb10 fs11">이글은 비밀글 입니다.<br>글작성시 입력한 비밀번호를 입력하여 주십시오. </td>
			</tr>
			<tr>
				<td align="center">
				<table>
				<tr>
					<td>
						<input name="inpasswd" type="password" class="frm_input">
						<input type="image" src="<?php echo $bo_img_url; ?>/img/sd_bt_ok.gif">
					</td>
				</tr>
				</table>
				</td>
			</tr>
			</table>
			</td>
		</tr>
		</table>
		</td>
	</tr>
	</table>	
	</td>
</tr>
</table>
</form>

<script>
function fboardform_submit(f)
{
	if(!f.inpasswd.value) {
		alert("비밀번호를 입력하세요."); 
		f.inpasswd.focus();
		return false;
	}

	return true;
}
</script>