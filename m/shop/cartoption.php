<?php
include_once("./_common.php");

$gs_id = trim($_POST['gs_id']);

$gs = get_goods($gs_id);
$gs['goods_price'] = get_sale_price($gs_id);

// 최소, 최대 주문수량체크
$it_buy_min_qty = 1;
$it_buy_max_qty = 0;
if($gs['odr_min']) {
	$it_buy_min_qty	= (int)$gs['odr_min'];
}

if($gs['odr_max']) {
	$it_buy_max_qty	= (int)$gs['odr_max'];
}

if(!$gs['index_no'])
    die('no-item');

$where_add = get_cart_mode('select','','where');

$sql_search = " ct_select='0' {$where_add} and gs_id='$gs_id' ";

// 장바구니 자료
$sql = " select * from shop_cart where $sql_search order by io_type asc, index_no asc ";
$result = sql_query($sql);

// 판매가격
$sql2 = " select * from shop_cart where $sql_search order by index_no asc limit 1 ";
$row2 = sql_fetch($sql2);

if(!sql_num_rows($result))
    die('no-cart');
?>

<!-- 장바구니 옵션 시작 { -->
<form name="foption" method="post" action="<?php echo TB_MSHOP_URL; ?>/cartupdate.php" onsubmit="return formcheck(this);">
<input type="hidden" name="act" value="optionmod">
<input type="hidden" name="gs_id[]" value="<?php echo $gs_id;?>">
<input type="hidden" id="it_price" value="<?php echo $row2['ct_price'];?>">
<div class="sp_wrap" style="border-top:0">
	<?php
	$option_1 = mobile_item_options($gs_id, $gs['opt_subject'], " style='width:100%'");
	if($option_1) {
	?>
	<div class="sp_tbox" style="border-top:0">
		<ul>
			<li class='tlst strong'>주문옵션</li>
			<li class='trst'>아래옵션은 필수선택 옵션입니다</li>
		</ul>
	</div>
	<?php echo $option_1;?>
	<?php
	}
	?>

	<?php
	$option_2 = mobile_item_supply($gs_id, $gs['spl_subject'], " style='width:100%'");
	if($option_2) {
	?>
	<div class="sp_tbox" <?php echo (!$option_1)?"style='border-top:0;'":"";?>>
		<ul>
			<li class='tlst strong'>추가구성</li>
			<li class='trst'>추가구매를 원하시면 선택하세요</li>
		</ul>
	</div>
	<?php echo $option_2;?>
	<?php
	}
	?>
	</table>

	<div id="option_set_list">
		<ul id="option_set_added">
			<?php
            while($row=sql_fetch_array($result)) {
                //	for($i=1; $row=sql_fetch_array($result); $i++) {
				if(!$row['io_id'])
					$it_stock_qty = get_it_stock_qty($row['gs_id']);
				else
					$it_stock_qty = get_option_stock_qty($row['gs_id'], $row['io_id'], $row['io_type']);

				$plus = '';
				if($row['io_price'] >= 0)
					$plus = '+';

				if(!$row['io_type'])
					$io_price = $plus . display_price($row['io_price'] + $gs['goods_price']);
				else
					$io_price = $plus . display_price($row['io_price']);

				$cls = 'opt';
				if($row['io_type'])
					$cls = 'spl';
			?>
			<li class="sit_<?php echo $cls;?>_list">
				<div class="sp_opt_set">
				<input type="hidden" name="io_type[<?php echo $gs_id;?>][]" value="<?php echo $row['io_type'];?>">
				<input type="hidden" name="io_id[<?php echo $gs_id;?>][]" value="<?php echo $row['io_id'];?>">
				<input type="hidden" name="io_value[<?php echo $gs_id;?>][]" value="<?php echo $row['ct_option'];?>">
				<input type="hidden" class="io_price" value="<?php echo $row['io_price'];?>">
				<input type="hidden" class="io_stock" value="<?php echo $it_stock_qty;?>">
					<ul>
						<li class="it_name"><span class="sit_opt_subj"><?php echo $row['ct_option'];?></span></li>
						<li class="it_qty">
							<dl class="fl">
								<dt class="fl padr3"><button type="button" class="btn_small grey">감소</button></dt>
								<dt class="fl padr3"><input type="text" name="ct_qty[<?php echo $gs_id;?>][]" value="<?php echo $row['ct_qty'];?>"></dt>
								<dt class="fl padr3"><button type="button" class="btn_small grey">증가</button></dt>
							</dl>
							<dl class="fr">
								<dt class="fl padr5 padt5 strong"><span class="sit_opt_prc"><?php echo $io_price;?></span></dt>
								<dt class="fl"><button type="button" class="btn_small yellow">삭제</button></dt>
							</dl>
						</li>
					</ul>
				</div>
			</li>
			<?php } ?>
		</ul>
	</div>

	<div id="sit_tot_views" class="dn">
		<div class="sp_tot">
			<ul>
				<li class='tlst strong'>총 합계금액</li>
				<li class='trst'><span id="sit_tot_price" class="trss-amt"></span><span class="trss-amt">원</span></li>
			</ul>
		</div>
	</div>
	<div class="btn_confirm padb20">
		<input type="submit" value="확인" class="btn_medium">
		<button type="button" id="mod_option_close" class="btn_medium bx-white">취소</button>
	</div>
</div>
</form>

<script>
function formcheck(f)
{
    var val, io_type, result = true;
    var sum_qty = 0;
    var min_qty = parseInt('<?php echo $it_buy_min_qty; ?>');
    var max_qty = parseInt('<?php echo $it_buy_max_qty; ?>');
    var $el_type = $("input[name^=io_type]");

    $("input[name^=ct_qty]").each(function(index) {
        val = $(this).val();

        if(val.length < 1) {
            alert("수량을 입력해 주십시오.");
            result = false;
            return false;
        }

        if(val.replace(/[0-9]/g, "").length > 0) {
            alert("수량은 숫자로 입력해 주십시오.");
            result = false;
            return false;
        }

        if(parseInt(val.replace(/[^0-9]/g, "")) < 1) {
            alert("수량은 1이상 입력해 주십시오.");
            result = false;
            return false;
        }

        io_type = $el_type.eq(index).val();
        if(io_type == "0")
            sum_qty += parseInt(val);
    });

    if(!result) {
        return false;
    }

    if(min_qty > 0 && sum_qty < min_qty) {
		alert("주문옵션 개수 총합 "+number_format(String(min_qty))+"개 이상 주문해 주십시오.");
        return false;
    }

    if(max_qty > 0 && sum_qty > max_qty) {
		alert("주문옵션 개수 총합 "+number_format(String(max_qty))+"개 이하로 주문해 주십시오.");
        return false;
    }

    return true;
}
</script>
<!-- } 장바구니 옵션 끝 -->
