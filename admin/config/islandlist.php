<?php
if(!defined('_TUBEWEB_')) exit;

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_island ";
$sql_search = " where (1) ";

if($sfl && $stx) {
    $sql_search .= " and $sfl like '%$stx%' ";
}

if(!$orderby) {
    $filed = "is_id";
    $sod = "desc";
} else {
	$sod = $orderby;
}

$sql_order = " order by $filed $sod ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

$btn_frmline = <<<EOF
<input type="submit" name="act_button" value="선택수정" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
<input type="submit" name="act_button" value="선택삭제" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
EOF;
?>

<form name="fsearch" id="fsearch" method="get">
<input type="hidden" name="code" value="<?php echo $code; ?>">
<h2>기본검색</h2>
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
				<?php echo option_selected('is_name', $sfl, '할증 지역명'); ?>
				<?php echo option_selected('is_zip1', $sfl, '우편번호 시작'); ?>
				<?php echo option_selected('is_zip2', $sfl, '우편번호 끝'); ?>
			</select>
			<input type="text" name="stx" value="<?php echo $stx; ?>" class="frm_input" size="30">
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

<form name="fislandlist" id="fislandlist" method="post" action="./config/islandlistupdate.php" onsubmit="return fislandlist_submit(this);">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<div class="local_ov mart30">
	전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회
</div>
<div class="local_frm01">
	<?php echo $btn_frmline; ?>
</div>
<div class="tbl_head01">
	<table>
	<colgroup>
		<col class="w50">
		<col>
		<col class="w100">
		<col class="w100">
		<col class="w100">
	</colgroup>
	<thead>
	<tr>
		<th scope="col"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
		<th scope="col"><?php echo subject_sort_link('is_name',$q2); ?>할증 지역명</a></th>
		<th scope="col"><?php echo subject_sort_link('is_zip1',$q2); ?>우편번호 시작</a></th>
		<th scope="col"><?php echo subject_sort_link('is_zip2',$q2); ?>우편번호 끝</a></th>
		<th scope="col"><?php echo subject_sort_link('is_price',$q2); ?>추가금액</a></th>
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;

		$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td>			
			<input type="hidden" name="is_id[<?php echo $i; ?>]" value="<?php echo $row['is_id']; ?>">
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>">
		</td>
		<td><input type="text" name="is_name[<?php echo $i; ?>]" value="<?php echo$row['is_name']; ?>" class="frm_input"></td>
		<td><input type="text" name="is_zip1[<?php echo $i; ?>]" value="<?php echo$row['is_zip1']; ?>" class="frm_input"></td>
		<td><input type="text" name="is_zip2[<?php echo $i; ?>]" value="<?php echo$row['is_zip2']; ?>" class="frm_input"></td>
		<td><input type="text" name="is_price[<?php echo $i; ?>]" value="<?php echo number_format($row['is_price']); ?>" class="frm_input" onkeyup="addComma(this)"></td>
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tbody><tr><td colspan="5" class="empty_table">자료가 없습니다.</td></tr>';
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

<form name="fisland2" action="./config/islandlistupdate.php" method="post" autocomplete="off">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="act_button" value="추가">
<h2>추가배송지역 등록</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row"><label for="is_name">지역명</label></th>
		<td><input type="text" name="is_name" id="is_name" required itemname="지역명" class="frm_input required"></td>
	</tr>
	<tr>
		<th scope="row">우편번호 범위</th>
		<td>
			<label for="is_zip1" class="sound_only">우편번호 시작</label>
			<input type="text" name="is_zip1" id="is_zip1" required itemname="우편번호 시작" class="frm_input required" size="10" maxlength="5"> 부터
			<label for="is_zip2" class="sound_only">우편번호 끝</label>
			<input type="text" name="is_zip2" id="is_zip2" required itemname="우편번호 끝" class="frm_input required" size="10" maxlength="5"> 까지
			<?php echo help('구 우편번호는 입력하시면 안됩니다.<br>우편번호는 00000 형식(5자)이며 숫자로만 입력하셔야 합니다.<br>예) <span class="fc_red">53321</span> 부터 <span class="fc_red">53325</span> 까지'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="is_price">추가배송비</label></th>
		<td><input type="text" name="is_price" id="is_price" required itemname="추가배송비" class="frm_input required" size="10" onkeyup="addComma(this)"> 원</td>
	</tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="추가" class="btn_medium red" accesskey="s">
</div>
</form>

<div class="information">
	<h4>도움말</h4>
	<div class="content">
		<div class="desc02">
			<p>ㆍ우편번호 범위로 할증지역을 관리합니다.</p>
			<p>ㆍ배송비가 추가되는 설정입니다. 우편번호를 정확하게 입력해 주시기 바랍니다.</p>
			<p class="fc_red">ㆍ우편번호 범위가 크면 다른 지역이 포함되는 경우가 간혹 발생되므로, 범위를 작게 설정하시기를 권장합니다.</p>
		</div>
	 </div>
</div>

<script>
function fislandlist_submit(f)
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
</script>