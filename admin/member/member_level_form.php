<?php
if(!defined('_TUBEWEB_')) exit;

$sql_common = " from shop_member_grade ";
$sql_order  = " order by gb_no desc ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt $sql_common ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select * $sql_common $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

$arr_memo = array();
$arr_memo[0] = '쇼핑몰 이용회원 (최하위)';
$arr_memo[1] = '쇼핑몰 이용회원';
$arr_memo[2] = '쇼핑몰 이용회원 (최상위)';
$arr_memo[3] = '가맹점 회원 (최하위)';
$arr_memo[4] = '가맹점 회원';
$arr_memo[5] = '가맹점 회원';
$arr_memo[6] = '가맹점 회원';
$arr_memo[7] = '가맹점 회원 (최상위)';
$arr_memo[8] = '최고 관리자';
?>

<h2>세부설정</h2>
<form name="frmlist" method="post" action="./member/member_level_form_update.php">
<div class="tbl_head01">
	<table>
	<colgroup>
		<col class="w70">
		<col class="w170">
		<col class="w130">
		<col class="w150">
		<col>
	</colgroup>
	<thead>
	<tr>
		<th scope="col">레벨</th>
		<th scope="col">레벨명</th>
		<th scope="col">할인률</th>
		<th scope="col">절삭</th>
		<th scope="col">비고</th>
	</tr>
	</thead>
	<tbody class="list">
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td class="bold">
			Lv.<?php echo $num--; ?>
			<input type="hidden" name="gb_no[<?php echo $i; ?>]" value="<?php echo $row['gb_no']; ?>">
			<input type="hidden" name="chk[]" value="<?php echo $i; ?>" checked>			
		</td>
		<td><input type="text" name="gb_name[<?php echo $i; ?>]" value="<?php echo $row['gb_name']; ?>" class="frm_input wfull"></td>
		<td>
			<input type="text" name="gb_sale[<?php echo $i; ?>]" value="<?php echo $row['gb_sale']; ?>" class="frm_input w70">
			<select name="gb_sale_rate[<?php echo $i; ?>]">
				<option value="0"<?php echo get_selected($row['gb_sale_rate'], '0'); ?>>%</option>
				<option value="1"<?php echo get_selected($row['gb_sale_rate'], '1'); ?>>원</option>
			</select>
		</td>
		<td class="tac">
			<select name="gb_sale_unit[<?php echo $i; ?>]">
				<?php echo option_selected('0', $row['gb_sale_unit'], '사용안함'); ?>
				<?php echo option_selected('100', $row['gb_sale_unit'], '백원 단위절삭'); ?>
				<?php echo option_selected('1000', $row['gb_sale_unit'], '천원 단위절삭'); ?>
				<?php echo option_selected('10000', $row['gb_sale_unit'], '만원 단위절삭'); ?>
			</select>
		</td>
		<td class="tal"><?php echo $arr_memo[$i]; ?></td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large">
</div>
</form>
