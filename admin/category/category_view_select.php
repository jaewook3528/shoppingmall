<?php
include_once("./_common.php");

switch($sel_id):
	case "sel_ca2": $len = 6;  break;
	case "sel_ca3": $len = 9;  break;
	case "sel_ca4": $len = 12;  break;
	case "sel_ca5": $len = 15; break;
endswitch;
?>
<form name="frm_<?php echo $sel_id; ?>" method="post" target="hiddenframe">	
<input type="hidden" name="W" value="<?php echo $sel_id; ?>">
<select multiple name="<?php echo $sel_id; ?>[]" id="<?php echo $sel_id; ?>" class="multiple-select2">
<?php
$sql = " select catecode, catename
		   from shop_cate
		  where length(catecode) = '$len' 
			and catecode like '$catecode%'
		  order by list_view, catecode ";
$result = sql_query($sql);
for($i=0; $row=sql_fetch_array($result); $i++) {
	echo '<option value="'.$row['catecode'].'">'.$row['catename'].'</option>'.PHP_EOL;
}
?>
</select>
<div class="btn_confirm03">
<button type="button" class="btn_small bx-white" onclick="category_move('<?php echo $sel_id; ?>','prev')">▲ 위로</button>
<button type="button" class="btn_small bx-white" onclick="category_move('<?php echo $sel_id; ?>','next')">▼ 아래로</button>
<button type="button" class="btn_small sel_submit">저장</button>
</div>
</form>