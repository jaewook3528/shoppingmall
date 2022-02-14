<?php
if(!defined("_TUBEWEB_")) exit;

$script = '';
$comma1 = '';

// 1차
$query1 = sql_query(get_admin_category($target_table, ''));
for($i=0; $row1=sql_fetch_array($query1); $i++) // 1차 분류
{	
	if($i==0) $script .= "multi_select['first'] = '';\n";
	$script .= "multi_select['first'] += '{$comma1}{$row1['catecode']}|".addslashes($row1['catename'])."';\n";	
	$comma1 = ',';
	$comma2 = '';

	$query2 = sql_query(get_admin_category($target_table, $row1['catecode']));
	for($j=0; $row2=sql_fetch_array($query2); $j++) // 2차 분류
	{	
		if($j==0) $script .= "multi_select['{$row1['catecode']}'] = '';\n";
		$script .= "multi_select['{$row1['catecode']}'] += '{$comma2}{$row2['catecode']}|".addslashes($row2['catename'])."';\n";	
		$comma2 = ',';
		$comma3 = '';

		$query3 = sql_query(get_admin_category($target_table, $row2['catecode']));
		for($k=0; $row3=sql_fetch_array($query3); $k++) // 3차 분류
		{
			if($k==0) $script .= "multi_select['{$row2['catecode']}'] = '';\n";
			$script .= "multi_select['{$row2['catecode']}'] += '{$comma3}{$row3['catecode']}|".addslashes($row3['catename'])."';\n";	
			$comma3 = ',';
			$comma4 = '';

			$query4 = sql_query(get_admin_category($target_table, $row3['catecode']));
			for($m=0; $row4=sql_fetch_array($query4); $m++) // 4차 분류
			{
				if($m==0) $script .= "multi_select['{$row3['catecode']}'] = '';\n";
				$script .= "multi_select['{$row3['catecode']}'] += '{$comma4}{$row4['catecode']}|".addslashes($row4['catename'])."';\n";	
				$comma4 = ',';
				$comma5 = '';
					
				$query5 = sql_query(get_admin_category($target_table, $row4['catecode']));
				for($n=0; $row5=sql_fetch_array($query5); $n++) // 5차 분류
				{
					if($n==0) $script .= "multi_select['{$row4['catecode']}'] = '';\n";
					$script .= "multi_select['{$row4['catecode']}'] += '{$comma5}{$row5['catecode']}|".addslashes($row5['catename'])."';\n";	
					$comma5 = ',';
				}
			}
		}
	}
}
?>

<script>
var multi_select = new Array();
<?php echo "\n$script"; ?>
</script>