<?php
if(!defined('_TUBEWEB_')) exit;

$pg_title = "회원목록";
include_once("./admin_head.sub.php");

if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $fr_date)) $fr_date = '';
if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $to_date)) $to_date = '';

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_member ";
$sql_search = " where pt_id = '{$member['id']}' ";

if($sfl && $stx) {
    $sql_search .= " and $sfl like '%$stx%' ";
}

if($sst) {
	$sql_search .= " and grade = '$sst' ";
}

// 기간검색
if($fr_date && $to_date)
    $sql_search .= " and {$spt} between '$fr_date 00:00:00' and '$to_date 23:59:59' ";
else if($fr_date && !$to_date)
	$sql_search .= " and {$spt} between '$fr_date 00:00:00' and '$fr_date 23:59:59' ";
else if(!$fr_date && $to_date)
	$sql_search .= " and {$spt} between '$to_date 00:00:00' and '$to_date 23:59:59' ";

if(!$orderby) {
    $filed = "index_no";
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

$is_intro = false;
$colspan = 12;
//if($config['cert_admin_yes'] && $config['cert_partner_yes']) {	//unltd 전체 적용시
if($partner['pt_cert_admin_yes'] && $partner['pt_cert_partner_yes']) {	//unltd   가맹점별 적용시
	$is_intro = true;
	$colspan++;
}

$btn_frmline = <<<EOF
<a href="./partner_member_list_excel.php?$q1" class="btn_lsmall bx-white"><i class="fa fa-file-excel-o"></i> 엑셀저장</a>
EOF;

include_once(TB_PLUGIN_PATH.'/jquery-ui/datepicker.php');
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
				<?php echo option_selected('id', $sfl, '아이디'); ?>
				<?php echo option_selected('name', $sfl, '회원명'); ?>
				<?php echo option_selected('cellphone', $sfl, '핸드폰'); ?>
				<?php echo option_selected('telephone', $sfl, '전화번호'); ?>
			</select>
			<input type="text" name="stx" value="<?php echo $stx; ?>" class="frm_input" size="30">
		</td>
	</tr>
	<tr>
		<th scope="row">기간검색</th>
		<td>
			<select name="spt">						
				<?php echo option_selected('reg_time', $spt, "가입날짜"); ?>
				<?php echo option_selected('today_login', $spt, "최근접속"); ?>
			</select>
			<?php echo get_search_date("fr_date", "to_date", $fr_date, $to_date); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">레벨검색</th>
		<td>				
			<?php echo get_search_level('sst', $sst, 2, 9); ?>
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
	총 회원수 : <b class="fc_red"><?php echo number_format($total_count); ?></b>명
</div>
<div class="local_frm01">
	<?php echo $btn_frmline; ?>
</div>
<div class="tbl_head01">
	<table>
	<colgroup>
		<col class="w50">
		<col class="w130">
		<col class="w150">
		<col class="w150">
		<col class="w130">
		<col class="w100">
		<col>
		<col class="w80">
		<col class="w40">
		<col class="w80">
		<col class="w60">
		<?php // if($is_intro) { ?>
		<col class="w40">
		<?php // } ?>
		<col class="w90">		
	</colgroup>
	<thead>
	<tr>
		<th scope="col">번호</th>
		<th scope="col"><?php echo subject_sort_link('name',$q2); ?>회원명</a></th>
		<th scope="col"><?php echo subject_sort_link('id',$q2); ?>아이디</a></th>
		<th scope="col"><?php echo subject_sort_link('grade',$q2); ?>레벨</a></th>
		<th scope="col">추천인</th>
		<th scope="col">핸드폰</th>
		<th scope="col">이메일</th>
		<th scope="col">가입일</th>		
		<th scope="col">메일</th>	
		<th scope="col"><?php echo subject_sort_link('login_sum',$q2); ?>로그인</a></th>
		<th scope="col">구매</th>
		<?php //if($is_intro) { ?>
		<th scope="col"><?php echo subject_sort_link('use_app',$q2); ?>인증</a></th>
		<?php //} ?>
		<th scope="col"><?php echo subject_sort_link('point',$q2); ?>포인트</a></th>		
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
		<td class="tal"><?php echo get_text($row['name']); ?></td>
		<td class="tal"><?php echo $row['id']; ?></td>
		<td><?php echo get_grade($row['grade']); ?></td>
		<td><?php echo $row['pt_id']; ?></td>
		<td><?php echo replace_tel($row['cellphone']); ?></td>
		<td class="tal"><?php echo $row['email']; ?></td>
		<td><?php echo substr($row['reg_time'],0,10); ?></td>
		<td><a href="<?php echo TB_ADMIN_URL; ?>/help/sendmail2.php?mail=<?php echo $row['email']; ?>" onclick="win_open(this,'pop_email','750','680','no'); return false"><img src="<?php echo TB_ADMIN_URL; ?>/img/bt_item_email.gif"></a></td>	
		<td><?php echo number_format($row['login_sum']); ?></td>	
		<td><?php echo number_format(shop_count($row['id'])); ?></td>		
		<?php // if($is_intro) { ?>
		<td><input type="checkbox" name="use_app" value="1"<?php echo ($row['use_app'])?' checked':''; ?> onclick="chk_use_app('<?php echo $row['id']; ?>');"></td>
		<?php //} ?>
		<td class="tar"><?php echo number_format($row['point']); ?> P</td>		
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tbody><tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>
<div class="local_frm02">
	<?php echo $btn_frmline; ?>
</div>

<?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=');
?>

<script>
function chk_use_app(mb_id) {
	var error = "";
	var token = get_ajax_token();
	if(!token) {
		alert("토큰 정보가 올바르지 않습니다.");
		return false;
	}

	$.ajax({
		url: tb_admin_url+"/member/member_use_app.php",
		type: "POST",
		data: {"mb_id": mb_id, "token": token },
		dataType: "json",
		async: false,
		cache: false,
		success: function(data, textStatus) {
			error = data.error;
		}
	});

	if(error) {
		alert(error);
		return false;
	}
}

$(function(){
	// 날짜 검색 : TODAY MAX값으로 인식 (maxDate: "+0d")를 삭제하면 MAX값 해제
	$("#fr_date, #to_date").datepicker({ changeMonth: true, changeYear: true, dateFormat: "yy-mm-dd", showButtonPanel: true, yearRange: "c-99:c+99", maxDate: "+0d" });
});
</script>

<?php
include_once("./admin_tail.sub.php");
?>