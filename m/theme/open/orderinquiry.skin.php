<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<div id="sod_v">
	<div id="sod_fin_no">
		<strong>총 <?php echo number_format($total_count); ?>건</strong>의 주문내역이 있습니다.
	</div>

	<div id="sod_inquiry">			
		<?php
		for($i=0; $row=sql_fetch_array($result); $i++)
		{			
			echo '<li>'.PHP_EOL;
			
			$sql = " select * from shop_cart where od_id = '$row[od_id]' ";
			$sql.= " group by gs_id order by io_type asc, index_no asc ";
			$res = sql_query($sql);
			for($k=0; $ct=sql_fetch_array($res); $k++) {
				$rw = get_order($ct['od_no']);
				$gs = unserialize($rw['od_goods']);

				$href = TB_MSHOP_URL.'/view.php?gs_id='.$rw['gs_id'];
				
				$dlcomp = explode('|', trim($rw['delivery']));
				
				$delivery_str = '';
				if($dlcomp[0] && $rw['delivery_no']) {
					$delivery_str = get_text($dlcomp[0]).' '.get_text($rw['delivery_no']);
				}

				$uid = md5($rw['od_id'].$rw['od_time'].$rw['od_ip']);

				if($k == 0) {
		?>	        
            <div class="inquiry_idtime">
                <a href="<?php echo TB_MSHOP_URL; ?>/orderinquiryview.php?od_id=<?php echo $rw['od_id']; ?>&uid=<?php echo $uid; ?>" class="idtime_link"><?php echo $rw['od_id']; ?></a>
                <span class="idtime_time"><?php echo substr($rw['od_time'],2,8); ?></span>
            </div>
			<?php } ?>
			<div class="inquiry_info">
				<div class="inquiry_name">
					<a href="<?php echo $href; ?>"><?php echo get_text($gs['gname']); ?></a>
				</div>
				<div class="inquiry_price">
					<?php echo display_price($rw['use_price']); ?>
				</div>
				<div class="inquiry_inv">
					<span class="inv_status"><?php echo $gw_status[$rw['dan']]; ?></span>
					<span class="inv_inv"><?php echo $delivery_str; ?></span>
				</div>
			</div>
		
		<?php
			}
			echo '</li>'.PHP_EOL;
		}

        if($i == 0)
            echo '<li class="empty_list">주문 내역이 없습니다.</li>';
        ?>
    </ul>

	<?php 
	echo get_paging($config['mobile_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?page='); 
	?>

	</div>
</div>
