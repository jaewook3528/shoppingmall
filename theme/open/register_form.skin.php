<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form name="fregisterform" id="fregisterform" action="<?php echo $register_action_url; ?>" onsubmit="return fregisterform_submit(this);" method="post" autocomplete="off">
<input type="hidden" name="pt_id" value="<?php echo $pt_id; ?>">
<input type="hidden" name="token" value="<?php echo $token; ?>">

<div><img src="<?php echo TB_IMG_URL; ?>/register_2.gif"></div>

<h3>사이트 이용정보 입력</h3>
<div class="tbl_frm01 tbl_wrap">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">회원명 <span class="fc_red">*</span></th>
		<td><input type="text" name="name" value="<?php echo $cert_name; ?>" <?php if($default['de_certify_use']){echo $readonly;}?> required itemname="회원명" class="frm_input required" size="20"></td>
	</tr>
	<tr>
		<th scope="row">아이디 <span class="fc_red">*</span></th>
		<td>
			<input type="text" name="id" id="mb_id" required memberid itemname="아이디" class="frm_input required" onkeyup="reg_mb_id_ajax();" size="20" minlength="3" maxlength="20">
			<span id="msg_mb_id" class="marl5"></span>
			<span class="frm_info">※ 영문자, 숫자, _ 만 입력 가능. 최소 3자이상 입력하세요.</span>
		</td>
	</tr>
	<tr>
		<th scope="row">비밀번호 <span class="fc_red">*</span></th>
		<td>
			<input type="password" name="passwd" required itemname="비밀번호" class="frm_input required" size="20" minlength="4" maxlength="20">
			<span class="frm_info">※ 4자 이상의 영문 및 숫자</span>
		</td>
	</tr>
	<tr>
		<th scope="row">비밀번호확인 <span class="fc_red">*</span></th>
		<td><input type="password" name="repasswd" required itemname="비밀번호확인" class="frm_input required" size="20" minlength="4" maxlength="20"></td>
	</tr>
	</tbody>
	</table>
</div>

<h3 class="mart30">개인정보 입력</h3>
<div class="tbl_frm01 tbl_wrap">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<?php if($config['register_use_hp']) { ?>
	<tr>
		<th scope="row">휴대전화<?php echo $config['register_req_hp']?' <span class="fc_red">*</span>':''; ?></th>
		<td>
			<input type="text" name="cellphone" value="<?php echo $cert['cell']; ?>" <?php if($default['de_certify_use'] && $cert['cell']){echo $readonly;}?> size="20"<?php echo $config['register_req_hp']?' required':''; ?> itemname="휴대전화" class="frm_input<?php echo $config['register_req_hp']?' required':''; ?>">
			<input type="checkbox" checked value="Y" name="smsser" class="marl7"> SMS를 수신합니다.
		</td>
	</tr>
	<?php } ?>
	<?php if($config['register_use_tel']) { ?>
	<tr>
		<th scope="row">전화번호<?php echo $config['register_req_tel']?' <span class="fc_red">*</span>':''; ?></th>
		<td><input type="text" name="telephone" size="20"<?php echo $config['register_req_tel']?' required':''; ?> itemname="전화번호" class="frm_input<?php echo $config['register_req_tel']?' required':''; ?>"></td>
	</tr>
	<?php } ?>
	<?php if($config['register_use_email']) { ?>
	<tr>
		<th scope="row">이메일<?php echo $config['register_req_email']?' <span class="fc_red">*</span>':''; ?></th>
		<td>
			<input type="text" name="email"<?php echo $config['register_req_email']?' required':''; ?>
			email itemname="이메일" class="frm_input<?php echo $config['register_req_email']?' required':''; ?>" size="40">
			<input type="checkbox" checked value="Y" name="mailser" class="marl7"> E-Mail을 수신합니다.
		</td>
	</tr>
	<?php } ?>
	<?php if($config['register_use_addr']) { ?>
	<tr>
		<th scope="row">주소<?php echo $config['register_req_addr']?' <span class="fc_red">*</span>':''; ?></th>
		<td>
			<div>
				<input type="text" name="zip"<?php echo $config['register_req_addr']?' required':''; ?> itemname="우편번호" class="frm_input<?php echo $config['register_req_addr']?' required':''; ?>" size="8" maxlength="5" readonly>
				<a href="javascript:win_zip('fregisterform', 'zip', 'addr1', 'addr2', 'addr3', 'addr_jibeon');" class="btn_small grey marl3">주소검색</a>
			</div>
			<div class="mart5">
				<input type="text" name="addr1"<?php echo $config['register_req_addr']?' required':''; ?> itemname="주소" class="frm_input<?php echo $config['register_req_addr']?' required':''; ?>" size="60" readonly> 기본주소
			</div>
			<div class="mart5">
				<input type="text" name="addr2" class="frm_input" size="60"> 상세주소
			</div>
			<div class="mart5">
				<input type="text" name="addr3" class="frm_input" size="60" readonly> 참고항목
				<input type="hidden" name="addr_jibeon" value="">
			</div>
		</td>
	</tr>
	<?php } ?>
	<tr>
		<th scope="row">생년월일 <span class="fc_red">*</span></th>
		<td>
			<div class="ini_wrap">
			<table>
			<tr>
				<td><input type="text" name="birth_year" value="<?php echo $cert_year; ?>" required itemname="생년월일" <?php if($default['de_certify_use']){echo $readonly;}?> class="frm_input required" size="8" maxlength="4"> 년</td>
				<td class="padl5"><input type="text" name="birth_month" value="<?php echo $cert_month; ?>" required itemname="생년월일" <?php if($default['de_certify_use']){echo $readonly;}?> class="frm_input required" size="4" maxlength="2"> 월</td>
				<td class="padl5"><input type="text" name="birth_day" value="<?php echo $cert_day; ?>" required itemname="생년월일" <?php if($default['de_certify_use']){echo $readonly;}?> class="frm_input required" size="4" maxlength="2"> 일</td>
				<td class="padl5">
					<select name="gender">
						<option value="">성별</option>
						<option value="M"<?php if($cert['j_sex']=='1'){echo " selected";}?>>남자</option>
						<option value="F"<?php if($cert['j_sex']=='0'){echo " selected";}?>>여자</option>
					</select>
				</td>
				<td class="padl5">
					<select name="birth_type">
						<option value="S">양력</option>
						<option value="L">음력</option>
					</select>
				</td>
			</tr>
			</table>
			</div>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<div class="mart10 lb-info fc_red bold">* 표시는 필수항목입니다.</div>

