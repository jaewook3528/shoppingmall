<?php
if(!defined('_TUBEWEB_')) exit;

$pg_anchor = <<<EOF
<ul class="anchor">
	<li><a href="#anc_pbasic">기본설정</a></li>
	<li><a href="#anc_panew">기타설정</a></li>
	<li><a href="#anc_pauth">권한설정</a></li>
	<li><a href="#anc_pterm">관리비</a></li>
</ul>
EOF;

$frm_submit = '<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
</div>';
?>

<form name="pform" id="pform" method="post" action="./partner/pt_pformupdate.php">
<input type="hidden" name="token" value="">

<section id="anc_pbasic">
	<h2>기본설정</h2>
	<?php echo $pg_anchor; ?>
	<div class="tbl_frm01">
		<table>
		<colgroup>
			<col class="w180">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row" rowspan="2">상품 판매수수료</th>
			<td>
				<input type="checkbox" name="pf_sale_use" value="1" id="pf_sale_use" 
				<?php echo get_checked($config['pf_sale_use'], "1"); ?>>
				<label for="pf_sale_use">사용함</label>
				<?php echo help('자신의 분양몰에서 상품구매 발생건당 판매수수료를 지급합니다.'); ?>
			</td>
		</tr>
		<tr>
			<td class="bo_label">
				<label><input type="radio" name="pf_sale_flag" value="0"<?php echo get_checked($config['pf_sale_flag'], "0"); ?>> <strong class="fc_red">결제액 - 배송비 - 쿠폰 - 포인트결제액 = 순수결제액</strong> 에서 판매수수료를 배분</label><br>
				<label><input type="radio" name="pf_sale_flag" value="1"<?php echo get_checked($config['pf_sale_flag'], "1"); ?>> <strong class="fc_red">판매가 - 공급가 - 쿠폰 - 포인트결제액 = 마진</strong> 에서 판매수수료를 배분<span>(마진이 없으면 적립되지 않음)</span></label><br>
				<label><input type="radio" name="pf_sale_flag" value="2"<?php echo get_checked($config['pf_sale_flag'], "2"); ?>> <strong class="fc_red">판매가 - 공급가 = 마진</strong> 에서 판매수수료를 배분<span>(쿠폰 및 포인트 사용액은 무시하고 무조건 적립)</span></label>
			</td>
		</tr>
		<tr>
			<th scope="row">분양 추천수수료</th>
			<td>
				<input type="checkbox" name="pf_anew_use" value="1" id="pf_anew_use" 
				<?php echo get_checked($config['pf_anew_use'], "1"); ?>>
				<label for="pf_anew_use">사용함</label>
				<?php echo help('기존 가맹점의 추천으로 다시 쇼핑몰이 분양될경우 추천수수료를 지급합니다.'); ?>
			</td>
		</tr>
		<tr>
			<th scope="row">상점 접속수수료</th>
			<td>
				<input type="checkbox" name="pf_visit_use" value="1" id="pf_visit_use" 
				<?php echo get_checked($config['pf_visit_use'], "1"); ?>>
				<label for="pf_visit_use">사용함</label>
				<?php echo help('블로그, 카페, 웹사이트, SNS등 자신의 광고물을 클릭하여 자신의 분양몰에 접속될경우 건당 접속수수료를 지급합니다.<br><em class="fc_red">단! IP당 하루에 한번만 지급되며 관리자에 항시 모니터링이 필요 합니다.</em>'); ?>
			</td>
		</tr>	
		<tr>
			<th scope="row" class="th_bg"><label for="pf_payment_type">수수료 정산방법</label></th>
			<td>
				<select name="pf_payment_type" id="pf_payment_type">
					<?php echo option_selected("0", $config['pf_payment_type'], '관리자 고정정산'); ?>
					<?php echo option_selected("1", $config['pf_payment_type'], '가맹점 직접출금'); ?>
				</select>
				<?php echo help('<em class="fc_084"><i class="fa fa-exclamation-circle fs11 fc_084"></i> 관리자 고정정산 : </em>관리자가 고정한 날짜에 정산처리 합니다.<br><em class="fc_084"><i class="fa fa-exclamation-circle fs11 fc_084"></i> 가맹점 직접출금 : </em>가맹점이 자율적으로 출금신청을 합니다.', 'lh5'); ?>
				<script>
				$(function() {
					$("#pf_payment_type").on("change", function() {
						$(".use_payment_fld").hide();
						var type = $(this).val();
						if(type == '1') {
							$(".use_payment_fld").show();
						}
					})
					.change();
				});
				</script>
			</td>
		</tr>		
		<tr class="use_payment_fld">
			<th scope="row" class="th_bg"><label for="pf_payment">수수료 출금조건</label></th>
			<td>				
				<input type="text" name="pf_payment" value="<?php echo $config['pf_payment']; ?>" id="pf_payment" class="frm_input" size="8"> 원부터
				<label for="pf_payment_unit" class="sound_only">수수료 출금단위</label>
				<select name="pf_payment_unit" id="pf_payment_unit">
					<?php echo option_selected("10000", $config['pf_payment_unit'], '10000'); ?>
					<?php echo option_selected("1000", $config['pf_payment_unit'], '1000'); ?>
					<?php echo option_selected("100", $config['pf_payment_unit'], '100'); ?>
					<?php echo option_selected("10", $config['pf_payment_unit'], '10'); ?>
					<?php echo option_selected("1", $config['pf_payment_unit'], '1'); ?>
				</select>
				원 단위로 출금신청 가능
			</td>
		</tr>	
		<tr>
			<th scope="row" class="th_bg"><label for="pf_payment_tax">수수료 세액공제</label></th>
			<td>
				<input type="text" name="pf_payment_tax" value="<?php echo $config['pf_payment_tax']; ?>" id="pf_payment_tax" class="frm_input" size="8"> % 를 공제 후 수수료 정산
				<?php echo help('<em class="fc_084"><i class="fa fa-exclamation-circle fs11 fc_084"></i> 실수령액 : </em> = [부가세 - 제세공제(원천징수 3.3%) - 기타 세액공제] 등을 고려해 입력하세요.'); ?>
			</td>
		</tr>
		</tbody>
		</table>
	</div>
