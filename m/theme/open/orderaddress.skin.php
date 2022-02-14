<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form name="forderaddress" method="post">
<div id="sod_addr" class="new_win">
    <h1 id="win_title">배송지 목록</h1>

    <div class="win_desc">
		<ul>
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
			?>
            <li>
                <div class="addr_addr"><?php echo print_address($row['b_addr1'], $row['b_addr2'], $row['b_addr3'], $row['b_addr_jibeon']); ?></div>
				<div class="addr_name"><?php echo $row['b_name']; ?></div>
                <div class="addr_tel"><?php echo $row['b_cellphone']; ?></div>
                <div class="addr_btn">
                    <input type="hidden" value="<?php echo $addr; ?>">
                    <button type="button" id="btn_sel" class="btn_lsmall sel_address">선택</button>
                </div>
            </li>
            <?php
				}
			}

			if(!$total_count) {
				echo '<li class="empty_list">자료가 없습니다.</li>';
			}
			?>
        </ul>
    </div>

    <div class="win_btn">
		<button type="button" class="btn_medium bx-white" onclick="self.close();">창닫기</button>
    </div>
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
