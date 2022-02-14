<?php
/*******************************************************************************
** 공통 변수, 상수, 코드
*******************************************************************************/
error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING );

// 보안설정이나 프레임이 달라도 쿠키가 통하도록 설정
header('P3P: CP="ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC"');

if(!defined('TB_SET_TIME_LIMIT')) define('TB_SET_TIME_LIMIT', 0);
@set_time_limit(TB_SET_TIME_LIMIT);


//===========================================================================================================
// extract($_GET); 명령으로 인해 page.php?_POST[var1]=data1&_POST[var2]=data2 와 같은 코드가 _POST 변수로 사용되는 것을 막음
// 081029 : letsgolee 님께서 도움 주셨습니다.
//-----------------------------------------------------------------------------------------------------------
$ext_arr = array ('PHP_SELF', '_ENV', '_GET', '_POST', '_FILES', '_SERVER', '_COOKIE', '_SESSION', '_REQUEST',
                  'HTTP_ENV_VARS', 'HTTP_GET_VARS', 'HTTP_POST_VARS', 'HTTP_POST_FILES', 'HTTP_SERVER_VARS',
                  'HTTP_COOKIE_VARS', 'HTTP_SESSION_VARS', 'GLOBALS');
$ext_cnt = count($ext_arr);
for($i=0; $i<$ext_cnt; $i++) {
    // POST, GET 으로 선언된 전역변수가 있다면 unset() 시킴
    if(isset($_GET[$ext_arr[$i]]))  unset($_GET[$ext_arr[$i]]);
    if(isset($_POST[$ext_arr[$i]])) unset($_POST[$ext_arr[$i]]);
}
//===========================================================================================================

function tb_path()
{
    $result['path'] = str_replace('\\', '/', dirname(__FILE__));
    $tilde_remove = preg_replace('/^\/\~[^\/]+(.*)$/', '$1', $_SERVER['SCRIPT_NAME']);
    $document_root = str_replace($tilde_remove, '', $_SERVER['SCRIPT_FILENAME']);
    $pattern = '/' . preg_quote($document_root, '/') . '/i';
    $root = preg_replace($pattern, '', $result['path']);
    $port = $_SERVER['SERVER_PORT'] != 80 ? ':'.$_SERVER['SERVER_PORT'] : '';
    $http = 'http' . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') ? 's' : '') . '://';
    $user = str_replace(preg_replace($pattern, '', $_SERVER['SCRIPT_FILENAME']), '', $_SERVER['SCRIPT_NAME']);
    $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
    if(isset($_SERVER['HTTP_HOST']) && preg_match('/:[0-9]+$/', $host))
        $host = preg_replace('/:[0-9]+$/', '', $host);
    $host = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*]/", '', $host);
    $result['url'] = $http.$host.$port.$user.$root;
    return $result;
}

$tb_path = tb_path();

include_once($tb_path['path'].'/config.php');   // 설정 파일

unset($tb_path);

// multi-dimensional array에 사용자지정 함수적용
function array_map_deep($fn, $array)
{
    if(is_array($array)) {
        foreach($array as $key => $value) {
            if(is_array($value)) {
                $array[$key] = array_map_deep($fn, $value);
            } else {
                $array[$key] = call_user_func($fn, $value);
            }
        }
    } else {
        $array = call_user_func($fn, $array);
    }

    return $array;
}

// SQL Injection 대응 문자열 필터링
function sql_escape_string($str)
{
    if(defined('TB_ESCAPE_PATTERN') && defined('TB_ESCAPE_REPLACE')) {
        $pattern = TB_ESCAPE_PATTERN;
        $replace = TB_ESCAPE_REPLACE;

        if($pattern)
            $str = preg_replace($pattern, $replace, $str);
    }

    $str = call_user_func('addslashes', $str);

    return $str;
}

//==============================================================================
// SQL Injection 등으로 부터 보호를 위해 sql_escape_string() 적용
//------------------------------------------------------------------------------
// magic_quotes_gpc 에 의한 backslashes 제거
if(get_magic_quotes_gpc()) {
    $_POST    = array_map_deep('stripslashes',  $_POST);
    $_GET     = array_map_deep('stripslashes',  $_GET);
    $_COOKIE  = array_map_deep('stripslashes',  $_COOKIE);
    $_REQUEST = array_map_deep('stripslashes',  $_REQUEST);
}

// sql_escape_string 적용
$_POST    = array_map_deep(TB_ESCAPE_FUNCTION,  $_POST);
$_GET     = array_map_deep(TB_ESCAPE_FUNCTION,  $_GET);
$_COOKIE  = array_map_deep(TB_ESCAPE_FUNCTION,  $_COOKIE);
$_REQUEST = array_map_deep(TB_ESCAPE_FUNCTION,  $_REQUEST);
//==============================================================================

