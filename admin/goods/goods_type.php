<?php
if(!defined('_TUBEWEB_')) exit;

if($sel_ca1) $sca = $sel_ca1;
if($sel_ca2) $sca = $sel_ca2;
if($sel_ca3) $sca = $sel_ca3;
if($sel_ca4) $sca = $sel_ca4;
if($sel_ca5) $sca = $sel_ca5;

if(isset($sel_ca1)) $qstr .= "&sel_ca1=$sel_ca1";
if(isset($sel_ca2)) $qstr .= "&sel_ca2=$sel_ca2";
if(isset($sel_ca3)) $qstr .= "&sel_ca3=$sel_ca3";
if(isset($sel_ca4)) $qstr .= "&sel_ca4=$sel_ca4";
if(isset($sel_ca5)) $qstr .= "&sel_ca5=$sel_ca5";
if(isset($q_type1)) $qstr .= "&q_type1=$q_type1";
if(isset($q_type2)) $qstr .= "&q_type2=$q_type2";
if(isset($q_type3)) $qstr .= "&q_type3=$q_type3";
if(isset($q_type4)) $qstr .= "&q_type4=$q_type4";
if(isset($q_type5)) $qstr .= "&q_type5=$q_type5";

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_goods a ";
$sql_search = " where a.use_aff='0' and a.shop_state='0' ";

// 상품 유형
$q_type_cnt = 0;
$sql_or = array();
if(isset($q_type1) && $q_type1) {
	$sql_or[] = " b.it_type1 = '1' ";
	$q_type_cnt++;
}

if(isset($q_type2) && $q_type2) {
	$sql_or[] = " b.it_type2 = '1' ";
	$q_type_cnt++;
}

if(isset($q_type3) && $q_type3) {
	$sql_or[] = " b.it_type3 = '1' ";
	$q_type_cnt++;
}

if(isset($q_type4) && $q_type4) {
	$sql_or[] = " b.it_type4 = '1' ";
	$q_type_cnt++;
}

if(isset($q_type5) && $q_type5) {
	$sql_or[] = " b.it_type5 = '1' ";
	$q_type_cnt++;
}

if($q_type_cnt) {
	$sql_common .= " left join shop_goods_type b on (a.index_no=b.gs_id) ";
	$sql_search .= " and b.mb_id = 'admin' ";
	if($sql_or) {
		$sql_search .= ' and ('.implode(' or ', $sql_or). ') ';
	}
}

if($sca) {
	$len = strlen($sca);
    $sql_common .= " left join shop_goods_cate c on (a.index_no=c.gs_id) ";
    $sql_search .= " and (left(c.gcate,$len) = '$sca') ";
}

// 검색어
if($stx) {
    switch($sfl) {
        case "gname" :
		case "explan" :
		case "maker" :
		case "origin" :		
		case "model" :
            $sql_search .= " and a.$sfl like '%$stx%' ";
            break;
        default : 
            $sql_search .= " and a.$sfl like '$stx%' ";
            break;
    }
}

$sql_order = " group by a.index_no order by a.index_no desc ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(DISTINCT a.index_no) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

if($_SESSION['ss_page_rows'])
	$page_rows = $_SESSION['ss_page_rows'];
else
	$page_rows = 30;

$rows = $page_rows;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select a.* $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

$target_table = 'shop_cate';
include_once(TB_LIB_PATH."/categoryinfo.lib.php");

$btn_frmline = <<<EOF
<button type="submit" class="btn_lsmall red"><i class="fa fa-refresh fa-spin"></i> 일괄적용</button>
EOF;
?>

