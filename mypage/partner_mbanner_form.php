<?php
if(!defined('_TUBEWEB_')) exit;

$pg_title = "통합 배너관리(모바일)";
include_once("./admin_head.sub.php");

// 배너코드를 얻는다.
$position = $gw_mbanner[$member['mobile_theme']];
if(!count($position)) {
	echo <<<EOF
	<div class="local_desc01 local_desc">
		<p class="lh6">
			{$member['mobile_theme']} 스킨의 노출위치 변수가 설정되어있지 않습니다.<br>이 경우는 별도 디자인작업을 통한 변수 설정 누락건이며 운영자에게 문의하시기 바랍니다.
		</p>
	</div>
EOF;
	include_once("./admin_tail.sub.php");
	return;
}

if($w == "") {
	$bn['bn_width']  = $position[0][1];
	$bn['bn_height'] = $position[0][2];
	$bn['bn_code']   = 1;
	$bn['bn_use']    = 1;
	$bn['bn_order']  = 0;
} else if($w == "u") {
	$bn	= sql_fetch("select * from shop_banner where bn_id='$bn_id'");
    if(!$bn['bn_id'])
        alert("존재하지 않은 배너 입니다.");
}

$frm_submit = '<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
	<a href="./page.php?code=partner_mbanner_list'.$qstr.'&page='.$page.'" class="btn_large bx-white">목록</a>'.PHP_EOL;
if($w == 'u')
	$frm_submit .= '<a href="./page.php?code=partner_mbanner_form" class="btn_large bx-red">추가</a>'.PHP_EOL;
$frm_submit .= '</div>';
?>

<script src="<?php echo TB_JS_URL; ?>/colorpicker.js"></script>

<form name="fbanner" id="fbanner" action="./partner_mbanner_form_update.php" method="post" enctype="MULTIPART/FORM-DATA">
<input type="hidden" name="w" value="<?php echo $w; ?>">	
<input type="hidden" name="sca"  value="<?php echo $sca; ?>">
<input type="hidden" name="page"  value="<?php echo $page; ?>">
<input type="hidden" name="bn_id" value="<?php echo $bn_id; ?>">
<input type="hidden" name="token" value="">

<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w140">
		<col> 
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">노출위치</th>
		<td>
			<select name="bn_code" onchange="chk_js_position(this.value);">
			<?php 
			for($i=0; $i<count($position); $i++)
				echo option_selected($position[$i][0], $bn['bn_code'], $position[$i][3]); 
			?>
			</select>
			<?php echo help('[고정] 같은 위치에 고정되어 노출되며 2개이상 등록시 랜덤으로 노출됩니다.<br>[연속] 2개이상 등록시 세로로 연속하여 노출됩니다.<br>[롤링] 2개이상 등록시 슬라이드형식으로 롤링되며 노출됩니다.'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">배너파일</th>
		<td>
			<input type="file" name="bn_file" id="bn_file">
			<?php
			$bimg_str = "";
			$bimg = TB_DATA_PATH.'/banner/'.$bn['bn_file'];
			if(is_file($bimg) && $bn['bn_file']) {
				$size = @getimagesize($bimg);
				if($size[0] && $size[0] > 700)
					$width = 700;
				else
					$width = $size[0];

				$bimg = rpc($bimg, TB_PATH, TB_URL);

				echo '<input type="checkbox" name="bn_file_del" value="'.$bn['bn_file'].'" id="bn_file_del"> <label for="bn_file_del">삭제</label>';
				$bimg_str = '<img src="'.$bimg.'" width="'.$width.'">';
			}
			if($bimg_str) {
				echo '<div class="banner_or_img">'.$bimg_str.'</div>';
			}
			?>
		</td>
	</tr>
	<tr>
		<th scope="row">링크주소</th>
		<td>
			<input type="text" name="bn_link" value="<?php echo $bn['bn_link']; ?>" class="frm_input" size="40">
			<select name="bn_target">
				<?php echo option_selected('_self', $bn['bn_target'], "현재창에서"); ?>
				<?php echo option_selected('_blank', $bn['bn_target'], "새창으로"); ?>
			</select>
			<?php echo help('[외부링크] http:// 를 포함해 절대경로로 입력해주시기 바랍니다.<br><span class="fc_197">절대경로 예시) http://test.com/shop/listtype.php?type=1</span>'); ?>
			<?php echo help('[내부링크] http:// 를 제외한 상대경로로 입력해주시기 바랍니다.<br><span class="fc_197">상대경로 예시) /shop/listtype.php?type=1</span>'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">가로사이즈</th>
		<td><input type="text" name="bn_width" value="<?php echo $bn['bn_width']; ?>" class="frm_input" size="7"> px</td>
	</tr>
	<tr>
		<th scope="row">세로사이즈</th>
		<td><input type="text" name="bn_height" value="<?php echo $bn['bn_height']; ?>" class="frm_input" size="7"> px</td>
	</tr>
	<tr>
		<th scope="row">백그라운드 색상</th>
		<td>
			<input type="text" name="bn_bg" value="<?php echo $bn['bn_bg']; ?>" id="bn_bg" class="frm_input" size="7" maxlength="6"> 
			<?php echo help('"#" 기호없이 색상값 6자만 입력하세요. 예) F6E3FB'); ?>
			<script>
			$(function() {
				$('#bn_bg').ColorPicker({
					onSubmit: function(hsb, hex, rgb, el) {
						$(el).val(hex);
						$(el).ColorPickerHide();
					},
					onBeforeShow: function () {
						$(this).ColorPickerSetColor(this.value);
					}
				})
				.bind('keyup', function(){
					$(this).ColorPickerSetColor(this.value);
				});
			});
			</script>
		</td>
	</tr>
	<tr>
		<th scope="row">배너문구</th>
		<td>
			<input type="text" name="bn_text" value="<?php echo $bn['bn_text']; ?>" class="frm_input" size="40">
			<?php echo help('특정 배너에만 문구가 노출되는 부분으로 모든 배너에 문구가 노출되지는 않습니다.'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">순서</th>
		<td>
			<input type="text" name="bn_order" value="<?php echo $bn['bn_order']; ?>" class="frm_input" size="7"> 숫자가 작을수록 우선 순위로 노출 됩니다.
			<?php echo help('롤링 및 연속배너에만 적용됩니다. (고정배너는 의미없음)'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">노출여부</th>
		<td>
			<input type="checkbox" name="bn_use" value="1" id="bn_use_yes" <?php echo get_checked($bn['bn_use'], "1"); ?>> <label for="bn_use_yes">노출함</label>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<?php echo $frm_submit; ?>
</form>

<script>
function chk_js_position(val) { 
    switch(val){
		<?php for($i=0; $i<count($position); $i++) { ?>
		case '<?php echo $position[$i][0]; ?>':
			$("input[name=bn_width]").val('<?php echo $position[$i][1]; ?>');
			$("input[name=bn_height]").val('<?php echo $position[$i][2]; ?>');
			break;
		<?php } ?>
		default:
			$("input[name=bn_width]").val('<?php echo $position[0][1]; ?>');
			$("input[name=bn_height]").val('<?php echo $position[0][2]; ?>');
			break;
	}
}
</script>

<?php
include_once("./admin_tail.sub.php");
?>