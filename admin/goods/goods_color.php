<?php
include_once("./_common.php");

$tb['title'] = '색상선택';
include_once(TB_ADMIN_PATH."/admin_head.php");

$sql_common = " from shop_goods_color ";

$sql = " select count(*) as cnt ".$sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];
$num = $total_count;

$sql = " select * $sql_common order by index_no desc ";
$result = sql_query($sql);

$colspan = 3;
$is_admin = is_admin();
if($is_admin) {
	$colspan++;
}
?>

<script src="<?php echo TB_JS_URL; ?>/colorpicker.js"></script>

<div class="new_win">
	<h1><?php echo $tb['title']; ?></h1>
	<form name="fcolor" id="fcolor" method="post" action="./goods_color_update.php">
	<input type="hidden" name="token" value="">
	<div id="scp_list_find">
		<div id="colorpicker" class="dib vam">
			<input type="text" name="gd_color" class="cValue frm_input w150 dib vam" required placeholder="예) #0000FF" value="">
			<div class="colorbox dib vam" style="width:21px;height:21px;background-color:#0000ff;border:1px solid #efefef;"></div>
		</div>
		<input type="checkbox" name="gd_b_use" class="marl10" value="1"> 테두리 사용
		<input type="submit" value="색상등록" class="btn_small red fr">
		<p class="mart5">※ 연한 색상 컬러만 테두리를 사용해 주시면 됩니다.</p>
	</div>
	<script>
		$('#colorpicker').ColorPicker({
			color: '#000ff',
			onShow: function (colpkr) {
				$(colpkr).fadeIn(500);
				return false;
			},
			onHide: function (colpkr) {
				$(colpkr).fadeOut(500);
				return false;
			},
			onChange: function (hsb, hex, rgb) {
				$('#colorpicker').find('.cValue').val('#'+hex.toUpperCase());
				$('#colorpicker').find('.colorbox').css('backgroundColor', '#' + hex.toUpperCase());
			}
		});
	</script>
	</form>

	<div class="new_win_desc marb10">
		총 등록된 색상 : <?php echo number_format($total_count); ?>개
	</div>

	<div class="tbl_head01 tbl_wrap">
		<table>
		<colgroup>
			<col width="50px">
			<col>
			<col width="60px">
			<?php if($is_admin) { ?>
			<col width="60px">
			<?php } ?>
		</colgroup>
		<thead>
		<tr>
			<th scope="col">번호</th>
			<th scope="col">색상코드</th>
			<th scope="col">테두리사용</th>
			<?php if($is_admin) { ?>
			<th scope="col">삭제</th>
			<?php } ?>
		</tr>
		</thead>
		<?php
		for($i=0; $row=sql_fetch_array($result); $i++) {
			$it_b_use = ($row['gd_b_use']) ? "yes" : "";

			if($i==0)
				echo '<tbody class="list">'.PHP_EOL;

			$bg = 'bg'.($i%2);
		?>
		<tr class="<?php echo $bg; ?>">
			<td><?php echo $num--; ?></td>
			<td>
				<div>
					<?php echo strtoupper($row['gd_color']); ?>
					<div class="colorbox dib vam marl5" style="width:21px;height:21px;background-color:<?php echo strtoupper($row['gd_color']); ?>;border: 1px solid #efefef;"></div>
				</div>
			</td>
			<td><?php echo $it_b_use; ?></td>
			<?php if($is_admin) { ?>
			<td><a href="./goods_color_update.php?w=d&index_no=<?php echo $row['index_no']; ?>" class="btn_small" onclick="return delete_confirm(this);">삭제</a></td>
			<?php } ?>
		</tr>
		<?php
		}
		if($i==0)
			echo '<tbody><tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
		?>
		</tbody>
		</table>
	</div>

    <div class="btn_confirm marb50">
		<button type="button" onclick="window.close();" class="btn_lsmall bx-white">창닫기</button>
    </div>
</div>

<?php
include_once(TB_ADMIN_PATH.'/admin_tail.sub.php');
?>