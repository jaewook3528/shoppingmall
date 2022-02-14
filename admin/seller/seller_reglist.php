<?php
include_once("./_common.php");

$tb['title'] = '회원선택';
include_once(TB_ADMIN_PATH."/admin_head.php");

$query_string = $qstr;
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_member a left join shop_seller b on (a.id=b.mb_id) ";
$sql_search = " where a.grade <> '1' and a.supply='' and b.mb_id is null ";

if($sfl) {
    $sql_search .= " and a.id like '%$sfl%' ";
}

if($stx) {
    $sql_search .= " and a.name like '%$stx%' ";
}

if(!$orderby) {
    $filed = "a.index_no";
    $sod = "desc";
} else {
	$sod = $orderby;
}

$sql_order = " order by $filed $sod ";

$sql = " select count(a.id) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select a.* $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);
?>

<h1 class="newp_tit"><?php echo $tb['title']; ?></h1>
<div class="new_win_body">
	<form name="fsearch" id="fsearch" method="get">
	<div class="guidebox tac">
		<b>아이디 : </b> <input type="text" name="sfl" value="<?php echo $sfl; ?>" class="frm_input w100 marr10">
		<b>회원명 : </b> <input type="text" name="stx" value="<?php echo $stx; ?>" class="frm_input w100">
		<input type="submit" value="검색" class="btn_small">
	</div>
	</form>

	<div class="local_frm01 mart20">
		전체 : <b class="fc_197"><?php echo number_format($total_count); ?></b> 명 조회
	</div>
	<div class="tbl_head01">
		<table>
		<colgroup>
			<col class="w50">
			<col class="w100">
			<col>
			<col class="w90">
			<col class="w60">
		</colgroup>
		<thead>
		<tr>
			<th scope="col">번호</th>
			<th scope="col"><?php echo subject_sort_link('a.id',$q2); ?>아이디</a></th>
			<th scope="col"><?php echo subject_sort_link('a.name',$q2); ?>회원명</a></th>
			<th scope="col"><?php echo subject_sort_link('a.reg_time',$q2); ?>가입일</a></th>
			<th scope="col">선택</td>
		</tr>
		</thead>
		<?php
		for($i=0; $row=sql_fetch_array($result); $i++) {
			if($i==0)
				echo '<tbody class="list">'.PHP_EOL;

			$bg = 'list'.($i%2);
		?>
		<tr class="<?php echo $bg; ?>">
			<td><?php echo $num--; ?></td>
			<td class="tal"><?php echo $row['id']; ?></td>
			<td class="tal"><?php echo $row['name']; ?></td>
			<td><?php echo substr($row['reg_time'],0,10); ?></td>
			<td><button type="button" onClick="yes('<?php echo $row['id']; ?>')" class="btn_small grey">선택</button></td>
		</tr>
		<?php
		}
		if($i==0)
			echo '<tbody><tr><td colspan="5" class="empty_table">자료가 없습니다.</td></tr>';
		?>
		</tbody>
		</table>
	</div>

	<?php
	echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=');
	?>
</div>

<script>
function yes(mb_id){
	opener.document.fregform.mb_id.value = mb_id;
	self.close();
}
</script>

<?php
include_once(TB_ADMIN_PATH.'/admin_tail.sub.php');
?>