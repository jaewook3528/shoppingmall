<?php
if(!defined('_TUBEWEB_')) exit;

$qstr1 = 'page_rows='.$page_rows.'&sort='.$sort.'&sortodr='.$sortodr;
$qstr2 = 'page_rows='.$page_rows;

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

<script language="javascript">
function CountDownTimer(dt, id)
{
	var end = new Date(dt);

	var _second = 1000;
	var _minute = _second * 60;
	var _hour = _minute * 60;
	var _day = _hour * 24;
	var timer;

	function showRemaining() {
		var now = new Date();
		var distance = end - now;
		if (distance < 0) {
			clearInterval(timer);
			document.getElementById(id).innerHTML = 'EXPIRED!';
			return;
		}
		var days = Math.floor(distance / _day);
		var hours = Math.floor((distance % _day) / _hour);
		var minutes = Math.floor((distance % _hour) / _minute);
		var seconds = Math.floor((distance % _minute) / _second);
		var str = "";
		str += '<span class="num">'+days + '</span> 일 ';
		str += '<span class="num marl5">'+pad(hours,2) + '</span> : ';
		str += '<span class="num">'+pad(minutes,2) + '</span> : ';
		str += '<span class="num">'+pad(seconds,2) + '</span>';
		document.getElementById(id).innerHTML = str;
	}

	timer = setInterval(showRemaining, 1000);
}

function pad(n, width) {
  n = n + '';
  return n.length >= width ? n : new Array(width - n.length + 1).join('0') + n;
}
</script>

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

<?php
if(!$total_count) {
	echo "<p class=\"empty_list\">게시글이 없습니다.</p>";
} else {
	echo "<ul class=\"timesale\">";
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

		$eb_date = date("Y-m-d",strtotime("+1 day", strtotime($row['eb_date'])));
		$yy = substr($eb_date, 0, 4);
		$mm = substr($eb_date, 5, 2);
		$dd = substr($eb_date, 8, 2);
	?>
		<li>
			<a href="<?php echo $it_href; ?>">
			<dl>
				<dt><img src="<?php echo $it_imageurl; ?>"></dt>
				<dd class="ptime"><span id="countdown_<?php echo $i; ?>"></span></dd>
				<dd class="pname"><?php echo $it_name; ?></dd>
				<?php
				if($row['info_color']) {
					echo "<dd class=\"op_color\">";
					$arr = explode(",", trim($row['info_color']));
					for($g=0; $g<count($arr); $g++) {
						echo get_color_boder(trim($arr[$g]), 1);
					}
					echo "</dd>";
				}
				?>
				<dd class="price"><?php echo $it_sprice; ?><?php echo $it_price; ?></dd>
			</dl>
			</a>
			<span onclick="javascript:itemlistwish('<?php echo $row['index_no']; ?>')" id="<?php echo $row['index_no']; ?>" class="<?php echo $row['index_no']; ?> <?php echo zzimCheck($row['index_no']); ?>"></span>
			<script language="javascript">
			CountDownTimer("<?php echo $mm; ?>/<?php echo $dd; ?>/<?php echo $yy; ?> 00:00 AM", "countdown_<?php echo $i; ?>");
			</script>
		</li>
	<?php
	}
	echo "</ul>";
}

echo get_paging($config['mobile_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$qstr1.'&page=');
?>
