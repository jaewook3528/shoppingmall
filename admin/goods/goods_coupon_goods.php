<?php
include_once("./_common.php");

$tb['title'] = '상품검색';
include_once(TB_ADMIN_PATH."/admin_head.php");

$query_string = $qstr;
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_goods a ";
$sql_search = " where a.use_aff = 0 and a.shop_state = 0 ";

if($sca) {
	$len = strlen($sca);
    $sql_common .= " left join shop_goods_cate b on a.index_no=b.gs_id ";
    $sql_search .= " and (left(b.gcate,$len) = '$sca') ";
}

if($stx && $sfl) {
    $sql_search .= " and (a.$sfl like '%$stx%') ";
}

if(!$orderby) {
    $filed = "a.index_no";
    $sod = "desc";
} else {
	$sod = $orderby;
}

$sql_order = " group by a.index_no order by $filed $sod";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(DISTINCT a.index_no) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select a.* $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

$btn_frmline = <<<EOF
<button type="button" onclick="btn_check('punish');" class="btn_lsmall bx-white">선택적용</button>
EOF;
?>

<div class="s_wrap">
	<h2><?php echo $tb['title']; ?></h2>
	<form name="fsearch" id="fsearch" method="get">
	<div class="tbl_frm01 mart7">
		<table class="tablef">
		<colgroup>
			<col class="w100">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">검색</th>
			<td>
				<select name="sfl">
					<?php echo option_selected('gname', $sfl, '상품명'); ?>
					<?php echo option_selected('gcode', $sfl, '상품코드'); ?>
					<?php echo option_selected('mb_id', $sfl, '업체코드'); ?>
					<?php echo option_selected('maker', $sfl, '제조사'); ?>
					<?php echo option_selected('origin', $sfl, '원산지'); ?>
				</select>
				<input type="text" name="stx" value="<?php echo $stx; ?>" class="frm_input" size="30">
			</td>
		</tr>
		<tr>
			<th scope="row">분류</th>
			<td>
				<?php echo get_goods_sca_select('sca', $sca); ?>
			</td>
		</tr>
		</tbody>
		</table>
	</div>
	<div class="btn_confirm">
		<input type="submit" value="검색" class="btn_lsmall">
		<input type="button" value="초기화" id="frmRest" class="btn_lsmall grey">
	</div>
	</form>

	<form name="fgoodslist" method="post">
	<input type="hidden" name="q1" value="<?php echo $q1; ?>">
	<input type="hidden" name="page" value="<?php echo $page; ?>">
	
	<div class="local_ov mart30">
		전체 : <b class="fc_red"><?php echo number_format($total_count); ?></b> 건 조회
	</div>
	<div class="local_frm01">
		<?php echo $btn_frmline; ?>
	</div>
	<div class="tbl_head01">
		<table>
		<colgroup>
			<col class="w50">
			<col class="w60">
			<col class="w80">
			<col class="w50">
			<col>
			<col class="w70">
			<col class="w70">
			<col class="w80">
			<col class="w60">
		</colgroup>
		<thead>
		<tr>
			<th><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
			<th>이미지</th>
			<th><?php echo subject_sort_link('a.gcode',$q2); ?>상품코드</a></th>
			<th><?php echo subject_sort_link('a.mb_id',$q2); ?>구분</a></th>
			<th><?php echo subject_sort_link('a.gname',$q2); ?>상품명</a></th>
			<th><?php echo subject_sort_link('a.isopen',$q2); ?>진열</a></th>
			<th><?php echo subject_sort_link('a.stock_qty',$q2); ?>재고</a></th>
			<th><?php echo subject_sort_link('a.goods_price',$q2); ?>판매가</a></th>
			<th>관리</th>
		</tr>
		</thead>
		<?php
		for($i=0; $row=sql_fetch_array($result); $i++) {
			$gs_id = $row['index_no'];
			$gs_img = get_it_image_url($gs_id, $row['simg1'], 40, 40);
			$gs_name = cut_str($row['gname'],70);
			$gs_price = $row['goods_price'];

			$s_upd = "<a href=\"javascript:chk_save('$gs_id','$gs_name','$gs_img','$gs_price');\" class=\"btn_small grey\">선택</a>";

			$bg = 'list'.($i%2);

			if($i==0)
				echo '<tbody class="list">'.PHP_EOL;
		?>
		<tr class="<?php echo $bg; ?>">
			<td>
				<input type="checkbox" name="chk[]" value="<?php echo $i; ?>">
				<input type="hidden" name="gs_id[]" value="<?php echo $gs_id; ?>">
				<input type="hidden" name="gs_name[]" value="<?php echo $gs_name; ?>">
				<input type="hidden" name="gs_img[]" value="<?php echo $gs_img; ?>">
				<input type="hidden" name="gs_price[]" value="<?php echo $gs_price; ?>">
			</td>
			<td><a href="<?php echo TB_SHOP_URL; ?>/view.php?index_no=<?php echo $gs_id; ?>" target="_blank"><?php echo get_it_image($gs_id, $row['simg1'], 40, 40); ?></a></td>
			<td><?php echo $row['gcode']; ?></td>
			<td><?php echo ($row['mb_id']=='admin')?"매입":"위탁"; ?></td>
			<td class="tal"><?php echo cut_str($row['gname'],50); ?></td>
			<td><?php echo $gw_isopen[$row['isopen']]; ?></td>
			<td class="tar"><?php echo number_format($row['stock_qty']); ?></td>
			<td class="tar"><?php echo number_format($row['goods_price']); ?></td>
			<td><?php echo $s_upd; ?></td>
		</tr>
		<?php 
		}
		if($i==0)
			echo '<tbody><tr><td colspan="9" class="empty_table">자료가 없습니다.</td></tr>';
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
</div>

