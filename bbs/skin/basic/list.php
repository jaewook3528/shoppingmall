<?php
if(!defined('_TUBEWEB_')) exit;

$usecate = explode("|", $board['usecate']);

$sql_search2 = "";
if($default['de_board_wr_use']) {
	$sql_search2 = " and pt_id = '$pt_id' ";
}

$sql_common = " from shop_board_{$boardid} ";
$sql_search = " where btype = '2' {$sql_search2} ";

if($pt_id!='admin')
{
    $sql_search .= " AND pt_id='{$pt_id}' ";
}else{
    $sql_search .= " AND pt_id='admin' ";
}

if($ca_name)
	$sql_search .= " and ca_name='{$ca_name}' ";

if($sfl && $stx) {
    $sql_search .= " and ($sfl like '%$stx%') ";
}

$sql_order  = " order by fid desc, thread asc ";

$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = $board['page_num'];
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

$reply_limit = 6;
$run = 0;

$colspan = 6;
if(is_admin()) $colspan++;

$qstr1 = "boardid=$boardid$qstr&page=$page";
$qstr2 = "boardid=$boardid$qstr";
?>

<form name="fboardlist" method="post" action="del_all.php" onsubmit="return Check_Select(this);">
<input type='hidden' name="boardid" value="<?php echo $boardid; ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
<input type="hidden" name="stx" value="<?php echo $stx; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<?php if($board['use_category']) { ?>
<ul class="bo_cate">
	<li<?php if(!$ca_name) { echo ' class="active"'; } ?>><a href="<?php echo TB_BBS_URL; ?>/list.php?<?php echo $qstr1; ?>">전체</a></li>
	<?php
	for($i=0; $i<count($usecate); $i++) {
		$active = '';
		if($ca_name == $usecate[$i])
			$active = ' class="active"';
	?>
	<li<?php echo $active; ?>><a href="<?php echo TB_BBS_URL; ?>/list.php?<?php echo $qstr1; ?>&ca_name=<?php echo $usecate[$i]; ?>"><?php echo $usecate[$i]; ?></a></li>
	<?php } ?>
</ul>
<?php } ?>

<p class="pg_cnt">
	<em>총 <?php echo number_format($total_count); ?>개</em>의 게시물이 있습니다.
</p>

