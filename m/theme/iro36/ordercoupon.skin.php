<?php
if(!defined("_TUBEWEB_")) exit; // 개별 페이지 접근 불가
?>

<h2 class="pop_title">
	<?php echo $tb['title']; ?>
	<a href="javascript:window.close();" class="btn_small bx-white">창닫기</a>
</h2>

<form name="flist" method="post">
<input type="hidden" name="sum_dc_amt">
<input type="hidden" name="layer_cnt">

<div id="sod_coupon">
	<div class="scope">
		1, 중복할인 가능 이외의 쿠폰은 개별 상품만 적용 가능합니다.<br>
		2, 쿠폰적용시 배송비는 할인되지 않습니다.<br>
		3, 주문을 (취소/반품) 하실 경우에는 쿠폰은 자동소멸 됩니다.<br>
		4, 발행된 쿠폰은 마이페이지에서 확인 할 수 있습니다.
	</div>

	<?php
	$tot_price = 0;
	for($i=0; $row=sql_fetch_array($result2); $i++) {
		$gs = get_goods($row['gs_id']);

		// 합계금액 계산
		$sql = " select SUM(IF(io_type = 1, (io_price * ct_qty),((io_price + ct_price) * ct_qty))) as price,
						SUM(IF(io_type = 1, (0),(ct_qty))) as qty
				   from shop_cart
				  where gs_id = '$row[gs_id]'
					and ct_direct = '$set_cart_id'
					and ct_select = '0'";
		$sum = sql_fetch($sql);

		$price = $sum['price'];
		$tot_price += $price;

		// 소속 카테고리를 콤마로 구분하여 추출
		$ca_list = get_extract($row['gs_id']);
		$cp_tmp[] = $price ."|". $row['gs_id'] ."|". $ca_list ."|". $gs['use_aff'];
	?>
	<div class="sod_cpuse">
		<input type="hidden" name="gd_dc_amt_<?php echo $i; ?>">
		<input type="hidden" name="gd_cp_info_<?php echo $i; ?>">
		<input type="hidden" name="gd_cp_no_<?php echo $i; ?>">
		<input type="hidden" name="gd_cp_idx_<?php echo $i; ?>">
		<table class="us_box">
		<tbody>
		<tr>
			<td class="mi_dt"><?php echo get_it_image($row['gs_id'], $gs['simg1'], 60, 60); ?></td>
			<td class="mi_bt">
				<?php echo get_text($gs['gname']); ?><br>
				<span class="strong"><?php echo display_price($price); ?></span>
			</td>
		</tr>
		<tr id="cp_avail_button_<?php echo $i; ?>">
			<td colspan='2'><button type="button" class="avail_button" onclick="show_coupon('<?php echo $i; ?>');return false;">적용가능 쿠폰선택</button></td>
		</tr>
		</tbody>
		</table>

		<table class="th_box">
		<tbody>
		<tr>
			<td class="tal">수량</td>
			<td class="tar"><?php echo display_qty($sum['qty']); ?></td>
		</tr>
		<tr>
			<td class="tal">할인금액</td>
			<td class="tar">
				<span id="dc_amt_<?php echo $i; ?>">0</span>원
				<span id="dc_cancel_bt_<?php echo $i; ?>" style="display:none">&nbsp;<a href="javascript:coupon_cancel('<?php echo $row['gs_id']; ?>','<?php echo $row['index_no']; ?>','<?php echo $i; ?>');" class='btn_ssmall bx-white'>삭제</a></span>
			</td>
		</tr>
		</tbody>
		</table>
	</div>
	<?php } ?>

	<div class="to_wrap to_box">
		<dl class="to_tline">
			<dt>상품금액 합계</dt>
			<dd><?php echo display_price($tot_price); ?></dd>
			<dt class="point_bg">할인금액 합계</dt>
			<dd class="point_bg"><span id="to_dc_amt">0</span>원</dd>
		</dl>
	</div>
	<div class="btn_confirm">
		<button type="button" onclick="cp_submit();return false;" class="btn_medium red">쿠폰적용하기</button>
		<button type="button" onclick="window.close();" class="btn_medium bx-white">창닫기</button>
	</div>
