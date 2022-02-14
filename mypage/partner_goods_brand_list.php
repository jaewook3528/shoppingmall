<?php
if(!defined('_TUBEWEB_')) exit;

if(!$pf_auth_good)
	alert('개별 상품판매 권한이 있어야만 이용 가능합니다.');

$pg_title = "브랜드관리";
include_once("./admin_head.sub.php");

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_brand ";
$sql_search = " where br_user_yes = '0' or (br_user_yes = '1' and mb_id = '{$member['id']}') ";

if($sfl && $stx) {
    $sql_search .= " and $sfl like '%$stx%' ";
}

if(!$orderby) {
    $filed = "br_id";
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
$num = $total_count - (($page-1)*$rows);

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

$btn_frmline = <<<EOF
<input type="submit" name="act_button" value="선택수정" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
<input type="submit" name="act_button" value="선택삭제" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
EOF;
?>

<h2>브랜드 등록</h2>
<form name="fbrandlist2" id="fbrandlist2" action="./partner_goods_brand_form_update.php" method="post" enctype="MULTIPART/FORM-DATA">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w100">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">브랜드명 (KOR)</th>
		<td>
			<input type="text" name="br_name" required itemname="브랜드명 (KOR)" class="required frm_input w200">
			<span class="fc_197">예시) 아르마니 익스체인지</span>
		</td>
	</tr>
	<tr>
		<th scope="row">브랜드명 (ENG)</th>
		<td>
			<input type="text" name="br_name_eng" class="frm_input w200">
			<span class="fc_197">예시) Armani Exchange</span>
		</td>
	</tr>
	<tr>
		<th scope="row">브랜드로고</th>
		<td>
			<input type="file" name="br_logo" id="br_logo">
			<span class="fc_197">사이즈(128픽셀 * 40픽셀)</span>
		</td>
	</tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="추가" class="btn_medium red">
</div>
</form>

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
				<?php echo option_selected('br_name', $sfl, '브랜드명 (KOR)'); ?>
				<?php echo option_selected('br_name_eng', $sfl, '브랜드명 (ENG)'); ?>
				<?php echo option_selected('mb_id', $sfl, '회원ID'); ?>
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

<form name="fbrandlist" id="fbrandlist" method="post" action="./partner_goods_brand_list_update.php" onsubmit="return fbrandlist_submit(this);">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<div class="local_ov mart30">
	전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회
	<span class="ov_a">본사에서 등록 된 브랜드는 삭제 및 수정이 불가합니다.</span>
</div>
<div class="local_frm01">
	<?php echo $btn_frmline; ?>
</div>
<div class="tbl_head01">
	<table>
	<colgroup>
		<col class="w50">
		<col class="w50">
		<col>
		<col>
		<col class="w130">
		<col class="w80">
		<col class="w110">
		<col class="w60">
	</colgroup>
	<thead>
	<tr>
		<th scope="col"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
		<th scope="col">번호</th>
		<th scope="col"><?php echo subject_sort_link('br_name',$q2); ?>브랜드명 (KOR)</a></th>
		<th scope="col"><?php echo subject_sort_link('br_name_eng',$q2); ?>브랜드명 (ENG)</a></th>
		<th scope="col"><?php echo subject_sort_link('mb_id',$q2); ?>회원ID</a></th>
		<th scope="col">상품수</th>
		<th scope="col">바로가기</th>
		<th scope="col">관리</th>
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$br_id = $row['br_id'];

		$row2 = sql_fetch("select count(*) as cnt from shop_goods where brand_uid='{$row['br_id']}'");

		if($row['mb_id'] == $member['id']) {
			$s_upd = "<a href='./page.php?code=partner_goods_brand_form&w=u&br_id=$br_id$qstr&page=$page' class=\"btn_small\">수정</a>";
			$readonly = "";
			$disabled = "";
			$td_bg = " style='background:yellow;'";
		} else {
			$s_upd = '-';			
			$readonly = " readonly style='background-color:#f4f4f4;'";
			$disabled = " disabled";
			$td_bg = "";
		}

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;

		$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td<?php echo $td_bg; ?>>			
			<input type="hidden" name="br_id[<?php echo $i; ?>]" value="<?php echo $br_id; ?>">
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>"<?php echo $disabled; ?>>
		</td>
		<td><?php echo $num--; ?></td>
		<td><input type="text" name="br_name[<?php echo $i; ?>]" value="<?php echo$row['br_name']; ?>" class="frm_input"<?php echo $readonly; ?>></td>
		<td><input type="text" name="br_name_eng[<?php echo $i; ?>]" value="<?php echo$row['br_name_eng']; ?>"class="frm_input"<?php echo $readonly; ?>></td>
		<td><?php echo $row['mb_id'] == 'admin' ? "본사":$row['mb_id']; ?></td>
		<td><?php echo number_format($row2['cnt']); ?></td>
		<td><a href="/shop/brandlist.php?br_id=<?php echo $br_id; ?>" target="_blank" class="btn_small grey">브랜드 바로가기</a></td>
		<td><?php echo $s_upd; ?></td>
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
function fbrandlist_submit(f)
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