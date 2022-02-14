<?php
if(!defined('_TUBEWEB_')) exit;
?>

<script src="<?php echo TB_MJS_URL; ?>/jquery.partner_form.js"></script>

<form name="fpartnerform" id="fpartnerform" action="<?php echo $from_action_url; ?>" onsubmit="return fpartnerform_submit(this);" method="post" autocomplete="off">
<input type="hidden" name="signatureJSON" value="mobile" id="signatureJSON">
<input type="hidden" name="token" value="<?php echo $token; ?>">

<div class="fpartnerform_term">
	<h2><?php echo $config['pf_stipulation_subj']; ?></h2>
	<textarea readonly><?php echo $config['pf_stipulation']; ?></textarea>
	<fieldset class="fpartnerform_agree">
		<input type="checkbox" name="agree1" value="1" id="agree11" class="css-checkbox lrg">
		<label for="agree11" class="css-label">위 내용을 읽었으며 이용약관에 동의합니다.</label>
	</fieldset>
	<?php if($config['pf_regulation_subj']) { ?>
	<h2 class="mart10"><?php echo $config['pf_regulation_subj']; ?></h2>
	<textarea readonly><?php echo $config['pf_regulation']; ?></textarea>
	<fieldset class="fpartnerform_agree">
		<input type="checkbox" name="agree2" value="1" id="agree21" class="css-checkbox lrg">
		<label for="agree21" class="css-label">위 내용을 읽었으며 규정안내에 동의합니다.</label>
	</fieldset>
	<?php } ?>
</div>

<div class="fp_sign">
	<dl class="info_bx">
		<dt>사용자 “갑”</dt>
		<dd>상호 : <?php echo $config['company_name']; ?></dd>
		<dd>대표번호 : <?php echo $config['company_tel']; ?></dd>
		<dd>사업자등록번호 : <?php echo $config['company_saupja_no']; ?></dd>
		<dd>주소 : <?php echo $config['company_addr']; ?></dd>
		<dd class="mart25">대표자 : <?php echo $config['company_owner']; ?> (인)
			<?php
			$file = TB_DATA_PATH.'/common/'.$config['admin_seal'];
			if(is_file($file) && $config['admin_seal']) {
				$seal = rpc($file, TB_PATH, TB_URL);
				echo '<span class="admin_seal"><img src="'.$seal.'"></span>';
			}
			?>
		</dd>
	</dl>
	<dl class="info_bx" style="height:auto;">
		<dt>위촉자 “을”</dt>
		<dd>성명 : <strong><?php echo get_text($member['name']); ?></strong></dd>
		<dd>연락처 : <?php echo replace_tel($member['cellphone']); ?></dd>
		<dd>이메일 : <?php echo get_text($member['email']); ?></dd>
	</dl>
</div>

<section class="mart30">
	<h3 class="anc_tit">입금받으실 계좌</h3>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w80">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row"><label for="bank_name">은행명</label></th>
			<td><input type="text" name="bank_name" id="bank_name" class="frm_input" size="20"></td>
		</tr>
		<tr>
			<th scope="row"><label for="bank_account">계좌번호</label></th>
			<td><input type="text" name="bank_account" id="bank_account" class="frm_input" size="30"></td>
		</tr>
		<tr>
			<th scope="row"><label for="bank_holder">예금주명</label></th>
			<td><input type="text" name="bank_holder" id="bank_holder" class="frm_input" size="20"></td>
		</tr>
		</tbody>
		</table>
	</div>
	<div class="fc_red">입금받으실 계좌정보를 정확히 입력해주세요.<br>이후 마이페이지에서도 입력 가능합니다.</div>
</section>

