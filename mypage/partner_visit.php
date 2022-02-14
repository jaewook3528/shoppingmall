<?php
if(!defined('_TUBEWEB_')) exit;

$pg_title = "접속자검색";
include_once("./admin_head.sub.php");

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date) ) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date) ) $to_date = '';

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$where = array();
$where[] = " mb_id = '{$member['id']}' ";
$sql_search = "";

if($stx) {
    switch($sfl) {
		case "vi_ip":
		case "mb_id":
			$where[] = " $sfl like '$stx%' ";
			break;
        default : 
            $where[] = " $sfl like '%$stx%' ";
            break;
    }
}

if($fr_date && $to_date)
	$where[] = " vi_date between '$fr_date' and '$to_date' ";  
else if($fr_date && !$to_date)
	$where[] = " vi_date = '$fr_date' ";
else if(!$fr_date && $to_date)
	$where[] = " vi_date = '$to_date' ";

if($where) {
    $sql_search = ' where '.implode(' and ', $where);
}

$sql_common = " from shop_visit $sql_search ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * $sql_common order by vi_id desc limit $from_record, $rows ";
$result = sql_query($sql);

include_once(TB_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>

<h2>통계검색</h2>
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
		<th scope="row"><label for="sch_sort">검색어</label></th>
		<td>
			<select name="sfl" id="sch_sort">
				<?php echo option_selected("vi_ip", $sfl, "IP"); ?>
				<?php echo option_selected("vi_referer", $sfl, "접속경로"); ?>
			</select>
			<label for="sch_word" class="sound_only">검색어</label>
			<input type="text" name="stx" value="<?php echo stripslashes($stx); ?>" id="sch_word" class="frm_input">	
		</td>
	</tr>
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
	총 접속자수 : <b class="fc_red"><?php echo number_format($total_count); ?></b>건
</div>

<div class="tbl_head01">
	<table>
	<colgroup>
		<col class="w120">
		<col>
		<col class="w100">
		<col class="w100">
		<col class="w100">
		<col class="w130">
	</colgroup>
	<thead>
	<tr>
		<th scope="col">IP</th>
		<th scope="col">접속경로</th>
		<th scope="col">가맹점ID</th>
		<th scope="col">브라우저</th>
		<th scope="col">OS</th>
		<th scope="col">일시</th>	
	</tr>
	</thead>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {

		$brow = get_brow($row['vi_agent']);
		$os   = get_os($row['vi_agent']);

		$a1 = $a2 = $referer = $title = '';
		if($row['vi_referer']) {

			$referer = get_text($row['vi_referer']);
			$referer = urldecode($referer);

			if(!is_utf8($referer)) {
				$referer = iconv_utf8($referer);
			}

			$title = str_replace(array('<', '>', '&'), array("&lt;", "&gt;", "&amp;"), $referer);
			$a1 = '<a href="'.$row['vi_referer'].'" class="normal" target="_blank" title="'.$title.'">';
			$a1 = str_replace('&', "&amp;", $a1);
			$a2 = '</a>';
		}

		$ip = $row['vi_ip'];

		if($brow == '기타') { $brow = '<span title="'.get_text($row['vi_agent']).'">'.$brow.'</span>'; }
		if($os == '기타') { $os = '<span title="'.get_text($row['vi_agent']).'">'.$os.'</span>'; }

		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;

		$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td class="tal"><?php echo $ip; ?></td>
		<td class="tal" style="word-break:break-all;">				
			<?php 
			if($row['vi_referer'])
				echo $a1.$title.$a2;
			else
				echo '<span class="fc_137">주소직접입력 또는 즐겨찾기를 이용한 방문</span>';
			?>				
		</td>
		<td><?php echo $row['mb_id']; ?></td>
		<td><?php echo $brow; ?></td>
		<td><?php echo $os; ?></td>
		<td><?php echo $row['vi_date']; ?> <?php echo $row['vi_time']; ?></td>
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tbody><tr><td colspan="6" class="empty_table">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>

<?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=');
?>

<script>
$(function(){
	// 날짜 검색 : TODAY MAX값으로 인식 (maxDate: "+0d")를 삭제하면 MAX값 해제
	$("#fr_date,#to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});
</script>

<?php
include_once("./admin_tail.sub.php");
?>