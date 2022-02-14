<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form name="fregform" method="post" action="./config/kakaopay_update.php">
<input type="hidden" name="token" value="">

<h2>카카오페이 서비스</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row"><label for="de_kakaopay_mid">카카오페이 상점MID</label></th>
		<td>                
		   <input type="text" name="de_kakaopay_mid" value="<?php echo $default['de_kakaopay_mid']; ?>" id="de_kakaopay_mid" class="frm_input" size="20">
		   <a href="https://cnspay.lgcns.com/" target="_blank" class="btn_small grey">카카오페이 서비스신청하기</a>
		   <?php echo help("카카오페이로 부터 발급 받으신 상점아이디(MID) 영문 10자리를 입력 합니다."); ?>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="de_kakaopay_key">카카오페이 상점키</label></th>
		<td>                
			<input type="text" name="de_kakaopay_key" value="<?php echo $default['de_kakaopay_key']; ?>" id="de_kakaopay_key" class="frm_input" size="50">
			<?php echo help("카카오페이로 부터 발급 받으신 상점 서명키를 입력합니다."); ?>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="de_kakaopay_enckey">카카오페이 상점 EncKey</label></th>
		<td>                
			<input type="text" name="de_kakaopay_enckey" value="<?php echo $default['de_kakaopay_enckey']; ?>" id="de_kakaopay_enckey" class="frm_input" size="20">
			<?php echo help("카카오페이로 부터 발급 받으신 상점 인증 전용 EncKey를 입력합니다."); ?>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="de_kakaopay_hashkey">카카오페이 상점 HashKey</label></th>
		<td>                
			<input type="text" name="de_kakaopay_hashkey" value="<?php echo $default['de_kakaopay_hashkey']; ?>" id="de_kakaopay_hashkey" class="frm_input" size="20">
			<?php echo help("카카오페이로 부터 발급 받으신 상점 인증 전용 HashKey를 입력합니다."); ?>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="de_kakaopay_cancelpwd">카카오페이 결제취소 비밀번호</label></th>
		<td>                
			<input type="text" name="de_kakaopay_cancelpwd" value="<?php echo $default['de_kakaopay_cancelpwd']; ?>" id="de_kakaopay_cancelpwd" class="frm_input" size="20">
			<?php echo help("카카오페이 상점관리자에서 설정하신 취소 비밀번호를 입력합니다.<br>입력하신 비밀번호와 상점관리자에서 설정하신 비밀번호가 일치하지 않으면 취소가 되지 않습니다."); ?>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
</div>
</form>

<div class="information">
	<h4>도움말</h4>
	<div class="content">
		<div class="hd">ㆍ카카오페이란?</div>
		<div class="desc01">
			<p>ㆍ카카오페이는 다음카카오와 ㈜LG CNS가 협력해 출시한 모바일 간편결제 서비스 입니다.</p>
			<p>ㆍ국내 최초 모든기기, 모든 OS 모든 브라우저에서 지원이 가능하여, 고객이 언제 어디서나 손쉽게 실제 서비스를 이용할 수 있습니다.</p>
			<p>ㆍ모바일/PC 동시 지원 뿐만 아니라 국내 대부분의 쇼핑몰이 지원하지 않는 Mac, Linux에서도 결제가 가능합니다.</p>
			<p>ㆍActive-X, 공인인증서 등 복잡한 절차에 구애 받지 않아 모든 브라우저 환경에서 편리하게 결제 서비스를 이용할 수 있습니다.</p>
		</div>
		<div class="hd">ㆍ서류 접수처</div>
		<div class="desc01">
			<p>ㆍ(07320) 서울특별시 영등포구 여의대로 24 FKI타워 28층 스마트페이먼트팀 카카오페이 신규계약 담당자앞</p>
		</div>
		<div class="hd">ㆍ문의 및 상담</div>
		<div class="desc01">
			<p>ㆍ상담시간 : 오전 09:00~오후6:00 (점심시간 11시 30분~12시 30분) <strong class="fc_red">주말 및 공휴일 휴무</strong></p>
			<p>ㆍ신규계약문의 : 02-2099-2646 / 02-2099-2647</p>
			<p>ㆍ고객센터 : 02-1661-8455 / kakaopay@lgcns.com</p>
			<p>ㆍ홈페이지 : <a href="https://cnspay.lgcns.com" target="_blank">http://cnspay.lgcns.com</a></p>
		</div>
	 </div>
</div>