// PHP 4.1.0 부터 지원됨
// php.ini 의 register_globals=off 일 경우
@extract($_GET);
@extract($_POST);
@extract($_SERVER);

// $member 에 값을 직접 넘길 수 있음
$config  = array();
$default = array();
$super	 = array();
$member  = array();
$partner = array();
$seller	 = array();
$tb		 = array();

//==============================================================================
// 항상 "www" 를 타고 들어오는 도메인은 "www" 를 제거
if(preg_match("/www\./i", $_SERVER['HTTP_HOST'])) {
	header("Location:http://".preg_replace("/www\./i", "", $_SERVER['HTTP_HOST']).$_SERVER['REQUEST_URI']);
}

//==============================================================================
// 공통
//------------------------------------------------------------------------------
$dbconfig_file = TB_DATA_PATH.'/'.TB_DBCONFIG_FILE;
if(file_exists($dbconfig_file)) {
    include_once($dbconfig_file);
	include_once(TB_LIB_PATH."/partner.lib.php"); // 가맹점 라이브러리
	include_once(TB_LIB_PATH."/global.lib.php"); // PC+모바일 공통 라이브러리
	include_once(TB_LIB_PATH."/common.lib.php"); // PC전용 라이브러리
	include_once(TB_LIB_PATH."/mobile.lib.php"); // 모바일전용 라이브러리
	include_once(TB_LIB_PATH."/thumbnail.lib.php"); // 썸네일 라이브러리
	include_once(TB_LIB_PATH."/editor.lib.php"); // 에디터 라이브러리
	include_once(TB_LIB_PATH."/login-oauth.php"); // SNS 로그인

    $connect_db = sql_connect(TB_MYSQL_HOST, TB_MYSQL_USER, TB_MYSQL_PASSWORD) or die('MySQL Connect Error!!!');
    $select_db  = sql_select_db(TB_MYSQL_DB, $connect_db) or die('MySQL DB Error!!!');

    // mysql connect resource $tb 배열에 저장 - 명랑폐인님 제안
    $tb['connect_db'] = $connect_db;

    sql_set_charset('utf8', $connect_db);
    if(defined('TB_MYSQL_SET_MODE') && TB_MYSQL_SET_MODE) sql_query("SET SESSION sql_mode = ''");
    if(defined(TB_TIMEZONE)) sql_query(" set time_zone = '".TB_TIMEZONE."'");
} else {
	header('Content-Type: text/html; charset=utf-8');

	die($dbconfig_file.' 파일을 찾을 수 없습니다.');
}
//==============================================================================


//==============================================================================
// SESSION 설정
//------------------------------------------------------------------------------
@ini_set("session.use_trans_sid", 0); // PHPSESSID를 자동으로 넘기지 않음
@ini_set("url_rewriter.tags",""); // 링크에 PHPSESSID가 따라다니는것을 무력화함 (해뜰녘님께서 알려주셨습니다.)

session_save_path(TB_SESSION_PATH);

if(isset($SESSION_CACHE_LIMITER))
    @session_cache_limiter($SESSION_CACHE_LIMITER);
else
    @session_cache_limiter("no-cache, must-revalidate");

ini_set("session.cache_expire", 180); // 세션 캐쉬 보관시간 (분)
ini_set("session.gc_maxlifetime", 10800); // session data의 garbage collection 존재 기간을 지정 (초)
ini_set("session.gc_probability", 1); // session.gc_probability는 session.gc_divisor와 연계하여 gc(쓰레기 수거) 루틴의 시작 확률을 관리합니다. 기본값은 1입니다. 자세한 내용은 session.gc_divisor를 참고하십시오.
ini_set("session.gc_divisor", 100); // session.gc_divisor는 session.gc_probability와 결합하여 각 세션 초기화 시에 gc(쓰레기 수거) 프로세스를 시작할 확률을 정의합니다. 확률은 gc_probability/gc_divisor를 사용하여 계산합니다. 즉, 1/100은 각 요청시에 GC 프로세스를 시작할 확률이 1%입니다. session.gc_divisor의 기본값은 100입니다.

session_set_cookie_params(0, '/');
ini_set("session.cookie_domain", TB_COOKIE_DOMAIN);

@session_start();
//==============================================================================