</section>

<?php echo $frm_submit; ?>

<section id="anc_panew">
	<h2>가입설정</h2>
	<?php echo $pg_anchor; ?>
	<div class="tbl_frm01">
		<table>
		<colgroup>
			<col class="w180">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">가맹점 모집</th>
			<td>
				<input type="checkbox" name="partner_reg_yes" value="1" id="partner_reg_yes" 
				<?php echo get_checked($config['partner_reg_yes'], "1"); ?>>
				<label for="partner_reg_yes">사용함</label>
				<?php echo help('체크안하면 가맹점 모집을 하실 수 없습니다.'); ?>
			</td>
		</tr>	
		<tr>
			<th scope="row">판매수수료 노출여부</th>
			<td>
				<input type="checkbox" name="pf_payment_yes" value="1" id="pf_payment_yes" 
				<?php echo get_checked($config['pf_payment_yes'], "1"); ?>>
				<label for="pf_payment_yes">사용함</label>
				<?php echo help('가맹점 마이페이지 > 본사상품목록과 상품상세페이지에 노출합니다.'); ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="pf_stipulation">가맹점 이용약관</label></th>
			<td>
				<label for="pf_stipulation_subj" class="sound_only">가맹점 이용약관 제목</label>
				<input type="text" name="pf_stipulation_subj" value="<?php echo $config['pf_stipulation_subj']; ?>" id="pf_stipulation_subj" required class="required frm_input" size="70" placeholder="예) 소프트웨어(쇼핑몰 솔루션) 사용권 계약서/이용약관(EULA)"> 제목
				<div class="mart5"><textarea id="pf_stipulation" name="pf_stipulation" class="frm_textbox" placeholder="해당 쇼핑몰에 맞는 가맹점 이용약관을 입력합니다."><?php echo $config['pf_stipulation']; ?></textarea></div>
				<?php echo help('가맹점 이용약관은 필수입력이므로 반드시 입력하세요.'); ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="pf_regulation">가맹점 규정안내</label></th>
			<td>
				<label for="pf_regulation_subj" class="sound_only">가맹점 규정안내 제목</label>
				<input type="text" name="pf_regulation_subj" value="<?php echo $config['pf_regulation_subj']; ?>" id="pf_regulation_subj" class="frm_input" size="70" placeholder="예) 불법홍보 금지 및 규정안내"> 제목
				<div class="mart5"><textarea id="pf_regulation" name="pf_regulation" class="frm_textbox" placeholder="해당 쇼핑몰에 맞는 가맹점 규정안내를 입력합니다."><?php echo $config['pf_regulation']; ?></textarea></div>
				<?php echo help('가맹점 규정안내 제목이 빈값이면 노출되지 않습니다.'); ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="pf_basedomain">도메인 종류</label></th>
			<td>
				<input type="text" name="pf_basedomain" value="<?php echo $config['pf_basedomain']; ?>" id="pf_basedomain" class="frm_input" size="70">
				<?php echo help('영문 도메인 종류를 입력하세요. | 로 구분 <span class="fc_red">(Shift + \)</span><br>입력 예) sample.com 과 sample.co.kr 은 <span class="fc_red">com|co|kr</span> 점 (.)을 구분으로 입력하세요.'); ?>
			</td>
		</tr>
		</tbody>
		</table>
	</div>
</section>

<?php echo $frm_submit; ?>

