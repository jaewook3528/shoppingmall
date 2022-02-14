<?php
if(!defined('_TUBEWEB_')) exit;
?>
		<?php
		if(!defined('_INDEX_')) { // index가 아니면 실행
			echo '</div>'.PHP_EOL;
		}
		?>
	</div>

	<!-- 카피라이터 시작 { -->
	<div id="ft">
		<?php
		if($default['de_insta_access_token']) { // 인스타그램
		   $userId = explode(".", $default['de_insta_access_token']);
		?>
		<script src="<?php echo TB_JS_URL; ?>/instafeed.min.js"></script>
		<script>
			var userFeed = new Instafeed({
				get: 'user',
				userId: "<?php echo $userId[0]; ?>",
				limit: 8,
				template: '<li class="ins_li"><a href="{{link}}" target="_blank"><img src="{{image}}" /></a></li>',
				accessToken: "<?php echo $default['de_insta_access_token']; ?>"
			});
			userFeed.run();
		</script>

		<div class="insta">
			<h2 class="tac"><i class="fa fa-instagram"></i> INSTAGRAM<a href="https://www.instagram.com/<?php echo $default['de_insta_url']; ?>" target="_blank">@ <?php echo $default['de_insta_url']; ?></a></h2>
			<ul id="instafeed">
			</ul>
		</div>
		<?php } ?>

		<div class="fgnb">
			<ul>
				<li><a href="<?php echo TB_BBS_URL; ?>/content.php?page_id=about&pt_id=<?=$pt_id?>">회사소개</a></li>
				<li><a href="<?php echo TB_BBS_URL; ?>/provision.php">이용약관</a></li>
				<li><a href="<?php echo TB_BBS_URL; ?>/policy.php">개인정보처리방침</a></li>
				<li><a href="<?php echo TB_BBS_URL; ?>/faq.php?faqcate=1">고객센터</a></li>
				<li class="sns_wrap">
					<?php if($default['de_sns_facebook']) { ?><a href="<?php echo $default['de_sns_facebook']; ?>" target="_blank" class="sns_fa"><img src="<?php echo TB_THEME_URL; ?>/img/sns_fa.png" title="facebook"></a><?php } ?>
					<?php if($default['de_sns_twitter']) { ?><a href="<?php echo $default['de_sns_twitter']; ?>" target="_blank" class="sns_tw"><img src="<?php echo TB_THEME_URL; ?>/img/sns_tw.png" title="twitter"></a><?php } ?>
					<?php if($default['de_sns_instagram']) { ?><a href="<?php echo $default['de_sns_instagram']; ?>" target="_blank" class="sns_in"><img src="<?php echo TB_THEME_URL; ?>/img/sns_in.png" title="instagram"></a><?php } ?>
					<?php if($default['de_sns_pinterest']) { ?><a href="<?php echo $default['de_sns_pinterest']; ?>" target="_blank" class="sns_pi"><img src="<?php echo TB_THEME_URL; ?>/img/sns_pi.png" title="pinterest"></a><?php } ?>
					<?php if($default['de_sns_naverblog']) { ?><a href="<?php echo $default['de_sns_naverblog']; ?>" target="_blank" class="sns_bl"><img src="<?php echo TB_THEME_URL; ?>/img/sns_bl.png" title="naverblog"></a><?php } ?>
					<?php if($default['de_sns_naverband']) { ?><a href="<?php echo $default['de_sns_naverband']; ?>" target="_blank" class="sns_ba"><img src="<?php echo TB_THEME_URL; ?>/img/sns_ba.png" title="naverband"></a><?php } ?>
					<?php if($default['de_sns_kakaotalk']) { ?><a href="<?php echo $default['de_sns_kakaotalk']; ?>" target="_blank" class="sns_kt"><img src="<?php echo TB_THEME_URL; ?>/img/sns_kt.png" title="kakaotalk"></a><?php } ?>
					<?php if($default['de_sns_kakaostory']) { ?><a href="<?php echo $default['de_sns_kakaostory']; ?>" target="_blank" class="sns_ks"><img src="<?php echo TB_THEME_URL; ?>/img/sns_ks.png" title="kakaostory"></a><?php } ?>
				</li>
			</ul>
		</div>
		<div class="ft_cs">
			<dl class="cswrap">
				<dt class="tit">고객센터 <span class="stxt">통화량이 많을 땐 게시판을 이용해주세요</span></dt>
				<dd class="tel"><?php echo $config['company_tel']; ?></dd>
				<dd>상담 : <?php echo $config['company_hours']; ?> (<?php echo $config['company_close']; ?>)</dd>
				<dd>점심 : <?php echo $config['company_lunch']; ?></dd>
			</dl>
			<dl class="bkwrap">
				<dt class="tit">입금계좌안내 <span class="stxt">은행 및 예금주를 확인해주세요</span></dt>
				<?php $bank = unserialize($default['de_bank_account']); ?>
				<dd class="bknum"><?php echo $bank[0]['account']; ?></dd>
				<dd>은행명 : <?php echo $bank[0]['name']; ?> / 예금주 : <?php echo $bank[0]['holder']; ?></dd>
				<dd class="etc_btn">
					<?php if($config['partner_reg_yes']) { ?>
					<a href="<?php echo TB_BBS_URL; ?>/partner_reg.php" class="btn_lsmall">쇼핑몰 분양신청</a>
					<?php } ?>
					<?php if($config['seller_reg_yes']) { ?>
					<a href="<?php echo TB_BBS_URL; ?>/seller_reg.php" class="btn_lsmall">온라인 입점신청</a>
					<?php } ?>
				</dd>
			</dl>
			<dl class="notice">
				<dt class="tit">공지사항 <a href="<?php echo TB_BBS_URL; ?>/list.php?boardid=13" class="bt_more">더보기 <i class="fa fa-angle-right"></i></a></dt>
				<?php echo board_latest(13, 100, 4, $pt_id); ?>
			</dl>
		</div>
		<div class="company">
			<ul>
				<li>
					<?php echo $config['company_name']; ?> <span class="g_hl"></span> 대표자 : <?php echo $config['company_owner']; ?> <span class="g_hl"></span> <?php echo $config['company_addr']; ?><br>사업자등록번호 : <?php echo $config['company_saupja_no']; ?> <a  href="javascript:saupjaonopen('<?php echo conv_number($config['company_saupja_no']); ?>');" class="btn_ssmall grey2 marl5">사업자정보확인</a> <span class="g_hl"></span> 통신판매업신고 : <?php echo $config['tongsin_no']; ?><br>고객센터 : <?php echo $config['company_tel']; ?> <span class="g_hl"></span> FAX : <?php echo $config['company_fax']; ?> <span class="g_hl"></span> Email : <?php echo $super['email']; ?><br>개인정보보호책임자 : <?php echo $config['info_name']; ?> (<?php echo $config['info_email']; ?>)
					<p class="etctxt"><?php echo $config['company_name']; ?>의 사전 서면 동의 없이 사이트의 일체의 정보, 콘텐츠 및 UI등을 상업적 목적으로 전재, 전송, 스크래핑 등 무단 사용할 수 없습니다.</p>
					<p class="cptxt">Copyright ⓒ <?php echo $config['company_name']; ?> All rights reserved.</p>
				</li>
				<li>
					<h3>에스크로 구매안전서비스</h3>
					고객님은 안전거래를 위해 현금으로 5만원이상 결제시 구매자가 보호를 받을 수 있는 구매안전서비스(에스크로)를 이용하실 수 있습니다.<br>보상대상 : 미배송, 반품/환불거부, 쇼핑몰부도
					<p class="mart7"><a href="#" onclick="escrow_foot_check(); return false;" class="btn_ssmall bx-grey">서비스가입사실 확인 <i class="fa fa-angle-right"></i></a></p>
				</li>
			</ul>
		</div>
	</div>

	<?php if($default['de_pg_service'] == 'kcp') { ?>
	<form name="escrow_foot" method="post" autocomplete="off">
	<input type="hidden" name="site_cd" value="<?php echo $default['de_kcp_mid']; ?>">
	</form>
	<?php } ?>

	<script>
	function escrow_foot_check()
	{
        <?php if($default['de_pg_service'] == 'allat') { ?>
        var business_no = "<?php echo conv_number($config['company_saupja_no']); ?>";
        window.open("https://www.allatpay.com/servlet/AllatBizV2/svcinfo/SvcInfoMainCL?menu_id=m010602&action_flag=SEARCH&search_no=biz_no&es_business_no=" + business_no, "escrow_foot_pop","scrollbars=yes,width=565,height=683,top=10,left=10");
        <?php } ?>
		<?php if($default['de_pg_service'] == 'inicis') { ?>
		var mid = "<?php echo $default['de_inicis_mid']; ?>";
		window.open("https://mark.inicis.com/mark/escrow_popup.php?mid="+mid, "escrow_foot_pop","scrollbars=yes,width=565,height=683,top=10,left=10");
		<?php } ?>
		<?php if($default['de_pg_service'] == 'lg') { ?>
		var mid = "<?php echo $default['de_lg_mid']; ?>";
		window.open("https://pgweb.uplus.co.kr/ms/escrow/s_escrowYn.do?mertid="+mid, "escrow_foot_pop","scrollbars=yes,width=465,height=530,top=10,left=10");
		<?php } ?>
		<?php if($default['de_pg_service'] == 'kcp') { ?>
		window.open("", "escrow_foot_pop", "width=500 height=450 menubar=no,scrollbars=no,resizable=no,status=no");

		document.escrow_foot.target = "escrow_foot_pop";
		document.escrow_foot.action = "http://admin.kcp.co.kr/Modules/escrow/kcp_pop.jsp";
		document.escrow_foot.submit();
		<?php } ?>
	}
	</script>
	<!-- } 카피라이터 끝 -->
</div>

<?php
if(TB_DEVICE_BUTTON_DISPLAY && !TB_IS_MOBILE && is_mobile()) { ?>
<a href="<?php echo TB_URL; ?>/index.php?device=mobile" id="device_change">모바일 버전으로 보기</a>
<?php } ?>
