<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<div class="mb_login">
	<form name="flogin" action="<?php echo $login_action_url; ?>" onsubmit="return flogin_submit(this);" method="post">
	<input type="hidden" name="url" value="<?php echo $login_url; ?>">
	<section class="login_fs">
		<p class="mart15">
			<label for="login_id" class="sound_only">회원아이디</label>
			<input type="text" name="mb_id" id="login_id" maxLength="20" placeholder="아이디">
		</p>
		<p class="mart3">
			<label for="login_pw" class="sound_only">비밀번호</label>
			<input type="password" name="mb_password" id="login_pw" maxLength="20" placeholder="비밀번호">		
		</p>	
		<p class="mart10 tal">
			<input type="checkbox" name="auto_login" id="login_auto_login" class="css-checkbox lrg">
			<label for="login_auto_login" class="css-label">자동로그인</label>
		</p>
		<p class="mart10"><button type="submit" class="btn_medium wfull">로그인</button></p>
		<p class="mart3"><a href="<?php echo TB_MBBS_URL; ?>/register.php" class="btn_medium wfull bx-white">회원가입</a></p>
		<p class="mart7 tar"><span><a href="<?php echo TB_MBBS_URL; ?>/password_lost.php">아이디/비밀번호 찾기</a></span></p>
	</section>
	<?php if($default['de_sns_login_use']) { ?>
	<p class="sns_btn">
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
	<?php } ?>
	</form>

	<?php if(preg_match("/orderform.php/", $url)) { ?>
	<section class="mb_login_od">
		<h3>비회원 구매</h3>
		<p class="mart15"><a href="<?php echo TB_MSHOP_URL; ?>/orderform.php" class="btn_medium wfull red">비회원으로 구매하기</a></p>
	</section>
	<?php } else if(preg_match("/orderinquiry.php$/", $url)) { ?>
	<form name="forderinquiry" method="post" action="<?php echo TB_MSHOP_URL; ?>/orderinquiry.php" autocomplete="off">
	<section class="mb_login_od">
		<h3>비회원 주문조회</h3>
		<p class="mart15">
			<label for="od_id" class="sound_only">주문번호</label>
            <input type="text" name="od_id" id="od_id" placeholder="주문번호">			
		</p>
		<p class="mart3">
			<label for="od_pwd" class="sound_only">비밀번호</label>
            <input type="password" name="od_pwd" id="od_pwd" placeholder="비밀번호">		
		</p>
		<p class="mart10"><button type="submit" class="btn_medium wfull">확인</button></p>
	</section>
	</form>
	<?php } ?>
</div>

<script>
$(function(){
    $("#login_auto_login").click(function(){
        if (this.checked) {
            this.checked = confirm("자동로그인을 사용하시면 다음부터 회원아이디와 비밀번호를 입력하실 필요가 없습니다.\n\n공공장소에서는 개인정보가 유출될 수 있으니 사용을 자제하여 주십시오.\n\n자동로그인을 사용하시겠습니까?");
        }
    });
});

function flogin_submit(f)
{
	if(!f.mb_id.value) {
		alert('아이디를 입력하세요.');
		f.mb_id.focus();
		return false;
	}
	if(!f.mb_password.value) {
		alert('비밀번호를 입력하세요.');
		f.mb_password.focus();
		return false;
	}

    return true;
}

function fguest_submit(f)
{
	if(!f.od_id.value) {
		alert('주문번호를 입력하세요.');
		f.od_id.focus();
		return false;
	}
	if(!f.od_pwd.value) {
		alert('비밀번호를 입력해주세요.');
		f.od_pwd.focus();
		return false;
	}

    return true;
}
</script>
