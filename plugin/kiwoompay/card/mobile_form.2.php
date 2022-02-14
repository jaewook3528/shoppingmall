<!DOCTYPE html>
<html>
<head>
<title>결제페이지(통합결제창_LINK)</title>
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr">
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate"/> 
<meta http-equiv="Expires" content="-1"/> 
<meta http-equiv="Pragma" content="no-cache"/>

<style type="text/css">
</style>
	<script  type="text/javascript"  language="javascript" charset="euc-kr">
		var pf;
		function pay() {
			pf = document.payForm;
			<?php
			if($DEV == 'Y'){
			?>
				var fileName = "https://apitest.payjoa.co.kr/pay/link";
			<?php
			}else{
			?>
				var fileName = "https://api.payjoa.co.kr/pay/link";
			<?php
			}
			?>						
			var Pay2pay = window.open("", "Pay2pay", "width=468,height=750");	
			pf.target = "Pay2pay";
			pf.action = fileName;
			pf.method = "post";
			pf.submit();
		}	
		window.setTimeout(function(){ pay();},1000);
		
	</script>
</HEAD>

<BODY >
<form name="payForm" accept-charset="euc-kr">
<!-- 필수 값 -->
<input type="hidden" name="CPID" value="<?=$kw_shop_id?>" >
<input type="hidden" name="TYPE" value="P">
<input type="hidden" name="PAYMETHOD" value="MOBILE">
<input type="hidden" name="ORDERNO" value="<?=$od_id?>"> 
<input type="hidden" name="PRODUCTTYPE" value="2"> <!-- 상품구분(1: 디지털, 2: 실물) -->
<input type="hidden" name="AMOUNT" value="<?=$tot_price?>"> <!-- 결제금액 -->
<input type="hidden" name="PRODUCTNAME" value="<?php echo cut_str($product_nm,20); ?>"> <!-- 상품명 -->
<input type="hidden" name="PRODUCTCODE" value="<?php echo cut_str($product_cd, 10); ?>"> <!-- 상품코드 -->	
<input type="hidden" name="USERID" value="<?php $t = $member['id']; echo $t?$t:'guest'; ?>"> <!-- 고객 ID -->	
<!-- 선택 값-->
<input type="hidden" name="EMAIL" value="<?=$od['email']?>"> <!-- 고객 이메일 -->	
<input type="hidden" name="USERNAME" value="<?php echo cut_str($od['name'], 10); ?>"> <!-- 고객명 -->	
<input type="hidden" name="RESERVEDINDEX1" value=""> <!-- 예약항목 예약어1-->	
<input type="hidden" name="RESERVEDINDEX2" value=""> <!-- 예약항목 예약어2-->	
<input type="hidden" name="RESERVEDSTRING" value=""> <!-- 예약스트링 예약어2-->	
<input type="hidden" name="RETURNURL" value=""> <!-- 결제 성공 후, 이동할 URL(새 창)-->
<input type="hidden" name="HOMEURL" value="<?php echo $receive_url; ?>"> <!-- 결제 성공 후, 이동할 URL(결제 창에서 이동)-->
<input type="hidden" name="DIRECTRESULTFLAG" value=""> <!-- 키움페이 결제 완료 창 없이 HOMEURL로 바로 이동(Y/N)-->	
<input type="hidden" name="SET_LOGO" value=""> <!-- 결제 창 하단 상점로고 URL-->
<!-- 웹뷰결제창(필수)-->
<input type="hidden" name="CLOSEURL" value=""> <!-- 결제 창에서 취소 누를 시 이동할 URL-->	
<input type="hidden" name="FAILURL" value=""> <!-- 결제실패 후, 확인버튼 입력 시 이동할 URL-->	
<input type="hidden" name="APPURL" value=""> <!-- 인증완료 후 돌아갈 앱의 URL(CARD, CARDK, BANK 필수)-->
<!-- 신용카드(필수)-->
<input type="hidden" name="TAXFREECD" value="00"> <!-- 과세/비과세여부(00: 과세, 01: 비과세, 02: 복합과세)-->
<input type="hidden" name="TELNO2" value=""> <!-- 비과세 대상금액(TAXFREECD가 02인 경우에만 필수전송)-->
<input type="hidden" name="CERTTYPE" value="00"> <!-- CARD-SUGI, CARDK-SUGI인 경우 필수(00:카드번호/유효기간)(11:카드번호/유효기간/생년월일/비밀번호앞2자리)-->

<!-- 신용카드(선택)-->
<input type="hidden" name="CPQUOTA" value=""> <!-- CARD, CARD-SUGI인 경우 최대 할부 개월 수(구분자 “ : “) (일시불~12개월 예: 00:02:03:04~~~:12)-->
<input type="hidden" name="QUOTAOPT" value=""> <!-- CARDK, CARDK-SUGI인 경우 최대 할부 개월 수 (일시불~12개월 예: 12)-->
<input type="hidden" name="CARDLIST" value=""> <!-- CARD, CARDK인 경우 결제 창 카드사 노출 값(구분자 “ : “)(카드사코드는 매뉴얼 하단참고)-->
<input type="hidden" name="HIDECARDLIST" value=""> <!-- CARD, CARDK인 경우 결제 창 카드사 숨김 값(구분자 “ : “)(카드사코드는 매뉴얼 하단참고)-->
<input type="hidden" name="AUTOINFOFLAG" value=""> <!-- CARD-BATCH, CARDK-BATCH인 경우 AUTOKEY 전송방식(A~D타입 메뉴얼참고)-->
<!-- 휴대폰(선택)-->
<input type="hidden" name="MOBILECOMPANYLIST" value=""><!-- MOBILE,MOBILE-BATCH 결제 창 통신사만 노출 값(구분자 “ , “)통신사 코드: SKT/KTF/LGT/CJH/KCT-->
<!-- 계좌이체(선택)-->
<input type="hidden" name="USERSSN" value=""> <!-- 생년월일(YYMMDD)-->
<input type="hidden" name="CHECKSSNYN" value=""> <!-- USERSSN사용여부(Y/N)(계좌이체 결제자와 명의자(USERSSN)가 동일한 경우만 결제가능)-->
<input type="hidden" name="DEPOSITENDDATE" value=""> <!-- 입금만료일(YYYYMMDD24MISS)(미지정 기본값 7일)-->
<input type="hidden" name="RECEIVERNAME" value=""> <!-- 수취인명(지정하지 않으면 업체명)-->
</form>
</BODY>
</HTML>
