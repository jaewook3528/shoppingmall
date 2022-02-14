<?php
if(!defined('_TUBEWEB_')) exit;

function printMenu1($svc_class, $subject)
{
	if(get_cookie("ck_{$svc_class}")) {
		$svc_class .= ' menu_close';
	}

	return '<dt class="'.$svc_class.' menu_toggle">'.$subject.'</dt>';
}

function printMenu2($svc_class, $subject, $url, $menu_cnt='')
{
	global $pg_title2;

	if(get_cookie("ck_{$svc_class}")) {
		$svc_class .= ' menu_close';
	}

	if($pg_title2 == $subject)
		$svc_class .= ' active';

	$current_class = '';
	$count_class = '';
	if(is_numeric($menu_cnt)) {
		if($menu_cnt > 0)
			$current_class = ' class="snb_air"';
		$count_class = '<em'.$current_class.'>'.$menu_cnt.'</em>';
	}

	return '<dd class="'.$svc_class.'"><a href="'.$url.'">'.$subject.$count_class.'</a></dd>';
}

{
    # 1:1상담관리 답변 전
    $sql_common = " from shop_qa ";
    $sql_search = " where 1=1 ";
    if($_SESSION['ss_mb_id']!='admin')
    {
        $sql_search .= " AND pt_id ='{$_SESSION['ss_mb_id']}' ";
    }else{
        $sql_search .= " AND pt_id ='' ";
    }
    $sql_search .= " AND result_yes='0' ";
    $m2m_cnt = get_qa_total($sql_common,$sql_search);
}
?>

