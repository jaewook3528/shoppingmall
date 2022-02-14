<?php
if(!defined('_TUBEWEB_')) exit;
?>

<h2 class="pg_title">
	<div class="inner">
		<dl class="txt_bx">
			<dt>기획전</dt>
			<dd>특별한 기획전 상품을 만나보세요</dd>
		</dl>
	</div>
</h2>
<ul class="plan">
	<?php
	$sql = "select * from shop_plan where pl_use = '1' ";
	$res = sql_query($sql);
	for($i=0; $row=sql_fetch_array($res); $i++) {
		$href = TB_SHOP_URL.'/planlist.php?pl_no='.$row['pl_no'];
		$bimg = TB_DATA_PATH.'/plan/'.$row['pl_limg'];
		if(is_file($bimg) && $row['pl_limg']) {
			$pl_limgurl = rpc($bimg, TB_PATH, TB_URL);
		} else {
			$pl_limgurl = TB_IMG_URL.'/plan_noimg.gif';
		}
	?>
	<li>
		<a href="<?php echo $href; ?>">
		<p class="plan_img"><img src="<?php echo $pl_limgurl; ?>"></p>
		<p class="plan_tit"><?php echo $row['pl_name']; ?></p>
		</a>
	</li>
	<?php } ?>
</ul>
