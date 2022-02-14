<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TB_THEME_PATH.'/aside_my.skin.php');
?>

<div id="con_lf">
	<h2 class="pg_tit">
		<span><?php echo $tb['title']; ?></span>
		<p class="pg_nav">HOME<i>&gt;</i>마이페이지<i>&gt;</i><?php echo $tb['title']; ?></p>
	</h2>

	<form name="fregisterform" id="fregisterform" action="<?php echo $register_action_url; ?>" onsubmit="return fregisterform_submit(this);" method="post" autocomplete="off">
	<input type="hidden" name="token" value="<?php echo $token; ?>">

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
			<td><input type="text" name="name" value="<?php echo $member['name']; ?>" <?php echo $readonly; ?> class="frm_input" size="20"></td>
		</tr>
		<tr>
			<th scope="row">아이디 <span class="fc_red">*</span></th>
			<td><input type="text" name="id" value="<?php echo $member['id']; ?>" <?php echo $readonly; ?> class="frm_input" size="20" minlength="3" maxlength="20"></td>
		</tr>
		<?php if(!$member['sns_id']) { ?>
		<tr>
			<th scope="row">현재비밀번호</th>
			<td><input type="password" name="dbpasswd" required itemname="현재비밀번호" class="frm_input required" size="20" minlength="4" maxlength="20"></td>
		</tr>
		<tr>
			<th scope="row">새비밀번호</th>
			<td><input type="password" name="passwd" class="frm_input" size="20" minlength="4" maxlength="20"></td>
		</tr>
		<tr>
			<th scope="row">새비밀번호확인</th>
			<td><input type="password" name="repasswd" class="frm_input" size="20" minlength="4" maxlength="20"></td>
		</tr>
		<?php } ?>
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
				<input type="text" name="cellphone" value="<?php echo $member['cellphone']; ?>"<?php echo $config['register_req_hp']?' required':''; ?> itemname="휴대전화" class="frm_input<?php echo $config['register_req_hp']?' required':''; ?>" size="20">
				<input type="checkbox" value="Y" name="smsser" class="marl7"<?php echo $member['smsser'] == 'Y'?' checked':''; ?>> SMS를 수신합니다.
			</td>
		</tr>
		<?php } ?>
		<?php if($config['register_use_tel']) { ?>
		<tr>
			<th scope="row">전화번호<?php echo $config['register_req_tel']?' <span class="fc_red">*</span>':''; ?></th>
			<td><input type="text" name="telephone" value="<?php echo $member['telephone']; ?>"<?php echo $config['register_req_tel']?' required':''; ?> itemname="전화번호" class="frm_input<?php echo $config['register_req_tel']?' required':''; ?>" size="20"></td>
		</tr>
		<?php } ?>
		<?php if($config['register_use_email']) { ?>
		<tr>
			<th scope="row">이메일<?php echo $config['register_req_email']?' <span class="fc_red">*</span>':''; ?></th>
			<td>
				<input type="text" name="email" value="<?php echo $member['email']; ?>"<?php echo $config['register_req_email']?' required':''; ?> email itemname="이메일" class="frm_input<?php echo $config['register_req_email']?' required':''; ?>" size="40">
				<input type="checkbox" value="Y" name="mailser" class="marl7"<?php echo $member['mailser'] == 'Y'?' checked':''; ?>> E-Mail을 수신합니다.
			</td>
		</tr>
		<?php } ?>
		<?php if($config['register_use_addr']) { ?>
		<tr>
			<th scope="row">주소<?php echo $config['register_req_addr']?' <span class="fc_red">*</span>':''; ?></th>
			<td>
				<div>
					<input type="text" name="zip" value="<?php echo $member['zip']; ?>"<?php echo $config['register_req_addr']?' required':''; ?> itemname="우편번호" class="frm_input<?php echo $config['register_req_addr']?' required':''; ?>" size="8" maxlength="5" readonly>
					<a href="javascript:win_zip('fregisterform', 'zip', 'addr1', 'addr2', 'addr3', 'addr_jibeon');" class="btn_small grey marl3">주소검색</a>
				</div>
				<div class="mart5">
					<input type="text" name="addr1" value="<?php echo $member['addr1']; ?>"<?php echo $config['register_req_addr']?' required':''; ?> itemname="주소" class="frm_input<?php echo $config['register_req_addr']?' required':''; ?>" size="60" readonly> 기본주소
				</div>
				<div class="mart5">
					<input type="text" name="addr2" value="<?php echo $member['addr2']; ?>" class="frm_input" size="60"> 상세주소
				</div>
				<div class="mart5">
					<input type="text" name="addr3" value="<?php echo $member['addr3']; ?>" class="frm_input" size="60"> 참고항목
					<input type="hidden" name="addr_jibeon" value="<?php echo $member['addr_jibeon']; ?>">
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
					<td><input type="text" name="birth_year" value="<?php echo $member['birth_year']; ?>" required itemname="생년월일" class="frm_input required" size="8" maxlength="4"> 년</td>
					<td class="padl5"><input type="text" name="birth_month" value="<?php echo $member['birth_month']; ?>" required itemname="생년월일" class="frm_input required" size="4" maxlength="2"> 월</td>
					<td class="padl5"><input type="text" name="birth_day" value="<?php echo $member['birth_day']; ?>" required itemname="생년월일" class="frm_input required" size="4" maxlength="2"> 일</td>
					<td class="padl5">
						<select name="gender">
						<option value="">성별</option>
						<option value="M"<?php echo get_selected($member['gender'],"M"); ?>>남자</option>
						<option value="F"<?php echo get_selected($member['gender'],"F"); ?>>여자</option>
						</select>
					</td>
					<td class="padl5">
						<select name="birth_type">
						<option value="S"<?php echo get_selected($member['birth_type'],"S"); ?>>양력</option>
						<option value="L"<?php echo get_selected($member['birth_type'],"L"); ?>>음력</option>
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
		<input type="submit" value="정보수정" id="btn_submit" class="btn_large wset" accesskey="s">
		<a href="<?php echo TB_URL; ?>" class="btn_large bx-white">취소</a>
	</div>
	</form>
</div>

<script>
function fregisterform_submit(f)
{
<?php if(!$member['sns_id']) { ?>
	if(f.passwd.value) {
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
	}
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

	document.getElementById("btn_submit").disabled = "disabled";

	return true;
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
</script>
