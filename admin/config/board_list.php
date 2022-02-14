<?php
if(!defined('_TUBEWEB_')) exit;

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_board_conf ";
$sql_search = " where (1) ";
$sql_order  = " order by gr_id desc,index_no desc ";

if($sfl && $stx) {
    switch($sfl) {
        case "bo_table" :
            $sql_search .= " and index_no like '$stx%' ";
            break;
        default :
            $sql_search .= " and $sfl like '%$stx%' ";
            break;
    }
}

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
<a href="./config.php?code=board_form" class="fr btn_lsmall red"><i class="ionicons ion-android-add"></i> 추가하기</a>
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
				<?php echo option_selected('bo_table', $sfl, 'TABLE'); ?>
				<?php echo option_selected('boardname', $sfl, '제목'); ?>
				<?php echo option_selected('gr_id', $sfl, '그룹ID'); ?>
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

<form name="fboardlist" id="fboardlist" method="post" action="./config/board_list_update.php" onsubmit="return fboardlist_submit(this);">
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
		<col class="w80">
		<col class="w80">
		<col class="w80">
		<col class="w60">
		<col class="w60">
		<col class="w60">
	</colgroup>
	<thead>
	<tr>
		<th scope="col"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
		<th scope="col">TABLE</th>
		<th scope="col">게시판제목</th>
		<th scope="col">그룹</th>
		<th scope="col">목록</th>
		<th scope="col">읽기</th>
		<th scope="col">쓰기</th>
		<th scope="col">답글</th>
		<th scope="col">코멘트</th>
		<th scope="col">관리</th>
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$bo_table = $row['index_no'];

		$list_priv = "비회원";
		$read_priv = "비회원";
		$write_priv = "비회원";

		if($row['list_priv']!=99) {
			$list_priv = get_grade($row['list_priv']);
		}

		if($row['read_priv']!=99) {
			$read_priv = get_grade($row['read_priv']);
		}

		if($row['write_priv']!=99) {
			$write_priv = get_grade($row['write_priv']);
		}

		$s_upd = "<a href='./config.php?code=board_form&w=u&bo_table=$bo_table$qstr&page=$page' class=\"btn_small\">수정</a>";
		
		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;

		$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td>			
			<input type="hidden" name="bo_table[<?php echo $i; ?>]" value="<?php echo $bo_table; ?>">
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>">
		</td>
		<td><a href="<?php echo TB_BBS_URL; ?>/list.php?boardid=<?php echo $bo_table; ?>" target="_blank"><?php echo $bo_table; ?></a></td>
		<td><input type="text" name="bo_subject[<?php echo $i; ?>]" value="<?php echo $row['boardname']; ?>" class="frm_input"></td>
		<td><?php echo get_group_select("gr_id[$i]", $row['gr_id']); ?></td>
		<td><?php echo $list_priv; ?></td>
		<td><?php echo $read_priv; ?></td>
		<td><?php echo $write_priv; ?></td>
		<td><?php echo $row['usereply']=='Y'?'yes':''; ?></td>
		<td><?php echo $row['usetail']=='Y'?'yes':''; ?></td>
		<td><?php echo $s_upd; ?></td>
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tbody><tr><td colspan="10" class="empty_table">자료가 없습니다.</td></tr>';
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
function fboardlist_submit(f)
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
