<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>:::: 매뉴얼 ::::</title>
<link rel="stylesheet" href="/admin/manual/css/style.css" type="text/css">
<link rel="stylesheet" href="/admin/manual/css/DynamicTree.css" type="text/css">
<script type="text/javascript" src="/admin/manual/js/DynamicTree.js"></script>
</head>
<body topmargin="0" leftmargin="0" >
<div id="site_wrap">
	<div id="site_top_wrap">
		<div id="site_top">
			<div class="site_top_left">프랜차이즈몰</div>
			<div class="site_top_right">			
			<span class="bold">				
				<ul>
					<li><a href="http://tubeweb.co.kr/bbs/board.php?bo_table=service" target="_blank">질문과답변</a></li>	
				</ul>
			</div>
		</div>
	</div>
	<div class="clr"></div>
	<div id="site_main">
		<div id="body_left_wrap">
			<div class="blank"></div>
			<div class="clr blank"></div>

			<div class="main_tab_wrap">
				<div class="DynamicTree">
					<div class="top"><strong>통합매뉴얼</strong></div>
					<?php
					include "./side.html";
					?>
				</div>
			</div>
		</div>			
		<div id="body_right_wrap">
			<?php
			include "./content/".$_GET[act].".html";
			?>
		</div>
	</div>
	<div id="site_bottom_wrap"></div>	
</div>

<script type="text/javascript">
var tree = new DynamicTree("tree");
tree.init();
</script>
</body>
</html>
