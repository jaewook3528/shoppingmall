<?php
if(!defined('_TUBEWEB_')) exit; // 개별 페이지 접근 불가
?>


/*******************************************************************************
/*18.10.31  전체카테고리 높이 수정 위치
/*******************************************************************************

전체카테고리 높이 수정 위치입니다

/theme/basic/style.css  72번째줄 에서
height:500px  이 값을 수정하시면 됩니다.



/*******************************************************************************
/*18.10.5  가맹점이 메인화면 배너 감춤 기능 수정
/*******************************************************************************

가맹점이 메인 화면 배너에서 감출수 있느 기능을 개선

/lib/global.lib.php  4976~4979 라인
주석처리 하시면 됩니다.



/*******************************************************************************
/*18.10.1  상품등록관련 엑셀 저장 오류 관련
/*******************************************************************************

엑셀 저장시 오류건에 대한 답변입니다.
담당 서버관리자분께 전달 부탁드립니다. 
 
현재 저희쪽에서 확인한 바로는 
PHP 설치 당시 ZipArchive 클래스가 없다고 확인되었습니다.
 
관련 사이트를 참고하시어 설치 부탁드립니다.
 
http://www.dev-su.com/main/read/55 
 
위 사이트를 참고해주시면 됩니다.


/*******************************************************************************
/*18.7.26  구글 애너리스틱스 
/*******************************************************************************

구글 애널리스틱스 - 유입분석및 통계자료분석
구글에서 제공하는 스크립트를 공통파일에 적용
<!-- Global site tag (gtag.js) - Google Analytics --> 로 제목주석처리
url : https://analytics.google.com/analytics/web

* ufound1006@gmail.com 계정으로 로그인할것


<적용방법>

관리자 로그인>환경설정>기본환경설정>검색엔진 최적화(SEO)설정
- <HEAD> 내부 태그

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-122841832-1"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'UA-122841832-1');
</script>

/*******************************************************************************
/* 2018-09-04 (분양몰 2.0.6)
/*******************************************************************************
(쿠폰관리 (인쇄용) 대량으로 쿠폰번호 생성시 중복방지 패치)
	수정) /public_html/lib/common.lib.php
	get_gift() 삭제
	get_coupon_id() 생성

	수정) /public_html/admin/goods/goods_gift_form_update.php
	수정) /public_html/admin/goods/goods_gift_serial.php
	수정) /public_html/config.php

/*******************************************************************************
/* 2018-08-08 (분양몰 2.0.5)
/*******************************************************************************
(쿠폰관리 (인쇄용) 엑셀저장시 한글깨짐 현상 오류 수정)
	수정) /public_html/admin/goods/goods_gift_excel.php

(모바일 인스타그램 userId 쌍따옴표 누락 수정)
	수정) /public_html/m/theme/basic/tail.skin.php

(모바일 검색창 추가)
	수정) /public_html/m/bbs/board_list.php
	수정) /public_html/m/theme/basic/board_list.skin.php
	수정) /public_html/m/theme/basic/board_gallery.skin.php
	수정) /public_html/m/theme/basic/style.css
	삭제) /public_html/m/js/jquery.lazyload.min.js
	추가) /public_html/m/js/imagesloaded.pkgd.min.js

/*******************************************************************************
/* 2018-08-08 (분양몰 2.0.4)
/*******************************************************************************
(배송완료 후 쿠폰발급 오류수정)
	수정) /public_html/bbs/register_form_update.php
	수정) /public_html/lib/global.lib.php
	수정) /public_html/m/shop/pop_coupon_update.php
	수정) /public_html/m/shop/orderformupdate.php
	수정) /public_html/m/bbs/register_form_update.php
	수정) /public_html/plugin/login-oauth/oauth_check.php
	수정) /public_html/shop/pop_coupon_update.php
	수정) /public_html/shop/orderformupdate.php
	수정) /public_html/config.php
	수정) /public_html/HISTORY.php

/*******************************************************************************
/* 2018-08-05 (분양몰 2.0.3)
/*******************************************************************************
(sns공유 오타수정)
	/public_html/shop/view.php
	수정전) $sns_url = TB_SHOP_URL.'/view.php?index_no='.$gs_id;
	수정후) $sns_url = TB_SHOP_URL.'/view.php?index_no='.$index_no;

(약관 nl2br() 태그 삭제)
	/public_html/theme/basic/seller_reg_from.skin.php
	/public_html/theme/basic/partner_reg.skin.php
	/public_html/theme/basic/register.skin.php
	/public_html/m/theme/basic/seller_reg_from.skin.php
	/public_html/m/theme/basic/partner_reg.skin.php

(define 선언 추가)
	/public_html/config.php
	추가) define('TB_VERSION', '분양몰 v2.0.3');

(업데이트 히스토리 파일추가)
	추가) /public_html/HISTORY.php

/*******************************************************************************
/* 2018-08-03 (분양몰 2.0.2)
/*******************************************************************************
(카테고리 인덱스값 추가)
	/public_html/admin/category/category_sql.php
	/public_html/install/sql_db.sql

/*******************************************************************************
/* 2018-07-31 (분양몰 2.0.1)
/*******************************************************************************
(가맹점카테고리 설정오류 수정)
	/public_html/extend/shop.extend.php
	삭제) // 기본값 본사 카테고리 테이블명
	삭제) $tb['category_table'] = 'shop_cate';

	/public_html/partner.config.php
	추가) // 기본값 본사 카테고리 테이블명
	추가) $tb['category_table'] = 'shop_cate';