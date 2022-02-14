<?php
if(!defined('_TUBEWEB_')) exit;

$pg_navi = '가맹점 관리자';

function printMenu1($key, $subject)
{
	$svc_class = 'pmenu'.$key;
	if(get_cookie("ck_{$svc_class}")) {
		$svc_class .= ' menu_close';
	}

	return '<dt class="'.$svc_class.' menu_toggle">'.$subject.'</dt>';
}

function printMenu2($key, $subject, $url, $menu_cnt='')
{
	$svc_class = 'pmenu'.$key;
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

$jaego1  = admin_gs_jaego_bujog("and mb_id = '{$member['id']}'"); // 재고부족
$jaego2  = admin_io_jaego_bujog("and b.mb_id = '{$member['id']}'"); // 옵션재고부족
$starCnt = admin_goods_review("and seller_id = '{$member['id']}'"); // 총 상품평점
$qaCnt   = admRequest("shop_goods_qa", "and seller_id = '{$member['id']}'"); // 총 상품문의

$sql_where = " where seller_id = '{$member['id']}' ";
$sodrr = admin_order_status_sum("{$sql_where} and dan > 0 "); // 총 주문내역
$sodr1 = admin_order_status_sum("{$sql_where} and dan = 1 "); // 총 입금대기
$sodr2 = admin_order_status_sum("{$sql_where} and dan = 2 "); // 총 입금완료
$sodr3 = admin_order_status_sum("{$sql_where} and dan = 3 "); // 총 배송준비
$sodr4 = admin_order_status_sum("{$sql_where} and dan = 4 "); // 총 배송중
$sodr5 = admin_order_status_sum("{$sql_where} and dan = 5 "); // 총 배송완료
$sodr6 = admin_order_status_sum("{$sql_where} and dan = 6 "); // 총 입금전 취소
$sodr7 = admin_order_status_sum("{$sql_where} and dan = 7 "); // 총 배송후 반품
$sodr8 = admin_order_status_sum("{$sql_where} and dan = 8 "); // 총 배송후 교환
$sodr9 = admin_order_status_sum("{$sql_where} and dan = 9 "); // 총 배송전 환불

unset($sql_where);

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

<dl>
	<?php echo printMenu1(1, '기본환경 설정'); ?>
	<?php echo printMenu2(1, '기본정보 관리', asideUrl.'?code=partner_info'); ?>
	<?php if($config['pf_expire_use']) { // 월관리비를 사용중인가? ?>
	<?php echo printMenu2(1, '가맹점 연장신청', asideUrl.'?code=partner_term'); ?>
	<?php } ?>
	<?php echo printMenu2(1, '검색엔진 최적화(SEO) 설정', asideUrl.'?code=partner_meta'); ?>
	<?php echo printMenu2(1, '소셜 네트워크 설정', asideUrl.'?code=partner_sns'); ?>
	<?php if($pf_auth_good) { // 상품판매 권한이있나? ?>
	<?php echo printMenu2(1, '배송/교환/반품 설정', asideUrl.'?code=partner_baesong'); ?>
	<?php } ?>
	<?php echo printMenu2(1, '쇼핑몰 약관 설정', asideUrl.'?code=partner_agree'); ?>
	<?php echo printMenu2(1, '검색키워드 관리', asideUrl.'?code=partner_keyword_list'); ?>
	<?php if($pf_auth_pg && get_session('admin_ss_mb_id')) { // 개별 PG결제 권한이있나? ?>
	<?php echo printMenu1(2, '결제관리'); ?>
	<?php echo printMenu2(2, '전자결제 (PG) 설정', asideUrl.'?code=partner_pg'); ?>
	<?php echo printMenu2(2, '카카오페이 설정', asideUrl.'?code=partner_kakaopay'); ?>
	<?php echo printMenu2(2, '네이버페이 설정', asideUrl.'?code=partner_naverpay'); ?>
	<?php } ?>
	<?php echo printMenu1(3, '디자인관리'); ?>
	<?php echo printMenu2(3, '로고 관리', asideUrl.'?code=partner_logo'); ?>
	<?php echo printMenu2(3, '통합 배너관리', asideUrl.'?code=partner_banner_list'); ?>
	<?php echo printMenu2(3, '통합 배너관리(모바일)', asideUrl.'?code=partner_mbanner_list'); ?>
	<?php echo printMenu2(3, '메인 진열관리', asideUrl.'?code=partner_best_item'); ?>
	<?php echo printMenu2(3, '팝업 관리', asideUrl.'?code=partner_popup_list'); ?>
    <?php echo printMenu2(3, '회사소개', asideUrl.'?code=partner_about_company'); ?>
	<?php echo printMenu1(4, '회원관리'); ?>
	<?php echo printMenu2(4, '회원목록', asideUrl.'?code=partner_member_list'); ?>
	<?php echo printMenu2(4, '신규 회원등록', asideUrl.'?code=partner_register_form'); ?>
	<?php echo printMenu2(4, '트리 회원조회', asideUrl.'?code=partner_tree'); ?>
	<?php echo printMenu2(4, '일별 가입통계분석', asideUrl.'?code=partner_stats_day'); ?>
	<?php echo printMenu2(4, '월별 가입통계분석', asideUrl.'?code=partner_stats_month'); ?>
	<?php echo printMenu2(4, '접속자검색', asideUrl.'?code=partner_visit'); ?>
	<?php echo printMenu1(5, '본사 상품관리'); ?>
	<?php echo printMenu2(5, '본사 상품목록', asideUrl.'?code=partner_goods_admlist'); ?>
	<?php echo printMenu2(5, '본사 상품판매실적', asideUrl.'?code=partner_order_admlist'); ?>
	<?php echo printMenu2(5, '상품 진열관리', asideUrl.'?code=partner_goods_type'); ?>
	<?php echo printMenu1(6, '카테고리 관리'); ?>
	<?php echo printMenu2(6, '카테고리 관리', asideUrl.'?code=partner_category_list'); ?>
	<?php echo printMenu2(6, '카테고리 순위관리', asideUrl.'?code=partner_category_view'); ?>
	<?php if($pf_auth_good) { // 상품판매 권한이있나? ?>
	<?php echo printMenu1(7, '상품관리'); ?>
	<?php echo printMenu2(7, '전체 상품관리', asideUrl.'?code=partner_goods_list'); ?>
	<?php echo printMenu2(7, '상품 재고관리', asideUrl.'?code=partner_goods_stock', $jaego1); ?>
	<?php echo printMenu2(7, '상품 옵션재고관리', asideUrl.'?code=partner_goods_optstock', $jaego2); ?>
	<?php echo printMenu2(7, '브랜드관리', asideUrl.'?code=partner_goods_brand_list'); ?>
	<?php echo printMenu2(7, '상품 문의관리', asideUrl.'?code=partner_goods_qa', $qaCnt); ?>
	<?php echo printMenu2(7, '상품 평점관리', asideUrl.'?code=partner_goods_review', $starCnt); ?>
	<?php echo printMenu1(8, '일괄처리'); ?>
	<?php echo printMenu2(8, '상품 일괄등록', asideUrl.'?code=partner_goods_xls_reg'); ?>
	<?php echo printMenu2(8, '상품 일괄수정', asideUrl.'?code=partner_goods_xls_mod'); ?>
	<?php echo printMenu1(9, '주문관리'); ?>
	<?php echo printMenu2(9, '주문리스트(전체)', asideUrl.'?code=partner_odr_list', $sodrr['cnt']); ?>
	<?php echo printMenu2(9, '입금대기', asideUrl.'?code=partner_odr_1', $sodr1['cnt']); ?>
	<?php echo printMenu2(9, '입금완료', asideUrl.'?code=partner_odr_2', $sodr2['cnt']); ?>
	<?php echo printMenu2(9, '배송준비', asideUrl.'?code=partner_odr_3', $sodr3['cnt']); ?>
	<?php echo printMenu2(9, '배송중', asideUrl.'?code=partner_odr_4', $sodr4['cnt']); ?>
	<?php echo printMenu2(9, '배송완료', asideUrl.'?code=partner_odr_5', $sodr5['cnt']); ?>
	<?php echo printMenu1(10, '취소/교환/반품/환불 관리'); ?>
	<?php echo printMenu2(10, '입금전 취소', asideUrl.'?code=partner_odr_6', $sodr6['cnt']); ?>
	<?php echo printMenu2(10, '배송전 환불', asideUrl.'?code=partner_odr_9', $sodr9['cnt']); ?>
	<?php echo printMenu2(10, '배송후 반품', asideUrl.'?code=partner_odr_7', $sodr7['cnt']); ?>
	<?php echo printMenu2(10, '배송후 교환', asideUrl.'?code=partner_odr_8', $sodr8['cnt']); ?>
	<?php } ?>
	<?php echo printMenu1(11, '수수료 리포트'); ?>
	<?php echo printMenu2(11, '수수료 정산', asideUrl.'?code=partner_paylist'); ?>
	<?php echo printMenu2(11, '수수료 건별내역', asideUrl.'?code=partner_payhistory'); ?>
    <?php echo printMenu1(12, '고객센터'); ?>
	<?php echo printMenu2(12, '공지사항', boardUrl.'?boardid=22'); ?>
	<?php echo printMenu2(12, '질문과답변', boardUrl.'?boardid=36'); ?>
    <?php echo printMenu2(12, 'FAQ 관리', asideUrl.'?code=partner_faq_list'); ?>
    <?php echo printMenu2(12, '1:1상담 관리', asideUrl.'?code=partner_qa_list',$m2m_cnt); ?>
</dl>