<div class="tbl_head01 tbl_wrap">
	<table>
	<colgroup>
		<col width="50">
		<?php if(is_admin()) { ?>
		<col width="50">
		<?php } ?>
		<col>
		<col width="90">
		<col width="50">
		<col width="50">
		<col width="90">
	</colgroup>
	<thead>
	<tr>
		<th scope="col">번호</th>
		<?php if(is_admin()) { ?>
		<th scope="col"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
		<?php } ?>
		<th scope="col">제목</th>
		<th scope="col">작성자</th>
		<th scope="col">파일</th>
		<th scope="col">조회</th>
		<th scope="col">등록일</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$sql = " select * from shop_board_{$boardid} where btype = '1' {$sql_search2} order by fid desc ";
	$rst = sql_query($sql);
	for($i=0; $row=sql_fetch_array($rst); $i++) {
		$bo_subject	= cut_str($row['subject'], $board['list_cut']);
		$bo_wdate	= date("Y-m-d", $row['wdate']);

		if($row['fileurl1'] || $row['fileurl2'])
			$bo_file_yes ="<img src='".$bo_img_url."/img/file_on.gif'>";
		else
			$bo_file_yes ="<img src='".$bo_img_url."/img/file_off.gif'>";

		if((TB_SERVER_TIME-$row['wdate']) < (60*60*24))
			$bo_newicon = "&nbsp;<img src='".$bo_img_url."/img/iconY.gif'>";
		else
			$bo_newicon = "";

		$bo_href = './read.php?index_no='.$row['index_no'].'&'.$qstr1;
	?>
	<tr>
		<td class="tac"><img src="<?php echo $bo_img_url; ?>/img/notice.gif"></td>
		<?php if(is_admin()) { ?>
		<td class="tac"><input type="checkbox" name="OrderNum[]" value="<?php echo $row['index_no']; ?>"></td>
		<?php } ?>
		<td class="tal">
			<a href="<?php echo $bo_href; ?>"><b><?php echo $bo_subject; ?></b></a><?php if($row['issecret'] == 'Y') { ?>&nbsp;<img src='<?php echo $bo_img_url; ?>/img/icon_secret.gif'><?php } ?><?php if($row['tailcount']) { ?>&nbsp;<img src='<?php echo $bo_img_url; ?>/img/dot_cnum.gif'>&nbsp;<span class="fc_197">(<?php echo $row['tailcount']; ?>)</span><?php } ?><?php echo $bo_newicon; ?>
		</td>
		<td class="tac"><?php echo $row['writer_s']; ?></td>
		<td class="tac"><?php echo $bo_file_yes; ?></td>
		<td class="tac"><?php echo $row['readcount']; ?></td>
		<td class="tac"><?php echo $bo_wdate; ?></td>
	</tr>
	<?php
		$run++;
	}

	for($i=0; $row=sql_fetch_array($result); $i++) {
		$bo_wdate = date("Y-m-d", $row['wdate']);

		if($row['fileurl1'] || $row['fileurl2'])
			$bo_file_yes ="<img src='".$bo_img_url."/img/file_on.gif'>";
		else
			$bo_file_yes ="<img src='".$bo_img_url."/img/file_off.gif'>";

		$spacer = strlen($row['thread'] != 'A');
		if($spacer > $reply_limit) {
			$spacer = $reply_limit;
		}

		$bo_subject = "";
		for($g=0; $g<$spacer; $g++) {
			$bo_subject .= "<img src='".$bo_img_url."/img/icon_reply.gif'>&nbsp;";
		}

		if($board['use_category'] == '1'  && $row['ca_name']) {
			$bo_subject .= '<strong>['.$row['ca_name'].']</strong>&nbsp;';
		}

		$bo_subject .= cut_str($row['subject'], $board['list_cut']);

		if((TB_SERVER_TIME-$row['wdate']) < (60*60*24))
			$bo_newicon = "&nbsp;<img src='".$bo_img_url."/img/iconY.gif'>";
		else
			$bo_newicon = "";

		$bo_href = './read.php?index_no='.$row['index_no'].'&'.$qstr1;
	?>
	<tr>
		<td class="tac"><?php echo $num--; ?></td>
		<?php if(is_admin()) { ?>
		<td class="tac"><input type="checkbox" name="OrderNum[]" value="<?php echo $row['index_no']; ?>"></td>
		<?php } ?>
		<td class="tal">
			<a href="<?php echo $bo_href; ?>"><?php echo $bo_subject; ?></a><?php if($row['issecret'] == 'Y') { ?>&nbsp;<img src="<?php echo $bo_img_url; ?>/img/icon_secret.gif"><?php } ?>&nbsp;<?php if($row['tailcount']) { ?><img src='<?php echo $bo_img_url; ?>/img/dot_cnum.gif'>&nbsp;<span class="fc_197">(<?php echo $row['tailcount']; ?>)</span><?php } ?><?php echo $bo_newicon; ?>
		</td>
		<td class="tac"><?php echo $row['writer_s']; ?></td>
		<td class="tac"><?php echo $bo_file_yes; ?></td>
		<td class="tac"><?php echo $row['readcount']; ?></td>
		<td class="tac"><?php echo $bo_wdate; ?></td>
	</tr>
	<?php
		$run++;
	}
	?>
	<?php if(!$run) { ?>
	<tr><td colspan="<?php echo $colspan; ?>" class="empty_table">게시물이 없습니다.</td></tr>
	<?php } ?>
	</tbody>
	</table>
</div>

<div class="page_wrap">
	<?php if(is_admin()) { ?>
	<div class="lbt_box">
		<input type="submit" value="삭제" class="btn_lsmall bx-white">
	</div>
	<?php } ?>
	<?php if($member['grade'] <= $board['write_priv']) { ?>
	<div class="rbt_box">
		<a href="./write.php?boardid=<?php echo $boardid; ?>" class="btn_lsmall">글쓰기</a>
	</div>
	<?php } ?>
</div>
</form>

<form name="searchform" method="get">
<input type="hidden" name="boardid" value="<?php echo $boardid; ?>">
<div class="bottom_sch">
	<select name="sfl">
	<?php
	for($i=0;$i<sizeof($gw_search_value);$i++) {
		echo "<option value='{$gw_search_value[$i]}'".get_selected($gw_search_value[$i], $sfl).">{$gw_search_text[$i]}</option>\n";
	}
	?>
	</select>
	<input type="text" name="stx" class="frm_input" value="<?php echo $stx; ?>">
	<input type="submit" value="검색" class="btn_lsmall grey">
</div>
</form>

<?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr2.'&page=');
?>

<script>
function Check_Select(form) {
	var check_nums = document.fboardlist.elements.length;
	for(var i=0; i<check_nums; i++) {
		var checkbox_obj = eval("document.fboardlist.elements[" + i + "]");
		if(checkbox_obj.checked == true) {
			break;
		}
	}

	if(i == check_nums) {
		alert ("삭제할 게시물을 하나 이상 선택하세요!");
			return false;
	} else {
		if(!confirm("한번 삭제한 자료는 복구할 수 없습니다.\n\n선택한 항목을 정말 삭제 하시겠습니까?"))
			return false;

		document.fboardlist.submit();
	}
}

function check_all(f)
{
    var chk = document.getElementsByName("OrderNum[]");

    for(i=0; i<chk.length; i++)
        chk[i].checked = f.chkall.checked;
}
</script>
