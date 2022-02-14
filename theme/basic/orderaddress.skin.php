<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form name="forderaddress" id="forderaddress" method="post" class="new_win">
<h1 id="win_title"><?php echo $tb['title']; ?></h1>

<div class="tbl_head02 tbl_wrap">
	<table>
	<colgroup>
		<col class="w50">
		<col>
		<col class="w50">
	</colgroup>
	<thead>
	<tr>
		<th scope="col">번호</th>
		<th scope="col">주소</th>
		<th scope="col">선택</th>
	</tr>
	</thead>
	<tbody>
	<?php
		$sep = chr(30);
		$k = 0; $ar_mk = array();
		for($i=0; $row=sql_fetch_array($result); $i++)
		{
			$info = array();
			$info[] = $row['b_name'];			
			$info[] = $row['b_cellphone'];
			$info[] = $row['b_telephone'];
			$info[] = $row['b_zip'];
			$info[] = $row['b_addr1'];
			$info[] = $row['b_addr2'];
			$info[] = $row['b_addr3'];
			$info[] = $row['b_addr_jibeon'];

			$addr = implode($sep, $info);			
			$addr = get_text($addr);

			if(!in_array($addr, $ar_mk)) {
				$k++;
				$ar_mk[$i] = $addr;

				$bg = 'list'.($k%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td class="tac"><?php echo $k; ?></td>
		<td><?php echo print_address($row['b_addr1'], $row['b_addr2'], $row['b_addr3'], $row['b_addr_jibeon']); ?></td>
		<td class="tac">
			<input type="hidden" value="<?php echo $addr; ?>">	
			<a href="javascript:void(0);" class="sel_address btn_small">선택</a>
		</td>
	</tr>
	<?php
		}
	}

	if($k==0)
		echo '<tr><td colspan="3" class="empty_list">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>

<div class="win_btn">
	<a href="javascript:window.close()" class="btn_lsmall bx-white">창닫기</a>
</div>	
</form>

<script>
$(function() {
    $(".sel_address").on("click", function() {
        var addr = $(this).siblings("input").val().split(String.fromCharCode(30));

        var f = window.opener.buyform;
		f.b_name.value			= addr[0];
		f.b_cellphone.value		= addr[1];
		f.b_telephone.value		= addr[2];
		f.b_zip.value			= addr[3];
		f.b_addr1.value			= addr[4];
		f.b_addr2.value			= addr[5];
		f.b_addr3.value			= addr[6];
		f.b_addr_jibeon.value	= addr[7];

        var zip = addr[3].replace(/[^0-9]/g, "");
        if(zip != "") {
            var code = String(zip);
			window.opener.calculate_sendcost(code);
        }

        window.close();
    });
});
</script>
