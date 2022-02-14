<?php
include_once("./_common.php");

$dan = trim($_POST['dan']);

$anew_benefit = array();
for($g=6; $g>1; $g--) {
	$anew_benefit[$g] = explode(chr(30), $config['pf_anew_benefit_'.$g]);
}

for($i=0; $i<(int)$dan; $i++) {
?>
<tr class="tr_alignc">
	<td class="bg3"><?php echo ($i+1); ?>UP</td>
	<?php 
	for($g=6; $g>1; $g--) {
		$amount = (int)trim($anew_benefit[$g][$i]);
	?>
	<td>                
		<label for="pf_anew_benefit_<?php echo $g; ?>_<?php echo $i; ?>" class="sound_only"><?php echo ($i+1); ?>UP <?php echo $g; ?>레벨 인센티브</label>
		<input type="text" name="pf_anew_benefit_<?php echo $g; ?>[<?php echo $i; ?>]" value="<?php echo $amount; ?>" id="pf_anew_benefit_<?php echo $g; ?>_<?php echo $i; ?>" class="frm_input" size="8"> 
	</td>
	<?php } ?>
</tr>
<?php 
}
if($i==0) {
	echo '<tr class="tr_alignc"><td colspan="6" class="empty_table">추가 인센티브 설정값이 없습니다.</td></tr>';
}
?>