//==============================================================================
// 공용 변수
//------------------------------------------------------------------------------
// 기본환경설정
// 기본적으로 사용하는 필드만 얻은 후 상황에 따라 필드를 추가로 얻음
$config = sql_fetch("select * from shop_config");
$default = sql_fetch("select * from shop_default");
$super = get_member('admin');
$super_hp = $super['cellphone'];

// 보안서버주소 설정
if(TB_HTTPS_DOMAIN) {
	define('TB_HTTPS_BBS_URL', TB_HTTPS_DOMAIN.'/'.TB_BBS_DIR);
    define('TB_HTTPS_MBBS_URL', TB_HTTPS_DOMAIN.'/'.TB_MOBILE_DIR.'/'.TB_BBS_DIR);
    define('TB_HTTPS_SHOP_URL', TB_HTTPS_DOMAIN.'/'.TB_SHOP_DIR);
    define('TB_HTTPS_MSHOP_URL', TB_HTTPS_DOMAIN.'/'.TB_MOBILE_DIR.'/'.TB_SHOP_DIR);
} else {
    define('TB_HTTPS_BBS_URL', TB_BBS_URL);
    define('TB_HTTPS_MBBS_URL', TB_MBBS_URL);
    define('TB_HTTPS_SHOP_URL', TB_SHOP_URL);
    define('TB_HTTPS_MSHOP_URL', TB_MSHOP_URL);
}

// 4.00.03 : [보안관련] PHPSESSID 가 틀리면 로그아웃한다.
if(isset($_REQUEST['PHPSESSID']) && $_REQUEST['PHPSESSID'] != session_id())
    goto_url(TB_BBS_URL.'/logout.php');

// QUERY_STRING
$qstr = '';

if(isset($_REQUEST['set'])) {
	$set = trim($_REQUEST['set']);
	$qstr .= '&set=' . urlencode($set);
}

if(isset($_REQUEST['sca'])) {
    $sca = trim($_REQUEST['sca']);
    $qstr .= '&sca=' . urlencode($sca);
}

if(isset($_REQUEST['sfl'])) {
    $sfl = trim($_REQUEST['sfl']);
    $sfl = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\/\^\*\s]/", "", $sfl);
    $qstr .= '&sfl=' . urlencode($sfl); // search field (검색 필드)
}

if(isset($_REQUEST['stx'])) {
    $stx = trim($_REQUEST['stx']);
    $qstr .= '&stx=' . urlencode($stx);
}

if(isset($_REQUEST['sst'])) {
    $sst = trim($_REQUEST['sst']);
    $qstr .= '&sst=' . urlencode($sst);
}

if(isset($_REQUEST['sod'])) {
    $sod = trim($_REQUEST['sod']);
    $qstr .= '&sod=' . urlencode($sod);
}

if(isset($_REQUEST['sop'])) {
    $sop = trim($_REQUEST['sop']);
    $qstr .= '&sop=' . urlencode($sop);
}

if(isset($_REQUEST['spt'])) {
    $spt = trim($_REQUEST['spt']);
    $qstr .= '&spt=' . urlencode($spt);
}

if(isset($_REQUEST['ca_id'])) {
    $ca_id = trim($_REQUEST['ca_id']);
    $qstr .= '&ca_id=' . urlencode($ca_id);
}

if(isset($_REQUEST['fr_date'])) {
    $fr_date = trim($_REQUEST['fr_date']);
    $qstr .= '&fr_date=' . urlencode($fr_date);
}

if(isset($_REQUEST['to_date'])) {
    $to_date = trim($_REQUEST['to_date']);
    $qstr .= '&to_date=' . urlencode($to_date);
}

if(isset($_REQUEST['filed'])) {
    $filed = trim($_REQUEST['filed']);
    $qstr .= '&filed=' . urlencode($filed);
}

if(isset($_REQUEST['orderby'])) {
    $orderby = trim($_REQUEST['orderby']);
    $qstr .= '&orderby=' . urlencode($orderby);
}

// URL ENCODING
if(isset($_REQUEST['url'])) {
	$url = strip_tags(trim($_REQUEST['url']));
	$urlencode = urlencode($url);
} else {
    $url = '';
    $urlencode = urlencode($_SERVER['REQUEST_URI']);
    if(TB_DOMAIN) {
        $p = @parse_url(TB_DOMAIN);
        $urlencode = TB_DOMAIN.urldecode(preg_replace("/^".urlencode($p['path'])."/", "", $urlencode));
    }
}
//===================================

