<?php
if(!defined('_TUBEWEB_')) exit;
?>

<div class="cont_wrap">
	<!-- 베스트 쇼핑특가 및 배너 시작 { -->
	<div class="best_wrap">
		<div class="bnr1"><?php echo display_banner(3, $pt_id); ?></div>
		<div class="bnr2"><?php echo display_banner(4, $pt_id); ?></div>
		<div class="bnr3"><?php echo display_banner(5, $pt_id); ?></div>
		<div class="best_rol_slide">
			<h2>쇼핑특가</h2>
			<?php
			$res = display_itemtype($pt_id, 1, 20);
			$type1_count = sql_num_rows($res);
			if($type1_count) {
			?>
			<div class="best_rol">
				<?php
				for($i=0; $row=sql_fetch_array($res); $i++) {
					$it_href = TB_SHOP_URL.'/view.php?index_no='.$row['index_no'];
					$it_image = get_it_image($row['index_no'], $row['simg1'], 190, 190);
					$it_name = cut_str($row['gname'], 100);
					$it_price = get_price($row['index_no']);
					$it_amount = get_sale_price($row['index_no']);
					$it_point = display_point($row['gpoint']);

					// (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
					$it_sprice = $sale = '';
					if($row['normal_price'] > $it_amount && !is_uncase($row['index_no'])) {
						$sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
						$sale = '<dd class="sale">'.number_format($sett,0).'%</dd>';
						$it_sprice = display_price2($row['normal_price']);
					}
				?>
				<dl>
					<?php echo $sale; ?>
					<a href="<?php echo $it_href; ?>">
						<dt class="pimg"><?php echo $it_image; ?></dt>
						<dd class="pname"><?php echo $it_name; ?></dd>
						<dd class="price"><?php echo $it_sprice; ?><?php echo $it_price; ?></dd>
					</a>
					<dd class="ic_bx"><span onclick="javascript:itemlistwish('<?php echo $row['index_no']; ?>');" id="<?php echo $row['index_no']; ?>" class="<?php echo $row['index_no'].' '.zzimCheck($row['index_no']); ?>"></span> <a href="<?php echo $it_href; ?>" target="_blank" class="nwin"></a></dd>
				</dl>
				<?php } ?>
			</div>
			<?php } ?>
		</div>
		<?php if($type1_count) { ?>
		<script>
		$(document).ready(function(){
			$('.best_rol').slick({
				autoplay: true,
				dots: false
			});
		});
		</script>
		<?php
        # 쇼핑특가 품목 없음
		}else{
		    # 해당 열 배너 없음
		    if(display_banner(3, $pt_id)=='' && display_banner(4, $pt_id)=='' && display_banner(5, $pt_id)=='')
            {
                #display:none;
                ?>
                <script>
                    $('.best_wrap').hide();
                </script>
                <?
            }
        } ?>
	</div>
	<!-- } 베스트 쇼핑특가 및 배너 끝 -->
</div>

<!-- 카테고리별 베스트 시작 {-->
<div class="cont_wrap">
	<?php
	if($default['de_maintype_best']) {
		$list_best = unserialize(base64_decode($default['de_maintype_best']));
		foreach ($list_best as $r)
        {
            if($r['code']!='')
            {
                $t[] = $r;
            }
        }
		$list_count = count($t);
//		$list_count = count($list_best);
		$tab_width = (float)(100 / $list_count);
	?>
	<h2 class="mtit mart65"><span><?php echo $default['de_maintype_title']; ?></span></h2>
	<ul class="bestca_tab">
		<?php for($i=0; $i<$list_count; $i++) { ?>
		<li data-tab="bstab_c<?php echo $i; ?>" style="width:<?php echo $tab_width; ?>%"><span><?php echo trim($list_best[$i]['subj']); ?></span></li>
		<?php } ?>
	</ul>

    <style> .bestca li { width: 33.3%; } </style>
	<div class="bestca">
		<?php echo get_listtype_cate($list_best, '209', '209'); ?>
	</div>
	<script>
	$(document).ready(function(){
		$(".bestca_tab>li:eq(0)").addClass('active');
		$("#bstab_c0").show();

		$(".bestca_tab>li").click(function() {
			var activeTab = $(this).attr('data-tab');
			$(".bestca_tab>li").removeClass('active');
			$(".bestca ul").hide();
			$(this).addClass('active');
			$("#"+activeTab).fadeIn(250);
		});
	});
	</script>
	<?php } ?>

	<div class="wide_bn mart40"><?php echo display_banner(6, $pt_id); ?></div>
</div>
<!-- } 카테고리별 베스트 끝 -->
<? if(goods_type_result("2")){ ?>
<!-- 베스트상품 시작 {-->
<div class="cont_bg mart40">
	<h2 class="mtit"><span>베스트셀러</span></h2>
	<?php echo get_listtype_best("2", '400', '400', '7', 'mart20'); ?>
</div>
<!-- } 베스트상품 끝 -->
<? } ?>
<? if(goods_type_result("3")){ ?>
<!-- 신상품 시작 { -->
<div class="cont_wrap mart60">
	<h2 class="mtit"><span>신상품</span></h2>
	<?php echo get_listtype_skin("3", '235', '235', '12', 'wli4 mart5'); ?>
</div>
<!-- } 신상품 끝 -->
<? } ?>
<!-- 큰 배너 배경 및 문구 시작 { -->
<?php echo mask_banner(7, $pt_id); ?>
<!-- } 큰 배너 배경 및 문구 끝 -->
<? if(goods_type_result("4")){ ?>
<!-- 인기상품 시작 { -->
<div class="cont_wrap mart60">
	<h2 class="mtit"><span>인기상품</span></h2>
	<?php echo get_listtype_skin("4", '315', '315', '6', 'wli3 mart5'); ?>
</div>
<!-- } 인기상품 끝  -->
<? } ?>
<?php if(display_banner(8, $pt_id) || display_banner(9, $pt_id) || display_banner(10, $pt_id) || display_banner(11, $pt_id)) { ?>
    <!-- 중간 배너영역 시작 { -->
    <ul class="mmd_bn mart60">
        <li class="bnr1"><?php echo display_banner(8, $pt_id); ?></li>
        <li class="bnr2"><?php echo display_banner(9, $pt_id); ?></li>
        <li class="bnr3"><?php echo display_banner(10, $pt_id); ?></li>
        <li class="bnr4"><?php echo display_banner(11, $pt_id); ?></li>
    </ul>
    <!-- } 중간 배너영역 끝 -->
<?php } ?>


<? if(goods_type_result("5")){ ?>
<!-- 추천상품 시작 { -->
<div class="cont_wrap mart60">
	<h2 class="mtit"><span>추천상품</span></h2>
	<?php echo get_listtype_skin("5", '190', '190', '10', 'wli5 mart5'); ?>
</div>
<!-- } 추천상품 끝 -->
<? } ?>

<?
function get_listtype_skin_iro36($type){
    return get_listtype_skin($type, '315', '315', '6', 'wli3 mart5');
}