<?php
/********************
    상수 선언
********************/

define('TB_VERSION', '분양몰 v2.0.6');

// 이 상수가 정의되지 않으면 각각의 개별 페이지는 별도로 실행될 수 없음
define('_TUBEWEB_', true);

if(PHP_VERSION >= '5.1.0') {
    //if(function_exists("date_default_timezone_set")) date_default_timezone_set("Asia/Seoul");
    date_default_timezone_set("Asia/Seoul");
}

/********************
    경로 상수
********************/

/*
보안서버 도메인
회원가입, 글쓰기에 사용되는 https 로 시작되는 주소를 말합니다.
포트가 있다면 도메인 뒤에 :443 과 같이 입력하세요.
보안서버주소가 없다면 공란으로 두시면 되며 보안서버주소 뒤에 / 는 붙이지 않습니다.
입력예) https://www.domain.com:443/gnuboard5
*/
define('TB_DOMAIN', '');
define('TB_HTTPS_DOMAIN', '');

/*
www.sample.co.kr 과 sample.co.kr 도메인은 서로 다른 도메인으로 인식합니다. 쿠키를 공유하려면 .sample.co.kr 과 같이 입력하세요.
이곳에 입력이 없다면 www 붙은 도메인과 그렇지 않은 도메인은 쿠키를 공유하지 않으므로 로그인이 풀릴 수 있습니다.
*/
define('TB_COOKIE_DOMAIN',  '');
define('TB_DBCONFIG_FILE',  'dbconfig.php');

define('TB_ADMIN_DIR',      'admin');
define('TB_BBS_DIR',        'bbs');
define('TB_COMMON_DIR',     'common');
define('TB_CSS_DIR',        'css');
define('TB_DATA_DIR',       'data');
define('TB_EXTEND_DIR',     'extend');
define('TB_IMG_DIR',        'img');
define('TB_JS_DIR',         'js');
define('TB_LIB_DIR',        'lib');
define('TB_MOBILE_DIR',     'm');
define('TB_MYPAGE_DIR',     'mypage');
define('TB_PLUGIN_DIR',     'plugin');
define('TB_SHOP_DIR',       'shop');
define('TB_THEME_DIR',		'theme');
define('TB_EDITOR_DIR',     'editor');
define('TB_LGXPAY_DIR',     'lgxpay');
define('TB_PHPMAILER_DIR',  'PHPMailer');
define('TB_SESSION_DIR',    'session');

// URL 은 브라우저상에서의 경로 (도메인으로 부터의)
if(TB_DOMAIN) {
    define('TB_URL', TB_DOMAIN);
} else {
    if(isset($tb_path['url']))
        define('TB_URL', $tb_path['url']);
    else
        define('TB_URL', '');
}

if(isset($tb_path['path'])) {
    define('TB_PATH', $tb_path['path']);
} else {
    define('TB_PATH', '');
}

define('TB_ADMIN_URL',      TB_URL.'/'.TB_ADMIN_DIR);
define('TB_BBS_URL',        TB_URL.'/'.TB_BBS_DIR);
define('TB_COMMON_URL',     TB_URL.'/'.TB_COMMON_DIR);
define('TB_CSS_URL',        TB_URL.'/'.TB_CSS_DIR);
define('TB_DATA_URL',       TB_URL.'/'.TB_DATA_DIR);
define('TB_IMG_URL',        TB_URL.'/'.TB_IMG_DIR);
define('TB_JS_URL',         TB_URL.'/'.TB_JS_DIR);
define('TB_SHOP_URL',       TB_URL.'/'.TB_SHOP_DIR);
define('TB_LIB_URL',        TB_URL.'/'.TB_LIB_DIR);
define('TB_PLUGIN_URL',     TB_URL.'/'.TB_PLUGIN_DIR);
define('TB_MYPAGE_URL',     TB_URL.'/'.TB_MYPAGE_DIR);
define('TB_EDITOR_URL',     TB_PLUGIN_URL.'/'.TB_EDITOR_DIR);
define('TB_LGXPAY_URL',     TB_PLUGIN_URL.'/'.TB_LGXPAY_DIR);

