<?php
define('_NEWWIN_', true);
include_once('./_common.php');
include_once(TB_ADMIN_PATH."/admin_access.php");

$tb['title'] = "회원정보수정";
include_once(TB_ADMIN_PATH."/admin_head.php");

$mb = get_member($mb_id);
$pt = get_partner($mb_id);
?>

<form name="fmemberform" id="fmemberform" action="./pop_memberformupdate.php" method="post">
<input type="hidden" name="mb_id" value="<?php echo $mb_id; ?>">

<div id="memberform_pop" class="new_win">
	<h1><?php echo $tb['title']; ?></h1>

	<section class="new_win_desc marb50">

	<ul class="anchor">
		<li><a href="./pop_memberform.php?mb_id=<?php echo $mb_id; ?>">회원정보수정</a></li>
		<?php if(is_seller($mb_id)) { ?>
		<li><a href="./pop_sellerform.php?mb_id=<?php echo $mb_id; ?>">공급사정보수정</a></li>
		<li><a href="./pop_sellerorder.php?mb_id=<?php echo $mb_id; ?>">공급사판매내역</a></li>
		<?php } ?>
		<li><a href="./pop_memberorder.php?mb_id=<?php echo $mb_id; ?>">주문내역</a></li>
		<li><a href="./pop_memberpoint.php?mb_id=<?php echo $mb_id; ?>">포인트내역</a></li>
	</ul>

	<div class="tbl_frm01">
		<table class="tablef">
		<colgroup>
			<col class="w130">
			<col>
			<col class="w130">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">회원명</th>
			<td><input type="text" name="name" value="<?php echo $mb['name']; ?>" required itemname="회원명" class="frm_input required"><?php if($mb['intercept_date']) { ?> <strong class="fc_red">[차단된회원]</strong><?php } ?></td>
			<th scope="row">아이디</th>
			<td><?php echo $mb['id']; ?></td>
		</tr>
		<tr>
			<th scope="row">패스워드</th>
			<td><input type="text" name="passwd" value="" class="frm_input"></td>
			<th scope="row">추천인아이디</th>
			<td><input type="text" name="pt_id" value="<?php echo $mb['pt_id']; ?>" required memberid itemname="추천인아이디" class="frm_input required"></td>
		</tr>
		<tr>
			<th scope="row">생년월일</th>
			<td>
				<input type="text" name="birth_year" value="<?php echo $mb['birth_year']; ?>" class="frm_input" size="8"> -
				<input type="text" name="birth_month" value="<?php echo $mb['birth_month']; ?>" class="frm_input" size="5"> -
				<input type="text" name="birth_day" value="<?php echo $mb['birth_day']; ?>" class="frm_input" size="5">
			</td>
			<th scope="row">E-Mail</th>
			<td><input type="text" name="email" value="<?php echo $mb['email']; ?>" email itemname="E-Mail" class="frm_input" size="30"></td>
		</tr>
		<tr>
			<th scope="row">전화번호</th>
			<td><input type="text" name="telephone" value="<?php echo $mb['telephone']; ?>" class="frm_input"></td>
			<th scope="row">휴대전화</th>
			<td><input type="text" name="cellphone" value="<?php echo $mb['cellphone']; ?>" class="frm_input"></td>
		</tr>
		<tr>
			<th scope="row">주소</th>
			<td colspan="3">
				<input type="text" name="zip" value="<?php echo $mb['zip']; ?>" class="frm_input" size="8" maxlength="5"> <a href="javascript:win_zip('fmemberform', 'zip', 'addr1', 'addr2', 'addr3', 'addr_jibeon');" class="btn_small grey">주소검색</a>
				<p class="mart5"><input type="text" name="addr1" value="<?php echo $mb['addr1']; ?>" class="frm_input" size="60"> 기본주소</p>
				<p class="mart5"><input type="text" name="addr2" value="<?php echo $mb['addr2']; ?>" class="frm_input" size="60"> 상세주소</p>
				<p class="mart5"><input type="text" name="addr3" value="<?php echo $mb['addr3']; ?>" class="frm_input" size="60"> 참고항목
				<input type="hidden" name="addr_jibeon" value="<?php echo $mb['addr_jibeon']; ?>"></p>
			</td>
		</tr>
		<tr>
			<th scope="row">성별</th>
			<td>
				<select name="gender">
					<?php echo option_selected('',  $mb['gender'], '선택'); ?>
					<?php echo option_selected('M', $mb['gender'], '남자'); ?>
					<?php echo option_selected('F', $mb['gender'], '여자'); ?>
				</select>
			</td>
			<th scope="row">음력/양력</th>
			<td>
				<select name="birth_type">
					<?php echo option_selected('',  $mb['birth_type'], '선택'); ?>
					<?php echo option_selected('S', $mb['birth_type'], '양력'); ?>
					<?php echo option_selected('L', $mb['birth_type'], '음력'); ?>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row">회원레벨</th>
			<td>
				<?php echo get_member_select("mb_grade", $mb['grade']); ?>
			</td>
			<th scope="row">포인트</th>
			<td>
				<b><?php echo number_format($mb['point']); ?></b> Point
				<a href="<?php echo TB_ADMIN_URL; ?>/member/member_point_req.php?mb_id=<?php echo $mb_id; ?>" onclick="win_open(this,'pop_point_req','600','500','yes');return false;" class="btn_small grey marl10">강제적립</a>
			</td>
		</tr>
		<tr class="mb_adm_fld">
			<th scope="row">부운영자 접근허용</th>
			<td colspan="3">
				<div class="sub_frm02">
					<table>
					<tr>
						<?php for($i=0; $i<5; $i++) { $k = ($i+1); ?>
						<td><input id="auth_<?php echo $k; ?>" type="checkbox" name="auth_<?php echo $k; ?>" value="1" <?php echo get_checked($mb['auth_'.$k], '1'); ?>> <label for="auth_<?php echo $k; ?>"><?php echo $gw_auth[$i]; ?></label></td>
						<?php } ?>
					</tr>
					<tr>
						<?php for($i=5; $i<10; $i++) { $k = ($i+1); ?>
						<td><input id="auth_<?php echo $k; ?>" type="checkbox" name="auth_<?php echo $k; ?>" value="1" <?php echo get_checked($mb['auth_'.$k], '1'); ?>> <label for="auth_<?php echo $k; ?>"><?php echo $gw_auth[$i]; ?></label></td>
						<?php } ?>
					</tr>
					</table>
				</div>
			</td>
		</tr>
		<tr class="pt_pay_fld">
			<th scope="row" class="fc_red">추가 판매수수료</th>
			<td colspan="3">
				<input type="text" name="payment" value="<?php echo $mb['payment']; ?>" class="frm_input" size="10">
				<select name="payflag">
					<?php echo option_selected('0', $mb['payflag'], '%'); ?>
					<?php echo option_selected('1', $mb['payflag'], '원'); ?>
				</select>
				(판매수수료를 개별적으로 추가적립 하실 수 있습니다)
			</td>
		</tr>
		<tr class="pt_pay_fld">
			<th scope="row" class="fc_red">은행명</th>
			<td><input type="text" name="bank_name" value="<?php echo $pt['bank_name']; ?>" class="frm_input"></td>
			<th scope="row" class="fc_red">계좌번호</th>
			<td><input type="text" name="bank_account" value="<?php echo $pt['bank_account']; ?>" class="frm_input" size="30"></td>
		</tr>
		<tr class="pt_pay_fld">
			<th scope="row" class="fc_red">예금주명</th>
			<td colspan="3"><input type="text" name="bank_holder" value="<?php echo $pt['bank_holder']; ?>" class="frm_input"></td>
		</tr>
		<tr class="pt_pay_fld">
			<th scope="row" class="fc_197">PC 쇼핑몰스킨</th>
			<td>
				<?php echo get_theme_select('theme', $mb['theme']); ?>
			</td>
			<th scope="row" class="fc_197">모바일 쇼핑몰스킨</th>
			<td>
				<?php echo get_mobile_theme_select('mobile_theme', $mb['mobile_theme']); ?>
			</td>
		</tr>
		<tr class="pt_pay_fld">
			<th scope="row" class="fc_197">개별 PG결제 허용</th>
			<td class="bo_label">
                <label><input type="checkbox" name="use_pg" value="1"<?php echo get_checked($mb['use_pg'], '1'); ?>> 승인<span>(본사지정)</span></label>
                &nbsp;&nbsp;
                <label><input type="checkbox" name="use_pg_rnd" value="1"<?php echo get_checked($mb['use_pg_rnd'], '1'); ?>> 연구비카드</label>
                &nbsp;&nbsp;
                <label><input type="checkbox" name="use_pg_long" value="1"<?php echo get_checked($mb['use_pg_long'], '1'); ?>> 장기무이자</label>
            </td>
			<th scope="row" class="fc_197">개별 상품판매 허용</th>
			<td class="bo_label"><label><input type="checkbox" name="use_good" value="1"<?php echo get_checked($mb['use_good'], '1'); ?>> 승인</b><span>(본사지정)</span></label></td>
		</tr>
		<tr class="pt_pay_fld">
			<th scope="row" class="fc_197">개별 도메인</th>
			<td colspan="3">
				<span class="sitecode">www.</span><label><input type="text" name="homepage" value="<?php echo $mb['homepage']; ?>" class="frm_input"></label>
				단독서버인경우만 입력하세요. 예시) sample.com
			</td>
		</tr>
		<tr>
			<th scope="row">메일수신</th>
			<td><?php echo $mb['mailser']; ?></td>
			<th scope="row">SMS수신</th>
			<td><?php echo $mb['smsser']; ?></td>
		</tr>
		<tr>
			<th scope="row">가입일시</th>
			<td><?php echo $mb['reg_time']; ?></td>
			<th scope="row">최후아이피</th>
			<td><?php echo $mb['login_ip']; ?></td>
		</tr>
		<tr>
			<th scope="row">로그인횟수</th>
			<td><?php echo number_format($mb['login_sum']); ?> 회</td>
			<th scope="row">마지막로그인</th>
			<td><?php echo (!is_null_time($mb['today_login'])) ? $mb['today_login'] : ''; ?></td>
		</tr>
		<tr>
			<th scope="row">구매횟수</th>
			<td><?php echo number_format(shop_count($mb['id'])); ?> 회</td>
			<th scope="row">총구매금액</th>
			<td><?php echo number_format(shop_price($mb['id'])); ?> 원</td>
		</tr>
		<tr>
			<th scope="row">접근차단일자</th>
			<td colspan="3">
				<input type="text" name="intercept_date" value="<?php echo $mb['intercept_date']; ?>" id="intercept_date" class="frm_input" size="10" maxlength="8">
				<input type="checkbox" value="<?php echo date("Ymd"); ?>" id="mb_intercept_date_set_today" onclick="if(this.form.intercept_date.value==this.form.intercept_date.defaultValue) { this.form.intercept_date.value=this.value; } else {
