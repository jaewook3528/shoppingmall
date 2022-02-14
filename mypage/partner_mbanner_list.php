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

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_banner ";
$sql_search = " where bn_device = 'mobile' and bn_theme = '{$member['mobile_theme']}' and mb_id = '{$member['id']}' ";

if(isset($sca) && is_numeric($sca)) {
	$sql_search .= " and bn_code = '$sca' ";
}

if(!$orderby) {
    $filed = "bn_code";
    $sod = "desc";
} else {
	$sod = $orderby;
}

$sql_order = " order by $filed $sod ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

$btn_frmline = <<<EOF
<input type="submit" name="act_button" value="선택수정" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
<input type="submit" name="act_button" value="선택삭제" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
<a href="./partner_mbanner_list_update.php?mod_type=R" class="btn_lsmall bx-red" onclick="return default_confirm(this, '');"><i class="fa fa-refresh fa-spin"></i> 본사와 동일하게 설정값 초기화</a>
<a href="./page.php?code=partner_mbanner_form" class="fr btn_lsmall red"><i class="ionicons ion-android-add"></i> 추가하기</a>
EOF;
?>

<h2>코드검색</h2>
<form name="fsearch" id="fsearch" method="get">
<input type="hidden" name="code" value="<?php echo $code; ?>">
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w100">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">노출위치</th>
		<td>
			<select name="sca">
				<?php 
				echo option_selected('', $sca, '전체');
				for($i=0; $i<count($position); $i++)
					echo option_selected($position[$i][0], $sca, $position[$i][3]); 
				?>
			</select>
		</td>
	</tr>
	</tbody>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="검색" class="btn_medium">
	<input type="button" value="초기화" id="frmRest" class="btn_medium grey">
</div>
</form>

<form name="fmbannerlist" id="fmbannerlist" method="post" action="./partner_mbanner_list_update.php" onsubmit="return fmbannerlist_submit(this);">
<input type="hidden" name="q1" value="<?php echo $q1; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<h2>모바일스킨 (<?php echo $member['mobile_theme']; ?>) 배너목록</h2>
<div class="local_ov">
	전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회
	<span class="ov_a fc_red">순서는 숫자가 작을수록 우선 순위로 노출되며 롤링 및 연속배너에만 적용됩니다. 고정배너는 의미없음</span>
</div>
<div class="local_frm01">
	<?php echo $btn_frmline; ?>
</div>
<div class="tbl_head01">
	<table class="tablef">
	<colgroup>
		<col class="w50">
		<col class="w50">
		<col class="w50">
		<col class="w60">
		<col>
		<col>
		<col class="w80">
		<col class="w80">
		<col class="w80">
		<col class="w60">
	</colgroup>
	<thead>
	<tr>
		<th scope="col" rowspan="2"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
		<th scope="col" rowspan="2"><?php echo subject_sort_link('bn_code',$q2); ?>코드</a></th>
		<th scope="col" rowspan="2"><?php echo subject_sort_link('bn_use',$q2); ?>노출</a></th>
		<th scope="col" rowspan="2"><?php echo subject_sort_link('bn_order',$q2); ?>순서</a></th>
		<th scope="col">노출위치</th>
		<th scope="col">링크주소</th>	
		<th scope="col">TARGET</th>	
		<th scope="col">가로사이즈</th>	
		<th scope="col">세로사이즈</th>
		<th scope="col">관리</th>
	</tr>
	<tr class="rows">
		<th scope="col" colspan="6">이미지</th>
	</tr>
	</thead>
	<tbody>
	<?php
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$bn_id = $row['bn_id'];		

		$position = $gw_mbanner[$row['bn_theme']];
		foreach($position as $key=>$value) {
			list($pos, $wpx, $hpx, $subj) = $value;
			if($pos == $row['bn_code']) break;
		}

		$bimg_str = '';
		$bimg = TB_DATA_PATH."/banner/{$row['bn_file']}";
		if(is_file($bimg) && $row['bn_file']) {
			$size = @getimagesize($bimg);
			if($size[0] && $size[0] > 700)
				$width = 700;
			else
				$width = $size[0];

			$bimg = rpc($bimg, TB_PATH, TB_URL);
			$bimg_str = '<img src="'.$bimg.'" width="'.$width.'">';
		}

		$s_upd = "<a href='./page.php?code=partner_mbanner_form&w=u&bn_id=$bn_id$qstr&page=$page' class=\"btn_small\">수정</a>";

		$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td rowspan="2">			
			<input type="hidden" name="bn_id[<?php echo $i; ?>]" value="<?php echo $bn_id; ?>">
			<input type="checkbox" name="chk[]" value="<?php echo $i; ?>">
		</td>
		<td rowspan="2"><?php echo $row['bn_code']; ?></td>
		<td rowspan="2"><input type="checkbox" name="bn_use[<?php echo $i; ?>]" value="1" <?php echo get_checked($row['bn_use'],"1"); ?>></td>
		<td rowspan="2"><input type="text" name="bn_order[<?php echo $i; ?>]" value="<?php echo $row['bn_order']; ?>" class="frm_input"></td>
		<td class="tal"><?php echo $subj; ?></td>	
		<td><input type="text" name="bn_link[<?php echo $i; ?>]" value="<?php echo $row['bn_link']; ?>" placeholder="URL" class="frm_input"></td>
		<td>
			<select name="bn_target[<?php echo $i; ?>]">
				<?php echo option_selected('_self', $row['bn_target'], "현재창"); ?>
				<?php echo option_selected('_blank', $row['bn_target'], "새창"); ?>
			</select>
		</td>	
		<td><input type="text" name="bn_width[<?php echo $i; ?>]" value="<?php echo $row['bn_width']; ?>" class="frm_input"></td>
		<td><input type="text" name="bn_height[<?php echo $i; ?>]" value="<?php echo $row['bn_height']; ?>" class="frm_input"></td>
		<td><?php echo $s_upd; ?></td>
	</tr>
	<tr class="<?php echo $bg; ?> rows">
		<td class="td_img_view sbn_img" colspan="6">
			<div class="sbn_image"><?php echo $bimg_str; ?></div>
			<button type="button" class="btn_lsmall bx-blue sbn_img_view">이미지보기</button>
			<button type="button" class="btn_lsmall bx-yellow sbn_all_view">모두보기</button>
			<button type="button" class="btn_lsmall bx-yellow sbn_all_close">모두닫기</button>
		</td>
	</tr>
	<?php 
	}
	if($i==0)
		echo '<tbody><tr><td colspan="10" class="empty_table">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>
<div class="local_frm02">
	<?php echo $btn_frmline; ?>
</div>
</form>

<?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=');
?>

<script>
function fmbannerlist_submit(f)
{
    if(!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }

    return true;
}

$(function(){
    $(".sbn_img_view").click(function(){
        var $con = $(this).closest(".td_img_view").find(".sbn_image");
        if($con.is(":visible")) {
            $con.slideUp("fast");
            $(this).text("이미지보기");
        } else {
            $con.slideDown("fast");
            $(this).text("이미지닫기");
        }
    });

	// 모두보기
    $(".sbn_all_view").click(function(){
        $(".sbn_image").slideDown("fast");
		$(".sbn_img_view").text("이미지닫기");
    });
	
	// 모두닫기
    $(".sbn_all_close").click(function(){
        $(".sbn_image").slideUp("fast");
		$(".sbn_img_view").text("이미지보기");
    });
});
</script>

<?php
include_once("./admin_tail.sub.php");
?>