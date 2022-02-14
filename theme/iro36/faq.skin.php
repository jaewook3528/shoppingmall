<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TB_THEME_PATH.'/aside_cs.skin.php');
?>

<div id="con_lf">
	<h2 class="pg_tit">
		<span><?php echo $tb['title']; ?></span>
		<p class="pg_nav">HOME<i>&gt;</i>고객센터<i>&gt;</i><?php echo $tb['title']; ?></p>
	</h2>

	<nav id="tab_cate">
		<h2>자주묻는질문 분류</h2>
		<ul id="tab_cate_ul">
			<?php
			if(!$faqcate) { $faqcate = 1; }
			$sql = get_faq_table_sql($pt_id,'shop_faq_cate');
			$res = sql_query($sql);
			for($i=0; $row=sql_fetch_array($res); $i++) {
				$category_option = "";
				if($row['index_no'] == $faqcate) {
					$category_option = ' class="active"';
				}
			?>
			<li<?php echo $category_option;?>><a href="<?php echo TB_BBS_URL; ?>/faq.php?faqcate=<?php echo $row['index_no']; ?>"><?php echo $row['catename']; ?></a></li>
			<?php } ?>
		</ul>
	</nav>

	<ul class="faq_li">
		<?php
        $sql = get_faq_table_sql($pt_id,"shop_faq","cate='{$faqcate}'");
		$res = sql_query($sql);
		for($i=0; $row=sql_fetch_array($res); $i++) {
		?>
		<li class="faq_q" onclick="js_faq('<?php echo $i; ?>');">
			<?php echo $row['subject']; ?>
		</li>
		<li id="sod_faq_con_<?php echo $i; ?>" class="faq_a">
			<?php echo get_view_thumbnail(conv_content($row['memo'], 1), 700); ?>
		</li>
		<?php } ?>
	</ul>
	<?php if($i==0) { ?>
	<div class="empty_list">등록된 FAQ가 없습니다.</div>
	<?php } ?>

	<script>
	function js_faq(id){
		var $con = $("#sod_faq_con_"+id);
		if($con.is(":visible")) {
			$con.slideUp("fast");
			$(".faq_q").removeClass("active");
		} else {
			$(".faq_a:visible").slideUp("fast");
			$con.slideDown("fast");
			$(".faq_q").removeClass("active");
			$con.prev().addClass("active");
		}
	}
	</script>
</div>
