<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<!-- 브랜드 검색 { -->
<div class="br_search">
	<form name="frmbrandsearch">
	<input type="hidden" name="qsort" id="qsort" value="<?php echo $qsort; ?>">
	<input type="hidden" name="qorder" id="qorder" value="<?php echo $qorder; ?>">
	<input type="hidden" name="qword" id="qword" value="<?php echo $qword; ?>">
	<dl class="sch_inner">
		<dt><label for="ssch_q">SEARCH</label></dt>
		<dd><input type="text" name="qstx" value="<?php echo $qstx; ?>" id="ssch_q" placeholder="브랜드명을 입력해주세요." maxlength="30"><button type="submit" class="btn_submit fa fa-search"></button></dd>
	</dl>
	<div id="br_sch">
		<ul class="sch_tab">
			<li<?php if($qsort == 'br_name') echo ' class="active"'; ?>><a href="javascript:set_sort('br_name', 'asc');">가나다순</a></li>
			<li<?php if($qsort == 'br_name_eng') echo ' class="active"'; ?>><a href="javascript:set_sort('br_name_eng', 'asc');">알파벳순</a></li>
		</ul>
		<div class="sch_tab_con">
			<ul>
				<li class="w35<?php if($qword == '') echo ' active'; ?>" onclick="set_word('');">전체</li>
				<?php
				foreach($charAt as $word) {
					$word_active = '';
					if($qword == $word)
						$word_active = ' class="active"';
					echo "<li{$word_active} onclick=\"set_word('{$word}');\">{$word}</li>\n";
				}
				?>
				<li class="w35<?php if($qword == 'etc') echo ' active'; ?>" onclick="set_word('etc');">기타</li>
			</ul>
		</div>
	</div>
	</form>
</div>

<script>
function set_sort(qsort, qorder)
{
    var f = document.frmbrandsearch;
    f.qsort.value = qsort;
    f.qorder.value = qorder;
	f.qword.value = '';
    f.submit();
}

function set_word(qword)
{
    var f = document.frmbrandsearch;
    f.qword.value = qword;
    f.submit();
}
</script>
<!-- } 브랜드 검색 -->

<!-- 브랜드 로고 { -->
<?php
$brand_str = "<div class='br_list'>\n";
$brand_str.= "<ul>\n";

for($i=0; $i<count($list); $i++) {
	$brand_str .= "<li>\n<a href='".$list[$i]['br_href']."'>\n";
	if($qsort != 'br_name_eng') {
		$brand_str .= "<img src='".$list[$i]['br_logo']."' title='".$list[$i]['br_name']."'>\n";
		$brand_str .= "<p class='mart8'>".$list[$i]['br_name']."</p>\n";
	}else {
		$brand_str .= "<img src='".$list[$i]['br_logo']."' title='".$list[$i]['br_name_eng']."'>\n";
		$brand_str .= "<p class='mart8'>".$list[$i]['br_name_eng']."</p>\n";
	}

	$brand_str .= "</a>\n</li>\n";
}
$brand_str .= "</ul>\n</div>\n";
echo $brand_str;
?>
<!-- } 브랜드 로고 -->
