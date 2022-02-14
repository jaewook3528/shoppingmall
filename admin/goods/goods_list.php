<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TB_ADMIN_PATH.'/goods/goods_sub.php');
?>

<form name="fgoodslist" id="fgoodslist" method="post" action="./goods/goods_list_update.php" onsubmit="return fgoodslist_submit(this);">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<div class="local_ov mart30">
	전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회
	<span class="ov_a">
		<select id="page_rows" onchange="location='<?php echo "{$_SERVER['SCRIPT_NAME']}?{$q1}&page=1"; ?>&page_rows='+this.value;">
			<?php echo option_selected('30',  $page_rows, '30줄 정렬'); ?>
			<?php echo option_selected('50',  $page_rows, '50줄 정렬'); ?>
			<?php echo option_selected('100', $page_rows, '100줄 정렬'); ?>
			<?php echo option_selected('150', $page_rows, '150줄 정렬'); ?>
		</select>
	</span>
</div>
<div class="local_frm01">
	<?php echo $btn_frmline; ?>
</div>

<div class="tbl_head02">
	<table id="sodr_list" class="tablef">
	<colgroup>
		<col class="w50">
		<col class="w50">
		<col class="w60">
		<col class="w120">
		<col>
		<col>
		<col class="w80">
		<col class="w80">
		<col class="w90">
		<col class="w90">
		<col class="w90">
		<col class="w90">
		<col class="w60">
		<col class="w60">
		<?php if($member['id'] == 'admin') { // unltd?>
		<col class="w50">
		<?php } ?>
	</colgroup>
	<thead>
	<tr>
		<th scope="col" rowspan="2"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
		<th scope="col" rowspan="2">번호</th>
		<th scope="col" rowspan="2">이미지</th>
		<th scope="col"><?php echo subject_sort_link('a.gcode',$q2); ?>상품코드</a></th>
		<th scope="col" colspan="2"><?php echo subject_sort_link('a.gname',$q2); ?>상품명</a></th>
		<th scope="col"><?php echo subject_sort_link('a.reg_time',$q2); ?>최초등록일</a></th>
		<th scope="col"><?php echo subject_sort_link('a.isopen',$q2); ?>진열</a></th>
		<th scope="col" colspan="4" class="th_bg">가격정보</th>
		<th scope="col" rowspan="2"><?php echo subject_sort_link('a.rank',$q2); ?>순위</a></th>
		<th scope="col" rowspan="2">관리</th>
		<?php if($member['id'] == 'admin') { // unltd?>
		<th scope="col" rowspan="2">장기<br>무이자</th>
		<?php } ?>
	</tr>
	<tr class="rows">
		<th scope="col"><?php echo subject_sort_link('a.mb_id',$q2); ?>업체코드</a></th>
		<th scope="col">공급사명</th>
		<th scope="col">카테고리</th>
		<th scope="col"><?php echo subject_sort_link('a.update_time',$q2); ?>최근수정일</a></th>
		<th scope="col"><?php echo subject_sort_link('a.stock_qty',$q2); ?>재고</a></th>
		<th scope="col" class="th_bg"><?php echo subject_sort_link('a.normal_price',$q2); ?>시중가</a></th>
		<th scope="col" class="th_bg"><?php echo subject_sort_link('a.supply_price',$q2); ?>공급가</a></th>
		<th scope="col" class="th_bg"><?php echo subject_sort_link('a.goods_price',$q2); ?>판매가</a></th>
		<th scope="col" class="th_bg"><?php echo subject_sort_link('a.gpoint',$q2); ?>포인트</a></th>
	</tr>
	</thead>
	<tbody>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$gs_id = $row['index_no'];

		if($row['stock_mod'])
			$stockQty = number_format($row['stock_qty']);
		else
			$stockQty = '<span class="txt_false">무제한</span>';

		$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td rowspan="2">
			<input type="hidden" name="gs_id[<?php echo $i; ?>]" value="<?php echo $gs_id; ?>">
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>">
		</td>
		<td rowspan="2"><?php echo $num--; ?></td>
		<td rowspan="2"><a href="<?php echo TB_SHOP_URL; ?>/view.php?index_no=<?php echo $gs_id; ?>" target="_blank"><?php echo get_it_image($gs_id, $row['simg1'], 40, 40); ?></a></td>
		<td><?php echo $row['gcode']; ?></td>
		<td colspan="2" class="tal"><?php echo get_text($row['gname']); ?></td>
		<td><?php echo substr($row['reg_time'],2,8); ?></td>
		<td><?php echo $gw_isopen[$row['isopen']]; ?></td>
		<td rowspan="2" class="tar"><?php echo number_format($row['normal_price']); ?></td>
		<td rowspan="2" class="tar"><?php echo number_format($row['supply_price']); ?></td>
		<td rowspan="2" class="tar"><?php echo number_format($row['goods_price']); ?></td>
		<td rowspan="2" class="tar"><?php echo number_format($row['gpoint']); ?></td>
		<td rowspan="2"><input type="text" name="rank[<?php echo $i; ?>]" value="<?php echo $row['rank']; ?>" class="frm_input"></td>
		<td rowspan="2"><a href="./goods.php?code=form&w=u&gs_id=<?php echo $gs_id.$qstr; ?>&page=<?php echo $page; ?>&bak=<?php echo $code; ?>" class="btn_small">수정</a></td>
		<?php if($member['id'] == 'admin') { // unltd?>
		<td rowspan="2"><input type="checkbox" name="psys_long_use[<?php echo $i; ?>]" value="1" id="psys_long_use"<?php echo get_checked($row['psys_long_use'], "1"); ?>></td>
		<?php } ?>

	</tr>
	<tr class="<?php echo $bg; ?>">
		<td class="fc_00f"><?php echo $row['mb_id']; ?></td>
		<td class="tal txt_succeed"><?php echo get_seller_name($row['mb_id']); ?></td>
		<td class="tal txt_succeed"><?php echo get_cgy_info($row); ?></td>
		<td class="fc_00f"><?php echo substr($row['update_time'],2,8); ?></td>
		<td><?php echo $stockQty; ?></td>
	</tr>
	<?php
	}
	if($i==0)
		echo '<tr><td colspan="14" class="empty_table">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>
