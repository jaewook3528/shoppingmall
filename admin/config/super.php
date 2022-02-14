<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form name="fregform" method="post" action="./config/super_update.php">
<input type="hidden" name="token" value="">

<h2>상점 관리에 사용될 비밀번호</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">관리자 비밀번호</th>
		<td>
			<input type="text" name="passwd" class="frm_input">
			<?php echo help("비밀번호는 되도록 영,숫자를 같이 사용하시는 것이 좋습니다.<br>비밀번호는 상점 관리에 매우 중요하므로 상점 관리자외 정보유출을 주의하시고 정기적으로 비밀번호를 변경하세요."); ?>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<h2>상점 관리에 사용될 필수정보</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">회원명</th>
		<td><input type="text" name="name" value="<?php echo $super['name']; ?>" required itemname="회원명" class="frm_input required"></td>
	</tr>
	<tr>
		<th scope="row">이메일주소</th>
		<td>
			<input type="text" name="email" value="<?php echo $super['email']; ?>" required email itemname="이메일" class="frm_input required" size="30">
			<?php echo help("회원 메일발송시 사용되므로 실제 사용중인 메일주소를 입력하세요."); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">핸드폰</th>
		<td><input type="text" name="cellphone" value="<?php echo $super['cellphone']; ?>" required itemname="핸드폰" class="frm_input required"></td>
	</tr>
	<tr>
		<th scope="row">주소</th>
		<td>
			<input type="text" name="zip" value="<?php echo $super['zip']; ?>" class="frm_input" size="8" maxlength="5"> <a href="javascript:win_zip('fregform', 'zip', 'addr1', 'addr2', 'addr3', 'addr_jibeon');" class="btn_small grey">주소검색</a>
			<p class="mart5">
				<input type="text" name="addr1" value="<?php echo $super['addr1']; ?>" class="frm_input" size="60"> 기본주소
			</p>
			<p class="mart5">
				<input type="text" name="addr2" value="<?php echo $super['addr2']; ?>" class="frm_input" size="60"> 상세주소
			</p>
			<p class="mart5">
				<input type="text" name="addr3" value="<?php echo $super['addr3']; ?>" class="frm_input" size="60"> 참고항목
				<input type="hidden" name="addr_jibeon" value="<?php echo $super['addr_jibeon']; ?>">
			</p>
		</td>
	</tr>
	<tr>
		<th scope="row">이메일 수신</th>
		<td>
			<select name="mailser">
				<?php echo option_selected('Y', $super['mailser'], '수신함'); ?>
				<?php echo option_selected('N', $super['mailser'], '수신안함'); ?>
			</select>
		</td>
	</tr>
	<tr>
		<th scope="row">SMS 수신</th>
		<td>
			<select name="smsser">
				<?php echo option_selected('Y', $super['smsser'], '수신함'); ?>
				<?php echo option_selected('N', $super['smsser'], '수신안함'); ?>
			</select>
		</td>
	</tr>
	<tr>
		<th scope="row">포인트</th>
		<td>
			<b><?php echo number_format($super['point']); ?></b> Point
			<a href="<?php echo TB_ADMIN_URL; ?>/member/member_point_req.php?mb_id=<?php echo $super['id']; ?>" onclick="win_open(this,'pop_point_req','450','450','no');return false" class="btn_small grey marl10">강제적립</a>
		</td>
	</tr>
	<tr>
		<th scope="row">최후아이피</th>
		<td><?php echo $super['login_ip']; ?></td>
	</tr>
	<tr>
		<th scope="row">로그인횟수</th>
		<td><?php echo number_format($super['login_sum']); ?> 회</td>
	</tr>
	<tr>
		<th scope="row">마지막로그인</th>
		<td><?php echo (!is_null_time($super['today_login'])) ? $super['today_login'] : ''; ?></td>
	</tr>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
</div>
</form>
