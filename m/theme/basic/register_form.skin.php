<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<form name="fregisterform" id="fregisterform" action="<?php echo $register_action_url; ?>" onsubmit="return fregisterform_submit(this);" method="post" autocomplete="off">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="pt_id" value="<?php echo $pt_id; ?>">
<input type="hidden" name="token" value="<?php echo $token; ?>">

<div class="tbl_frm01 tbl_wrap">
	<table>
	<colgroup>
		<col class="w100">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row"><label for="reg_mb_name">회원명</label> <span class="fc_red">*</span></th>
		<td>
			<input type="text" name="name" value="<?php echo $member['name']; ?>" id="reg_mb_name" required itemname="회원명" class="frm_input required"<?php if($w=='u' || $default['de_certify_use']) echo $readonly; ?>>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_mb_id">아이디</label> <span class="fc_red">*</span></th>
		<td>
			<input type="text" name="id" value="<?php echo $member['id']; ?>" id="reg_mb_id" required memberid itemname="아이디" class="frm_input required" onkeyup="reg_mb_id_ajax();" minlength="3" maxlength="20"<?php if($w=='u') echo $readonly; ?>>
			<span class="frm_info" id="msg_mb_id">최소 3자이상의 영문자, 숫자, _ 만 입력</span>
		</td>
	</tr>
	<?php if($w=='') { ?>
	<tr>
		<th scope="row"><label for="reg_mb_password">비밀번호</label> <span class="fc_red">*</span></th>
		<td>
			<input type="password" name="passwd" id="reg_mb_password" required itemname="비밀번호" class="frm_input required" minlength="4" maxlength="20">
			<span class="frm_info">4자 이상의 영문 및 숫자</span>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_mb_password_re">비밀번호확인</label> <span class="fc_red">*</span></th>
		<td><input type="password" name="repasswd" id="reg_mb_password_re" required itemname="비밀번호확인" class="frm_input required" minlength="4" maxlength="20"></td>
	</tr>
	<?php } else if($w=='u') { ?>
	<?php if(!$member['sns_id']) { ?>
	<tr>
		<th scope="row"><label for="reg_mb_password_db">현재비밀번호</label> <span class="fc_red">*</span></th>
		<td><input type="password" name="dbpasswd" id="reg_mb_password_db" required itemname="현재비밀번호" class="frm_input required" minlength="4" maxlength="20"></td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_mb_password">새비밀번호</label></th>
		<td>
			<input type="password" name="passwd" id="reg_mb_password" class="frm_input" minlength="4" maxlength="20">
			<span class="frm_info">4자 이상의 영문 및 숫자</span>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_mb_password_re">새비밀번호확인</label></th>
		<td><input type="password" name="repasswd" id="reg_mb_password_re" class="frm_input" minlength="4" maxlength="20"></td>
	</tr>
	<?php } ?>
	<?php } ?>
	<?php if($config['register_use_hp']) { ?>
	<tr>
		<th scope="row"><label for="reg_cellphone">핸드폰</label><?php echo $config['register_req_hp']?' <span class="fc_red">*</span>':''; ?></th>
		<td>
			<input type="text" name="cellphone" value="<?php echo $member['cellphone']; ?>" id="reg_cellphone"<?php echo $config['register_req_hp']?' required':''; ?> itemname="핸드폰" class="frm_input<?php echo $config['register_req_hp']?' required':''; ?>">
			<div class="padt5">
				<input type="checkbox" name="smsser" value="Y" id="smsser_yes"<?php echo get_checked('Y', $member['smsser']); ?> class="css-checkbox lrg"><label for="smsser_yes" class="css-label">SMS를 수신합니다.</label>
			</div>
		</td>
	</tr>
	<?php } ?>
	<?php if($config['register_use_tel']) { ?>
	<tr>
		<th scope="row"><label for="reg_telephone">전화번호</label><?php echo $config['register_req_tel']?' <span class="fc_red">*</span>':''; ?></th>
		<td>
			<input type="text" name="telephone" value="<?php echo $member['telephone']; ?>" id="reg_telephone"<?php echo $config['register_req_tel']?' required':''; ?> itemname="전화번호" class="frm_input<?php echo $config['register_req_tel']?' required':''; ?>">
		</td>
	</tr>
	<?php } ?>
	<?php if($config['register_use_email']) { ?>
	<tr>
		<th scope="row"><label for="reg_mb_email">이메일</label><?php echo $config['register_req_email']?' <span class="fc_red">*</span>':''; ?></th>
		<td>
			<input type="email" name="email" value="<?php echo $member['email']; ?>" id="reg_mb_email"<?php echo $config['register_req_email']?' required':''; ?> email itemname="이메일" class="frm_input<?php echo $config['register_req_email']?' required':''; ?>">
			<div class="padt5">
				<input type="checkbox" name="mailser" value="Y" id="mailser_yes"<?php echo get_checked('Y', $member['mailser']); ?> class="css-checkbox lrg"><label for="mailser_yes" class="css-label">E-Mail을 수신합니다.</label>
			</div>
		</td>
	</tr>
	<?php } ?>
	<?php if($config['register_use_addr']) { ?>
	<tr>
		<th scope="row">주소<?php echo $config['register_req_addr']?' <span class="fc_red">*</span>':''; ?></th>
		<td>
			<label for="reg_mb_zip" class="sound_only">우편번호</label>
			<input type="text" name="zip" value="<?php echo $member['zip']; ?>" id="reg_mb_zip"<?php echo $config['register_req_addr']?' required':''; ?> itemname="우편번호" class="frm_input<?php echo $config['register_req_addr']?' required':''; ?>" size="5" maxlength="5" readonly>
			<button type="button" class="btn_small grey" onclick="win_zip('fregisterform', 'zip', 'addr1', 'addr2', 'addr3', 'addr_jibeon');">주소검색</button><br>

			<label for="reg_mb_addr1" class="sound_only">주소</label>
			<input type="text" name="addr1" value="<?php echo $member['addr1']; ?>" id="reg_mb_addr1"<?php echo $config['register_req_addr']?' required':''; ?> itemname="주소" class="frm_input frm_address<?php echo $config['register_req_addr']?' required':''; ?>" readonly><br>

			<label for="reg_mb_addr2" class="sound_only">상세주소</label>
			<input type="text" name="addr2" value="<?php echo $member['addr2']; ?>" id="reg_mb_addr2" class="frm_input frm_address"><br>

			<label for="reg_mb_addr3" class="sound_only">참고항목</label>
			<input type="text" name="addr3" value="<?php echo $member['addr3']; ?>" id="reg_mb_addr3" class="frm_input frm_address" readonly>
			<input type="hidden" name="addr_jibeon" value="<?php echo $member['addr_jibeon']; ?>">
		</td>
	</tr>
	<?php } ?>
	<tr>
		<th scope="row">생년월일 <span class="fc_red">*</span></th>
		<td>
			<label for="reg_mb_birth_year" class="sound_only"></label>
			<input type="text" name="birth_year" value="<?php echo $member['birth_year']; ?>" id="reg_mb_birth_year" required itemname="생년월일" class="frm_input required" maxlength="4" size="5"<?php if($default['de_certify_use']) echo $readonly; ?>>년

			<label for="reg_mb_birth_month" class="sound_only"></label>
			<input type="text" name="birth_month" value="<?php echo $member['birth_month']; ?>" id="reg_mb_birth_month" required itemname="생년월일" class="frm_input required" maxlength="2" size="5"<?php if($default['de_certify_use']) echo $readonly; ?>>월

			<label for="reg_mb_birth_day" class="sound_only"></label>
			<input type="text" name="birth_day" value="<?php echo $member['birth_day']; ?>" id="reg_mb_birth_day" required itemname="생년월일" class="frm_input required" maxlength="2" size="5"<?php if($default['de_certify_use']) echo $readonly; ?>>일
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_mb_birth_type">양력/음력</label></th>
		<td>
			<select name="birth_type" id="reg_mb_birth_type">
				<?php echo option_selected('S', $member['birth_type'], '양력'); ?>
				<?php echo option_selected('L', $member['birth_type'], '음력'); ?>
			</select>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="reg_mb_gender">성별</label></th>
		<td>
			<select name="gender" id="reg_mb_gender">
				<?php echo option_selected('',  $member['gender'], '성별'); ?>
				<?php echo option_selected('M', $member['gender'], '남자'); ?>
				<?php echo option_selected('F', $member['gender'], '여자'); ?>
			</select>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<div class="mart10 lb-info fc_red bold">* 표시는 필수항목입니다.</div>