<section class="mart30">
	<h3 class="anc_tit">결제정보</h3>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w80">
			<col>
		</colgroup>
		<tr>
			<th scope="row">서비스선택</th>
			<td class="td_label">
				<?php
				$multi_settle = 0;
				$sql = " select *
						   from shop_member_grade
						  where gb_no between 2 and 6
							and gb_name <> ''
						  order by gb_no desc ";
				$res = sql_query($sql);
				for($i=0; $row=sql_fetch_array($res); $i++) {
					$checked = '';
					if($i==0) $checked = ' checked="checked"';

					$reg = $row['gb_no'].'^'.$row['gb_anew_price'];
					echo '<label><input type="radio" name="reg_level" value="'.$reg.'"'.$checked.'> '.get_text($row['gb_name']).'</label>'.PHP_EOL;

					$multi_settle++;
				}
				if(!$multi_settle)
					echo '설정값이 없습니다. 운영자에게 알려주시면 감사하겠습니다.';
				?>
			</td>
		</tr>
		<?php if($multi_settle) { ?>
		<tr>
			<th scope="row">결제방법</th>
			<td>
				<input type="hidden" name="anew_grade" value="">
				<input type="hidden" name="receipt_price" value="0">
				<input type="radio" name="pay_settle_case" value="1" id="pay_settle_case" checked="checked">
				<label for="pay_settle_case">무통장입금</label>
			</td>
		</tr>
		<tr>
			<th scope="row">결제금액</th>
			<td><span id="reg_tot_price"></span></td>
		</tr>
		<tr>
			<th scope="row"><label for="bank_acc">입금계좌</label></th>
			<td>
				<?php echo mobile_bank_account("bank_acc"); ?>
				<div class="padt10">
					<input type="text" name="reg_hp" value="<?php echo replace_tel($member['cellphone']); ?>" id="reg_hp" class="frm_input" size="20">
				</div>
				<div class="padt5">
					<button type="button" class="btn_small btn_sms_send">위 입금계좌 문자받기</button>
				</div>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="deposit_name">입금자명</label></th>
			<td><input type="text" name="deposit_name" id="deposit_name" class="frm_input" size="20" placeholder="실제 입금자명"></td>
		</tr>
		<tr>
			<th scope="row"><label for="reg_memo">전하실말씀</label></th>
			<td><textarea name="memo" id="reg_memo" rows="10" class="frm_textbox h60"></textarea></td>
		</tr>
		<?php } ?>
		</tbody>
		</table>
	</div>
</section>

<?php if($multi_settle) { ?>
<div class="btn_confirm">
	<input type="submit" value="신청하기" id="btn_submit" class="btn_medium wset" accesskey="s">
	<a href="<?php echo TB_MURL; ?>" class="btn_medium bx-white">취소</a>
</div>
<?php } ?>
</form>

<script>
function fpartnerform_submit(f) {
	errmsg = "";
	errfld = "";

	check_field(f.bank_name, "입금받으실 은행명을 입력하세요.");
	check_field(f.bank_account, "입금받으실 계좌번호를 입력하세요");
	check_field(f.bank_holder, "입금받으실 예금주명을 입력하세요");
	check_field(f.bank_acc, "입금하실 입금계좌를 선택하세요");
	check_field(f.deposit_name, "입금하실 입금자명을 입력하세요");

    if(errmsg)
    {
        alert(errmsg);
        errfld.focus();
        return false;
    }

	if(typeof(f.agree1) != "undefined") {
		if(!f.agree1.checked) {
			alert("이용약관에 동의하셔야 신청 하실 수 있습니다.");
			f.agree1.focus();
			return false;
		}
	}

	if(typeof(f.agree2) != "undefined") {
		if(!f.agree2.checked) {
			alert("규정안내에 동의하셔야 신청 하실 수 있습니다.");
			f.agree2.focus();
			return false;
		}
	}

	if(!confirm("신청 하시겠습니까?")) {
		return false;
	}

	document.getElementById("btn_submit").disabled = "disabled";

	return true;
}

<?php if($multi_settle) { ?>
calculate_total_price();
<?php } ?>
</script>