<script>
function chk_save(gs_id, gs_name, gs_img, gs_price){
	var ck_use_goods = opener.document.getElementById('ck_use_goods');
	var cp_use_goods = opener.document.getElementById('cp_use_goods');
	var comma = '';

	var this_good = 'chk_'+gs_id;
	var ck_use_goods_info = ck_use_goods.outerHTML;

	var str = ck_use_goods.innerHTML;
	if(ck_use_goods_info.indexOf(this_good)==-1){
		str += "<li id=\"chk_"+gs_id+"\">";
		str += "<img src=\""+gs_img+"\" class=\"pr_img\">";
		str += "<p>"+gs_name+"</p><p class=\"mart5 bold\">"+commaStr(gs_price)+"원</p>";
		str += "<a href=\"javascript:chk_del('"+gs_id+"');\" class=\"bt_del\"><img src=\"<?php echo TB_ADMIN_URL; ?>/img/bt_delete.gif\"></a>";
		str += "</li>";
		ck_use_goods.innerHTML = str;

		if(cp_use_goods.value)
			comma = ',';

		cp_use_goods.value += comma + gs_id;
	}
}

function check_all(f)
{
    var chk = document.getElementsByName("chk[]");

    for (i=0; i<chk.length; i++)
        chk[i].checked = f.chkall.checked;
}

function btn_check(act)
{
	var f = document.fgoodslist;

    if(act == "punish")
        str = "적용";
    else
        return;

    var chk		 = document.getElementsByName("chk[]");
	var gs_id	 = document.getElementsByName("gs_id[]");
	var gs_name  = document.getElementsByName("gs_name[]");
	var gs_img   = document.getElementsByName("gs_img[]");
	var gs_price = document.getElementsByName("gs_price[]");
    var bchk = false;

	var cnt = 0;
    for(i=0; i<chk.length; i++)
    {
        if(chk[i].checked) {
            bchk = true;
			cnt++;

			chk_save(gs_id[i].value, gs_name[i].value, gs_img[i].value, gs_price[i].value);
		}
    }

    if(!bchk)
    {
        alert(str + "할 자료를 하나 이상 선택하세요.");
        return;
    } else {
		alert(cnt + "건이 적용 되었습니다.");
	}
}
</script>

<?php
include_once(TB_ADMIN_PATH.'/admin_tail.sub.php');
?>