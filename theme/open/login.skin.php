<?php
if(!defined('_TUBEWEB_')) exit;
?>

<p class="tit_navi">홈 <i class="ionicons ion-ios-arrow-right"></i> 로그인</p>
<h2 class="stit">LOGIN</h2>
<ul class="login_tab">
	<li data-tab="login_fld"><span>회원 로그인</span></li>
	<li data-tab="guest_fld"><span>비회원 주문조회</span></li>
</ul>

<form name="flogin" action="<?php echo $login_action_url; ?>" onsubmit="return flogin_submit(this);" method="post">
<input type="hidden" name="url" value="<?php echo $login_url; ?>">

<div class="login_wrap" id="login_fld">		
	<dl class="log_inner">
		<dt>회원 로그인</dt>
		<dd class="stxt">로그인하시면 다양한 서비스와 혜택을 받으실 수 있습니다.</dd>
		<dd>
			<label for="login_id" class="sound_only">회원아이디</label>
			<input type="text" name="mb_id" id="login_id" class="frm_input" maxLength="20" placeholder="아이디">	
		</dd>
		<dd>
			<label for="login_pw" class="sound_only">비밀번호</label>
			<input type="password" name="mb_password" id="login_pw" class="frm_input" maxLength="20" placeholder="비밀번호">		
		</dd>
		<dd><button type="submit" class="btn_large">로그인</button></dd>
		<?php if(preg_match("/orderform.php/", $url)) { ?>
		<dd><a href="<?php echo TB_SHOP_URL; ?>/orderform.php" class="btn_large red wfull">비회원으로 구매하기</a></dd>
		<?php } ?>
		<dd class="log_op">
			<span><input type="checkbox" name="auto_login" id="login_auto_login"> <label for="login_auto_login">자동로그인</label></span>	
			<span class="fr"><a href="<?php echo TB_BBS_URL; ?>/password_lost.php" onclick="win_open(this,'pop_password_lost','500','400','no');return false;">아이디 / 비밀번호 찾기</a></span>
		</dd>
	</dl>
	<?php if($default['de_sns_login_use']) { ?>
	<div class="sns_btn">
		<h3>SNS 계정 로그인</h3>
		<?php if($default['de_naver_appid'] && $default['de_naver_secret']) { ?>
		<?php echo get_login_oauth('naver', 1); ?>
		<?php } ?>
		<?php if($default['de_facebook_appid'] && $default['de_facebook_secret']) { ?>
		<?php echo get_login_oauth('facebook', 1); ?>
		<?php } ?>
		<?php if($default['de_kakao_rest_apikey']) { ?>
		<?php echo get_login_oauth('kakao', 1); ?>
		<?php } ?>
	</div>
	<?php } ?>	
</div>
</form>

<form name="forderinquiry" method="post" action="<?php echo TB_SHOP_URL; ?>/orderinquiry.php" autocomplete="off">
<div class="login_wrap" id="guest_fld">	
	<dl class="log_inner">
		<dt>비회원 주문조회</dt>
		<dd class="stxt">
			결제 완료 후 안내해드린 주문번호와 주문 결제 시에 작성한 비밀번호를 입력해주세요.
		</dd>
		<dd>
			<label for="od_id" class="sound_only">주문번호</label>
            <input type="text" name="od_id" id="od_id" class="frm_input" placeholder="주문번호">		
		</dd>
		<dd>
			<label for="od_pwd" class="sound_only">비밀번호</label>
            <input type="password" name="od_pwd" id="od_pwd" class="frm_input" placeholder="비밀번호">		
		</dd>
		<dd><button type="submit" class="btn_large">확인</button></dd>
	</dl>	
</div>
</form>

<div class="log_bt_box">
	회원가입하시고 풍성한 혜택을 누리세요.
	<a href="<?php echo TB_BBS_URL; ?>/register.php" class="btn_lsmall bx-white marl15">회원가입</a>
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

$(document).ready(function(){
	$(".login_tab>li:eq(0)").addClass('active');
	$("#login_fld").addClass('active');

	$(".login_tab>li").click(function() {
		var activeTab = $(this).attr('data-tab');
		$(".login_tab>li").removeClass('active');
		$(".login_wrap").removeClass('active');
		$(this).addClass('active');
		$("#"+activeTab).addClass('active');
	});
});
</script>