</div>

<?php
for($i=0; $row=sql_fetch_array($result); $i++) {
	$lo_id = $row['lo_id'];

	//$str = mobile_cp_contents();

	for($j=0; $j<$cart_count; $j++) {

		$is_coupon = false;
		$is_gubun = explode("|", $cp_tmp[$j]);

		switch($row['cp_use_part']) {
			case '0': // 전체상품에 쿠폰사용 가능
				$is_coupon = true;
				break;
			case '1': // 일부 상품만 쿠폰사용 가능
				if($row['cp_use_goods']) {
					$fields_cnt = get_substr_count($is_gubun[1], $row['cp_use_goods']);
					if($fields_cnt)
						$is_coupon = true;
				}
				break;
			case '2': // 일부 카테고리만 쿠폰사용 가능
				if($row['cp_use_category']) {
					$fields_cnt = get_substr_count($is_gubun[2], $row['cp_use_category']);
					if($fields_cnt)
						$is_coupon = true;
				}
				break;
			case '3': // 일부 상품은 쿠폰사용 불가
				if($row['cp_use_goods']) {
					$fields_cnt = get_substr_count($is_gubun[1], $row['cp_use_goods']);
					if(!$fields_cnt)
						$is_coupon = true;
				}
				break;
			case '4': // 일부 카테고리는 쿠폰사용 불가
				if($row['cp_use_category']) {
					$fields_cnt = get_substr_count($is_gubun[2], $row['cp_use_category']);
					if(!$fields_cnt)
						$is_coupon = true;
				}
				break;
		}

		// 적용여부 && 가맹점상품제외 && 최대금액 <= 상품금액
		$seq = array();
		if($is_coupon && !$is_gubun[3] && ($row['cp_low_amt'] <= (int)$is_gubun[0])) {
			// 할인해택 검사
			$amt =  get_cp_sale_amt((int)$is_gubun[0]);
			$seq[] = $is_gubun[1];
			$seq[] = $lo_id;
			$seq[] = $row['cp_id'];
			$seq[] = $row['cp_dups'];
			$seq[] = $amt[1];
			$seq[] = $amt[0];
			$is_possible[] = implode("|", $seq);
		}
	}
}

$result2 = sql_query($sql2);
for($i=0; $row=sql_fetch_array($result2); $i++) {
?>
<div id="cp_list<?php echo $i; ?>" class="mw" style='display:none;'>
	<div class="bg"></div>
	<div class="fg">
		<table class="ty_box">
		<tr>
			<td class="tal">※ 적용할 쿠폰선택</td>
			<td class="tar"><a href="#" onclick="hide_cp_list('<?php echo $i; ?>'); return false;" class="btn_small bx-white">닫기</a></td>
		</tr>
		</table>

		<table class="ly_box mart5">
		<tbody>
		<tr>
			<td class="cell">쿠폰번호</td>
			<td class="cell">할인</td>
			<td class="cell">할인가</td>
		</tr>
		<?php
		//5|1|8|0|10%|37496
		// 상품주키|쿠폰주키|쿠폰번호|동시사용 여부|할인금액(율)|할인가
		$chk = 0;
		for($j=0; $j<count($is_possible); $j++) {
			$arr = explode("|", $is_possible[$j]);
			if($row['gs_id'] == $arr[0]) {
				$chk++;
		?>
		<tr>
			<td class="tac"><input id="ids_shown<?php echo $j; ?>" type="radio" name="use_cp_<?php echo $row['gs_id']; ?>_<?php echo $row['index_no']?>" value="<?php echo $arr[2]; ?>|<?php echo $arr[5]; ?>|<?php echo $arr[1]; ?>|<?php echo $arr[3]; ?>">
			<label for="ids_shown<?php echo $j; ?>"><?php echo $arr[2]; ?></label></td>
			<td class="tac"><?php echo $arr[4]; ?></td>
			<td class="tac"><?php echo display_price($arr[5]); ?></td>
		</tr>
		<?php
			}
		}

		if(!$chk) {
		?>
		<tr><td colspan="3" class="empty_list">사용할 수 있는 쿠폰이 없습니다.</td></tr>
		<?php } ?>
		<tbody>
		</table>

		<div class="btn_confirm">
			<button type="button" onclick="return applycoupon('<?php echo $row['gs_id']; ?>','<?php echo $row['index_no']; ?>','<?php echo $i; ?>');" class="btn_medium red">쿠폰적용하기</button>
		</div>
	</div>
</div>
<?php } ?>
</form>