this.form.intercept_date.value=this.form.intercept_date.defaultValue; }">
				<label for="mb_intercept_date_set_today">접근차단일을 오늘로 지정</label>
			</td>
		</tr>
		<tr>
			<th scope="row">관리자메모</th>
			<td colspan="3"><textarea name="memo" class="frm_textbox" rows="3"><?php echo $mb['memo']; ?></textarea></td>
		</tr>
		</tbody>
		</table>
	</div>

	<div class="btn_confirm">
		<input type="submit" value="저장" class="btn_medium" accesskey="s">
		<button type="button" class="btn_medium bx-red" onclick="member_leave();">탈퇴</button>
		<button type="button" class="btn_medium bx-white" onclick="window.close();">닫기</button>
	</div>
	</section>
</div>
</form>

<script>
function member_leave() {
    if(confirm("영구 탈퇴처리 하시겠습니까?\n한번 삭제된 데이터는 복구 불가능합니다.")) {
        var token = get_ajax_token();
        if(!token) {
            alert("토큰 정보가 올바르지 않습니다.");
            return false;
        }
        location.href = "./member_delete.php?mb_id=<?php echo $mb_id; ?>&token="+token;
        return true;
    } else {
        return false;
    }
}

$(function() {
    $(".pt_pay_fld").hide();
	$(".mb_adm_fld").hide();
	<?php if(is_partner($mb[id])) { ?>
    $(".pt_pay_fld").show();
    <?php } ?>
	<?php if($mb[grade] == 1) { ?>
    $(".mb_adm_fld").show();
    <?php } ?>
	$("#mb_grade").on("change", function() {
		$(".pt_pay_fld:visible").hide();
		$(".mb_adm_fld:visible").hide();
        var level = $(this).val();
		if(level >= 2 && level <= 6) {
			$(".pt_pay_fld").show();
		} else if(level == 1) {
			$(".mb_adm_fld").show();
		}
    });
});
</script>

<?php
include_once(TB_ADMIN_PATH."/admin_tail.sub.php");
?>