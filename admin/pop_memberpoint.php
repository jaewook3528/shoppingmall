<?php
define('_NEWWIN_', true);
include_once('./_common.php');
include_once(TB_ADMIN_PATH."/admin_access.php");

$tb['title'] = "포인트내역";
include_once(TB_ADMIN_PATH."/admin_head.php");

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

$query_string = "mb_id=$mb_id";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_point ";
$sql_search = " where mb_id = '$mb_id' ";

if($fr_date && $to_date)
    $sql_search .= " and po_datetime between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
else if($fr_date && !$to_date)
	$sql_search .= " and po_datetime between '$fr_date 00:00:00' and '$fr_date 23:59:59' ";
else if(!$fr_date && $to_date)
	$sql_search .= " and po_datetime between '$to_date 00:00:00' and '$to_date 23:59:59' ";

$sql_order = " order by po_id desc ";

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

$row2 = sql_fetch("select sum(po_point) as sum_point $sql_common $sql_search ");
$sum_point = $row2['sum_point'];

include_once(TB_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>

<div id="memberpoint_pop" class="new_win">
	<h1><?php echo $tb['title']; ?></h1>	

	<section class="new_win_desc marb50">

	<ul class="anchor">
		<li><a href="./pop_memberform.php?mb_id=<?php echo $mb_id; ?>">회원정보수정</a></li>
		<?php if(is_seller($mb_id)) { ?>
		<li><a href="./pop_sellerform.php?mb_id=<?php echo $mb_id; ?>">공급사정보수정</a></li>
		<li><a href="./pop_sellerorder.php?mb_id=<?php echo $mb_id; ?>">공급사판매내역</a></li>
		<?php } ?>
		<li><a href="./pop_memberorder.php?mb_id=<?php echo $mb_id; ?>">주문내역</a></li>
		<li><a href="./pop_memberpoint.php?mb_id=<?php echo $mb_id; ?>">포인트내역</a></li>
	</ul>

	<h3 class="anc_tit">기본검색</h3>
	<form name="fsearch" id="fsearch" method="get">
	<input type="hidden" name="mb_id" value="<?php echo $mb_id; ?>">
	<div class="tbl_frm01">
		<table>
		<colgroup>
			<col class="w100">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">기간검색</th>
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

	<div class="local_ov mart30">
		전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회	
		<strong class="ov_a">포인트 합계 : <?php echo number_format($sum_point); ?>원</strong>
	</div>

	<div class="tbl_head01">
		<table>
		<colgroup>
			<col class="w50">
			<col>
			<col class="w130">
			<col class="w90">
			<col class="w90">
		</colgroup>
		<thead>
		<tr>
			<th scope="col">번호</th>
			<th scope="col">포인트내용</th>
			<th scope="col">일시</th>
			<th scope="col">포인트</th>			
			<th scope="col">포인트합</th>
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
			<td class="tal"><?php echo $row['po_content']; ?></td>
			<td><?php echo $row['po_datetime']; ?></td>
			<td class="tar"><?php echo number_format($row['po_point']); ?></td>
			<td class="tar"><?php echo number_format($row['po_mb_point']); ?></td>
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
	</section>
</div>

<script>
$(function(){
    $("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});
</script>

<?php
include_once(TB_ADMIN_PATH."/admin_tail.sub.php");
?>