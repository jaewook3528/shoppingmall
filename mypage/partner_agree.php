<?php
if(!defined('_TUBEWEB_')) exit;

$pg_title = "쇼핑몰 약관 설정";
include_once("./admin_head.sub.php");
?>

<form name="fregform" method="post" action="./partner_agree_update.php">
<input type="hidden" name="token" value="">

<h2>약관 설정</h2>
<div class="local_cmd01">
	<p>※ 아래 설정값이 없으면 본사 설정값으로 대체되어 노출됩니다.</p>
</div>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">회원가입약관<br>(회원가입 시)</th>
		<td><textarea name="shop_provision" class="frm_textbox wfull" rows="7"><?php echo preg_replace("/\\\/", "", $partner['shop_provision']); ?></textarea></td>
	</tr>
	<tr>
		<th scope="row">개인정보 수집 및 이용<br>(회원가입 시)</th>
		<td><textarea name="shop_private" class="frm_textbox wfull" rows="7"><?php echo preg_replace("/\\\/", "", $partner['shop_private']); ?></textarea></td>
	</tr>
	<tr>
		<th scope="row">개인정보처리방침</th>
		<td><textarea name="shop_policy" class="frm_textbox wfull" rows="7"><?php echo preg_replace("/\\\/", "", $partner['shop_policy']); ?></textarea></td>
	</tr>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
</div>
</form>

<?php
include_once("./admin_tail.sub.php");
?>