<div id="snb">
	<div class="snb_header ico_config">
		<h2><?php echo $snb_icon; ?><?php echo $pg_title; ?></h2>
	</div>
	<?php
	if($pg_title == ADMIN_MENU1) { ?>
	<dl>
		<?php echo printMenu1('m10', '회원관리'); ?>
		<?php echo printMenu2('m10', ADMIN_MENU1_01, TB_ADMIN_URL.'/member.php?code=list'); ?>
		<?php echo printMenu2('m10', ADMIN_MENU1_02, TB_ADMIN_URL.'/member.php?code=level_form'); ?>
		<?php echo printMenu2('m10', ADMIN_MENU1_03, TB_ADMIN_URL.'/member.php?code=register_form'); ?>
		<?php echo printMenu2('m10', ADMIN_MENU1_04, TB_ADMIN_URL.'/member.php?code=xls'); ?>
		<?php echo printMenu2('m10', ADMIN_MENU1_05, TB_ADMIN_URL.'/member.php?code=month'); ?>
		<?php echo printMenu2('m10', ADMIN_MENU1_06, TB_ADMIN_URL.'/member.php?code=day'); ?>
		<?php echo printMenu1('m20', '포인트관리'); ?>
		<?php echo printMenu2('m20', ADMIN_MENU1_07, TB_ADMIN_URL.'/member.php?code=point'); ?>
	</dl>
	<?php }
	else if($pg_title == ADMIN_MENU2) {
		$anewCnt = admRequest("shop_partner");
		$termCnt = admRequest("shop_partner_term");
		$runCnt  = admRequest("shop_partner_payrun");
	?>
	<dl>
		<?php echo printMenu1('p10', '가맹점 관리'); ?>
		<?php echo printMenu2('p10', ADMIN_MENU2_01, TB_ADMIN_URL.'/partner.php?code=pform'); ?>
		<?php echo printMenu2('p10', ADMIN_MENU2_02, TB_ADMIN_URL.'/partner.php?code=pbasic'); ?>
		<?php echo printMenu2('p10', ADMIN_MENU2_05, TB_ADMIN_URL.'/partner.php?code=plist'); ?>
		<?php echo printMenu2('p10', ADMIN_MENU2_03, TB_ADMIN_URL.'/partner.php?code=anewlist', $anewCnt); ?>
        <?php echo printMenu2('p30', ADMIN_MENU2_12, TB_ADMIN_URL.'/partner.php?code=uf_reg'); ?>
		<?php if($config['pf_expire_use']) { // 관리비를 사용중인가? ?>
		<?php echo printMenu2('p10', ADMIN_MENU2_04, TB_ADMIN_URL.'/partner.php?code=termlist', $termCnt); ?>
		<?php } ?>
		<?php echo printMenu1('p20', '가맹점 수수료'); ?>
		<?php echo printMenu2('p20', ADMIN_MENU2_06, TB_ADMIN_URL.'/partner.php?code=paylist'); ?>
		<?php if($config['pf_payment_type']) { // 가맹점 직접출금 ?>
		<?php echo printMenu2('p20', ADMIN_MENU2_08, TB_ADMIN_URL.'/partner.php?code=payrun', $runCnt); ?>
		<?php } else { // 관리자 고정정산 ?>
		<?php echo printMenu2('p20', ADMIN_MENU2_07, TB_ADMIN_URL.'/partner.php?code=balancelist'); ?>
		<?php } ?>
		<?php echo printMenu2('p20', ADMIN_MENU2_09, TB_ADMIN_URL.'/partner.php?code=payhistory'); ?>
		<?php echo printMenu1('p30', '가맹점 기타'); ?>
		<?php echo printMenu2('p30', ADMIN_MENU2_10, TB_ADMIN_URL.'/partner.php?code=leave'); ?>
		<?php echo printMenu2('p30', ADMIN_MENU2_11, TB_ADMIN_URL.'/partner.php?code=tree'); ?>
	</dl>
	<?php }
	else if($pg_title == ADMIN_MENU3) {
		$anewCnt = admRequest("shop_seller");
	?>
	<dl>
		<?php echo printMenu1('s10', '공급사 관리'); ?>
		<?php echo printMenu2('s10', ADMIN_MENU3_01, TB_ADMIN_URL.'/seller.php?code=list', $anewCnt); ?>
		<?php echo printMenu2('s10', ADMIN_MENU3_02, TB_ADMIN_URL.'/seller.php?code=register'); ?>
		<?php echo printMenu2('s10', ADMIN_MENU3_03, TB_ADMIN_URL.'/seller.php?code=xls'); ?>
		<?php echo printMenu1('s20', '공급사 정산'); ?>
		<?php echo printMenu2('s20', ADMIN_MENU3_04, TB_ADMIN_URL.'/seller.php?code=pay'); ?>
		<?php echo printMenu2('s20', ADMIN_MENU3_05, TB_ADMIN_URL.'/seller.php?code=pay_history'); ?>
	</dl>
	<?php }
	else if($pg_title == ADMIN_MENU4) { ?>
	<dl>
		<?php echo printMenu1('c10', '카테고리 관리'); ?>
		<?php echo printMenu2('c10', ADMIN_MENU4_01, TB_ADMIN_URL.'/category.php?code=list'); ?>
		<?php echo printMenu2('c10', ADMIN_MENU4_02, TB_ADMIN_URL.'/category.php?code=view'); ?>
	</dl>
	<?php }
	else if($pg_title == ADMIN_MENU5) {
		$regCnt = admRequest("shop_goods");
		$qaCnt  = admRequest("shop_goods_qa", "and (left(seller_id,3)='AP-' or seller_id = 'admin')");
		$jaego1 = admin_gs_jaego_bujog("and shop_state = 0 and use_aff = 0"); // 재고부족
		$jaego2 = admin_io_jaego_bujog("and b.shop_state = 0 and b.use_aff = 0"); // 옵션재고부족
	?>
	<dl>
		<?php echo printMenu1('g10', '상품관리'); ?>
		<?php echo printMenu2('g10', ADMIN_MENU5_01, TB_ADMIN_URL.'/goods.php?code=list'); ?>
		<?php echo printMenu2('g10', ADMIN_MENU5_02, TB_ADMIN_URL.'/goods.php?code=type'); ?>
		<?php echo printMenu2('g10', ADMIN_MENU5_03, TB_ADMIN_URL.'/goods.php?code=brand_list'); ?>
		<?php echo printMenu2('g10', ADMIN_MENU5_04, TB_ADMIN_URL.'/goods.php?code=plan'); ?>
		<?php echo printMenu2('g10', ADMIN_MENU5_05, TB_ADMIN_URL.'/goods.php?code=price'); ?>
		<?php echo printMenu1('g11', '재고관리'); ?>
		<?php echo printMenu2('g11', ADMIN_MENU5_06, TB_ADMIN_URL.'/goods.php?code=stock', $jaego1); ?>
		<?php echo printMenu2('g11', ADMIN_MENU5_07, TB_ADMIN_URL.'/goods.php?code=optstock', $jaego2); ?>
		<?php echo printMenu1('g20', '일괄처리'); ?>
		<?php echo printMenu2('g20', ADMIN_MENU5_08, TB_ADMIN_URL.'/goods.php?code=xls_reg'); ?>
		<?php echo printMenu2('g20', ADMIN_MENU5_09, TB_ADMIN_URL.'/goods.php?code=xls_option_reg'); ?>
		<?php echo printMenu2('g20', ADMIN_MENU5_10, TB_ADMIN_URL.'/goods.php?code=xls_mod'); ?>
		<?php echo printMenu2('g20', ADMIN_MENU5_11, TB_ADMIN_URL.'/goods.php?code=getprice'); ?>
		<?php echo printMenu2('g20', ADMIN_MENU5_12, TB_ADMIN_URL.'/goods.php?code=getpoint'); ?>
		<?php echo printMenu2('g20', ADMIN_MENU5_13, TB_ADMIN_URL.'/goods.php?code=getuse'); ?>
		<?php echo printMenu2('g20', ADMIN_MENU5_14, TB_ADMIN_URL.'/goods.php?code=getmove'); ?>
		<?php echo printMenu2('g20', ADMIN_MENU5_15, TB_ADMIN_URL.'/goods.php?code=getbrand'); ?>
		<?php echo printMenu2('g20', ADMIN_MENU5_16, TB_ADMIN_URL.'/goods.php?code=getdelivery'); ?>
		<?php echo printMenu2('g20', ADMIN_MENU5_17, TB_ADMIN_URL.'/goods.php?code=getbuylevel'); ?>
		<?php echo printMenu1('g30', '대기상품'); ?>
		<?php echo printMenu2('g30', ADMIN_MENU5_18, TB_ADMIN_URL.'/goods.php?code=supply', $regCnt); ?>
		<?php echo printMenu2('g30', ADMIN_MENU5_19, TB_ADMIN_URL.'/goods.php?code=userlist'); ?>
		<?php echo printMenu1('g40', '문의 / 후기'); ?>
		<?php echo printMenu2('g40', ADMIN_MENU5_20, TB_ADMIN_URL.'/goods.php?code=qa', $qaCnt); ?>
		<?php echo printMenu2('g40', ADMIN_MENU5_21, TB_ADMIN_URL.'/goods.php?code=review'); ?>
		<?php echo printMenu1('g50', '쿠폰관리'); ?>
		<?php echo printMenu2('g50', ADMIN_MENU5_22, TB_ADMIN_URL.'/goods.php?code=gift'); ?>
		<?php echo printMenu2('g50', ADMIN_MENU5_23, TB_ADMIN_URL.'/goods.php?code=coupon'); ?>
	</dl>
	<?php }
	else if($pg_title == ADMIN_MENU6) {
		$sodrr = admin_order_status_sum("where dan > 0 "); // 총 주문내역
		$sodr1 = admin_order_status_sum("where dan = 1 "); // 총 입금대기
		$sodr2 = admin_order_status_sum("where dan = 2 "); // 총 입금완료
		$sodr3 = admin_order_status_sum("where dan = 3 "); // 총 배송준비
		$sodr4 = admin_order_status_sum("where dan = 4 "); // 총 배송중
		$sodr5 = admin_order_status_sum("where dan = 5 "); // 총 배송완료
		$sodr6 = admin_order_status_sum("where dan = 6 "); // 총 입금전 취소
		$sodr7 = admin_order_status_sum("where dan = 7 "); // 총 배송후 반품
		$sodr8 = admin_order_status_sum("where dan = 8 "); // 총 배송후 교환
		$sodr9 = admin_order_status_sum("where dan = 9 "); // 총 배송전 환불
		$memoCnt = admin_order_memo(); // 총 관리자메모
	?>
	<dl>
		<?php echo printMenu1('o10', '주문관리'); ?>
		<?php echo printMenu2('o10', ADMIN_MENU6_01, TB_ADMIN_URL.'/order.php?code=list', $sodrr['cnt']); ?>
		<?php echo printMenu2('o10', ADMIN_MENU6_02, TB_ADMIN_URL.'/order.php?code=1', $sodr1['cnt']); ?>
		<?php echo printMenu2('o10', ADMIN_MENU6_03, TB_ADMIN_URL.'/order.php?code=2', $sodr2['cnt']); ?>
		<?php echo printMenu2('o10', ADMIN_MENU6_04, TB_ADMIN_URL.'/order.php?code=3', $sodr3['cnt']); ?>
		<?php echo printMenu2('o10', ADMIN_MENU6_05, TB_ADMIN_URL.'/order.php?code=4', $sodr4['cnt']); ?>
		<?php echo printMenu2('o10', ADMIN_MENU6_06, TB_ADMIN_URL.'/order.php?code=5', $sodr5['cnt']); ?>
		<?php echo printMenu2('o10', ADMIN_MENU6_07, TB_ADMIN_URL.'/order.php?code=delivery'); ?>
		<?php echo printMenu1('o20', '취소/교환/반품/환불 관리'); ?>
		<?php echo printMenu2('o20', ADMIN_MENU6_08, TB_ADMIN_URL.'/order.php?code=6', $sodr6['cnt']); ?>
		<?php echo printMenu2('o20', ADMIN_MENU6_09, TB_ADMIN_URL.'/order.php?code=9', $sodr9['cnt']); ?>
		<?php echo printMenu2('o20', ADMIN_MENU6_10, TB_ADMIN_URL.'/order.php?code=7', $sodr7['cnt']); ?>
		<?php echo printMenu2('o20', ADMIN_MENU6_11, TB_ADMIN_URL.'/order.php?code=8', $sodr8['cnt']); ?>
		<?php echo printMenu1('o30', '기타관리'); ?>
		<?php echo printMenu2('o30', ADMIN_MENU6_12, TB_ADMIN_URL.'/order.php?code=memo', $memoCnt); ?>
	</dl>
	<?php }
	else if($pg_title == ADMIN_MENU7) { ?>
	<dl>
		<?php echo printMenu1('v10', '접속자통계'); ?>
		<?php echo printMenu2('v10', ADMIN_MENU7_01, TB_ADMIN_URL.'/visit.php?code=hour'); ?>
		<?php echo printMenu2('v10', ADMIN_MENU7_02, TB_ADMIN_URL.'/visit.php?code=date'); ?>
		<?php echo printMenu2('v10', ADMIN_MENU7_03, TB_ADMIN_URL.'/visit.php?code=week'); ?>
		<?php echo printMenu2('v10', ADMIN_MENU7_04, TB_ADMIN_URL.'/visit.php?code=month'); ?>
		<?php echo printMenu2('v10', ADMIN_MENU7_05, TB_ADMIN_URL.'/visit.php?code=year'); ?>
		<?php echo printMenu2('v10', ADMIN_MENU7_06, TB_ADMIN_URL.'/visit.php?code=browser'); ?>
		<?php echo printMenu2('v10', ADMIN_MENU7_07, TB_ADMIN_URL.'/visit.php?code=os'); ?>
		<?php echo printMenu2('v10', ADMIN_MENU7_08, TB_ADMIN_URL.'/visit.php?code=domain'); ?>
		<?php echo printMenu2('v10', ADMIN_MENU7_09, TB_ADMIN_URL.'/visit.php?code=search'); ?>
		<?php echo printMenu1('v20', '주문통계'); ?>
		<?php echo printMenu2('v20', ADMIN_MENU7_10, TB_ADMIN_URL.'/visit.php?code=order1'); ?>
		<?php echo printMenu2('v20', ADMIN_MENU7_11, TB_ADMIN_URL.'/visit.php?code=order2'); ?>
		<?php echo printMenu2('v20', ADMIN_MENU7_12, TB_ADMIN_URL.'/visit.php?code=cancel'); ?>
		<?php echo printMenu2('v20', ADMIN_MENU7_13, TB_ADMIN_URL.'/visit.php?code=return'); ?>
		<?php echo printMenu2('v20', ADMIN_MENU7_14, TB_ADMIN_URL.'/visit.php?code=change'); ?>
		<?php echo printMenu2('v20', ADMIN_MENU7_15, TB_ADMIN_URL.'/visit.php?code=refund'); ?>
	</dl>
	<?php }
	else if($pg_title == ADMIN_MENU8) { ?>
	<dl>
		<?php echo printMenu1('h10', '고객지원'); ?>
		<?php echo printMenu2('h10', ADMIN_MENU8_01, TB_ADMIN_URL.'/help.php?code=qa',$m2m_cnt); ?>
		<?php echo printMenu2('h10', ADMIN_MENU8_02, TB_ADMIN_URL.'/help.php?code=leave'); ?>
		<?php echo printMenu1('h20', 'FAQ 관리'); ?>
		<?php echo printMenu2('h20', ADMIN_MENU8_03, TB_ADMIN_URL.'/help.php?code=faq_group'); ?>
		<?php echo printMenu2('h20', ADMIN_MENU8_04, TB_ADMIN_URL.'/help.php?code=faq'); ?>
	</dl>
	<?php }
	else if($pg_title == ADMIN_MENU9) { ?>
	<dl>
		<?php echo printMenu1('d10', '배너관리'); ?>
		<?php echo printMenu2('d10', ADMIN_MENU9_01, TB_ADMIN_URL.'/design.php?code=banner_list'); ?>
		<?php echo printMenu2('d10', ADMIN_MENU9_02, TB_ADMIN_URL.'/design.php?code=mbanner_list'); ?>
		<?php echo printMenu1('d20', '로고/페이지관리'); ?>
		<?php echo printMenu2('d20', ADMIN_MENU9_03, TB_ADMIN_URL.'/design.php?code=logo'); ?>
		<?php echo printMenu2('d20', ADMIN_MENU9_04, TB_ADMIN_URL.'/design.php?code=contentlist'); ?>
        <?php echo printMenu2('d20', ADMIN_MENU9_07, TB_ADMIN_URL.'/design.php?code=contentdefaultlist'); ?>
		<?php echo printMenu1('d30', '기타관리'); ?>
		<?php echo printMenu2('d30', ADMIN_MENU9_05, TB_ADMIN_URL.'/design.php?code=best_item'); ?>
		<?php echo printMenu2('d30', ADMIN_MENU9_06, TB_ADMIN_URL.'/design.php?code=popup_list'); ?>
	</dl>
	<?php }
	else if($pg_title == ADMIN_MENU10) { ?>
	<dl>
		<?php echo printMenu1('q10', '기본환경설정'); ?>
		<?php echo printMenu2('q10', ADMIN_MENU10_01, TB_ADMIN_URL.'/config.php?code=default'); ?>
		<?php echo printMenu2('q10', ADMIN_MENU10_02, TB_ADMIN_URL.'/config.php?code=meta'); ?>
		<?php echo printMenu2('q10', ADMIN_MENU10_03, TB_ADMIN_URL.'/config.php?code=sns'); ?>
		<?php echo printMenu2('q10', ADMIN_MENU10_04, TB_ADMIN_URL.'/config.php?code=register'); ?>
		<?php echo printMenu2('q10', ADMIN_MENU10_05, TB_ADMIN_URL.'/config.php?code=sendmail_test'); ?>
		<?php echo printMenu2('q10', ADMIN_MENU10_06, TB_ADMIN_URL.'/config.php?code=sms'); ?>
		<?php echo printMenu2('q10', ADMIN_MENU10_07, TB_ADMIN_URL.'/config.php?code=supply'); ?>
		<?php echo printMenu2('q10', ADMIN_MENU10_08, TB_ADMIN_URL.'/config.php?code=super'); ?>
		<?php echo printMenu1('q20', '결제관리'); ?>
		<?php echo printMenu2('q20', ADMIN_MENU10_09, TB_ADMIN_URL.'/config.php?code=pg'); ?>
		<?php echo printMenu2('q20', ADMIN_MENU10_10, TB_ADMIN_URL.'/config.php?code=kakaopay'); ?>
		<?php echo printMenu2('q20', ADMIN_MENU10_11, TB_ADMIN_URL.'/config.php?code=naverpay'); ?>
		<?php echo printMenu1('q30', '배송관리'); ?>
		<?php echo printMenu2('q30', ADMIN_MENU10_12, TB_ADMIN_URL.'/config.php?code=baesong'); ?>
		<?php echo printMenu2('q30', ADMIN_MENU10_13, TB_ADMIN_URL.'/config.php?code=islandlist'); ?>
		<?php echo printMenu1('q40', '보안관리'); ?>
		<?php echo printMenu2('q40', ADMIN_MENU10_14, TB_ADMIN_URL.'/config.php?code=nicecheck'); ?>
		<?php echo printMenu2('q40', ADMIN_MENU10_15, TB_ADMIN_URL.'/config.php?code=ipaccess'); ?>
		<?php echo printMenu1('q50', '게시판관리'); ?>
		<?php echo printMenu2('q50', ADMIN_MENU10_16, TB_ADMIN_URL.'/config.php?code=board_group_list'); ?>
		<?php echo printMenu2('q50', ADMIN_MENU10_17, TB_ADMIN_URL.'/config.php?code=board_list'); ?>
		<?php echo printMenu2('q50', ADMIN_MENU10_18, TB_ADMIN_URL.'/config.php?code=keyword_list'); ?>
	</dl>
	<?php } ?>
</div>
