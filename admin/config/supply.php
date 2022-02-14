<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form name="fregform" method="post" onsubmit="return fregform_submit(this);">
<input type="hidden" name="token" value="">

<h2>기본설정</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">입점서비스 사용</th>
		<td class="td_label">
			<label><input type="radio" name="seller_reg_yes" value="1"<?php echo ($config['seller_reg_yes']==1)?' checked':''; ?>> 사용함</label>
			<label><input type="radio" name="seller_reg_yes" value="0"<?php echo ($config['seller_reg_yes']==0)?' checked':''; ?>> 사용안함</label>
		</td>
	</tr>
	<tr>
		<th scope="row">신규상품등록 진열</th>
		<td class="td_label">			
			<label><input type="radio" name="seller_reg_auto" value="1"<?php echo ($config['seller_reg_auto']==1)?' checked':''; ?>> 관리자 승인</label>
			<label><input type="radio" name="seller_reg_auto" value="0"<?php echo ($config['seller_reg_auto']==0)?' checked':''; ?>> 등록시 바로 승인</label>
		</td>
	</tr>
	<tr>
		<th scope="row">기존상품수정 진열</th>
		<td class="td_label">			
			<label><input type="radio" name="seller_mod_auto" value="1"<?php echo ($config['seller_mod_auto']==1)?' checked':''; ?>> 관리자 승인</label>
			<label><input type="radio" name="seller_mod_auto" value="0"<?php echo ($config['seller_mod_auto']==0)?' checked':''; ?>> 수정시 바로 승인</label>
		</td>
	</tr>	
	</tbody>
	</table>
</div>

<h2>가입설정</h2>
<div class="tbl_frm01">
	<table class="tablef">
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">입점 가입약관</th>
		<td><textarea name="seller_reg_agree" class="frm_textbox wfull" rows="7"><?php echo preg_replace("/\\\/", "", $config['seller_reg_agree']); ?></textarea></td>
	</tr>	
	<tr>
		<th scope="row">PC 입점 이용안내</th>
		<td>
			<?php echo editor_html('seller_reg_guide', get_text(stripslashes($config['seller_reg_guide']), 0)); ?>	
		</td>
	</tr>
	<tr>
		<th scope="row">모바일 입점 이용안내</th>
		<td>
			<?php echo editor_html('seller_reg_mobile_guide', get_text(stripslashes($config['seller_reg_mobile_guide']), 0)); ?>	
		</td>
	</tr>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
</div>
</form>

<script>
function fregform_submit(f) {
	<?php echo get_editor_js('seller_reg_guide'); ?>
	<?php echo get_editor_js('seller_reg_mobile_guide'); ?>

	f.action = "./config/supply_update.php";
    return true;
}
</script>
