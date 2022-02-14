<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TB_THEME_PATH.'/aside_my.skin.php');
?>

<div id="con_lf">
	<h2 class="pg_tit">
		<span><?php echo $tb['title']; ?></span>
		<p class="pg_nav">HOME<i>&gt;</i>마이페이지<i>&gt;</i><?php echo $tb['title']; ?></p>
	</h2>

	<p class="pg_cnt">
		<em>총 <?php echo number_format($wish_count); ?>개</em>의 상품이 보관되어 있습니다.
	</p>

	<form name="fwishlist" id="fwishlist" method="post">
	<input type="hidden" name="act" value="multi">
	<input type="hidden" name="sw_direct">

	<div class="tbl_head02 tbl_wrap">
		<table>
		<colgroup>
			<col width="50">
			<col width="80">
			<col>
			<col width="100">
			<col width="60">
		</colgroup>
		<thead>
		<tr>
			<th scope="col">선택</th>
			<th scope="col">이미지</th>
			<th scope="col">상품정보</th>
			<th scope="col">가격</th>
			<th scope="col">삭제</th>
		</tr>
		</thead>
		<tbody>
		<?php
		for($i=0; $row = sql_fetch_array($result); $i++) {
			$out_cd = '';
			$sql = " select count(*) as cnt from shop_goods_option where gs_id = '{$row['gs_id']}' and io_type = '0' ";
			$tmp = sql_fetch($sql);
			if($tmp['cnt'])
				$out_cd = 'no';

			if($row['price_msg']) {
				$out_cd = 'price_msg';
			}
		?>
		<tr>
			<td class="tac">
				<?php if(is_soldout($row['gs_id'])) { ?>
				품절
				<?php } else { ?>
				<input type="checkbox" name="chk_gs_id[<?php echo $i; ?>]" value="1" onclick="out_cd_check(this, '<?php echo $out_cd; ?>');">
				<?php } ?>
				<input type="hidden" name="gs_id[<?php echo $i; ?>]" value="<?php echo $row['gs_id']; ?>">
				<input type="hidden" name="io_type[<?php echo $row['gs_id']; ?>][0]" value="0">
				<input type="hidden" name="io_id[<?php echo $row['gs_id']; ?>][0]" value="">
				<input type="hidden" name="io_value[<?php echo $row['gs_id']; ?>][0]" value="<?php echo $row['gname']; ?>">
				<input type="hidden" name="ct_qty[<?php echo $row['gs_id']; ?>][0]" value="1">
			</td>
			<td class="tac"><a href="<?php echo TB_SHOP_URL; ?>/view.php?index_no=<?php echo $row['gs_id']; ?>"><?php echo get_it_image($row['gs_id'], $row['simg1'], 70, 70); ?></a></td>
			<td class="td_name">
				<a href="<?php echo TB_SHOP_URL; ?>/view.php?index_no=<?php echo $row['gs_id']; ?>"><?php echo $row['gname']; ?></a>
				<p class="fc_999"><?php echo $row['explan']; ?></p>
			</td>			
			<td class="tar"><?php echo get_price($row['gs_id']); ?></td>
			<td class="tac"><a href="<?php echo TB_SHOP_URL; ?>/wishupdate.php?w=d&wi_id=<?php echo $row['wi_id']; ?>" class="btn_small bx-red">삭제</td>
		</tr>
		<?php
		} 
		if($i==0) 
			echo '<tr><td colspan="5" class="empty_list">보관함이 비었습니다.</td></tr>';
		?>
		</tbody>
		</table>
	</div>
	
	<div class="btn_confirm">		
		<button type="submit" class="btn_large wset" onclick="return fwishlist_check(document.fwishlist,'direct_buy');">주문하기</button>
		<button type="submit" class="btn_large bx-white" onclick="return fwishlist_check(document.fwishlist,'');">장바구니 담기</button>
	</div>
	</form>
</div>

<script>
<!--
function out_cd_check(fld, out_cd)
{
	if(out_cd == 'no'){
		alert("옵션이 있는 상품입니다.\n\n상품을 클릭하여 상품페이지에서 옵션을 선택한 후 주문하십시오.");
		fld.checked = false;
		return;
	}

	if(out_cd == 'price_msg'){
		alert("이 상품은 전화로 문의해 주십시오.\n\n장바구니에 담아 구입하실 수 없습니다.");
		fld.checked = false;
		return;
	}
}

function fwishlist_check(f, act)
{
	var k = 0;
	var length = f.elements.length;

	for(i=0; i<length; i++) {
		if(f.elements[i].checked) {
			k++;
		}
	}

	if(k == 0)
	{
		alert("상품을 하나 이상 체크 하십시오");
		return false;
	}

	if(act == "direct_buy")
	{
		f.sw_direct.value = 1;
	}
	else
	{
		f.sw_direct.value = 0;
	}

	f.action = "./cartupdate.php";
	f.submit();
}
//-->
</script>