// 자동로그인 부분에서 첫로그인에 포인트 부여하던것을 로그인중일때로 변경하면서 코드도 대폭 수정하였습니다.
if($_SESSION['ss_mb_id']) { // 로그인중이라면
	$member = get_member($_SESSION['ss_mb_id']);

    // 차단된 회원이면 ss_mb_id 초기화
    if($member['intercept_date'] && $member['intercept_date'] <= date("Ymd", TB_SERVER_TIME)) {
		if(!get_session('admin_ss_mb_id')) { // 관리자 강제접속이 아닐때만.
			set_session('ss_mb_id', '');
			$member = array();
		}
    } else {
        // 오늘 처음 로그인 이라면
        if(substr($member['today_login'], 0, 10) != TB_TIME_YMD) {
            // 첫 로그인 포인트 지급
            insert_point($member['id'], $config['login_point'], TB_TIME_YMD.' 첫로그인', '@login', $member['id'], TB_TIME_YMD);

            // 오늘의 로그인이 될 수도 있으며 마지막 로그인일 수도 있음
            // 해당 회원의 접근일시와 IP 를 저장
            $sql = " update shop_member set login_sum = login_sum + 1, today_login = '".TB_TIME_YMDHIS."', login_ip = '{$_SERVER['REMOTE_ADDR']}' where id = '{$member['id']}' ";
            sql_query($sql);
        }
    }
} else {
    // 자동로그인 ---------------------------------------
    // 회원아이디가 쿠키에 저장되어 있다면 (3.27)
    if($tmp_mb_id = get_cookie('ck_mb_id')) {

        $tmp_mb_id = substr(preg_replace("/[^a-zA-Z0-9_]*/", "", $tmp_mb_id), 0, 20);
        // 최고관리자는 자동로그인 금지
        if(strtolower($tmp_mb_id) != 'admin') {
            $sql = " select passwd, intercept_date from shop_member where id = '{$tmp_mb_id}' ";
            $row = sql_fetch($sql);
            $key = md5($_SERVER['SERVER_ADDR'] . $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'] . $row['passwd']);
            // 쿠키에 저장된 키와 같다면
            $tmp_key = get_cookie('ck_auto');
			if($tmp_key === $key && $tmp_key) {
                // 차단, 인트로 사용이 아니라면
                if($row['intercept_date'] == '' && !$config['shop_intro_yes'] ) {
                    // 세션에 회원아이디를 저장하여 로그인으로 간주
                    set_session('ss_mb_id', $tmp_mb_id);

                    // 페이지를 재실행
                    echo "<script type='text/javascript'> window.location.reload(); </script>";
                    exit;
                }
            }
            // $row 배열변수 해제
            unset($row);
        }
    }
    // 자동로그인 end ---------------------------------------
}

if($boardid) {
	$board = sql_fetch("select * from shop_board_conf where index_no='$boardid'");
    if($board['index_no']) {
		$write_table = 'shop_board_'.$boardid; // 게시판 테이블 전체이름
        if(isset($index_no) && $index_no)
            $write = sql_fetch(" select * from $write_table where index_no = '$index_no' ");
    }
}

// 비회원구매를 위해 쿠키를 1년간 저장
if(!get_cookie("ck_guest_cart_id"))
	set_cookie("ck_guest_cart_id", TB_SERVER_TIME, 86400 * 365);

$set_cart_id = get_cookie('ck_guest_cart_id');

// 회원, 비회원 구분
$is_admin = $mb_no = '';
if($member['id']) {
	$is_member = 1;
    $is_admin = get_admin($member['id']);
	$partner = get_partner($member['id']);
	$seller = get_seller($member['id']);
	$mb_no = $member['index_no'];
} else {
	$is_member = 0;
    $member['id'] = '';
    $member['grade'] = 10; // 비회원의 경우 회원레벨을 가장 낮게 설정
}

if(!is_admin()) {
    // 접근가능 IP
    $possible_ip = trim($config['possible_ip']);
    if($possible_ip) {
        $is_possible_ip = false;
        $pattern = explode("\n", $possible_ip);
        for($i=0; $i<count($pattern); $i++) {
            $pattern[$i] = trim($pattern[$i]);
            if(empty($pattern[$i]))
                continue;

            $pattern[$i] = str_replace(".", "\.", $pattern[$i]);
            $pattern[$i] = str_replace("+", "[0-9\.]+", $pattern[$i]);
            $pat = "/^{$pattern[$i]}$/";
            $is_possible_ip = preg_match($pat, $_SERVER['REMOTE_ADDR']);
            if($is_possible_ip)
                break;
        }
        if(!$is_possible_ip)
            die ("접근이 가능하지 않습니다.");
    }

    // 접근차단 IP
    $is_intercept_ip = false;
    $pattern = explode("\n", trim($config['intercept_ip']));
    for($i=0; $i<count($pattern); $i++) {
        $pattern[$i] = trim($pattern[$i]);
        if(empty($pattern[$i]))
            continue;

        $pattern[$i] = str_replace(".", "\.", $pattern[$i]);
        $pattern[$i] = str_replace("+", "[0-9\.]+", $pattern[$i]);
        $pat = "/^{$pattern[$i]}$/";
        $is_intercept_ip = preg_match($pat, $_SERVER['REMOTE_ADDR']);
        if($is_intercept_ip)
            die ("접근 불가합니다.");
    }
}

//==============================================================================
// 사용기기 설정
// config.php TB_SET_DEVICE 설정에 따라 사용자 화면 제한됨
// pc 설정 시 모바일 기기에서도 PC화면 보여짐
// mobile 설정 시 PC에서도 모바일화면 보여짐
// both 설정 시 접속 기기에 따른 화면 보여짐
//------------------------------------------------------------------------------
$is_mobile = false;
$set_device = true;

if(defined('TB_SET_DEVICE')) {
    switch(TB_SET_DEVICE) {
        case 'pc':
            $is_mobile  = false;
            $set_device = false;
            break;
        case 'mobile':
            $is_mobile  = true;
            $set_device = false;
            break;
        default:
            break;
    }
}
//==============================================================================


//==============================================================================
// Mobile 모바일 설정
// 쿠키에 저장된 값이 모바일이라면 브라우저 상관없이 모바일로 실행
// 그렇지 않다면 브라우저의 HTTP_USER_AGENT 에 따라 모바일 결정
// TB_MOBILE_AGENT : config.php 에서 선언
//------------------------------------------------------------------------------
if(TB_USE_MOBILE && $set_device) {
    if($_REQUEST['device']=='pc')
        $is_mobile = false;
    else if($_REQUEST['device']=='mobile')
        $is_mobile = true;
	else if(defined('TB_USERIN_MOBILE'))
        $is_mobile = true;
    else if(isset($_SESSION['ss_is_mobile']))
        $is_mobile = $_SESSION['ss_is_mobile'];
    else if(is_mobile())
        $is_mobile = true;
} else {
    $set_device = false;
}

$_SESSION['ss_is_mobile'] = $is_mobile;
define('TB_IS_MOBILE', $is_mobile);
define('TB_DEVICE_BUTTON_DISPLAY', $set_device);
if(TB_IS_MOBILE) {
    $tb['mobile_path'] = TB_PATH.'/'.$tb['mobile_dir'];
}
//==============================================================================

// 가맹점 쇼핑몰설정
include_once(TB_PATH.'/partner.config.php');

// 일정 기간이 지난 DB 데이터 삭제 및 최적화
include_once(TB_LIB_PATH.'/db_table.optimize.php');

// common.php 파일을 수정할 필요가 없도록 확장합니다.
$extend_file = array();
$tmp = dir(TB_EXTEND_PATH);
while($entry = $tmp->read()) {
    // php 파일만 include 함
    if (preg_match("/(\.php)$/i", $entry))
        $extend_file[] = $entry;
}

if(!empty($extend_file) && is_array($extend_file)) {
    natsort($extend_file);

    foreach($extend_file as $file) {
        include_once(TB_EXTEND_PATH.'/'.$file);
    }
}
unset($extend_file);

ob_start();

// 자바스크립트에서 go(-1) 함수를 쓰면 폼값이 사라질때 해당 폼의 상단에 사용하면
// 캐쉬의 내용을 가져옴. 완전한지는 검증되지 않음
header('Content-Type: text/html; charset=utf-8');
$gmnow = gmdate('D, d M Y H:i:s') . ' GMT';
header('Expires: 0'); // rfc2616 - Section 14.21
header('Last-Modified: ' . $gmnow);
header('Cache-Control: no-store, no-cache, must-revalidate'); // HTTP/1.1
header('Cache-Control: pre-check=0, post-check=0, max-age=0'); // HTTP/1.1
header('Pragma: no-cache'); // HTTP/1.0

$html_process = new html_process();

// 보안서버 관련 (http:// => https://)
// - .htaccess 방법이 아닌 아래의 방법을 사용하는 이유는 모든 서브몰이 보안서버가 구축된 것이 아니기 때문.
if( ! $no_https) {
    $t_host = $_SERVER['HTTP_HOST'];
    $excep_domain = "rndmarket.co.kr";
    if( strstr($t_host, $excep_domain) ) {
        if( $_SERVER['HTTPS'] != 'on' ) {
            goto_url("https://{$t_host}{$_SERVER["REQUEST_URI"]}");
        }
    }
}
?>