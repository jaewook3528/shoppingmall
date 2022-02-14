<?php
if(!defined('_TUBEWEB_')) exit;

if($sel_ca1) $sca = $sel_ca1;
if($sel_ca2) $sca = $sel_ca2;
if($sel_ca3) $sca = $sel_ca3;
if($sel_ca4) $sca = $sel_ca4;
if($sel_ca5) $sca = $sel_ca5;

if(isset($sel_ca1))			$qstr .= "&sel_ca1=$sel_ca1";
if(isset($sel_ca2))			$qstr .= "&sel_ca2=$sel_ca2";
if(isset($sel_ca3))			$qstr .= "&sel_ca3=$sel_ca3";
if(isset($sel_ca4))			$qstr .= "&sel_ca4=$sel_ca4";
if(isset($sel_ca5))			$qstr .= "&sel_ca5=$sel_ca5";
if(isset($q_date_field))	$qstr .= "&q_date_field=$q_date_field";
if(isset($q_brand))			$qstr .= "&q_brand=$q_brand";
if(isset($q_zone))			$qstr .= "&q_zone=$q_zone";
if(isset($q_stock_field))	$qstr .= "&q_stock_field=$q_stock_field";
if(isset($fr_stock))		$qstr .= "&fr_stock=$fr_stock";
if(isset($to_stock))		$qstr .= "&to_stock=$to_stock";
if(isset($q_price_field))	$qstr .= "&q_price_field=$q_price_field";
if(isset($fr_price))		$qstr .= "&fr_price=$fr_price";
if(isset($to_price))		$qstr .= "&to_price=$to_price";
if(isset($q_isopen))		$qstr .= "&q_isopen=$q_isopen";
if(isset($q_option))		$qstr .= "&q_option=$q_option";
if(isset($q_supply))		$qstr .= "&q_supply=$q_supply";
if(isset($q_notax))			$qstr .= "&q_notax=$q_notax";
if(isset($psys_long_use))			$qstr .= "&psys_long_use=$psys_long_use";	//unltd 장기무이자 검색 조건 추가

$query_string = "code=$code$qstr";
$q1 = $query_string;
$q2 = $query_string."&page=$page";

$sql_common = " from shop_goods a ";
$sql_search = " where a.use_aff = 0 and a.shop_state = 0 ";

include_once(TB_ADMIN_PATH.'/goods/goods_query.inc.php');

if(!$orderby) {
    $filed = "a.index_no";
    $sod = "desc";
} else {
	$sod = $orderby;
}

$sql_order = " group by a.index_no order by $filed $sod ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(DISTINCT a.index_no) as cnt $sql_common $sql_search ";

$row = sql_fetch($sql);
$total_count = $row['cnt'];

if($_SESSION['ss_page_rows'])
	$page_rows = $_SESSION['ss_page_rows'];
else
	$page_rows = 30;

$rows = $page_rows;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함
$num = $total_count - (($page-1)*$rows);

$sql = " select a.* $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

$target_table = 'shop_cate';
include_once(TB_LIB_PATH."/categoryinfo.lib.php");
include_once(TB_PLUGIN_PATH.'/jquery-ui/datepicker.php');

$btn_frmline = <<<EOF
<input type="submit" name="act_button" value="선택삭제" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
<input type="submit" name="act_button" value="선택순위수정" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
<input type="submit" name="act_button" value="선택상품복사" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
<a href="./goods/goods_list_excel.php?$q1" class="btn_lsmall bx-white"><i class="fa fa-file-excel-o"></i> 엑셀저장</a>
<a href="./goods.php?code=form" class="fr btn_lsmall red"><i class="ionicons ion-android-add"></i> 상품등록</a>
<input type="submit" name="act_button" value="선택장기무이자수정" class="btn_lsmall bx-white" onclick="document.pressed=this.value">
EOF;
?>

