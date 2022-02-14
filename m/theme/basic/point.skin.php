<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<div id="point">
	<p id="sod_fin_no">
		총 <b class="fc_red"><?php echo number_format($total_count); ?></b>건의 포인트내역이 있습니다.
	</p>

	<ul id="point_ul">
		<?php
		$sum_point1 = $sum_point2 = $sum_point3 = 0;

		for($i=0; $row=sql_fetch_array($result); $i++) {
			$point1 = $point2 = 0;
			if($row['po_point'] > 0) {
				$point1 = '+' .number_format($row['po_point']);
				$sum_point1 += $row['po_point'];
			} else {
				$point2 = number_format($row['po_point']);
				$sum_point2 += $row['po_point'];
			}
		?>
        <li>
            <div class="point_wrap01">
                <span class="point_date"><?php echo conv_date_format('y-m-d H시', $row['po_datetime']); ?></span>
                <span class="point_log"><?php echo $row['po_content']; ?></span>
            </div>
            <div class="point_wrap02">
                <span class="point_inout"><?php if($point1) echo $point1; else echo $point2; ?></span>
            </div>
        </li>
        <?php
        }

        if($i == 0)
            echo '<li class="empty_list">자료가 없습니다.</li>';
        else {
            if($sum_point1 > 0)
                $sum_point1 = "+" . number_format($sum_point1);
            $sum_point2 = number_format($sum_point2);
        }
        ?>
    </ul>

    <div id="point_sum">
        <div class="sum_row">
            <span class="sum_tit">지급</span>
            <b class="sum_val"><?php echo $sum_point1; ?></b>
        </div>
        <div class="sum_row">
            <span class="sum_tit">사용</span>
            <b class="sum_val"><?php echo $sum_point2; ?></b>
        </div>
        <div class="sum_row">
            <span class="sum_tit">보유</span>
            <b class="sum_val"><?php echo number_format($member['point']); ?></b>
        </div>
    </div>

    <?php 
	echo get_paging($config['mobile_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?page=');
	?>
</div>
