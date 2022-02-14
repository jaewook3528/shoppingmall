<?php
include_once("./_common.php");

$gs = get_goods($index, 'ppay_fee');

for($i=0; $i<(int)$no; $i++){
	$arr = explode(chr(30), trim($gs['ppay_fee']));
	if($arr[$i] == "")
		$ppay = 0;
	else
		$ppay = trim($arr[$i]);
?>
<input type="text" name="ppay_fee[]" value="<?php echo $ppay; ?>" class="frm_input w80">
<?php } ?>