// PATH 는 서버상에서의 절대경로
define('TB_ADMIN_PATH',     TB_PATH.'/'.TB_ADMIN_DIR);
define('TB_BBS_PATH',       TB_PATH.'/'.TB_BBS_DIR);
define('TB_COMMON_PATH',    TB_PATH.'/'.TB_COMMON_DIR);
define('TB_DATA_PATH',      TB_PATH.'/'.TB_DATA_DIR);
define('TB_EXTEND_PATH',    TB_PATH.'/'.TB_EXTEND_DIR);
define('TB_LIB_PATH',       TB_PATH.'/'.TB_LIB_DIR);
define('TB_PLUGIN_PATH',    TB_PATH.'/'.TB_PLUGIN_DIR);
define('TB_SHOP_PATH',      TB_PATH.'/'.TB_SHOP_DIR);
define('TB_MYPAGE_PATH',    TB_PATH.'/'.TB_MYPAGE_DIR);
define('TB_SESSION_PATH',   TB_DATA_PATH.'/'.TB_SESSION_DIR);
define('TB_EDITOR_PATH',    TB_PLUGIN_PATH.'/'.TB_EDITOR_DIR);
define('TB_LGXPAY_PATH',    TB_PLUGIN_PATH.'/'.TB_LGXPAY_DIR);
define('TB_PHPMAILER_PATH', TB_PLUGIN_PATH.'/'.TB_PHPMAILER_DIR);

// 모바일경로 상수
define('TB_MPATH',			TB_PATH.'/'.TB_MOBILE_DIR);
define('TB_MURL',			TB_URL.'/'.TB_MOBILE_DIR);
define('TB_MBBS_PATH',		TB_MPATH.'/'.TB_BBS_DIR);
define('TB_MBBS_URL',		TB_MURL.'/'.TB_BBS_DIR);
define('TB_MCSS_PATH',		TB_MPATH.'/'.TB_CSS_DIR);
define('TB_MCSS_URL',		TB_MURL.'/'.TB_CSS_DIR);
define('TB_MIMG_PATH',		TB_MPATH.'/'.TB_IMG_DIR);
define('TB_MIMG_URL',		TB_MURL.'/'.TB_IMG_DIR);
define('TB_MJS_PATH',		TB_MPATH.'/'.TB_JS_DIR);
define('TB_MJS_URL',		TB_MURL.'/'.TB_JS_DIR);
define('TB_MSHOP_PATH',		TB_MPATH.'/'.TB_SHOP_DIR);
define('TB_MSHOP_URL',		TB_MURL.'/'.TB_SHOP_DIR);
//==============================================================================


//==============================================================================
// 사용기기 설정
// pc 설정 시 모바일 기기에서도 PC화면 보여짐
// mobile 설정 시 PC에서도 모바일화면 보여짐
// both 설정 시 접속 기기에 따른 화면 보여짐
//------------------------------------------------------------------------------
define('TB_SET_DEVICE', 'both');
define('TB_USE_MOBILE', true); // 모바일 홈페이지를 사용하지 않을 경우 false 로 설정


/********************
    시간 상수
********************/
// 서버의 시간과 실제 사용하는 시간이 틀린 경우 수정하세요.
// 하루는 86400 초입니다. 1시간은 3600초
// 6시간이 빠른 경우 time() + (3600 * 6);
// 6시간이 느린 경우 time() - (3600 * 6);
define('TB_SERVER_TIME',    time());
define('TB_TIME_YEAR',		date("Y", TB_SERVER_TIME));
define('TB_TIME_MONTH',		date("m", TB_SERVER_TIME));
define('TB_TIME_DAY',		date("d", TB_SERVER_TIME));
define('TB_TIME_YM',		date("Y-m", TB_SERVER_TIME));
define('TB_TIME_YMDHIS',	date("Y-m-d H:i:s", TB_SERVER_TIME));
define('TB_TIME_YHS',		date("YmdHis", TB_SERVER_TIME));
define('TB_TIME_YMD',		substr(TB_TIME_YMDHIS, 0, 10));
define('TB_TIME_HIS',		substr(TB_TIME_YMDHIS, 11, 8));

