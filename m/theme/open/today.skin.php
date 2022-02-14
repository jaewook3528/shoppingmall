<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<div id="sod_ws">
	<p id="sod_fin_no">
		<strong>총 <?php echo number_format($total_count); ?>개</strong>의 최근 본 상품이 있습니다.
	</p>

	<?php 
	if(!$total_count) {
		echo "<p class=\"empty_list\">최근 본 상품이 없습니다.</p>";
	} else {
	?>
	<div class="ws_wrap">
		<table>
		<tbody>
		<?php
		for($i=0; $row=sql_fetch_array($result); $i++) {
			$gs_id = $row['index_no'];
			$it_href = TB_MSHOP_URL.'/view.php?gs_id='.$gs_id;
		?>
		<tr>
			<td class="wish_img"><a href="<?php echo $it_href; ?>"><?php echo get_it_image($gs_id, $row['simg1'], 60, 60); ?></a></td>
			<td class="wish_info">
				<div class="wish_gname">
					<a href="<?php echo $it_href; ?>"><?php echo cut_str($row['gname'],80); ?></a>
					<div class="bold mart5"><?php echo mobile_price($gs_id)?></div>
				</div>
				<div class="wish_del"><a href="<?php echo TB_MSHOP_URL; ?>/today.php?w=d&gs_id=<?php echo $gs_id; ?>" class="btn_small grey">삭제</a></div>
			</td>
		</tr>
		<?php } ?>
		</tbody>
		</table>
	</div>
	<?php
	}
	
	echo get_paging($config['mobile_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?page='); 
	?>
</div>
