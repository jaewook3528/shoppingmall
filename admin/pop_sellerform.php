<?php
define('_NEWWIN_', true);
include_once('./_common.php');
include_once(TB_ADMIN_PATH."/admin_access.php");

$tb['title'] = "공급사정보수정";
include_once(TB_ADMIN_PATH."/admin_head.php");

$mb = get_member($mb_id);
$sr = get_seller($mb_id);
?>

<form name="fsellerform" id="fsellerform" action="./pop_sellerformupdate.php" method="post">
<input type="hidden" name="mb_id" value="<?php echo $mb_id; ?>">
<input type="hidden" name="seller_code" value="<?php echo $sr['seller_code']; ?>">

<div id="sellerform_pop" class="new_win">
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

	<h3 class="anc_tit">사업자 정보</h3>
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
			<th scope="row">입점 승인상태</th>
			<td class="td_label">
				<?php echo radio_checked('state', $sr['state'], '1', '승인'); ?>
				<?php echo radio_checked('state', $sr['state'], '0', '대기'); ?>
			</td>
			<th scope="row">전체 상품진열</th>
			<td class="td_label">
				<?php echo radio_checked('seller_open', $sr['seller_open'], '1', '진열'); ?>
				<?php echo radio_checked('seller_open', $sr['seller_open'], '2', '품절'); ?>
				<?php echo radio_checked('seller_open', $sr['seller_open'], '3', '단종'); ?>
				<?php echo radio_checked('seller_open', $sr['seller_open'], '4', '중지'); ?>
			</td>
		</tr>
		<tr>
			<th scope="row">공급사명</th>
			<td><input type="text" name="company_name" value="<?php echo $sr['company_name']; ?>" class="frm_input" size="30"></td>
			<th scope="row">제공상품</th>
			<td><input type="text" name="seller_item" value="<?php echo $sr['seller_item']; ?>" class="frm_input" size="30"></td>
		</tr>
		<tr>
			<th scope="row">대표자명</th>
			<td><input type="text" name="company_owner" value="<?php echo $sr['company_owner']; ?>" class="frm_input" size="30"></td>
			<th scope="row">사업자등록번호</th>
			<td><input type="text" name="company_saupja_no" value="<?php echo $sr['company_saupja_no']; ?>" class="frm_input" size="30"></td>
		</tr>
		<tr>
			<th scope="row">업태</th>
			<td><input type="text" name="company_item" value="<?php echo $sr['company_item']; ?>" class="frm_input" size="30"></td>
			<th scope="row">종목</th>
			<td><input type="text" name="company_service" value="<?php echo $sr['company_service']; ?>" class="frm_input" size="30"></td>
		</tr>
		<tr>
			<th scope="row">전화번호</th>
			<td><input type="text" name="company_tel" value="<?php echo $sr['company_tel']; ?>" class="frm_input" size="30"></td>
			<th scope="row">팩스번호</th>
			<td><input type="text" name="company_fax" value="<?php echo $sr['company_fax']; ?>" class="frm_input" size="30"></td>
		</tr>
		<tr>
			<th scope="row">사업장주소</th>
			<td colspan="3">
				<input type="text" name="company_zip" value="<?php echo $sr['company_zip']; ?>" class="frm_input" size="8" maxlength="5"> <a href="javascript:win_zip('fsellerform', 'company_zip', 'company_addr1', 'company_addr2', 'company_addr3', 'company_addr_jibeon');" class="btn_small grey">주소검색</a>
				<p class="mart5"><input type="text" name="company_addr1" value="<?php echo $sr['company_addr1']; ?>" class="frm_input" size="60"> 기본주소</p>
				<p class="mart5"><input type="text" name="company_addr2" value="<?php echo $sr['company_addr2']; ?>" class="frm_input" size="60"> 상세주소</p>
				<p class="mart5"><input type="text" name="company_addr3" value="<?php echo $sr['company_addr3']; ?>" class="frm_input" size="60"> 참고항목
				<input type="hidden" name="company_addr_jibeon" value="<?php echo $sr['company_addr_jibeon']; ?>"></p>
			</td>
		</tr>
		<tr>
			<th scope="row">홈페이지</th>
			<td colspan="3"><input type="text" name="company_hompage" value="<?php echo $sr['company_hompage']; ?>" class="frm_input" size="40" placeholder="http://"></td>
		</tr>
		<tr>
			<th scope="row">전달사항</th>
			<td colspan="3"><textarea name="memo" class="frm_textbox wfull"><?php echo $sr['memo']; ?></textarea></td>
		</tr>
		</tbody>
		</table>
	</div>

	<h3 class="anc_tit mart30">정산계좌 정보</h3>
	<div class="tbl_frm01">
		<table class="tablef">
		<colgroup>
			<col class="w130">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">은행명</th>
			<td><input type="text" name="bank_name" value="<?php echo $sr['bank_name']; ?>" class="frm_input" size="30"></td>
		</tr>
		<tr>
			<th scope="row">계좌번호</th>
			<td><input type="text" name="bank_account" value="<?php echo $sr['bank_account']; ?>" class="frm_input" size="30"></td>
		</tr>
		<tr>
			<th scope="row">예금주명</th>
			<td><input type="text" name="bank_holder" value="<?php echo $sr['bank_holder']; ?>" class="frm_input" size="30"></td>
		</tr>
		</tbody>
		</table>
	</div>

	<h3 class="anc_tit mart30">담당자 정보</h3>
	<div class="tbl_frm01">
		<table class="tablef">
		<colgroup>
			<col class="w130">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">담당자명</th>
			<td><input type="text" name="info_name" value="<?php echo $sr['info_name']; ?>" class="frm_input" size="30"></td>
		</tr>
		<tr>
			<th scope="row">담당자 핸드폰</th>
			<td><input type="text" name="info_tel" value="<?php echo $sr['info_tel']; ?>" class="frm_input" size="30"></td>
		</tr>
		<tr>
			<th scope="row">담당자 이메일</th>
			<td><input type="text" name="info_email" value="<?php echo $sr['info_email']; ?>" class="frm_input" size="30"></td>
		</tr>
		</tbody>
		</table>
	</div>
	<div class="btn_confirm">
		<input type="submit" value="저장" class="btn_medium" accesskey="s">
		<button type="button" class="btn_medium bx-white" onclick="window.close();">닫기</button>
	</div>
	</section>
</div>
</form>

<?php
include_once(TB_ADMIN_PATH."/admin_tail.sub.php");
?>