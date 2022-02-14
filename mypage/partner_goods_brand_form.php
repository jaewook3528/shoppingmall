<?php
if(!defined('_TUBEWEB_')) exit;

if(!$pf_auth_good)
	alert('개별 상품판매 권한이 있어야만 이용 가능합니다.');

$pg_title = "브랜드 수정";
include_once("./admin_head.sub.php");

if($w == "u") {
	$br = sql_fetch("select * from shop_brand where br_id='$br_id'");
    if(!$br['br_id'])
        alert("자료가 존재하지 않습니다.");

	if($br['mb_id'] != $member['id']) {
		alert("자신이 등록한 브랜드만 수정하실 수 있습니다.");
	}
}
?>

<form name="fregform" method="post" action="./partner_goods_brand_form_update.php" enctype="MULTIPART/FORM-DATA">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
<input type="hidden" name="stx" value="<?php echo $stx; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="br_id" value="<?php echo $br_id; ?>">

<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">브랜드명 (KOR)</th>
		<td><input type="text" name="br_name" value="<?php echo $br['br_name']; ?>" required itemname="브랜드명 (KOR)" class="required frm_input w200"></td>
	</tr>
	<tr>
		<th scope="row">브랜드명 (ENG)</th>
		<td><input type="text" name="br_name_eng" value="<?php echo $br['br_name_eng']; ?>" class="frm_input w200"></td>
	</tr>
	<tr>
		<th scope="row">브랜드 URL</th>
		<td><input type="text" value="/shop/brandlist.php?br_id=<?php echo $br_id; ?>" readonly class="frm_input list1 w300"> <a href="/shop/brandlist.php?br_id=<?php echo $br_id; ?>" target="_blank" class="btn_small grey">브랜드 바로가기</a></td>
	</tr>
	<tr>
		<th scope="row">브랜드로고</th>
		<td>
			<input type="file" name="br_logo" id="br_logo"> 사이즈(128픽셀 * 40픽셀)
			<?php
			$file = TB_DATA_PATH.'/brand/'.$br['br_logo'];
			if(is_file($file) && $br['br_logo']) {
				$br_logo = rpc($file, TB_PATH, TB_URL);
			?>
			<input type="checkbox" name="br_logo_del" value="<?php echo $br['br_logo']; ?>" id="br_logo_del">
			<label for="br_logo_del">삭제</label>
			<div class="banner_or_img"><img src="<?php echo $br_logo; ?>"></div>
			<?php } ?>
		</td>
	</tr>
	<tr>
		<th scope="row">등록일</th>
		<td><?php echo $br['br_time']; ?></td>
	</tr>
	<?php if(!is_null_time($br['br_updatetime'])) { ?>
	<tr>
		<th scope="row">최근 수정일</th>
		<td><?php echo $br['br_updatetime']; ?></td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
	<a href="./page.php?code=partner_goods_brand_list<?php echo $qstr; ?>&page=<?php echo $page; ?>" class="btn_large bx-white">목록</a>
</div>
</form>

<?php
include_once("./admin_tail.sub.php");
?>