<div class="btn_confirm">
	<input type="submit" value="<?php echo $w==''?'회원가입':'정보수정'; ?>" id="btn_submit" class="btn_medium wset" accesskey="s">
	<a href="<?php echo TB_MURL; ?>" class="btn_medium bx-white">취소</a>
</div>
</form>

<script>
function fregisterform_submit(f)
{
	var str;
	<?php if($w=='') { ?>
	var mb_id = reg_mb_id_check(f.id.value);
	if(mb_id) {
		alert("'"+mb_id+"'은(는) 사용하실 수 없는 아이디입니다.");
		f.id.focus();
		return false;
	}
	<?php } ?>

    // 회원아이디 검사
	if(f.id.value.length < 3) {
		alert('아이디를 3글자 이상 입력하십시오.');
		f.id.focus();
		return false;
	}

	<?php if($w=='') { ?>
    // 패스워드 검사
	if(f.passwd.value.length < 4) {
		alert('패스워드를 4글자 이상 입력하십시오.');
		f.passwd.focus();
		return false;
	}

    if(f.passwd.value != f.repasswd.value) {
        alert('패스워드가 같지 않습니다.');
        f.repasswd.focus();
        return false;
    }

    if(f.passwd.value.length > 0) {
        if(f.repasswd.value.length < 4) {
            alert('패스워드를 4글자 이상 입력하십시오.');
            f.repasswd.focus();
            return false;
        }
    }

	str = "회원가입";
	<?php } else if($w=='u') { ?>
	<?php if(!$member['sns_id']) { ?>
	if(f.passwd.value) {
		// 패스워드 검사
		if(f.passwd.value.length < 4) {
			alert('패스워드를 4글자 이상 입력하십시오.');
			f.passwd.focus();
			return false;
		}

		if(f.passwd.value != f.repasswd.value) {
			alert('패스워드가 같지 않습니다.');
			f.repasswd.focus();
			return false;
		}

		if(f.passwd.value.length > 0) {
			if(f.repasswd.value.length < 4) {
				alert('패스워드를 4글자 이상 입력하십시오.');
				f.repasswd.focus();
				return false;
			}
		}
	}
	<?php } ?>

	str = "정보수정";
	<?php } ?>

	<?php if($config['register_use_email']) { ?>
	// 사용할 수 없는 E-mail 도메인
	var domain = prohibit_email_check(f.email.value);
	if(domain) {
		alert("'"+domain+"'은(는) 사용하실 수 없는 메일입니다.");
		f.email.focus();
		return false;
	}
	<?php } ?>

	if(confirm(str+" 하시겠습니까?") == false)
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
	var mb_id = $.trim($("#reg_mb_id").val());
	$.post(
		tb_bbs_url+"/ajax.mb_id_check.php",
		{ mb_id: mb_id },
		function(data) {
			$("#msg_mb_id").empty().html(data);
		}
	);
}
</script>
