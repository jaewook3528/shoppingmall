<?php
if(!defined('_TUBEWEB_')) exit;

$pg_title = "검색키워드 관리";
include_once("./admin_head.sub.php");

if($w == "reg") {
	check_demo();

	$keyword = trim(strip_tags($_POST['keyword']));
	if(!$keyword) alert('검색키워드가 값이 넘어오지 않았습니다.');

	if(substr_count($keyword, "&#") > 50) {
		alert("내용에 올바르지 않은 코드가 다수 포함되어 있습니다.");
	}

	unset($value);
	$value['keyword']	= $keyword;
	$value['scount']	= 1;
	$value['pp_date']	= date('W');
	$value['pt_id']		= $member['id'];
	insert("shop_keyword", $value);

	goto_url(TB_MYPAGE_URL."/page.php?code=partner_keyword_list");
}

$query_string = "code=$code";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_keyword ";
$sql_search = " where pt_id = '{$member['id']}' ";
$sql_order  = " order by scount desc, old_scount desc ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

$btn_frmline = <<<EOF
<input type="submit" name="act_button" value="선택수정" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
<input type="submit" name="act_button" value="선택삭제" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
EOF;
?>

<form name="fregform" method="post" action="./page.php?code=partner_keyword_list">
<input type="hidden" name="w" value="reg">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w100">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">검색어등록</th>
		<td><input type="text" name="keyword" required itemname="검색어" class="frm_input required" size="40"></td>
	</tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="추가" class="btn_medium red">
</div>
</form>

<form name="fwordlist" id="fwordlist" method="post" action="./partner_keyword_list_update.php" onsubmit="return fwordlist_submit(this);">
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
		<col class="w100">
		<col class="w100">
		<col>
	</colgroup>
	<thead>
	<tr>
		<th scope="col"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
		<th scope="col">번호</th>
		<th scope="col">이번주검색수</th>
		<th scope="col">지난검색수</th>
		<th scope="col">검색어</th>
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
			<input type="hidden" name="index_no[<?php echo $i; ?>]" value="<?php echo $row['index_no']; ?>">
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>">
		</td>
		<td><?php echo $num--; ?></td>
		<td><input type="text" name="scount[<?php echo $i; ?>]" value="<?php echo $row['scount']; ?>" class="frm_input"></td>
		<td><input type="text" name="old_scount[<?php echo $i; ?>]" value="<?php echo $row['old_scount']; ?>" class="frm_input"></td>
		<td><input type="text" name="keyword[<?php echo $i; ?>]" value="<?php echo $row['keyword']; ?>" class="frm_input"></td>
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

<script>
function fwordlist_submit(f)
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

<?php
include_once("./admin_tail.sub.php");
?>