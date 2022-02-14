<?php
if(!defined('_TUBEWEB_')) exit;

if(!$pf_auth_good)
	alert('개별 상품판매 권한이 있어야만 이용 가능합니다.');

$pg_title = "상품 문의관리";
include_once("./admin_head.sub.php");

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_goods_qa ";
$sql_search = " where seller_id = '{$member['id']}' ";

if($sfl && $stx) {
    $sql_search .= " and $sfl like '%$stx%' ";
}

if($sst) {
    $sql_search .= " and iq_ty = '$sst' ";
}

if(!$orderby) {
    $filed = "iq_reply";
    $sod = "asc, iq_id desc";
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

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

$btn_frmline = <<<EOF
<input type="submit" name="act_button" value="선택삭제" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
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
			<select name="sst">
				<option value="">구분</option>
				<option value="상품"<?php echo get_selected($sst, '상품'); ?>>상품</option>
				<option value="배송"<?php echo get_selected($sst, '배송'); ?>>배송</option>
				<option value="반품/환불/취소"<?php echo get_selected($sst, '반품/환불/취소'); ?>>반품/환불/취소</option>
				<option value="교환/변경"<?php echo get_selected($sst, '교환/변경'); ?>>교환/변경</option>
				<option value="기타"<?php echo get_selected($sst, '기타'); ?>>기타</option>
			</select>
			<select name="sfl">
				<option value="iq_name"<?php echo get_selected($sfl, 'iq_name'); ?>>작성자명</option>
				<option value="iq_email"<?php echo get_selected($sfl, 'iq_email'); ?>>작성자 이메일</option>
				<option value="iq_hp"<?php echo get_selected($sfl, 'iq_hp'); ?>>작성자 핸드폰</option>
				<option value="iq_subject"<?php echo get_selected($sfl, 'iq_subject'); ?>>제목</option>
				<option value="iq_question"<?php echo get_selected($sfl, 'iq_question'); ?>>질문내용</option>
				<option value="iq_answer"<?php echo get_selected($sfl, 'iq_answer'); ?>>답변내용</option>
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

<form name="fqalist" id="fqalist" method="post" action="./partner_goods_qa_delete.php" onsubmit="return fqalist_submit(this);">
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
		<col class="w130">
		<col>
		<col class="w130">
		<col class="w80">
		<col class="w60">
		<col class="w60">
	</colgroup>
	<thead>
	<tr>
		<th scope="col"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
		<th scope="col">번호</th>
		<th scope="col"><?php echo subject_sort_link('iq_ty',$q2); ?>구분</a></th>
		<th scope="col"><?php echo subject_sort_link('iq_subject',$q2); ?>제목</a></th>
		<th scope="col"><?php echo subject_sort_link('iq_name',$q2); ?>작성자</a></th>
		<th scope="col"><?php echo subject_sort_link('iq_time',$q2); ?>작성일</a></th>
		<th scope="col"><?php echo subject_sort_link('iq_reply',$q2); ?>답변</a></th>
		<th scope="col">관리</th>
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$iq_id = $row['iq_id'];
		
		$iq_url = "code=partner_goods_qa_form&w=u&iq_id=$iq_id$qstr&page=$page";
		$iq_upd = "<a href=\"./page.php?{$iq_url}\" class=\"btn_small\">수정</a>";
		$iq_subject = "<a href=\"./page.php?{$iq_url}\">".cut_str($row['iq_subject'],50)."</a>";

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;

		$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td>
			<input type="hidden" name="iq_id[<?php echo $i; ?>]" value="<?php echo $iq_id; ?>">
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>">
		</td>
		<td><?php echo $num--; ?></td>
		<td><?php echo $row['iq_ty']; ?></td>
		<td class="tal"><?php echo $iq_subject; ?></td>
		<td><?php echo $row['iq_name']; ?></a></td>
		<td><?php echo substr($row['iq_time'],0,10); ?></td>
		<td><?php echo $row['iq_answer']?'yes':''; ?></td>
		<td><?php echo $iq_upd; ?></td>
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tbody><tr><td colspan="8" class="empty_table">자료가 없습니다.</td></tr>';
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
function fqalist_submit(f)
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