<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form  name="fregister" id="fregister" action="<?php echo $register_action_url; ?>" onsubmit="return fregister_submit(this);" method="POST" autocomplete="off">

<div><img src="<?php echo TB_IMG_URL; ?>/register_1.gif"></div>

<?php if($default['de_certify_use']) { // 실명인증 사용시 ?>
<input type="hidden" name="m" value="checkplusSerivce">
<input type="hidden" name="EncodeData" value="<?php echo $enc_data; ?>">
<input type="hidden" name="enc_data" value="<?php echo $sEncData; ?>">
<input type="hidden" name="param_r1" value="">
<input type="hidden" name="param_r2" value="">
<input type="hidden" name="param_r3" value="<?php echo $regReqSeq; ?>">
<?php } ?>

<?php if($default['de_sns_login_use']) { ?>
<div class="sns_box mart20">
	<h3>SNS 계정으로 가입</h3>
	<p>
		<?php if($default['de_naver_appid'] && $default['de_naver_secret']) { ?>
		<?php echo get_login_oauth('naver', 1); ?>
		<?php } ?>
		<?php if($default['de_facebook_appid'] && $default['de_facebook_secret']) { ?>
		<?php echo get_login_oauth('facebook', 1); ?>
		<?php } ?>
		<?php if($default['de_kakao_rest_apikey']) { ?>
		<?php echo get_login_oauth('kakao', 1); ?>
		<?php } ?>
	</p>
</div>
<?php } ?>

<section id="fregister_term">
	<h2>회원가입 약관</h2>
	<textarea readonly><?php echo $config['shop_provision']; ?></textarea>
	<fieldset class="fregister_agree">
		<input type="checkbox" name="agree" value="1" id="agree11">
		<label for="agree11">회원가입 약관 내용에 동의합니다.</label>
	</fieldset>
</section>

<section id="fregister_private">
	<h2>개인정보 수집 및 이용</h2>
	<div class="tbl_head02 tbl_wrap">
		<table>
		<thead>
		<tr>
			<th>목적</th>
			<th>항목</th>
			<th>보유기간</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td>이용자 식별 및 본인여부 확인</td>
			<td>아이디, 이름, 비밀번호</td>
			<td>회원 탈퇴 시까지</td>
		</tr>
		<tr>
			<td>고객서비스 이용에 관한 통지, CS대응을 위한 이용자 식별</td>
			<td>연락처 (이메일, 휴대전화번호)</td>
			<td>회원 탈퇴 시까지</td>
		</tr>
		</tbody>
		</table>
	</div>
	<fieldset class="fregister_agree">
		<input type="checkbox" name="agree2" value="1" id="agree21">
		<label for="agree21">개인정보 수집 및 이용 내용에 동의합니다.</label>
	</fieldset>
</section>

<?php if($default['de_certify_use']) { // 실명인증 사용시 ?>
<section>
	<div class="agree_txt">
		<i class="fa fa-exclamation-circle"></i> 개정 정보통신법 제23조에 따라 회원가입시에는 주민등록번호를 수집하지 않습니다.
		<span class="bold marl20">
			<input type="radio" value="1" name="chkplus" id="chkplus11">
			<label for="chkplus11" class="marr10">휴대폰 인증</label>
			<input type="radio" value="0" name="chkplus" id="chkplus10">
			<label for="chkplus10">아이핀 인증</label>
		</span>
	</div>
</section>
<?php } ?>

<div class="btn_confirm">
	<input type="submit" value="확인" class="btn_large wset">
	<a href="<?php echo TB_URL; ?>" class="btn_large bx-white">취소</a>
</div>
</form>

<script>
window.name ="Parent_window";
function fnPopup(val){
	switch(val){
		case 1: //휴대폰인증
			window.open('', 'popupChk', 'width=500, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
			document.fregister.action = "https://nice.checkplus.co.kr/CheckPlusSafeModel/checkplus.cb";
			document.fregister.target = "popupChk";
			document.fregister.submit();
			break;
		case 0: // 아이핀인증
			window.open('', 'popupIPIN2', 'width=450, height=550, top=100, left=100, fullscreen=no, menubar=no, status=no, toolbar=no, titlebar=yes, location=no, scrollbar=no');
			document.fregister.target = "popupIPIN2";
			document.fregister.action = "https://cert.vno.co.kr/ipin.cb";
			document.fregister.submit();
			break;
	}
}

function fregister_submit(f)
{
	if(!f.agree.checked) {
		alert("회원가입 약관 내용에 동의하셔야 회원가입 하실 수 있습니다.");
		f.agree.focus();
		return false;
	}

	if(!f.agree2.checked) {
		alert("개인정보 수집 및 이용 내용에 동의하셔야 회원가입 하실 수 있습니다.");
		f.agree2.focus();
		return false;
	}

	<?php if($default['de_certify_use']) { ?>
    var chkplus = document.getElementsByName("chkplus");
    if(!chkplus[0].checked && !chkplus[1].checked) {
        alert("휴대폰인증 및 아이핀인증 후 회원가입 하실 수 있습니다.");
        return false;
    }
	if(chkplus[0].checked) {
        fnPopup(1);
		return false;
    }
	if(chkplus[1].checked) {
        fnPopup(0);
		return false;
    }
	<?php } else { ?>
	return true;
	<?php } ?>
}
</script>
