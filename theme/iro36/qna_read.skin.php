<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TB_THEME_PATH.'/aside_cs.skin.php');
?>

<div id="con_lf">
	<h2 class="pg_tit">
		<span><?php echo $tb['title']; ?></span>
		<p class="pg_nav">HOME<i>&gt;</i>고객센터<i>&gt;</i><?php echo $tb['title']; ?></p>
	</h2>

	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w100">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">제목</th>
			<td><?php echo $qa['subject']; ?></td>
		</tr>
		<tr>
			<th scope="row">내용</th>
			<td style="height:150px;vertical-align:top;"><?php echo nl2br($qa['memo']); ?></td>
		</tr>
		</tbody>
		</table>
	</div>

	<?php if($qa['result_yes']) { ?>
	<h3 class="anc_tit mart30">답변내용</h3>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w100">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">답변일</th>
			<td><?php echo substr($qa['result_date'],0,10); ?></td>
		</tr>
		<tr>
			<th scope="row">답변내용</th>
			<td style="height:150px;vertical-align:top;"><?php echo nl2br($qa['reply']); ?></td>
		</tr>
		</tbody>
		</table>
	</div>
	<?php } ?>

	<div class="btn_confirm">
		<a href="<?php echo TB_BBS_URL; ?>/qna_write.php" class="btn_lsmall">상담문의하기</a>
		<a href="<?php echo TB_BBS_URL; ?>/qna_modify.php?index_no=<?php echo $index_no; ?>" class="btn_lsmall bx-white">수정</a>
		<a href="<?php echo TB_BBS_URL; ?>/qna_list.php" class="btn_lsmall bx-white">목록</a>
		<a href="javascript:del('<?php echo TB_BBS_URL; ?>/qna_read.php?index_no=<?php echo $index_no; ?>&mode=d');" class="btn_lsmall bx-white">삭제</a>
	</div>
</div>

<script>
function del(url) {
	answer = confirm("삭제 하시겠습니까?");
	if(answer==true) {
		location.href = url;
	}
}
</script>