<h2>기본검색</h2>
<form name="fsearch" id="fsearch" method="get">
<input type="hidden" name="code" value="<?php echo $code; ?>">
<div class="tbl_frm01">
	<table class="tablef">
	<colgroup>
		<col class="w100">
		<col>
		<col class="w100">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">검색어</th>
		<td colspan="3">
			<select name="sfl">
				<?php echo option_selected('gname', $sfl, '상품명'); ?>				
				<?php echo option_selected('gcode', $sfl, '상품코드'); ?>
				<?php echo option_selected('mb_id', $sfl, '업체코드'); ?>
				<?php echo option_selected('maker', $sfl, '제조사'); ?>
				<?php echo option_selected('origin', $sfl, '원산지'); ?>
				<?php echo option_selected('model', $sfl, '모델명'); ?>
				<?php echo option_selected('explan', $sfl, '짧은설명'); ?>
			</select>
			<input type="text" name="stx" value="<?php echo $stx; ?>" class="frm_input" size="30">
		</td>
	</tr>
	<tr>
		<th scope="row">카테고리</th>
		<td colspan="3">
			<script>multiple_select('sel_ca');</script>
		</td>
	</tr>
	<tr>
		<th scope="row">기간검색</th>
		<td colspan="3">
			<select name="q_date_field" id="q_date_field">						
				<?php echo option_selected('update_time', $q_date_field, "최근수정일"); ?>
				<?php echo option_selected('reg_time', $q_date_field, "최초등록일"); ?>
			</select>
			<?php echo get_search_date("fr_date", "to_date", $fr_date, $to_date); ?>
			<?php if($member['id'] == 'admin') { // unltd?>
			<?php echo check_checked('psys_long_use', $psys_long_use, '1', '장기무이자'); ?>
			<?php } ?>
		</td>
	</tr>
	<tr>
		<th scope="row">브랜드</th>
		<td>
			<select name="q_brand">
				<?php
				echo option_selected('', $q_brand, '전체');				
				$sql = "select * from shop_brand where br_user_yes='0' order by br_name asc ";
				$res = sql_query($sql);
				while($row = sql_fetch_array($res)){
					echo option_selected($row['br_id'], $q_brand, $row['br_name']);
				}
				?>
			</select>
		</td>
		<th scope="row">배송가능 지역</th>
		<td>
			<select name="q_zone">
				<?php echo option_selected('',  $q_zone, '전체'); ?>
				<?php echo option_selected('전국', $q_zone, '전국'); ?>
				<?php echo option_selected('강원도', $q_zone, '강원도'); ?>
				<?php echo option_selected('경기도', $q_zone, '경기도'); ?>
				<?php echo option_selected('경상도', $q_zone, '경상도'); ?>
				<?php echo option_selected('서울/경기도', $q_zone, '서울/경기도'); ?>
				<?php echo option_selected('서울특별시', $q_zone, '서울특별시'); ?>
				<?php echo option_selected('전라도', $q_zone, '전라도'); ?>
				<?php echo option_selected('제주도', $q_zone, '제주도'); ?>
				<?php echo option_selected('충청도', $q_zone, '충청도'); ?>
			</select>
		</td>
	</tr>
	<tr>
		<th scope="row">상품재고</th>
		<td>
			<select name="q_stock_field" id="q_stock_field">
				<?php echo option_selected('stock_qty', $q_stock_field, "재고수량"); ?>
				<?php echo option_selected('noti_qty', $q_stock_field, "통보수량"); ?>
			</select>
			<label for="fr_stock" class="sound_only">재고수량 시작</label>
			<input type="text" name="fr_stock" value="<?php echo $fr_stock; ?>" id="fr_stock" class="frm_input" size="6"> 개 이상 ~
			<label for="to_stock" class="sound_only">재고수량 끝</label>
			<input type="text" name="to_stock" value="<?php echo $to_stock; ?>" id="to_stock" class="frm_input" size="6"> 개 이하
		</td>
		<th scope="row">상품가격</th>
		<td>
			<select name="q_price_field" id="q_price_field">
				<?php echo option_selected('goods_price', $q_price_field, "판매가격"); ?>				
				<?php echo option_selected('supply_price', $q_price_field, "공급가격"); ?>
				<?php echo option_selected('normal_price', $q_price_field, "시중가격"); ?>
				<?php echo option_selected('gpoint', $q_price_field, "포인트"); ?>
			</select>
			<label for="fr_price" class="sound_only">상품가격 시작</label>
			<input type="text" name="fr_price" value="<?php echo $fr_price; ?>" id="fr_price" class="frm_input" size="6"> 원 이상 ~
			<label for="to_price" class="sound_only">상품가격 끝</label>
			<input type="text" name="to_price" value="<?php echo $to_price; ?>" id="to_price" class="frm_input" size="6"> 원 이하
		</td>
	</tr>
	<tr>
		<th scope="row">판매여부</th>
		<td>
			<?php echo radio_checked('q_isopen', $q_isopen,  '', '전체'); ?>
			<?php echo radio_checked('q_isopen', $q_isopen, '1', '진열'); ?>
			<?php echo radio_checked('q_isopen', $q_isopen, '2', '품절'); ?>
			<?php echo radio_checked('q_isopen', $q_isopen, '3', '단종'); ?>
			<?php echo radio_checked('q_isopen', $q_isopen, '4', '중지'); ?>
		</td>
		<th scope="row">필수옵션</th>
		<td>
			<?php echo radio_checked('q_option', $q_option,  '', '전체'); ?>
			<?php echo radio_checked('q_option', $q_option, '1', '사용'); ?>
			<?php echo radio_checked('q_option', $q_option, '0', '미사용'); ?>
		</td>
	</tr>
	<tr>
		<th scope="row">과세유형</th>
		<td>
			<?php echo radio_checked('q_notax', $q_notax,  '', '전체'); ?>
			<?php echo radio_checked('q_notax', $q_notax, '1', '과세'); ?>
			<?php echo radio_checked('q_notax', $q_notax, '0', '비과세'); ?>
		</td>
		<th scope="row">추가옵션</th>
		<td>
			<?php echo radio_checked('q_supply', $q_supply,  '', '전체'); ?>
			<?php echo radio_checked('q_supply', $q_supply, '1', '사용'); ?>
			<?php echo radio_checked('q_supply', $q_supply, '0', '미사용'); ?>
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