<section id="anc_pauth">
	<h2>권한설정</h2>
	<?php echo $pg_anchor; ?>
	<div class="tbl_frm01">
		<table>
		<colgroup>
			<col class="w180">
			<col>
		</colgroup>
		<tbody>	
		<tr>
			<th scope="row"><label for="pf_auth_good">상품판매 권한</label></th>
			<td>
				<select name="pf_auth_good" id="pf_auth_good">
				<?php echo option_selected("1", $config['pf_auth_good'], '개별 상품판매 불가'); ?>
				<?php echo option_selected("2", $config['pf_auth_good'], '개별 상품판매 허용 (전체가맹점)'); ?>
				<?php echo option_selected("3", $config['pf_auth_good'], '개별 상품판매 허용 (본사지정)'); ?>	
				</select>
				<?php echo help('<em class="fc_084"><i class="fa fa-exclamation-circle fs11 fc_084"></i> 개별 상품판매 불가 : </em>본사 상품만 판매가능하며 개별 상품은 판매할 수 없습니다.<br><em class="fc_084"><i class="fa fa-exclamation-circle fs11 fc_084"></i> 개별 상품판매 허용 (전체가맹점) : </em>전체 가맹점 모두 개별 상품을 판매할 수 있습니다.<br><em class="fc_084"><i class="fa fa-exclamation-circle fs11 fc_084"></i> 개별 상품판매 허용 (본사지정) : </em>본사에서 지정한 가맹점만 개별 상품을 판매할 수 있습니다.', 'lh5'); ?>	
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="pf_auth_cgy">카테고리 권한</label></th>
			<td>
				<select name="pf_auth_cgy" id="pf_auth_cgy">
					<?php echo option_selected("1", $config['pf_auth_cgy'], '본사 카테고리 고정'); ?>
					<?php echo option_selected("2", $config['pf_auth_cgy'], '개별 카테고리 허용'); ?>
				</select>
				<?php echo help('<em class="fc_084"><i class="fa fa-exclamation-circle fs11 fc_084"></i> 본사 카테고리 고정 : </em>본사 카테고리를 수정은 할수있지만 개별적으로 생성은 불가합니다.<br><em class="fc_084"><i class="fa fa-exclamation-circle fs11 fc_084"></i> 개별 카테고리 허용 : </em>본사 카테고리 + 개별 카테고리 혼합설정 가능합니다.', 'lh5'); ?>				
			</td>
		</tr>
		<tr>
			<th scope="row"><label for="pf_auth_pg">PG결제연동 권한</label></th>
			<td>
				<select name="pf_auth_pg" id="pf_auth_pg">
					<?php echo option_selected("1", $config['pf_auth_pg'], '본사 PG결제 고정'); ?>				
					<?php echo option_selected("2", $config['pf_auth_pg'], '개별 PG결제 허용 (무조건)'); ?>
					<?php echo option_selected("3", $config['pf_auth_pg'], '개별 PG결제 허용 (본사지정)'); ?>
				</select>
				<?php echo help('<em class="fc_084"><i class="fa fa-exclamation-circle fs11 fc_084"></i> 본사 PG결제 고정 : </em>본사 PG결제 및 입금계좌로 고정 결제 됩니다.<br><em class="fc_084"><i class="fa fa-exclamation-circle fs11 fc_084"></i> 개별 PG결제 허용 (무조건) : </em>전체 가맹점 모두 본사+본인상품 제한없이 무조건 본인 PG결제 및 입금계좌로 결제 됩니다.<br><em class="fc_084"><i class="fa fa-exclamation-circle fs11 fc_084"></i> 개별 PG결제 허용 (본사지정) : </em>본사에서 지정한 가맹점만 본사+본인상품 제한없이 무조건 본인 PG결제 및 입금계좌로 결제 됩니다.', 'lh5'); ?>	
			</td>
		</tr>
		</tbody>
		</table>
	</div>
</section>

<?php echo $frm_submit; ?>

<section id="anc_pterm">	
	<h2>관리비설정</h2>
	<?php echo $pg_anchor; ?>
	<div class="tbl_frm01">
		<table>
		<colgroup>
			<col class="w180">
			<col>
		</colgroup>
		<tbody>			
		<tr>
			<th scope="row">관리비 사용여부</th>
			<td>                
				<input type="checkbox" name="pf_expire_use" value="1" id="pf_expire_use"<?php echo get_checked($config['pf_expire_use'], "1"); ?>>
				<label for="pf_expire_use">사용함</label>
			</td>
		</tr>  
		<tr>
			<th scope="row"><label for="pf_expire_term">관리비 연장기간</label></th>
			<td>
				<input type="text" name="pf_expire_term" value="<?php echo $config['pf_expire_term']; ?>" id="pf_expire_term" class="frm_input" size="8"> 개월 단위
			</td>
		</tr>
		<tr>
			<th scope="row">관리비 미납시</th>
			<td>                
				<input type="checkbox" name="pf_login_no" value="1" id="pf_login_no"<?php echo get_checked($config['pf_login_no'], "1"); ?>>
				<label for="pf_login_no">회원로그인 차단</label>			
				<input type="checkbox" name="pf_account_no" value="1" id="pf_account_no"<?php echo get_checked($config['pf_account_no'], "1"); ?>>
				<label for="pf_account_no">수수료 출금신청 차단</label>					
				<input type="checkbox" name="pf_session_no" value="1" id="pf_session_no"<?php echo get_checked($config['pf_session_no'], "1"); ?>>
				<label for="pf_session_no">운영권한을 본사로 귀속</label>
			</td>
		</tr>
		</tbody>
		</table>
	</div>
</section>

<?php echo $frm_submit; ?>
</form>
