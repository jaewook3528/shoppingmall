<?php
include_once("./_common.php");

$is_seometa = 'it'; // SEO 메타태그

$gs = get_goods($gs_id);
if(!$gs['index_no'])
	alert('등록된 상품이 없습니다');
else if(!is_admin() && $gs['shop_state'])
	alert('현재 판매가능한 상품이 아닙니다.');

// 오늘 본 상품 저장 시작
if(get_session('ss_pr_idx')) {
	$arr_ss_pr_idx = get_session('ss_pr_idx');
	$arr_tmps = explode(",",$arr_ss_pr_idx);
	if(!in_array($gs_id,$arr_tmps)) {
		$ss_pr_idx = get_session('ss_pr_idx').",".$gs_id;
		set_session('ss_pr_idx', $ss_pr_idx);
	}
} else {
	$ss_pr_idx = get_session('ss_pr_idx').$gs_id;
	set_session('ss_pr_idx', $ss_pr_idx);
}

// 공급업체 정보
$sr = get_seller_cd($gs['mb_id']);
if($gs['use_aff']) {
	$sr = get_partner($gs['mb_id']);
}

// 포인트 적용에 따른 출력형태
if($gs['gpoint'] > 0 && $gs['goods_price'] > 0){
	$rate = number_format((($gs['gpoint'] / $gs['goods_price']) * 100), 0);
	$gpoint = display_point($gs['gpoint'])." <span class='fc_107'>($rate%)</span>";
}

// 상품문의 건수구하기
$sql = "select count(*) as cnt from shop_goods_qa where gs_id = '$gs_id'";
$itemqa_count = (int)$row['cnt'];

// 구매후기 건수구하기
$sql = "select count(*) as cnt from shop_goods_review where gs_id = '$gs_id'";
if($default['de_review_wr_use']) {
	$sql .= " and pt_id = '$pt_id' ";
}
$row = sql_fetch($sql);
$item_use_count = (int)$row['cnt'];

// 고객선호도 별점수
$star_score = get_star_image($gs_id);

// 고객선호도 평점
$aver_score = ($star_score * 10) * 2;

// 대표 카테고리
$sql = "select * from shop_goods_cate where gs_id='$gs_id' order by index_no asc limit 1 ";
$ca = sql_fetch($sql);

// 상품조회 카운터하기
sql_query("update shop_goods set readcount = readcount + 1 where index_no='$gs_id'");

// 수량체크
if(!$gs['stock_mod']) {
	$gs['stock_qty'] = 999999999;
}

if($gs['odr_min']) // 최소구매수량
	$odr_min = (int)$gs['odr_min'];
else
	$odr_min = 1;

if($gs['odr_max']) // 최대구매수량
	$odr_max = (int)$gs['odr_max'];
else
	$odr_max = 0;

$is_only = false;
$is_buy_only = false;
$is_pr_msg = false;
$is_social_end = false;
$is_social_ing = false;

// 품절체크
$is_soldout = is_soldout($gs_id);

if($is_soldout) {
	$script_msg = "현재상품은 품절 상품입니다.";
} else {
	if($gs['price_msg']) {
		$is_pr_msg = true;
		$script_msg = "현재상품은 구매신청 하실 수 없습니다.";
	} else if($gs['buy_only'] == 1 && $member['grade'] > $gs['buy_level']) {
		$is_only = true;
		$script_msg = "현재상품은 구매신청 하실 수 없습니다.";
	} else if($gs['buy_only'] == 0 && $member['grade'] > $gs['buy_level']) {
		if(!$is_member) {
			$is_buy_only = true;
			$script_msg = "현재상품은 회원만 구매 하실 수 있습니다.";
		} else {
			$script_msg = "현재상품을 구매하실 권한이 없습니다.";
		}
	} else {
		$script_msg = "";
	}

	if(substr($gs['sb_date'],0,1) != '0' && substr($gs['eb_date'],0,1) != '0') {
		if($gs['eb_date'] < TB_TIME_YMD) {
			$is_social_end	= true;
			$is_social_txt	= "(판매종료) 시작일 : ".substr($gs['sb_date'],0,4)." / ";
			$is_social_txt .= substr($gs['sb_date'],5,2)." / ";
			$is_social_txt .= substr($gs['sb_date'],8,2)." ~ ";
			$is_social_txt .= "종료일 : ".substr($gs['eb_date'],0,4)." / ";
			$is_social_txt .= substr($gs['eb_date'],5,2)." / ";
			$is_social_txt .= substr($gs['eb_date'],8,2);

			$script_msg	= "현재 상품은 판매기간이 종료 되었습니다.";

		} else if($gs['sb_date'] > TB_TIME_YMD) {
			$is_social_end	= true;
			$is_social_txt	= "(판매대기) 시작일 : ".substr($gs['sb_date'],0,4)." / ";
			$is_social_txt .= substr($gs['sb_date'],5,2)." / ";
			$is_social_txt .= substr($gs['sb_date'],8,2)." ~ ";
			$is_social_txt .= "종료일 : ".substr($gs['eb_date'],0,4)." / ";
			$is_social_txt .= substr($gs['eb_date'],5,2)." / ";
			$is_social_txt .= substr($gs['eb_date'],8,2);

			$script_msg	= "현재 상품은 판매대기 상품 입니다.";

		} else if($gs['sb_date'] <= TB_TIME_YMD && $gs['eb_date'] >= TB_TIME_YMD) {
			$is_social_ing	= true;

			// 소셜 스크립트
			define('M_TIMESALE', TB_MTHEME_PATH.'/time.skin.php');
		}
	}
}