<h2>기본검색</h2>
<form name="fsearch" id="fsearch" method="get">
<input type="hidden" name="code" value="<?php echo $code; ?>">
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w100">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">검색어</th>
		<td>
			<select name="sfl">
				<?php echo option_selected('gname', $sfl, '상품명'); ?>				
				<?php echo option_selected('gcode', $sfl, '상품코드'); ?>
				<?php echo option_selected('mb_id', $sfl, '업체코드'); ?>
				<?php echo option_selected('maker', $sfl, '제조사'); ?>
				<?php echo option_selected('origin', $sfl, '원산지'); ?>
				<?php echo option_selected('model', $sfl, '모델명'); ?>
				<?php echo option_selected('explan', $sfl, '짧은설명'); ?>
			</select>
			<input type="text" name="stx" value="<?php echo $stx; ?>" class="frm_input" size="30">
		</td>
	</tr>
	<tr>
		<th scope="row">카테고리</th>
		<td colspan="3">
			<script>multiple_select('sel_ca');</script>
		</td>
	</tr>
	<tr>
		<th scope="row">진열영역</th>
		<td>
			<?php echo check_checked('q_type1', $q_type1, '1', '쇼핑특가'); ?>
			<?php echo check_checked('q_type2', $q_type2, '1', '베스트셀러'); ?>
			<?php echo check_checked('q_type3', $q_type3, '1', '신규상품'); ?>
			<?php echo check_checked('q_type4', $q_type4, '1', '인기상품'); ?>
			<?php echo check_checked('q_type5', $q_type5, '1', '추천상품'); ?>
		</td>
	</tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="검색" class="btn_medium">
	<input type="button" value="초기화" id="frmRest" class="btn_medium grey">	
</div>
</form>

<form name="fgoodslist" method="post" action="./goods/goods_type_update.php">
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

<div class="tbl_head01">
	<table>
	<colgroup>
		<col class="w60">
		<col class="w60">
		<col class="w80">
		<col>			
		<col class="w50">
		<col class="w50">
		<col class="w50">
		<col class="w50">
		<col class="w50">
		<col class="w80">
		<col class="w80">
		<col class="w80">			
	</colgroup>
	<thead>
	<tr>
		<th scope="col">번호</th>
		<th scope="col">이미지</th>
		<th scope="col">상품코드</th>
		<th scope="col">상품명</th>			
		<th scope="col" class="th_bg lh2">쇼핑<br>특가</th>
		<th scope="col" class="th_bg lh2">베스트<br>셀러</th>
		<th scope="col" class="th_bg lh2">신규<br>상품</th>
		<th scope="col" class="th_bg lh2">인기<br>상품</th>
		<th scope="col" class="th_bg lh2">추천<br>상품</th>
		<th scope="col">진열</th>
		<th scope="col">재고</th>
		<th scope="col">판매가</th>
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {		
		$gs_id = $row['index_no'];
		$href = TB_SHOP_URL.'/view.php?index_no='.$gs_id;

		$sql2 = " select * from shop_goods_type where mb_id = 'admin' and gs_id = '$gs_id' ";
		$row2 = sql_fetch($sql2);		

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;

		$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td>
			<input type="hidden" name="gs_id[<?php echo $i; ?>]" value="<?php echo $gs_id; ?>">
			<?php echo $num--; ?>
		</td>
		<td><a href="<?php echo $href; ?>" target="_blank"><?php echo get_it_image($gs_id, $row['simg1'], 40, 40); ?></a></td>
		<td><?php echo get_text($row['gcode']); ?></td>
		<td class="tal"><?php echo get_text($row['gname']); ?></td>			
		<td><input type="checkbox" name="it_type1[<?php echo $i; ?>]" value="1"<?php echo get_checked($row2['it_type1'],"1"); ?>></td>
		<td><input type="checkbox" name="it_type2[<?php echo $i; ?>]" value="1"<?php echo get_checked($row2['it_type2'],"1"); ?>></td>
		<td><input type="checkbox" name="it_type3[<?php echo $i; ?>]" value="1"<?php echo get_checked($row2['it_type3'],"1"); ?>></td>
		<td><input type="checkbox" name="it_type4[<?php echo $i; ?>]" value="1"<?php echo get_checked($row2['it_type4'],"1"); ?>></td>
		<td><input type="checkbox" name="it_type5[<?php echo $i; ?>]" value="1"<?php echo get_checked($row2['it_type5'],"1"); ?>></td>
		<td><?php echo $gw_isopen[$row['isopen']]; ?></td>
		<td class="tar"><?php echo number_format($row['stock_qty']); ?></td>
		<td class="tar"><?php echo number_format($row['goods_price']); ?></td>
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tbody><tr><td colspan="12" class="empty_table">자료가 없습니다.</td></tr>';
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
});
</script>
