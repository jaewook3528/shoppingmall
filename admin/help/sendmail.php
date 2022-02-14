<?php
include_once("./_common.php");
include_once(TB_LIB_PATH."/mailer.lib.php");

$tb['title'] = '전체메일발송';
include_once(TB_ADMIN_PATH."/admin_head.php");

$mode = $_REQUEST['mode'];
$index_no = $_REQUEST['index_no'];

if($mode=='w') {
	check_demo();

	if($_POST['mb_grade']=='all')
		$sql ="select * from shop_member where mailser='Y' and id!='admin'";
	else
		$sql ="select * from shop_member where grade ='$_POST[mb_grade]' ";

	$res = sql_query($sql);
	while($r=sql_fetch_array($res)){
		$content = stripslashes($_POST['contents']);
		mailer($config['company_name'], $super['email'], $r['email'], $subject, $content, 1);
    }

	alert_close('정상적으로 메일이 발송 되었습니다');
}
?>

<h1 class="newp_tit"><?php echo $tb['title']; ?></h1>
<div class="new_win_body">
<form name="fregform" method="post" onsubmit="return fregform_submit(this)">
<input type="hidden" name="mode" value="w">
	<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w130">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">레벨전송</th>
		<td>
			<select name="mb_grade">
				<option value="all">전체전송</option>
				<?php
				$sql = "select * from shop_member_grade where gb_name!='' and gb_no!='1' order by gb_no ";
				$res = sql_query($sql);
				while($row = sql_fetch_array($res)) {
					echo "<option value=\"$row[gb_no]\">$row[gb_name]</option>\n";
				}
				?>
			</select>
		</td>
	</tr>
	<tr>
		<th scope="row">보내는이</th>
		<td><input type="text" name="send_name" value="<?php echo $super['email']; ?>" required itemname="보내는이" class="frm_input required"></td>
	</tr>
	<tr>
		<th scope="row">메일제목</th>
		<td><input type="text" name="subject" required itemname="메일제목" class="frm_input required wfull"></td>
	</tr>
	<tr>
		<th scope="row">메일내용</th>
		<td>
			<?php echo editor_html('contents', ""); ?>
		</td>
	</tr>
	</tbody>
	</table>
	</div>
	<div class="btn_confirm">
		<input type="submit" class="btn_medium" value="확인">
		<a href="javascript:self.close();" class="btn_medium bx-white">닫기</a>
	</div>
</form>
</div>

<script>
function fregform_submit(f) {
	<?php echo get_editor_js('contents'); ?>
	<?php echo chk_editor_js('contents'); ?>

	f.action = "./sendmail.php";
    return true;
}
</script>

<?php
include_once(TB_ADMIN_PATH.'/admin_tail.sub.php');
?>