// 입력값 검사 상수 (숫자를 변경하시면 안됩니다.)
define('TB_ALPHAUPPER',		1); // 영대문자
define('TB_ALPHALOWER',		2); // 영소문자
define('TB_ALPHABETIC',		4); // 영대,소문자
define('TB_NUMERIC',		8); // 숫자
define('TB_HANGUL',		   16); // 한글
define('TB_SPACE',         32); // 공백
define('TB_SPECIAL',       64); // 특수문자

// 퍼미션
define('TB_DIR_PERMISSION',  0707); // 디렉토리 생성시 퍼미션
define('TB_FILE_PERMISSION', 0644); // 파일 생성시 퍼미션

// 모바일 인지 결정 $_SERVER['HTTP_USER_AGENT']
define('TB_MOBILE_AGENT', 'phone|samsung|lgtel|mobile|[^A]skt|nokia|blackberry|android|sony');

// SMTP
// lib/mailer.lib.php 에서 사용
define('TB_SMTP',      '127.0.0.1');
define('TB_SMTP_PORT', '25');

// 아이코드 코인 최소금액 설정
// 코인 잔액이 설정 금액보다 작을 때는 주문시 SMS 발송 안함
define('TB_ICODE_COIN', 100);
/********************
    기타 상수
********************/

// 암호화 함수 지정
// 사이트 운영 중 설정을 변경하면 로그인이 안되는 등의 문제가 발생합니다.
define('TB_STRING_ENCRYPT_FUNCTION', 'sql_password');

// SQL 에러를 표시할 것인지 지정
// 에러를 표시하려면 TRUE 로 변경
define('TB_DISPLAY_SQL_ERROR', TRUE);

// escape string 처리 함수 지정
// addslashes 로 변경 가능
define('TB_ESCAPE_FUNCTION', 'sql_escape_string');

// sql_escape_string 함수에서 사용될 패턴
//define('TB_ESCAPE_PATTERN',  '/(and|or).*(union|select|insert|update|delete|from|where|limit|create|drop).*/i');
//define('TB_ESCAPE_REPLACE',  '');

// 썸네일 jpg Quality 설정
define('TB_THUMB_JPG_QUALITY', 90);

// 썸네일 png Compress 설정
define('TB_THUMB_PNG_COMPRESS', 5);

// MySQLi 사용여부를 설정합니다.
define('TB_MYSQLI_USE', true);

// 옵션 ID 특수문자 필터링 패턴
define('TB_OPTION_ID_FILTER', '/[\'\"\\\'\\\"]/');

// 스팸방지를 위한 암호화 키값
define('TB_HASH_TOKEN', md5(TB_URL.TB_TIME_YMD.$_SERVER['REMOTE_ADDR']));

// ip 숨김방법 설정
/* 123.456.789.012 ip의 숨김 방법을 변경하는 방법은
\\1 은 123, \\2는 456, \\3은 789, \\4는 012에 각각 대응되므로
표시되는 부분은 \\1 과 같이 사용하시면 되고 숨길 부분은 ♡등의
다른 문자를 적어주시면 됩니다.
*/
define('TB_IP_DISPLAY', '\\1.♡.\\3.\\4');

if(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') {   //https 통신일때 daum 주소 js
    define('TB_POSTCODE_JS', '<script src="https://spi.maps.daum.net/imap/map_js_init/postcode.v2.js"></script>');
} else {  //http 통신일때 daum 주소 js
    define('TB_POSTCODE_JS', '<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>');
}
?>