<script>
var max_layer = '<?php echo $cart_count; ?>';
document.flist.layer_cnt.value = max_layer;

function applycoupon(gs_id, cart_id, layer_idx) {
	var f = document.flist;

	// 개별 상품에 적용할 쿠폰 미선택 시
	if(!getRadioValue(f["use_cp_"+gs_id+"_"+cart_id])){
		alert('상품에 적용하실 쿠폰을 선택해주세요.');
		return false;
	}

	// 쿠폰번호 얻기
	var info = getRadioValue(f["use_cp_"+gs_id+"_"+cart_id]).split("|");
	var cp_no = info[0]; // 사용된 쿠폰 번호
	var gd_dc_amt = info[1]; // 쿠폰 할인액
	var cp_idx = info[2]; // 쿠폰 IDX
	var cp_dups = info[3]; // 중복 적용 여부

	// 이미 적용된 쿠폰인지 검사
	for(i=0;i<max_layer;i++){
		tmp = f["gd_cp_no_"+i].value; // 사용된 쿠폰 번호
		if(tmp != ""){
			if(cp_no == tmp){
				// 중복 적용 불가
				if(cp_dups == "1"){
					alert('해당 쿠폰은 중복할인이 되지 않습니다.');
					f["use_cp_"+gs_id+"_"+cart_id].checked = false;
					hide_cp_list(layer_idx);
					return false;
				}
			}
		}
	}

	// 쿠폰 적용 할인가를 상품별로 기록
	f["gd_dc_amt_"+layer_idx].value = gd_dc_amt;

	// 적용된 쿠폰 정보를 상품별로 저장
	f["gd_cp_info_"+layer_idx].value = gs_id+"|"+cart_id+"|"+cp_no+"|"+cp_idx+"|"+gd_dc_amt;
	f["gd_cp_no_"+layer_idx].value = cp_no;
	f["gd_cp_idx_"+layer_idx].value = cp_idx;

	// 전체 할인가 얻기
	var sum_dc_amt = 0;
	var tmp = 0;
	for(i = 0; i < max_layer; i++){
		if(f["gd_dc_amt_"+i].value == ""){
			tmp = 0;
		}else{
			tmp = parseInt(f["gd_dc_amt_"+i].value);
		}
		sum_dc_amt += tmp;
	}
	// 총 할인액 기록
	f.sum_dc_amt.value = sum_dc_amt;

	// label 변경
	document.getElementById("dc_amt_"+layer_idx).innerText = number_format(String(gd_dc_amt));
	document.getElementById("to_dc_amt").innerText = number_format(String(sum_dc_amt));
	document.getElementById("cp_avail_button_"+layer_idx).style.display = "none"; // 적용한 것은 안보이게
	document.getElementById("dc_cancel_bt_"+layer_idx).style.display = "";

	hide_cp_list(layer_idx);
}

