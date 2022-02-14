<?php
if(!defined('_TUBEWEB_')) exit;

$pg_navi = '공급사 관리자';

function printMenu1($key, $subject)
{
	$svc_class = 'smenu'.$key;
	if(get_cookie("ck_{$svc_class}")) {
		$svc_class .= ' menu_close';
	}

	return '<dt class="'.$svc_class.' menu_toggle">'.$subject.'</dt>';
}

function printMenu2($key, $subject, $url, $menu_cnt='')
{
	$svc_class = 'smenu'.$key;
	if(get_cookie("ck_{$svc_class}")) {
		$svc_class .= ' menu_close';
	}

	$current_class = '';
	$count_class = '';
	if(is_numeric($menu_cnt)) {
		if($menu_cnt > 0)
			$current_class = ' class="snb_air"';
		$count_class = '<em'.$current_class.'>'.$menu_cnt.'</em>';
	}

	return '<dd class="'.$svc_class.'"><a href="'.$url.'">'.$subject.$count_class.'</a></dd>';
}

define('asideUrl', TB_MYPAGE_URL.'/page.php');
define('boardUrl', TB_BBS_URL.'/list.php');

$jaego1  = admin_gs_jaego_bujog("and mb_id = '{$seller['seller_code']}'"); // 재고부족
$jaego2  = admin_io_jaego_bujog("and b.mb_id = '{$seller['seller_code']}'"); // 옵션재고부족
$starCnt = admin_goods_review("and seller_id = '{$seller['seller_code']}'"); // 총 상품평점
$qaCnt   = admRequest("shop_goods_qa", "and seller_id = '{$seller['seller_code']}'"); // 총 상품문의

$sql_where = " where seller_id = '{$seller['seller_code']}' ";
$sodrr = admin_order_status_sum("{$sql_where} and dan > 1 "); // 총 주문내역
$sodr2 = admin_order_status_sum("{$sql_where} and dan = 2 "); // 총 입금완료
$sodr3 = admin_order_status_sum("{$sql_where} and dan = 3 "); // 총 배송준비
$sodr4 = admin_order_status_sum("{$sql_where} and dan = 4 "); // 총 배송중
$sodr5 = admin_order_status_sum("{$sql_where} and dan = 5 "); // 총 배송완료
$sodr6 = admin_order_status_sum("{$sql_where} and dan = 6 "); // 총 입금전 취소
$sodr7 = admin_order_status_sum("{$sql_where} and dan = 7 "); // 총 배송후 반품
$sodr8 = admin_order_status_sum("{$sql_where} and dan = 8 "); // 총 배송후 교환
$sodr9 = admin_order_status_sum("{$sql_where} and dan = 9 "); // 총 배송전 환불
?>

<dl>
	<?php echo printMenu1(1, '정보관리'); ?>
	<?php echo printMenu2(1, '업체 정보관리', asideUrl.'?code=seller_info'); ?>
	<?php echo printMenu2(1, '업체 배송정책', asideUrl.'?code=seller_baesong'); ?>
	<?php echo printMenu1(2, '상품관리'); ?>
	<?php echo printMenu2(2, '전체 상품관리', asideUrl.'?code=seller_goods_list'); ?>
	<?php echo printMenu2(2, '상품 재고관리', asideUrl.'?code=seller_goods_stock', $jaego1); ?>
	<?php echo printMenu2(2, '상품 옵션재고관리', asideUrl.'?code=seller_goods_optstock', $jaego2); ?>
	<?php echo printMenu2(2, '브랜드관리', asideUrl.'?code=seller_goods_brand_list'); ?>
	<?php echo printMenu2(2, '상품 문의관리', asideUrl.'?code=seller_goods_qa', $qaCnt); ?>
	<?php echo printMenu2(2, '상품 평점관리', asideUrl.'?code=seller_goods_review', $starCnt); ?>
	<?php echo printMenu1(3, '일괄처리'); ?>
	<?php echo printMenu2(3, '상품 엑셀일괄등록', asideUrl.'?code=seller_goods_xls_reg'); ?>
	<?php echo printMenu2(3, '상품 엑셀일괄수정', asideUrl.'?code=seller_goods_xls_mod'); ?>
	<?php echo printMenu1(4, '주문관리'); ?>
	<?php echo printMenu2(4, '주문리스트(전체)', asideUrl.'?code=seller_odr_list', $sodrr['cnt']); ?>
	<?php echo printMenu2(4, '입금완료(배송요청)', asideUrl.'?code=seller_odr_2', $sodr2['cnt']); ?>
	<?php echo printMenu2(4, '배송준비', asideUrl.'?code=seller_odr_3', $sodr3['cnt']); ?>
	<?php echo printMenu2(4, '배송중', asideUrl.'?code=seller_odr_4', $sodr4['cnt']); ?>
	<?php echo printMenu2(4, '배송완료', asideUrl.'?code=seller_odr_5', $sodr5['cnt']); ?>
	<?php echo printMenu1(5, '취소/교환/반품/환불 관리'); ?>
	<?php echo printMenu2(5, '입금전 취소', asideUrl.'?code=seller_odr_6', $sodr6['cnt']); ?>
	<?php echo printMenu2(5, '배송전 환불', asideUrl.'?code=seller_odr_9', $sodr9['cnt']); ?>
	<?php echo printMenu2(5, '배송후 반품', asideUrl.'?code=seller_odr_7', $sodr7['cnt']); ?>
	<?php echo printMenu2(5, '배송후 교환', asideUrl.'?code=seller_odr_8', $sodr8['cnt']); ?>
	<?php echo printMenu1(6, '기타/정산관리'); ?>
	<?php echo printMenu2(6, '주문통계분석', asideUrl.'?code=seller_odr_stats'); ?>
	<?php echo printMenu2(6, '정산대기목록', asideUrl.'?code=seller_odr_balance'); ?>
	<?php echo printMenu2(6, '정산완료목록', asideUrl.'?code=seller_odr_account'); ?>
	<?php echo printMenu2(6, '공지사항', boardUrl.'?boardid=20'); ?>
	<?php echo printMenu2(6, '질문과답변', boardUrl.'?boardid=21'); ?>
</dl>
