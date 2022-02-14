<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가

$qstr1 = 'br_id='.$br_id.'&sort='.$sort.'&sortodr='.$sortodr;
$qstr2 = 'br_id='.$br_id;

$sort_str = '';
for($i=0; $i<count($gw_msort); $i++) {
	list($tsort, $torder, $tname) = $gw_msort[$i];

	$sct_sort_href = $_SERVER['SCRIPT_NAME'].'?'.$qstr2.'&sort='.$tsort.'&sortodr='.$torder;

	if($sort == $tsort && $sortodr == $torder)
		$sort_name = $tname;
	if($i==0 && !($sort && $sortodr))
		$sort_name = $tname;

	$sort_str .= '<li><a href="'.$sct_sort_href.'">'.$tname.'</a></li>'.PHP_EOL;
}
?>

<h2 class="br_view_tit">
	<span class="tit_txt"><?php echo $br['br_name'];?></span>
	<span class="tit_logo"><img src="<?php echo $br_logo;?>" title="<?php echo $br['br_name']; ?>"></span>
</h2>

<!-- 상품 정렬 선택 시작 { -->
<div id="sct_sort">
	<div class="count">전체 <strong><?php echo number_format($total_count); ?></strong>개</div>
	<span id="btn_sort"><?php echo $sort_name; ?></span>
</div>
<div id="sort_li">
	<h2>상품 정렬</h2>
	<ul>
		<?php echo $sort_str; // 탭메뉴 ?>
	</ul>
	<span id="sort_close" class="ionicons ion-ios-close-empty"></span>
</div>
<div id="sort_bg"></div>

<script>
$(function() {
	var mbheight = $(window).height();

	$('#btn_sort').click(function(){
		$('#sort_bg').fadeIn(300);
		$('#sort_li').slideDown('fast');
		$('html').css({'height':mbheight+'px', 'overflow':'hidden'});
	});

	$('#sort_bg, #sort_close').click(function(){
		$('#sort_bg').fadeOut(300);
		$('#sort_li').slideUp('fast');
		$('html').css({'height':'100%', 'overflow':'scroll'});
	});
});
</script>
<!-- } 상품 정렬 선택 끝 -->

<div>
	<p class="sct_li_type">
		<a href=""><img src="<?php echo TB_MTHEME_URL; ?>/img/bt_litype1.gif"></a>
		<a href="wli2"><img src="<?php echo TB_MTHEME_URL; ?>/img/bt_litype2_on.gif"></a>
		<a href="wli3"><img src="<?php echo TB_MTHEME_URL; ?>/img/bt_litype3.gif"></a>
	</p>

	<?php
	if(!$total_count) {
		echo "<p class=\"empty_list\">자료가 없습니다.</p>";
	} else {
		echo "<ul class=\"pr_desc wli2\">";
		for($i=0; $row=sql_fetch_array($result); $i++) {
			$it_href = TB_MSHOP_URL.'/view.php?gs_id='.$row['index_no'];
			$it_name = cut_str($row['gname'], 50);
			$it_imageurl = get_it_image_url($row['index_no'], $row['simg2'], 400, 400);
			$it_price = mobile_price($row['index_no']);
			$it_amount = get_sale_price($row['index_no']);
			$it_point = display_point($row['gpoint']);

			// (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
			$it_sprice = $sale = '';
			if($row['normal_price'] > $it_amount && !is_uncase($row['index_no'])) {
				$sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
				$sale = '<span class="sale">['.number_format($sett,0).'%]</span>';
				$it_sprice = display_price2($row['normal_price']);
			}

			echo "<li>";
				echo "<a href=\"{$it_href}\">";
				echo "<dl>";
					echo "<dt><img src=\"{$it_imageurl}\"></dt>";
					echo "<dd class=\"pname\">{$it_name}</dd>\n";
					if($row['info_color']) {
						echo "<dd class=\"op_color\">\n";
						$arr = explode(",", trim($row['info_color']));
						for($g=0; $g<count($arr); $g++) {
							echo get_color_boder(trim($arr[$g]), 1);
						}
						echo "</dd>\n";
					}
					echo "<dd class=\"price\">{$it_sprice}{$it_price}</dd>\n";
				echo "</dl>";
				echo "</a>";
				echo "<span onclick='javascript:itemlistwish(\"$row[index_no]\")' id='$row[index_no]' class='$row[index_no] ".zzimCheck($row['index_no'])."'></span>\n";
			echo "</li>";
		}
		echo "</ul>";
	}

	echo get_paging($config['mobile_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr1.'&page=');
	?>
</div>
