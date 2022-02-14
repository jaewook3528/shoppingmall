<?php
include_once("./_common.php");

$tb['title'] = '이미지 뷰어';
include_once(TB_PATH."/head.sub.php");

$size = getimagesize($_GET['img']);
if($size[0] > 980) {	
	$size[1] = $size[1] / $size[0] * 980;
}

$width = $size[0];
$height = $size[1];

$winwidth = $size[0]+30;
$winheight = $size[1]+110;
?>

<div id="sit_pvi_nw">
	<table width="<?php echo $size[0]; ?>" height="<?php echo $size[1]; ?>">
	<tr>
		<td width="<?php echo $size[0]; ?>" height="<?php echo $size[1]; ?>"><a href="javascript:window.close();"><img src="<?php echo $_GET['img']; ?>"></a></td>
	</tr>
	</table>
</div>

<script>
$(function(){
	// 창 사이즈 조절
	$(window).on("load", function() {
		var w = <?php echo $size[0]; ?> + 50;
		var h = $("#sit_pvi_nw").outerHeight(true) + $("#sit_pvi_nw h1").outerHeight(true);
		window.resizeTo(w, h);
	});
});
</script>

<?php
include_once(TB_PATH."/tail.sub.php");
?>