<div class="btn_confirm">
	<input type="submit" value="회원가입" id="btn_submit" class="btn_large wset" accesskey="s">
	<a href="<?php echo TB_URL; ?>" class="btn_large bx-white">취소</a>
</div>
</form>

<script>
function fregisterform_submit(f)
{
	var mb_id = reg_mb_id_check(f.id.value);
	if(mb_id) {
		alert("'"+mb_id+"'은(는) 사용하실 수 없는 아이디입니다.");
		f.id.focus();
		return false;
	}

    // 회원아이디 검사
	if(f.id.value.length < 3) {
		alert("아이디를 3글자 이상 입력하십시오.");
		f.id.focus();
		return false;
	}

    // 패스워드 검사
	if(f.passwd.value.length < 4) {
		alert("패스워드를 4글자 이상 입력하십시오.");
		f.passwd.focus();
		return false;
	}

    if(f.passwd.value != f.repasswd.value) {
        alert("패스워드가 같지 않습니다.");
        f.repasswd.focus();
        return false;
    }

    if(f.passwd.value.length > 0) {
        if(f.repasswd.value.length < 4) {
            alert("패스워드를 4글자 이상 입력하십시오.");
            f.repasswd.focus();
            return false;
        }
    }

	<?php if($config['register_use_email']) { ?>
	// 사용할 수 없는 E-mail 도메인
	var domain = prohibit_email_check(f.email.value);
	if(domain) {
		alert("'"+domain+"'은(는) 사용하실 수 없는 메일입니다.");
		f.email.focus();
		return false;
	}
	<?php } ?>

	if(confirm("회원가입 하시겠습니까?") == false)
		return false;

	document.getElementById("btn_submit").disabled = "disabled";

    return true;
}

// 회원아이디 검사
function reg_mb_id_check(mb_id)
{
    mb_id = mb_id.toLowerCase();

    var prohibit_mb_id = "<?php echo trim(strtolower($config['prohibit_id'])); ?>";
    var s = prohibit_mb_id.split(",");

    for(i=0; i<s.length; i++) {
        if(s[i] == mb_id)
            return mb_id;
    }
    return "";
}

// 금지 메일 도메인 검사
function prohibit_email_check(email)
{
    email = email.toLowerCase();

    var prohibit_email = "<?php echo trim(strtolower(preg_replace("/(\r\n|\r|\n)/", ",", $config['prohibit_email']))); ?>";
    var s = prohibit_email.split(",");
    var tmp = email.split("@");
    var domain = tmp[tmp.length - 1]; // 메일 도메인만 얻는다

    for(i=0; i<s.length; i++) {
        if(s[i] == domain)
            return domain;
    }
    return "";
}

function reg_mb_id_ajax() {
	var mb_id = $.trim($("#mb_id").val());
	$.post(
		tb_bbs_url+"/ajax.mb_id_check.php",
		{ mb_id: mb_id },
		function(data) {
			$("#msg_mb_id").empty().html(data);
		}
	);
}
</script>