function coupon_cancel(gs_id, cart_id, layer_idx){
	var f = document.flist;

	// 쿠폰 적용 할인가를 상품별로 기록
	f["gd_dc_amt_"+layer_idx].value = 0;

	// 적용된 쿠폰 정보를 상품별로 삭제
	f["gd_cp_info_"+layer_idx].value = "";
	f["gd_cp_no_"+layer_idx].value = "";
	f["gd_cp_idx_"+layer_idx].value = "";

	// 전체 할인가 얻기
	var sum_dc_amt = 0;
	var tmp = 0;
	for(i = 0; i < max_layer; i++){
		if(f["gd_dc_amt_"+i].value == ""){
			tmp = 0;
		}else{
			tmp = parseInt(f["gd_dc_amt_"+i].value);
		}
		sum_dc_amt += tmp;
	}
	// 총 할인액 기록
	f.sum_dc_amt.value = sum_dc_amt;

	// label 변경
	document.getElementById("dc_amt_"+layer_idx).innerText = 0;
	document.getElementById("to_dc_amt").innerText = number_format(String(sum_dc_amt));
	document.getElementById("cp_avail_button_"+layer_idx).style.display = ""; // 다시 보이게
	document.getElementById("dc_cancel_bt_"+layer_idx).style.display = "none";
}

function show_coupon(idx) {
	var coupon_layer = document.getElementById("cp_list"+idx);
	var IpopTop = (document.body.clientHeight - coupon_layer.offsetHeight) / 2;
	var IpopLeft = (document.body.clientWidth - coupon_layer.offsetWidth) / 2;

	coupon_layer.style.left=IpopLeft / 2 + document.body.scrollLeft;
	coupon_layer.style.top=IpopTop / 2 + document.body.scrollTop;
 	coupon_layer.style.display = "block";
}

function hide_cp_list(idx) {
	var coupon_layer = document.getElementById("cp_list"+idx);
	coupon_layer.style.display = 'none';
}

function cp_submit() {
	var f = document.flist;
	var tot_price = 0;
	var tot_price = opener.document.buyform.tot_price.value;

	if(f.sum_dc_amt.value == 0 || !f.sum_dc_amt.value) {
		alert("상품에 쿠폰을 선택해주세요.");
		return false;
	}

	if(parseInt(no_comma(tot_price)) < f.sum_dc_amt.value) {
		alert("쿠폰 할인 금액이 결제금액을 초과하였습니다.");
		return false;
	}

	if(!confirm("쿠폰적용을 하시겠습니까?"))
		return false;


	var tmp_dc_amt	= '';
	var tmp_lo_id	= '';
	var tmp_cp_id	= '';
	var chk_dc_amt	= '';
	var chk_lo_id	= '';
	var chk_cp_id	= '';
	var comma		= '';
	for(i = 0; i < max_layer; i++) {
		chk_dc_amt	= eval("f.gd_dc_amt_"+i).value ? eval("f.gd_dc_amt_"+i).value : 0;
		chk_lo_id   = eval("f.gd_cp_idx_"+i).value ? eval("f.gd_cp_idx_"+i).value : 0;
		chk_cp_id	= eval("f.gd_cp_no_"+i).value ? eval("f.gd_cp_no_"+i).value : 0;

		tmp_dc_amt += comma + chk_dc_amt;
		tmp_lo_id  += comma + chk_lo_id;
		tmp_cp_id  += comma + chk_cp_id;
		comma = '|';
	}

	// 로그
	opener.document.buyform.coupon_price.value = tmp_dc_amt;
	opener.document.buyform.coupon_lo_id.value = tmp_lo_id;
	opener.document.buyform.coupon_cp_id.value = tmp_cp_id;

	// 총 할인액
	opener.document.buyform.coupon_total.value = f.sum_dc_amt.value;
	opener.document.getElementById("dc_amt").innerText = number_format(String(f.sum_dc_amt.value));
	opener.document.getElementById("dc_cancel").style.display = "";
	opener.document.getElementById("dc_coupon").style.display = "none";

	tot_price = parseInt(no_comma(tot_price)) - f.sum_dc_amt.value;

	// 최종 결제금액
	opener.document.buyform.tot_price.value = number_format(String(tot_price));

	self.close();
}
</script>
