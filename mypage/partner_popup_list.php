<?php
if(!defined('_TUBEWEB_')) exit;

$pg_title = "팝업 관리";
include_once("./admin_head.sub.php");

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_popup ";
$sql_search = " where mb_id = '{$member['id']}' ";
$sql_order  = " order by index_no desc ";

if($sfl && $stx) {
    $sql_search .= " and $sfl like '%$stx%' ";
}

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
<a href="./page.php?code=partner_popup_form" class="fr btn_lsmall red"><i class="ionicons ion-android-add"></i> 추가하기</a>
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
				<option value="title">제목</option>
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

<form name="fpopuplist" id="fpopuplist" method="post" action="./partner_popup_list_update.php" onsubmit="return fpopuplist_submit(this);">
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
		<col>
		<col class="w100">
		<col class="w100">
		<col class="w130">
		<col class="w130">
		<col class="w60">
		<col class="w60">
	</colgroup>
	<thead>
	<tr>
		<th scope="col"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
		<th scope="col">번호</th>
		<th scope="col">제목</th>
		<th scope="col">접속기기</th>
		<th scope="col">실행기간</th>
		<th scope="col">팝업크기</th>
		<th scope="col">위치좌표</th>
		<th scope="col">노출</th>
		<th scope="col">관리</th>
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$pp_id = $row['index_no'];

		$s_upd = "<a href='./page.php?code=partner_popup_form&w=u&pp_id=$pp_id$qstr&page=$page' class=\"btn_small\">수정</a>";

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;

		$bg = 'list'.$i%2;
	?>
	<tr class="<?php echo $bg; ?>">
		<td>			
			<input type="hidden" name="pp_id[<?php echo $i; ?>]" value="<?php echo $pp_id; ?>">
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>">
		</td>
		<td><?php echo $num--; ?></td>
		<td><input type="text" name="title[<?php echo $i; ?>]" value="<?php echo $row['title']; ?>" class="frm_input"></td>
		<td>
			<select name="device[<?php echo $i; ?>]">
				<?php echo option_selected('both', $row['device'], "모두"); ?>
				<?php echo option_selected('pc', $row['device'], "PC"); ?>
				<?php echo option_selected('mobile', $row['device'], "모바일"); ?>
			</select>
		</td>
		<td><?php echo $row['begin_date']; ?><br><?php echo $row['end_date']; ?></td>
		<td>
			<input type="text" name="width[<?php echo $i; ?>]" value="<?php echo $row['width']; ?>" class="frm_input w50"> *
			<input type="text" name="height[<?php echo $i; ?>]" value="<?php echo $row['height']; ?>" class="frm_input w50">
		</td>
		<td>
			<input type="text" name="lefts[<?php echo $i; ?>]" value="<?php echo $row['lefts']; ?>" class="frm_input w50"> *
			<input type="text" name="top[<?php echo $i; ?>]" value="<?php echo $row['top']; ?>" 
			class="frm_input w50">
		</td>
		<td><?php echo ($row['state']=='0')?'yes':'no'; ?></td>
		<td><?php echo $s_upd; ?></td>
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
function fpopuplist_submit(f)
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