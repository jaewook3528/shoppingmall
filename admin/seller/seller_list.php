<?php
if(!defined('_TUBEWEB_')) exit;

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_seller a, shop_member b ";
$sql_search = " where a.mb_id = b.id ";

if($sfl && $stx) {
    $sql_search .= " and $sfl like '%$stx%' ";
}

if($fr_date && $to_date)
    $sql_search .= " and a.reg_time between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
else if($fr_date && !$to_date)
	$sql_search .= " and a.reg_time between '$fr_date 00:00:00' and '$fr_date 23:59:59' ";
else if(!$fr_date && $to_date)
	$sql_search .= " and a.reg_time between '$to_date 00:00:00' and '$to_date 23:59:59' ";

if(!$orderby) {
    $filed = "a.state";
    $sod = "asc, a.index_no desc";
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
$num = $total_count - (($page-1)*$rows);

$sql = " select a.*, b.name, b.email $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

include_once(TB_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$btn_frmline = <<<EOF
<input type="submit" name="act_button" value="선택승인" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
<input type="submit" name="act_button" value="선택삭제" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
<a href="./help/sendmail3.php" onclick="win_open(this,'allemail','750','680','no');return false" class="btn_lsmall bx-white">전체메일발송</a>
<a href="./sms/sms_seller.php" onclick="win_open(this,'allsms','245','360','no');return false" class="btn_lsmall bx-white">전체문자발송</a>
<a href="./seller/seller_list_excel.php?$q1" class="btn_lsmall bx-white"><i class="fa fa-file-excel-o"></i> 엑셀저장</a>
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
				<?php echo option_selected('a.company_name', $sfl, '공급사명'); ?>
				<?php echo option_selected('a.seller_code', $sfl, '업체코드'); ?>
				<?php echo option_selected('a.info_name', $sfl, '담당자명'); ?>
				<?php echo option_selected('a.company_owner', $sfl, '대표자명'); ?>
				<?php echo option_selected('a.mb_id', $sfl, '아이디'); ?>
				<?php echo option_selected('b.name', $sfl, '회원명'); ?>
			</select>
			<input type="text" name="stx" value="<?php echo $stx; ?>" class="frm_input" size="30">
		</td>
	</tr>
	<tr>
		<th scope="row">신청일</th>
		<td>
			<?php echo get_search_date("fr_date", "to_date", $fr_date, $to_date); ?>
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

<form name="fsellerlist" id="fsellerlist" method="post" action="./seller/seller_list_update.php" onsubmit="return fsellerlist_submit(this);">
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
		<col class="w50">
		<col class="w50">
		<col class="w130">
		<col class="w130">
		<col class="w80">
		<col>
		<col class="w100">
		<col class="w130">
	</colgroup>
	<thead>
	<tr>
		<th scope="col"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
		<th scope="col"><?php echo subject_sort_link('a.state',$q2); ?>승인</a></th>
		<th scope="col"><?php echo subject_sort_link('a.seller_open',$q2); ?>상품</a></th>
		<th scope="col"><?php echo subject_sort_link('b.name',$q2); ?>회원명</a></th>
		<th scope="col"><?php echo subject_sort_link('a.mb_id',$q2); ?>아이디</a></th>
		<th scope="col"><?php echo subject_sort_link('a.seller_code',$q2); ?>업체코드</a></th>
		<th scope="col"><?php echo subject_sort_link('a.company_name',$q2); ?>공급사명</a></th>
		<th scope="col">전화번호</th>
		<th scope="col"><?php echo subject_sort_link('a.reg_time',$q2); ?>등록일시</a></th>
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {

		$disabled = '';
		if($row['state']) $disabled = ' disabled';

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;

		$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td>
			<input type="hidden" name="mb_id[<?php echo $i; ?>]" value="<?php echo $row['mb_id']; ?>">
			<input type="hidden" name="seller_code[<?php echo $i; ?>]" value="<?php echo $row['seller_code']; ?>">
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>"<?php echo $disabled; ?>>
		</td>
		<td><?php echo $row['state']?'yes':'no'; ?></td>
		<td><?php echo $gw_isopen[$row['seller_open']]; ?></td>
		<td class="tal"><?php echo get_sideview($row['mb_id'], $row['name']); ?></td>
		<td class="tal"><?php echo $row['mb_id']; ?></td>
		<td><?php echo $row['seller_code']; ?></td>
		<td class="tal"><?php echo get_text($row['company_name']); ?></td>
		<td><?php echo $row['company_tel']; ?></td>
		<td><?php echo $row['reg_time']; ?></td>
	</tr>
	<?php
	}
	if($i==0)
		echo '<tbody><tr><td colspan="9" class="empty_table">자료가 없습니다.</td></tr>';
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
function fsellerlist_submit(f)
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
	// 날짜 검색 : TODAY MAX값으로 인식 (maxDate: "+0d")를 삭제하면 MAX값 해제
	$("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});
</script>
