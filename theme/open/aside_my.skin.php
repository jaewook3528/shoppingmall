<?php
if(!defined('_TUBEWEB_')) exit;
?>

<!-- 좌측메뉴 시작 { -->
<aside id="aside">
	<div class="aside_hd">
		<p class="eng">MY PAGE</p>
		<p class="kor">마이페이지</p>
	</div>
	<div class="aside_name"><?php echo get_text($member['name']); ?></div>
	<ul class="aside_bx">
		<li>포인트 <span><a href="<?php echo TB_SHOP_URL; ?>/point.php"><?php echo number_format($member['point']); ?></a>P</span></li>
	</ul>	
	<dl class="aside_my">
		<dt>주문현황</dt>
		<dd><a href="<?php echo TB_SHOP_URL; ?>/orderinquiry.php">주문/배송조회</a></dd>
		<dt>쇼핑통장</dt>
		<dd><a href="<?php echo TB_SHOP_URL; ?>/point.php">포인트조회</a></dd>
		<?php if($config['gift_yes']) { ?>
		<dd><a href="<?php echo TB_SHOP_URL; ?>/gift.php">쿠폰인증</a></dd>
		<?php } ?>
		<?php if($config['coupon_yes']) { ?>
		<dd><a href="<?php echo TB_SHOP_URL; ?>/coupon.php">쿠폰관리</a></dd>
		<?php } ?>
		<dt>관심상품</dt>
		<dd><a href="<?php echo TB_SHOP_URL; ?>/cart.php">장바구니</a></dd>
		<dd><a href="<?php echo TB_SHOP_URL; ?>/wish.php">내가 찜한상품</a></dd>
		<dt>회원정보</dt>
		<dd><a href="<?php echo TB_BBS_URL; ?>/register_mod.php">회원정보수정</a></dd>		
		<dd class="marb5"><a href="<?php echo TB_BBS_URL; ?>/leave_form.php">회원탈퇴</a></dd>
	</ul>
</aside>
<!-- } 좌측메뉴 끝 -->
