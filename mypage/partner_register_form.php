<?php
if(!defined('_TUBEWEB_')) exit;

$pg_title = "신규 회원등록";
include_once("./admin_head.sub.php");
?>

<form name="fregisterform" method="post" action="./partner_register_form_update.php" onsubmit="return fregisterform_submit(this);">
<input type="hidden" name="token" value="">

<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">회원명</th>
		<td><input type="text" name="name" required itemname="회원명" class="frm_input required" size="20"></td>
	</tr>
	<tr>
		<th scope="row">아이디</th>
		<td>
			<input type="text" name="id" id="mb_id" required memberid itemname="아이디" class="frm_input required" onkeyup="reg_mb_id_ajax();" size="20" minlength="3" maxlength="20">
			<span id="msg_mb_id" class="marl5"></span>
			<?php echo help('영문자, 숫자, _ 만 입력 가능. 최소 3자이상 입력하세요.'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">비밀번호</th>
		<td>
			<input type="password" name="passwd" required itemname="비밀번호" class="frm_input required" size="20" minlength="4" maxlength="20">
			<?php echo help('4자 이상의 영문 및 숫자'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">비밀번호확인</th>
		<td><input type="password" name="repasswd" required itemname="비밀번호확인" class="frm_input required" size="20" minlength="4" maxlength="20"></td>
	</tr>
	<tr>
		<th scope="row">생년월일</th>
		<td>
			<input name="birth_year" required itemname="생년월일" class="frm_input required" size="8" maxlength="4"> 년
			<input name="birth_month" required itemname="생년월일" class="frm_input required" size="4" maxlength="2"> 월
			<input name="birth_day" required itemname="생년월일" class="frm_input required" size="4" maxlength="2"> 일
			<select name="birth_type">
				<option value="S">양력</option>
				<option value="L">음력</option>
			</select>
			<select name="gender">
				<option value="">성별</option>
				<option value="M">남자</option>
				<option value="F">여자</option>
			</select>
		</td>
	</tr>
	<?php if($config['register_use_tel']) { ?>
	<tr>
		<th scope="row">전화번호</th>
		<td><input type="text" name="telephone" size="20"<?php echo $config['register_req_tel']?' required':''; ?> itemname="전화번호" class="frm_input<?php echo $config['register_req_tel']?' required':''; ?>"></td>
	</tr>
	<?php } ?>
	<?php if($config['register_use_hp']) { ?>
	<tr>
		<th scope="row">휴대전화</th>
		<td>
			<input type="text" name="cellphone" size="20"<?php echo $config['register_req_hp']?' required':''; ?> itemname="휴대전화" class="frm_input<?php echo $config['register_req_hp']?' required':''; ?>">
			<label><input type="checkbox" value="Y" name="smsser" checked> SMS를 수신합니다.</label>
		</td>
	</tr>
	<?php } ?>
	<?php if($config['register_use_email']) { ?>
	<tr>
		<th scope="row">이메일</th>
		<td>
			<input type="text" name="email"<?php echo $config['register_req_email']?' required':''; ?>
			email itemname="이메일" class="frm_input<?php echo $config['register_req_email']?' required':''; ?>" size="40">
			<label><input type="checkbox" value="Y" name="mailser" checked> E-Mail을 수신합니다.</label>
		</td>
	</tr>
	<?php } ?>
	<?php if($config['register_use_addr']) { ?>
	<tr>
		<th scope="row">주소</th>
		<td>
			<div>
				<input type="text" name="zip"<?php echo $config['register_req_addr']?' required':''; ?> itemname="우편번호" class="frm_input<?php echo $config['register_req_addr']?' required':''; ?>" size="8" maxlength="5" readonly>
				<a href="javascript:win_zip('fregisterform', 'zip', 'addr1', 'addr2', 'addr3', 'addr_jibeon');" class="btn_small grey">주소검색</a>
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
		<th scope="row">추천인</th>
		<td><input type="text" name="pt_id" value="<?php echo $member['id']; ?>" required itemname="추천인" class="frm_input required"></td>
	</tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="저장" id="btn_submit" class="btn_large" accesskey="s">
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

	if(confirm("입력하신 사항들이 맞는지 확인하시기 바랍니다.\n\n저장 하시려면 '확인'버튼을 클릭하세요") == false)
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

<?php
include_once("./admin_tail.sub.php");
?>