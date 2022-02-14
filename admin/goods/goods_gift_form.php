<?php
if(!defined('_TUBEWEB_')) exit;

if($w == "") {
	$gr_id = substr(strtoupper(uniqid()),-12);

} else if($w == "u") {
	$group = sql_fetch("select * from shop_gift_group where gr_id = '$gr_id'");
    if(!$group['gr_id'])
        alert("존재하지 않은 쿠폰 입니다.");
}

include_once(TB_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$frm_submit = '<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
	<a href="./goods.php?code=gift'.$qstr.'&page='.$page.'" class="btn_large bx-white">목록</a>'.PHP_EOL;
if($w == 'u') {
	$frm_submit .= '<a href="./goods.php?code=gift_form" class="btn_large bx-red">추가</a>'.PHP_EOL;
}
$frm_submit .= '</div>';
?>

<form name="fregform" method="post" action="./goods/goods_gift_form_update.php" autocomplete="off">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="q1" value="<?php echo $qstr; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="gr_id" value="<?php echo $gr_id; ?>">

<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">일련번호</th>
		<td><input type="text" name="gr_id" value="<?php echo $gr_id; ?>" class="frm_input" readonly style="background-color:#ddd"></td>
	</tr>
	<tr>
		<th scope="row">쿠폰명</th>
		<td><input type="text" name="gr_subject" value="<?php echo $group['gr_subject']; ?>" required itemname="쿠폰명" class="frm_input required" size="60"></td>
	</tr>
	<tr>
		<th scope="row">설명</b></th>
		<td><input type="text" name="gr_explan" value="<?php echo $group['gr_explan']; ?>" class="frm_input" size="60"></td>
	</tr>
	<tr>
		<th scope="row">발행금액</th>
		<td><input type="text" name="gr_price" value="<?php echo $group['gr_price']; ?>"
		required itemname="발행금액" class="frm_input required w80"> 원</td>
	</tr>
	<?php if($w == "")  {?>
	<tr>
		<th scope="row">발행매수</th>
		<td><input type="text" name="gr_quant" value="<?php echo $group['gr_quant']; ?>"<?php echo ($w == "")?' required':''?> class="frm_input<?php echo ($w == "")?' required':''?> w80"> 매</td>
	</tr>
	<?php } ?>
	<tr>
		<th scope="row">사용기간</th>
		<td>
			<input type="text" name="gr_sdate" value="<?php echo $group['gr_sdate']; ?>" id="gr_sdate" required class="frm_input required w80"> ~
			<input type="text" name="gr_edate" value="<?php echo $group['gr_edate']; ?>" id="gr_edate" required class="frm_input required w80">
		</td>
	</tr>
	<?php if($w == "") { ?>
	<tr>
		<th scope="row">발행방식</th>
		<td class="td_label">
			<label><input type="radio" name="use_gift" value="0" checked> 숫자로만 발행</label>
			<label><input type="radio" name="use_gift" value="1"> 영문, 숫자 혼합해서 발행</label>
		</td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
</div>

<?php echo $frm_submit; ?>
</form>

<script>
$(function(){
	// 날짜 검색 : TODAY MAX값으로 인식 (maxDate: "+0d")를 삭제하면 MAX값 해제
	$("#gr_sdate, #gr_edate").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99" });
});
</script>