<div class="local_frm02">
	<?php echo $btn_frmline; ?>
</div>
</form>

<?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=');
?>

<script>
function fgoodslist_submit(f)
{
    if(!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }

    return true;
}

$(function(){
	<?php if($sel_ca1) { ?>
	$("select#sel_ca1").val('<?php echo $sel_ca1; ?>');
	categorychange('<?php echo $sel_ca1; ?>', 'sel_ca2');
	<?php } ?>
	<?php if($sel_ca2) { ?>
	$("select#sel_ca2").val('<?php echo $sel_ca2; ?>');
	categorychange('<?php echo $sel_ca2; ?>', 'sel_ca3');
	<?php } ?>
	<?php if($sel_ca3) { ?>
	$("select#sel_ca3").val('<?php echo $sel_ca3; ?>');
	categorychange('<?php echo $sel_ca3; ?>', 'sel_ca4');
	<?php } ?>
	<?php if($sel_ca4) { ?>
	$("select#sel_ca4").val('<?php echo $sel_ca4; ?>');
	categorychange('<?php echo $sel_ca4; ?>', 'sel_ca5');
	<?php } ?>
	<?php if($sel_ca5) { ?>
	$("select#sel_ca5").val('<?php echo $sel_ca5; ?>');
	<?php } ?>

	// 날짜 검색 : TODAY MAX값으로 인식 (maxDate: "+0d")를 삭제하면 MAX값 해제
	$("#fr_date,#to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});
</script>
