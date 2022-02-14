<?php
if(!defined('_TUBEWEB_')) exit;
?>

<form name="fregform" method="post" action="./config/meta_update.php">
<input type="hidden" name="token" value="">

<h2>메타태그 설정</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">브라우저 타이틀</th>
		<td>
			<input type="text" name="head_title" value="<?php echo $config['head_title']; ?>" class="frm_input" size="60">
			<?php echo help('타이틀은 인터넷 브라우저 상단에 출력되는 문구이자, 검색엔진의 검색결과에서 제목부분으로 나타납니다.<br>타이틀은 간단명료하면서 페이지 내용에 대한 정보를 제공할 수 있도록작성하는 것이 좋습니다.'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">Author : 메타태그 1</th>
		<td>
			<input type="text" name="meta_author" value="<?php echo $config['meta_author']; ?>" class="frm_input" size="60">
			<?php echo help('페이지 또는 사이트의 제작자명을 명시할 수 있습니다. 간략하게 입력하세요.'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">description : 메타태그 2</th>
		<td>
			<input type="text" name="meta_description" value="<?php echo $config['meta_description']; ?>" class="frm_input" size="60">
			<?php echo help('검색엔진의 검색결과에서 페이지의 요약내용을 보여주는 부분으로 1-2개의 문장이나 짧은 단락을 사용하는 것이 좋습니다.'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">keywords : 메타태그 3</th>
		<td>
			<textarea name="meta_keywords" class="frm_textbox wfull" rows="5"><?php echo $config['meta_keywords']; ?></textarea>
			<?php echo help('사용자가 많이 검색하는 검색어 및 사이트와 연관된 키워드 정보를 기입합니다. 여러개이면 콤마(,)로 구분하여 입력하시기 바랍니다.'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">추가 메타태그</th>
		<td>
			<textarea name="add_meta" class="frm_textbox wfull" rows="5"><?php echo $config['add_meta']; ?></textarea>
			<?php echo help('추가로 사용하실 meta 태그를 입력합니다. 네이버 사이트소유확인 meta 태그 등'); ?>
			<p class="mart5"><a href="http://webmastertool.naver.com/" target="_blank" class="btn_small grey">네이버 웹마스터도구</a></p>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<h2>HEAD, BODY - JAVASCRIPT 추가입력</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">&lt;HEAD&gt; 내부 태그</th>
		<td>
			<textarea name="head_script" class="frm_textbox wfull" rows="5"><?php echo $config['head_script']; ?></textarea>
			<?php echo help('HTML의 &lt;HEAD&gt; ~ &lt;/HEAD&gt; 태그 사이에 추가될 소스를 설정합니다.<br><span class="fc_red">JAVASCRIPT 태그만 허용되며 그 외 다른 태그는 입력하시면 안됩니다.</span>'); ?>
			<p class="mart5"><a href="http://analytics.naver.com/" target="_blank" class="btn_small grey">네이버 애널리틱스 (웹로그분석)</a> <a href="https://www.google.com/intl/ko_KR/analytics/" target="_blank" class="btn_small grey">구글 애널리틱스 (웹로그분석)</a></p>
		</td>
	</tr>
	<tr>
		<th scope="row">&lt;BODY&gt; 내부 태그</th>
		<td>
			<textarea name="tail_script" class="frm_textbox wfull" rows="5"><?php echo $config['tail_script']; ?></textarea>
			<?php echo help('HTML의 &lt;BODY&gt; ~ &lt;/BODY&gt; 태그 사이에 추가될 소스를 설정합니다. (BODY 후반부에 삽입될 소스 삽입)<br><span class="fc_red">JAVASCRIPT 태그만 허용되며 그 외 다른 태그는 입력하시면 안됩니다.</span>'); ?>
			<p class="mart5"><a href="https://tocplus.co.kr/" target="_blank" class="btn_small grey">톡플러스 실시간 상담</a></p>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
</div>
</form>