// 필수 옵션
$option_item = mobile_item_options($gs_id, $gs['opt_subject'], " style='width:100%'");

// 추가 옵션
$supply_item = mobile_item_supply($gs_id, $gs['spl_subject'], " style='width:100%'");

// 가맹점상품은 쿠폰발급안함
if(!$gs['use_aff'] && $config['coupon_yes']) {
	// 쿠폰발급 (회원직접 다운로드)
	$cp_used = is_used_coupon('0', $gs_id);

	if($is_member)
		$cp_btn = "<a href=\"javascript:window.open('".TB_MSHOP_URL."/pop_coupon.php?gs_id=$gs_id','_blank');\" class=\"btn_ssmall bx-blue\">쿠폰다운로드</a>";
	else
		$cp_btn = "<a href=\"javascript:alert('로그인 후 이용 가능합니다.')\" class=\"btn_ssmall bx-blue\">쿠폰다운로드</a>";
}

// SNS
$sns_title = get_text($gs['gname']).' | '.get_head_title('head_title', $pt_id);
$sns_url = TB_SHOP_URL.'/view.php?index_no='.$gs_id;
$sns_share_links .= get_sns_share_link('facebook', $sns_url, $sns_title, TB_IMG_URL.'/sns/facebook.gif');
$sns_share_links .= get_sns_share_link('twitter', $sns_url, $sns_title, TB_IMG_URL.'/sns/twitter.gif');
$sns_share_links .= get_sns_share_link('kakaostory', $sns_url, $sns_title, TB_IMG_URL.'/sns/kakaostory.gif');
$sns_share_links .= get_sns_share_link('naverband', $sns_url, $sns_title, TB_IMG_URL.'/sns/naverband.gif');
$sns_share_links .= get_sns_share_link('googleplus', $sns_url, $sns_title, TB_IMG_URL.'/sns/googleplus.gif');
$sns_share_links .= get_sns_share_link('naver', $sns_url, $sns_title, TB_IMG_URL.'/sns/naver.gif');
$sns_share_links .= get_sns_share_link('pinterest', $sns_url, $sns_title, TB_IMG_URL.'/sns/pinterest.gif');

$token = md5(uniqid(rand(), true));
set_session("ss_token", $token);

$pg['pagename'] = '상품 상세보기';
include_once("./_head.php");
include_once(TB_LIB_PATH.'/goodsinfo.lib.php');
include_once(TB_SHOP_PATH.'/settle_naverpay.inc.php');

$slide_img = array();
for($i=2; $i<=6; $i++) { // 슬라이드 이미지
	$it_image = trim($gs['simg'.$i]);
	if(!$it_image) continue;

	if(preg_match("/^(http[s]?:\/\/)/", $it_image) == false) {
		$file = TB_DATA_PATH."/goods/".$it_image;	
		if(is_file($file)) {
			$slide_img[] = rpc($file, TB_PATH, TB_URL);		
		}
	} else {
		$slide_img[] = $it_image;
	}	
}

$slide_url = implode('|', $slide_img);
$slide_cnt = count($slide_img);

include_once(TB_MTHEME_PATH.'/view.skin.php');

include_once("./_tail.php");
?>