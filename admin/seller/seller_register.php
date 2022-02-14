<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form name="fregform" method="post" onsubmit="return fregform_submit(this);">
<input type="hidden" name="token" value="">

<h2>사업자 정보</h2>
<div class="tbl_frm01">
	<table class="tablef">
	<colgroup>
		<col class="w140">
		<col>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">회원선택</th>
		<td colspan="3">
			<select name="mb_id" required>
				<option value="">선택하세요</option>
				<?php
				$sql = "select * from shop_member where grade <> '1' and supply = '' order by name ";
				$res = sql_query($sql);
				while($row=sql_fetch_array($res)){
					$sr = get_seller($row['id'], 'mb_id');
					if($sr['mb_id']) continue;

					echo "<option value='{$row[id]}'>[Lv.{$row[grade]}] {$row[name]} ($row[id])</option>\n";
				}
				?>
			<select>
			<a href="./seller/seller_reglist.php" onclick="win_open(this,'seller_reglist','550','500','1'); return false" class="btn_small grey">선택</a>
		</td>
	</tr>
	<tr>
		<th scope="row">공급사명</th>
		<td><input type="text" name="company_name" required itemname="공급사명" class="required frm_input" size="30"></td>
		<th scope="row">제공상품</th>
		<td><input type="text" name="seller_item" required itemname="제공상품" class="required frm_input" size="30" placeholder="예) 가전제품"></td>
	</tr>
	<tr>
		<th scope="row">대표자명</th>
		<td><input type="text" name="company_owner" required itemname="대표자명" class="required frm_input" size="30"></td>
		<th scope="row">사업자등록번호</th>
		<td><input type="text" name="company_saupja_no" class="frm_input" size="30" placeholder="예) 000-00-00000"></td>
	</tr>
	<tr>
		<th scope="row">업태</th>
		<td><input type="text" name="company_item" class="frm_input" size="30" placeholder="예) 서비스업"></td>
		<th scope="row">종목</th>
		<td><input type="text" name="company_service" class="frm_input" size="30" placeholder="예) 전자상거래업"></td>
	</tr>
	<tr>
		<th scope="row">전화번호</th>
		<td><input type="text" name="company_tel" class="frm_input" size="30" placeholder="예) 02-1234-5678"></td>
		<th scope="row">팩스번호</th>
		<td><input type="text" name="company_fax" class="frm_input" size="30" placeholder="예) 02-1234-5678"></td>
	</tr>
	<tr>
		<th scope="row">사업장주소</th>
		<td colspan="3">
			<p><input type="text" name="company_zip" class="frm_input" size="8" maxlength="5"> <a href="javascript:win_zip('fregform', 'company_zip', 'company_addr1', 'company_addr2', 'company_addr3', 'company_addr_jibeon');" class="btn_small grey">주소검색</a></p>
			<p class="mart3"><input type="text" name="company_addr1" class="frm_input" size="60"> 기본주소</p>
			<p class="mart3"><input type="text" name="company_addr2" class="frm_input" size="60"> 상세주소</p>
			<p class="mart3"><input type="text" name="company_addr3" class="frm_input" size="60"> 참고항목
			<input type="hidden" name="company_addr_jibeon" value=""></p>
		</td>
	</tr>
	<tr>
		<th scope="row">홈페이지</th>
		<td colspan="3"><input type="text" name="company_hompage" class="frm_input" size="30" placeholder="http://"></td>
	</tr>
	</tbody>
	</table>
</div>

<h2>정산계좌 정보</h2>
<div class="tbl_frm01">
	<table class="tablef">
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">은행명</th>
		<td><input type="text" name="bank_name" class="frm_input" size="30"></td>
	</tr>
	<tr>
		<th scope="row">계좌번호</th>
		<td><input type="text" name="bank_account" class="frm_input" size="30"></td>
	</tr>
	<tr>
		<th scope="row">예금주명</th>
		<td><input type="text" name="bank_holder" class="frm_input" size="30"></td>
	</tr>
	</tbody>
	</table>
</div>

<h2>담당자 정보</h2>
<div class="tbl_frm01">
	<table class="tablef">
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">담당자명</th>
		<td><input type="text" name="info_name" class="frm_input" size="30"></td>
	</tr>
	<tr>
		<th scope="row">담당자 핸드폰</th>
		<td><input type="text" name="info_tel" class="frm_input" size="30"></td>
	</tr>
	<tr>
		<th scope="row">담당자 이메일</th>
		<td><input type="text" name="info_email" class="frm_input" size="30"></td>
	</tr>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="저장" id="btn_submit" class="btn_large" accesskey="s">
</div>
</form>

<script>
function fregform_submit(f) {
	if(confirm("등록 하시겠습니까?") == false)
		return false;

	document.getElementById("btn_submit").disabled = "disabled";

	f.action = "./seller/seller_register_update.php";
    return true;
}
</script>
