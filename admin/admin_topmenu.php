<?php
if(!defined('_TUBEWEB_')) exit;
?>

<header id="hd">
	<div id="hd_wrap">
		<h1><?php echo $config['shop_name']; ?></h1>
		<div id="logo"><a href="<?php echo TB_ADMIN_URL; ?>/"><img src="<?php echo TB_ADMIN_URL; ?>/img/logo.gif" alt="<?php echo $config['shop_name']; ?> 관리자"></a></div>
		<div id="tnb">
			<ul>
				<li><?php echo $super['vi_history']; ?></li>
				<li><a href="<?php echo TB_ADMIN_URL; ?>/manual/index.php?act=mb_1" onclick="win_open(this,'pop_manual','1050','1000','yes');return false;">관리자메뉴얼</a></li>
				<li><a href="<?php echo TB_ADMIN_URL; ?>/config.php?code=super">관리자정보</a></li>
				<li><a href="<?php echo TB_ADMIN_URL; ?>/">관리자홈</a></li>
				<li><a href="<?php echo TB_URL; ?>" target="_blank">쇼핑몰</a></li>
				<li id="tnb_logout"><a href="<?php echo TB_BBS_URL; ?>/logout.php">로그아웃</a></li>
			</ul>
		</div>

		<nav id="gnb">
			<h2>관리자 주메뉴</h2>
			<ul id="gnb_1dul">
				<li class="gnb_1dli<?php if($pg_title == ADMIN_MENU1){ ?> active<?php } ?>">
					<a href="<?php echo TB_ADMIN_URL; ?>/member.php?code=list" class="gnb_1da"><?php echo ADMIN_MENU1; ?></a>
				</li>
				<li class="gnb_1dli<?php if($pg_title == ADMIN_MENU2){ ?> active<?php } ?>">
					<a href="<?php echo TB_ADMIN_URL; ?>/partner.php?code=plist" class="gnb_1da"><?php echo ADMIN_MENU2; ?></a>
				</li>
				<li class="gnb_1dli<?php if($pg_title == ADMIN_MENU3){ ?> active<?php } ?>">
					<a href="<?php echo TB_ADMIN_URL; ?>/seller.php?code=list" class="gnb_1da"><?php echo ADMIN_MENU3; ?></a>
				</li>
				<li class="gnb_1dli<?php if($pg_title == ADMIN_MENU4){ ?> active<?php } ?>">
					<a href="<?php echo TB_ADMIN_URL; ?>/category.php?code=list" class="gnb_1da"><?php echo ADMIN_MENU4; ?></a>
				</li>
				<li class="gnb_1dli<?php if($pg_title == ADMIN_MENU5){ ?> active<?php } ?>">
					<a href="<?php echo TB_ADMIN_URL; ?>/goods.php?code=list" class="gnb_1da"><?php echo ADMIN_MENU5; ?></a>
				</li>
				<li class="gnb_1dli<?php if($pg_title == ADMIN_MENU6){ ?> active<?php } ?>">
					<a href="<?php echo TB_ADMIN_URL; ?>/order.php?code=list" class="gnb_1da"><?php echo ADMIN_MENU6; ?></a>
				</li>
				<li class="gnb_1dli<?php if($pg_title == ADMIN_MENU7){ ?> active<?php } ?>">
					<a href="<?php echo TB_ADMIN_URL; ?>/visit.php?code=hour" class="gnb_1da"><?php echo ADMIN_MENU7; ?></a>
				</li>
				<li class="gnb_1dli<?php if($pg_title == ADMIN_MENU8){ ?> active<?php } ?>">
					<a href="<?php echo TB_ADMIN_URL; ?>/help.php?code=qa" class="gnb_1da"><?php echo ADMIN_MENU8; ?></a>
				</li>
				<li class="gnb_1dli<?php if($pg_title == ADMIN_MENU9){ ?> active<?php } ?>">
					<a href="<?php echo TB_ADMIN_URL; ?>/design.php?code=banner_list" class="gnb_1da"><?php echo ADMIN_MENU9; ?></a>
				</li>
				<li class="gnb_1dli<?php if($pg_title == ADMIN_MENU10){ ?> active<?php } ?>">
					<a href="<?php echo TB_ADMIN_URL; ?>/config.php?code=default" class="gnb_1da"><?php echo ADMIN_MENU10; ?></a>
				</li>
			</ul>
		</nav>
	</div>
</header>
<div id="wrapper">
	<?php
	if(!defined('NO_CONTAINER')) {
		include_once(TB_ADMIN_PATH."/admin_snb.php");
	?>
	<div id="content">
		<div class="breadcrumb">
			<span>HOME</span> <i class="ionicons ion-ios-arrow-right"></i> <?php echo $pg_title; ?> <i class="ionicons ion-ios-arrow-right"></i> <?php echo $pg_title2; ?>
		</div>
	<?php } ?>
