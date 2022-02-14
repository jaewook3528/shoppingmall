<?php
if(!defined('_TUBEWEB_')) exit; // 개별 페이지 접근 불가

// 마이크로 타임을 얻어 계산 형식으로 만듦
function get_microtime()
{
	list($usec, $sec) = explode(" ",microtime());
	return ((float)$usec + (float)$sec);
}

// 세션변수 생성
function set_session($session_name, $value)
{
	if(PHP_VERSION < '5.3.0')
		session_register($session_name);
	// PHP 버전별 차이를 없애기 위한 방법
	$$session_name = $_SESSION["$session_name"] = $value;
}

// 세션변수값 얻음
function get_session($session_name)
{
	return $_SESSION[$session_name];
}

// 쿠키변수 생성
function set_cookie($cookie_name, $value, $expire)
{
	setcookie(md5($cookie_name), base64_encode($value), time() + $expire, '/', $_SERVER['HTTP_HOST']);
}

// 쿠키변수값 얻음
function get_cookie($cookie_name)
{
	return base64_decode($_COOKIE[md5($cookie_name)]);
}

// 변수 또는 배열의 이름과 값을 얻어냄. print_r() 함수의 변형
function print_r2($var)
{
    ob_start();
    print_r($var);
    $str = ob_get_contents();
    ob_end_clean();
    $str = str_replace(" ", "&nbsp;", $str);
    echo nl2br("<span style='font-family:Tahoma, 굴림; font-size:9pt;'>$str</span>");
}

// 한페이지에 보여줄 행, 현재페이지, 총페이지수, URL
function get_paging($write_pages, $cur_page, $total_page, $url, $add="")
{
	if(!$cur_page) $cur_page = 1;
	if(!$total_page) $total_page = 1;
	if($total_page < 2) return '';

	$url = preg_replace('#&page=[0-9]*#', '', $url) . '&page=';

    $str = '';
	if($cur_page < 2) {
		$str .= '<span class="pg_start">처음</span>'.PHP_EOL;
	} else {
		$str .= '<a href="'.$url.'1'.$add.'" class="pg_page pg_start">처음</a>'.PHP_EOL;
	}

	$start_page = (((int)(($cur_page - 1 ) / $write_pages)) * $write_pages) + 1;
	$end_page = $start_page + $write_pages - 1;

	if($end_page >= $total_page) $end_page = $total_page;

	if($start_page > 1) {
		$str .= '<a href="'.$url.($start_page-1).$add.'" class="pg_page pg_prev">이전</a>'.PHP_EOL;
	} else {
		$str .= '<span class="pg_prev">이전</span>'.PHP_EOL;
	}

    if($total_page > 1) {
        for($k=$start_page;$k<=$end_page;$k++) {
            if($cur_page != $k) {
                $str .= '<a href="'.$url.$k.$add.'" class="pg_page">'.$k.'<span class="sound_only">페이지</span></a>'.PHP_EOL;
            } else {
                $str .= '<span class="sound_only">열린</span><strong class="pg_current">'.$k.'</strong><span class="sound_only">페이지</span>'.PHP_EOL;
			}
        }
    }

	if($total_page > $end_page) {
		$str .= '<a href="'.$url.($end_page+1).$add.'" class="pg_page pg_next">다음</a>'.PHP_EOL;
	} else {
		$str .= '<span class="pg_next">다음</span>'.PHP_EOL;
	}

	if($cur_page < $total_page) {
		$str .= '<a href="'.$url.$total_page.$add.'" class="pg_page pg_end">맨끝</a>'.PHP_EOL;
	} else {
		$str .= '<span class="pg_end">맨끝</span>'.PHP_EOL;
	}

    return "<nav class=\"pg_wrap\"><span class=\"pg\">{$str}</span></nav>";
}

// 모바일인지 체크
function is_mobile()
{
	return preg_match('/'.TB_MOBILE_AGENT.'/i', $_SERVER['HTTP_USER_AGENT']);
}

// 전화번호 정규식 0112223333을 011-222-3333 으로 변환
function replace_tel($obj)
{
	if(!$obj) return;

	$obj = preg_replace('/[^\d\n]+/', '', $obj);

	if(substr($obj,0,1) != "0" && strlen ($obj ) > 8) $obj = "0".$obj ;
		$telnum3 = substr( $obj, -4 );

	if(in_array(substr($obj, 0, 3), array('013','050','030')))
		$telnum1 = substr($obj, 0, 4);
	else if(substr($obj, 0, 2) == "01")
		$telnum1 = substr($obj, 0, 3);
	else if(substr($obj, 0, 2) == "02")
		$telnum1 = substr($obj, 0, 2);
	else if(substr($obj, 0, 1) == "0" )
		$telnum1 = substr($obj, 0, 3);

	$telnum2 = substr($obj, strlen($telnum1), -4);
	if(!$telnum1) return $telnum2 . "-" . $telnum3 ;
	else return $telnum1 . "-" . $telnum2 . "-" . $telnum3 ;
}

// unescape nl 얻기
function conv_unescape_nl($str)
{
    $search = array('\\r', '\r', '\\n', '\n');
    $replace = array('', '', "\n", "\n");

    return str_replace($search, $replace, $str);
}

// 에디터 이미지 얻기
function get_editor_image($contents, $view=true)
{
    if(!$contents)
        return false;

    // $contents 중 img 태그 추출
    if($view)
        $pattern = "/<img([^>]*)>/iS";
    else
        $pattern = "/<img[^>]*src=[\'\"]?([^>\'\"]+[^>\'\"]+)[\'\"]?[^>]*>/i";
    preg_match_all($pattern, $contents, $matchs);

    return $matchs;
}

// 게시판 첨부파일 썸네일 삭제
function delete_board_thumbnail($boardid, $file)
{
    if(!$boardid || !$file)
        return;

    $fn = preg_replace("/\.[^\.]+$/i", "", basename($file));
    $files = glob(TB_DATA_PATH.'/board/'.$boardid.'/thumb-'.$fn.'*');
    if (is_array($files)) {
        foreach ($files as $filename)
            unlink($filename);
    }
}

// 에디터 썸네일 삭제
function delete_editor_thumbnail($contents)
{
    if(!$contents)
        return;

    // $contents 중 img 태그 추출
    $matchs = get_editor_image($contents, false);

    if(!$matchs)
        return;

    for($i=0; $i<count($matchs[1]); $i++) {
        // 이미지 path 구함
        $imgurl = @parse_url($matchs[1][$i]);
        $srcfile = $_SERVER['DOCUMENT_ROOT'].$imgurl['path'];

        $filename = preg_replace("/\.[^\.]+$/i", "", basename($srcfile));
        $filepath = dirname($srcfile);
        $files = glob($filepath.'/thumb-'.$filename.'*');
        if(is_array($files)) {
            foreach($files as $filename)
                unlink($filename);
        }
    }
}

// 에디터 이미지 삭제
function delete_editor_image($contents)
{
    if(!$contents)
        return;

    // $contents 중 img 태그 추출
    $imgs = get_editor_image($contents, false);

    if(!$imgs)
        return;

	// 썸네일 삭제
	delete_editor_thumbnail($contents);

	for($i=0;$i<count($imgs[1]);$i++) {
		$p = @parse_url($imgs[1][$i]);

		if(strpos($p['path'], '/data/') != 0)
			$data_path = preg_replace('/^\/.*\/data/', '/data', $p['path']);
		else
			$data_path = $p['path'];

		$destfile = TB_PATH.$data_path;

		if(is_file($destfile))
			@unlink($destfile);
	}
}

// 상품이미지 썸네일 삭제
function delete_item_thumbnail($dir, $file)
{
    if(!$dir || !$file)
        return;

    $filename = preg_replace("/\.[^\.]+$/i", "", $file); // 확장자제거

    $files = glob($dir.'/thumb-'.$filename.'*');

    if(is_array($files)) {
        foreach($files as $thumb_file) {
            @unlink($thumb_file);
        }
    }
}

// 시간이 비어 있는지 검사
function is_null_time($datetime)
{
    // 공란 0 : - 제거
    $datetime = preg_replace("/[ 0:-]/", "", $datetime);
    if ($datetime == "")
        return true;
    else
        return false;
}

// 경고메세지 출력후 창을 닫음
function alert_close($msg)
{
   if(!$msg) $msg = '올바른 방법으로 이용해 주십시오.';

	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\">";
	echo "<script type='text/javascript'>alert(\"{$msg}\");window.close();</script>";exit;
}

// 메타태그를 이용한 URL 이동
// header("location:URL") 을 대체
function goto_url($url)
{
	echo "<meta http-equiv=\"content-type\" content=\"text/html; charset=utf-8\">";
	echo "<script type='text/javascript'>location.replace('{$url}');</script>";
	exit;
}

// DEMO 라는 파일이 있으면 데모 화면으로 인식함
function check_demo()
{
	if(!in_array($_SERVER['REMOTE_ADDR'], array('112.187.136.134','1.244.139.27','118.47.197.208'))) {
		if(file_exists(TB_PATH."/DEMO")) {
			alert("데모 화면에서는 하실(보실) 수 없는 작업입니다.");
		}
	}
}

// 글자수를 자루는 함수.
function cut_str($str, $len, $suffix="…")
{
    $arr_str = preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY);
    $str_len = count($arr_str);

    if($str_len >= $len) {
        $slice_str = array_slice($arr_str, 0, $len);
        $str = join("", $slice_str);

        return $str . ($str_len > $len ? $suffix : '');
    } else {
        $str = join("", $arr_str);
        return $str;
    }
}

// 문자열이 한글, 영문, 숫자, 특수문자로 구성되어 있는지 검사
function check_string($str, $options)
{
    $s = '';
    for($i=0;$i<strlen($str);$i++) {
        $c = $str[$i];
        $oc = ord($c);

        // 한글
        if($oc >= 0xA0 && $oc <= 0xFF) {
            if($options & TB_HANGUL) {
                $s .= $c . $str[$i+1] . $str[$i+2];
            }
            $i+=2;
        }
        // 숫자
        else if($oc >= 0x30 && $oc <= 0x39) {
            if($options & TB_NUMERIC) {
                $s .= $c;
            }
        }
        // 영대문자
        else if($oc >= 0x41 && $oc <= 0x5A) {
            if(($options & TB_ALPHABETIC) || ($options & TB_ALPHAUPPER)) {
                $s .= $c;
            }
        }
        // 영소문자
        else if($oc >= 0x61 && $oc <= 0x7A) {
            if(($options & TB_ALPHABETIC) || ($options & TB_ALPHALOWER)) {
                $s .= $c;
            }
        }
        // 공백
        else if($oc == 0x20) {
            if($options & TB_SPACE) {
                $s .= $c;
            }
        }
        else {
            if($options & TB_SPECIAL) {
                $s .= $c;
            }
        }
    }

    // 넘어온 값과 비교하여 같으면 참, 틀리면 거짓
    return ($str == $s);
}

// url에 http:// 를 붙인다
function set_http($url)
{
    if(!trim($url)) return;

    if(!preg_match("/^(http|https|ftp|telnet|news|mms)\:\/\//i", $url))
        $url = "http://" . $url;

    return $url;
}

// 불법접근을 막도록 토큰을 생성하면서 토큰값을 리턴
function get_token()
{
	$token = md5(uniqid(rand(), true));
	set_session("ss_token", $token);

	return $token;
}

// POST로 넘어온 토큰과 세션에 저장된 토큰 비교
function check_token()
{
	set_session('ss_token', '');
	return true;
}

// 내용을 변환
function conv_content($content, $html, $filter=true)
{
    if($html)
    {
        $source = array();
        $target = array();

        $source[] = "//";
        $target[] = "";

        if($html == 2) { // 자동 줄바꿈
            $source[] = "/\n/";
            $target[] = "<br/>";
        }

        // 테이블 태그의 개수를 세어 테이블이 깨지지 않도록 한다.
        $table_begin_count = substr_count(strtolower($content), "<table");
        $table_end_count = substr_count(strtolower($content), "</table");
        for($i=$table_end_count; $i<$table_begin_count; $i++)
        {
            $content .= "</table>";
        }

        $content = preg_replace($source, $target, $content);

        if($filter)
            $content = html_purifier($content);
    }
    else // text 이면
    {
        // & 처리 : &amp; &nbsp; 등의 코드를 정상 출력함
        $content = html_symbol($content);

        // 공백 처리
		//$content = preg_replace("/  /", "&nbsp; ", $content);
		$content = str_replace("  ", "&nbsp; ", $content);
		$content = str_replace("\n ", "\n&nbsp;", $content);

        $content = get_text($content, 1);
        $content = url_auto_link($content);
    }

    return $content;
}

// http://htmlpurifier.org/
// Standards-Compliant HTML Filtering
// Safe  : HTML Purifier defeats XSS with an audited whitelist
// Clean : HTML Purifier ensures standards-compliant output
// Open  : HTML Purifier is open-source and highly customizable
function html_purifier($html)
{
    $f = file(TB_PLUGIN_PATH.'/htmlpurifier/safeiframe.txt');
    $domains = array();
    foreach($f as $domain){
        // 첫행이 # 이면 주석 처리
        if(!preg_match("/^#/", $domain)) {
            $domain = trim($domain);
            if($domain)
                array_push($domains, $domain);
        }
    }
    // 내 도메인도 추가
    array_push($domains, $_SERVER['HTTP_HOST'].'/');
    $safeiframe = implode('|', $domains);

    include_once(TB_PLUGIN_PATH.'/htmlpurifier/HTMLPurifier.standalone.php');
    $config = HTMLPurifier_Config::createDefault();
    // data/cache 디렉토리에 CSS, HTML, URI 디렉토리 등을 만든다.
    $config->set('Cache.SerializerPath', TB_DATA_PATH.'/cache');
    $config->set('HTML.SafeEmbed', false);
    $config->set('HTML.SafeObject', false);
    $config->set('Output.FlashCompat', false);
    $config->set('HTML.SafeIframe', true);
    $config->set('URI.SafeIframeRegexp','%^(https?:)?//('.$safeiframe.')%');
    $config->set('Attr.AllowedFrameTargets', array('_blank'));
    $purifier = new HTMLPurifier($config);
    return $purifier->purify($html);
}

// 악성태그 변환
function bad_tag_convert($code)
{
	//return preg_replace("/\<([\/]?)(script|iframe)([^\>]*)\>/i", "&lt;$1$2$3&gt;", $code);
	// script 나 iframe 태그를 막지 않는 경우 필터링이 되도록 수정
	return preg_replace("/\<([\/]?)(script|iframe|form)([^\>]*)\>?/i", "&lt;$1$2$3&gt;", $code);
}

// way.co.kr 의 wayboard 참고
function url_auto_link($str)
{
    $str = str_replace(array("&lt;", "&gt;", "&amp;", "&quot;", "&nbsp;", "&#039;"), array("\t_lt_\t", "\t_gt_\t", "&", "\"", "\t_nbsp_\t", "'"), $str);
    $str = preg_replace("/([^(href=\"?'?)|(src=\"?'?)]|\(|^)((http|https|ftp|telnet|news|mms):\/\/[a-zA-Z0-9\.-]+\.[가-힣\xA1-\xFEa-zA-Z0-9\.:&#=_\?\/~\+%@;\-\|\,\(\)]+)/i", "\\1<A HREF=\"\\2\" TARGET=\"_blank\">\\2</A>", $str);
    $str = preg_replace("/(^|[\"'\s(])(www\.[^\"'\s()]+)/i", "\\1<A HREF=\"http://\\2\" TARGET=\"_blank\">\\2</A>", $str);
    $str = preg_replace("/[0-9a-z_-]+@[a-z0-9._-]{4,}/i", "<a href=\"mailto:\\0\">\\0</a>", $str);
    $str = str_replace(array("\t_nbsp_\t", "\t_lt_\t", "\t_gt_\t", "'"), array("&nbsp;", "&lt;", "&gt;", "&#039;"), $str);

    return $str;
}

// TEXT 형식으로 변환
function get_text($str, $html=0, $restore=false)
{
    $source[] = "<";
    $target[] = "&lt;";
    $source[] = ">";
    $target[] = "&gt;";
    $source[] = "\"";
    $target[] = "&#034;";
    $source[] = "\'";
    $target[] = "&#039;";

    if($restore)
        $str = str_replace($target, $source, $str);

    // 3.31
    // TEXT 출력일 경우 &amp; &nbsp; 등의 코드를 정상으로 출력해 주기 위함
    if($html == 0) {
        $str = html_symbol($str);
    }

    if($html) {
        $source[] = "\n";
        $target[] = "<br/>";
    }

    return str_replace($source, $target, $str);
}

// 3.31
// HTML SYMBOL 변환
// &nbsp; &amp; &middot; 등을 정상으로 출력
function html_symbol($str)
{
    return preg_replace("/\&([a-z0-9]{1,20}|\#[0-9]{0,3});/i", "&#038;\\1;", $str);
}

function get_selected($field, $value)
{
	return ($field==$value) ? ' selected="selected"' : '';
}

function get_checked($field, $value)
{
	return ($field==$value) ? ' checked="checked"' : '';
}

function option_selected($value, $selected, $text='')
{
    if(!$text) $text = $value;
    if($value == $selected)
        return "<option value=\"$value\" selected=\"selected\">$text</option>\n";
    else
        return "<option value=\"$value\">$text</option>\n";
}

// 코드 : http://in2.php.net/manual/en/function.mb-check-encoding.php#95289
function is_utf8($str)
{
    $len = strlen($str);
    for($i = 0; $i < $len; $i++) {
        $c = ord($str[$i]);
        if ($c > 128) {
            if (($c > 247)) return false;
            elseif ($c > 239) $bytes = 4;
            elseif ($c > 223) $bytes = 3;
            elseif ($c > 191) $bytes = 2;
            else return false;
            if (($i + $bytes) > $len) return false;
            while ($bytes > 1) {
                $i++;
                $b = ord($str[$i]);
                if ($b < 128 || $b > 191) return false;
                $bytes--;
            }
        }
    }
    return true;
}

// UTF-8 문자열 자르기
// 출처 : https://www.google.co.kr/search?q=utf8_strcut&aq=f&oq=utf8_strcut&aqs=chrome.0.57j0l3.826j0&sourceid=chrome&ie=UTF-8
function utf8_strcut( $str, $size, $suffix='...' )
{
	$substr = substr( $str, 0, $size * 2 );
	$multi_size = preg_match_all( '/[\x80-\xff]/', $substr, $multi_chars );

	if( $multi_size > 0 )
		$size = $size + intval( $multi_size / 3 ) - 1;

	if( strlen( $str ) > $size ) {
		$str = substr( $str, 0, $size );
		$str = preg_replace( '/(([\x80-\xff]{3})*?)([\x80-\xff]{0,2})$/', '$1', $str );
		$str .= $suffix;
	}

	return $str;
}

// CHARSET 변경 : euc-kr -> utf-8
function iconv_utf8($str)
{
	return iconv('euc-kr', 'utf-8', $str);
}

// CHARSET 변경 : utf-8 -> euc-kr
function iconv_euckr($str)
{
	return iconv('utf-8', 'euc-kr', $str);
}

// 한글 요일
function get_yoil($date, $full=0)
{
	$arr_yoil = array ('일', '월', '화', '수', '목', '금', '토');

	$yoil = date("w", strtotime($date));
	$str = $arr_yoil[$yoil];
	if($full) {
		$str .= '요일';
	}
	return $str;
}

// 날짜형식 변환
function date_conv($date, $case=1)
{
	$date = conv_number($date);
    if($case == 1) { // 년-월-일 로 만들어줌
        $date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3", $date);
    } else if($case == 2) { // 년월일 로 만들어줌
        $date = preg_replace("/-/", "", $date);
    } else if($case == 3) { // 년 월 일 로 만들어줌
        $date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1년 \\2월 \\3일", $date);
    } else if($case == 4) { // 년.월.일 로 만들어줌
        $date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1/\\2/\\3", $date);
    }

    return $date;
}

// rm -rf 옵션 : exec(), system() 함수를 사용할 수 없는 서버 또는 win32용 대체
// www.php.net 참고 : pal at degerstrom dot com
function rm_rf($file)
{
    if(file_exists($file)) {
        if(is_dir($file)) {
            $handle = opendir($file);
            while($filename = readdir($handle)) {
                if($filename != '.' && $filename != '..') {
                    rm_rf($file.'/'.$filename);
				}
            }
            closedir($handle);

            @chmod($file, TB_DIR_PERMISSION);
            @rmdir($file);
        } else {
            @chmod($file, TB_FILE_PERMISSION);
            @unlink($file);
        }
    }
}

// 동일한 host url 인지
function check_url_host($url, $msg='', $return_url=TB_URL)
{
    if(!$msg)
        $msg = 'url에 타 도메인을 지정할 수 없습니다.';

    $p = @parse_url($url);
    $host = preg_replace('/:[0-9]+$/', '', $_SERVER['HTTP_HOST']);

    if(stripos($url, 'http:') !== false) {
        if(!isset($p['scheme']) || !$p['scheme'] || !isset($p['host']) || !$p['host'])
            alert('url 정보가 올바르지 않습니다.', $return_url);
    }

    if((isset($p['scheme']) && $p['scheme']) || (isset($p['host']) && $p['host'])) {
        //if($p['host'].(isset($p['port']) ? ':'.$p['port'] : '') != $_SERVER['HTTP_HOST']) {
        if($p['host'] != $host) {
            echo '<script>'.PHP_EOL;
            echo 'alert("url에 타 도메인을 지정할 수 없습니다.");'.PHP_EOL;
            echo 'document.location.href = "'.$return_url.'";'.PHP_EOL;
            echo '</script>'.PHP_EOL;
            echo '<noscript>'.PHP_EOL;
            echo '<p>'.$msg.'</p>'.PHP_EOL;
            echo '<p><a href="'.$return_url.'">돌아가기</a></p>'.PHP_EOL;
            echo '</noscript>'.PHP_EOL;
            exit;
        }
    }
}

/*******************************************************************************
    유일한 키를 얻는다.

    결과 :

        년월일시분초00 ~ 년월일시분초99
        년(4) 월(2) 일(2) 시(2) 분(2) 초(2) 100분의1초(2)
        총 16자리이며 년도는 2자리로 끊어서 사용해도 됩니다.
        예) 2008062611570199 또는 08062611570199 (2100년까지만 유일키)

    사용하는 곳 :
    1. 주문번호 생성시에 사용한다.
    2. 기타 유일키가 필요한 곳에서 사용한다.
*******************************************************************************/
function get_uniqid()
{
    sql_query(" LOCK TABLE shop_uniqid WRITE ");
    while (1) {
        // 년월일시분초에 100분의 1초 두자리를 추가함 (1/100 초 앞에 자리가 모자르면 0으로 채움)
        $key = date('ymdHis', time()) . str_pad((int)(microtime()*100), 2, "0", STR_PAD_LEFT);

        $result = sql_query(" insert into shop_uniqid set uq_id = '$key', uq_ip = '{$_SERVER['REMOTE_ADDR']}' ", false);
        if($result) break; // 쿼리가 정상이면 빠진다.

        // insert 하지 못했으면 일정시간 쉰다음 다시 유일키를 만든다.
        usleep(10000); // 100분의 1초를 쉰다
    }
    sql_query(" UNLOCK TABLES ");

    return $key;
}

// 장바구니 유일키검사
function cart_uniqid()
{
    while(1) {

		srand((double)microtime()*1000000);
		$key = rand(1000000000,9999999999);

		$row1 = sql_fetch(" select count(*) as cnt from shop_cart where od_no = '$key' ");
		$row2 = sql_fetch(" select count(*) as cnt from shop_order where od_no = '$key' ");

        if(!$row1['cnt'] && !$row2['cnt']) break; // 없다면 빠진다.

        // count 하지 못했으면 일정시간 쉰다음 다시 유일키를 검사한다.
        usleep(10000); // 100분의 1초를 쉰다
    }

    return $key;
}

// 문자열 암호화
function get_encrypt_string($str)
{
    if(defined('TB_STRING_ENCRYPT_FUNCTION') && TB_STRING_ENCRYPT_FUNCTION) {
        $encrypt = call_user_func(TB_STRING_ENCRYPT_FUNCTION, $str);
    } else {
        $encrypt = sql_password($str);
    }

    return $encrypt;
}

function escape_trim($field)
{
    $str = call_user_func('addslashes', $field);
    return $str;
}

// XSS 관련 태그 제거
function clean_xss_tags($str)
{
    $str = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $str);

    return $str;
}

// date 형식 변환
function conv_date_format($format, $date, $add='')
{
    if($add)
        $timestamp = strtotime($add, strtotime($date));
    else
        $timestamp = strtotime($date);

    return date($format, $timestamp);
}

// 검색어 특수문자 제거
function get_search_string($stx)
{
    $stx_pattern = array();
    $stx_pattern[] = '#\.*/+#';
    $stx_pattern[] = '#\\\*#';
    $stx_pattern[] = '#\.{2,}#';
    $stx_pattern[] = '#[/\'\"%=*\#\(\)\|\+\&\!\$~\{\}\[\]`;:\?\^\,]+#';

    $stx_replace = array();
    $stx_replace[] = '';
    $stx_replace[] = '';
    $stx_replace[] = '.';
    $stx_replace[] = '';

    $stx = preg_replace($stx_pattern, $stx_replace, $stx);

    return $stx;
}

// ftp 폴더삭제
function rrmdir($dir) {
	foreach(glob($dir . '/*') as $file) {
		if(is_dir($file))
			rrmdir($file);
		else
			@unlink($file);
	}
	rmdir($dir);
}

// sns 공유하기
function get_sns_share_link($sns, $url, $title, $image_url)
{
    if(!$sns)
        return '';

	$sns_url = $url;
	$sns_msg = str_replace('\"', '"', strip_tags($title));
	$sns_msg = str_replace('\'', '', $sns_msg);
	$sns_send = TB_LIB_URL.'/sns_send.php?longurl='.urlencode($sns_url).'&amp;title='.urlencode($sns_msg);

    switch($sns) {
		case 'facebook':
			$facebook_url = $sns_send.'&amp;sns=facebook';
			$str = 'share_sns(\'facebook\', \''.$facebook_url.'\'); return false;';
			$str = '<a href="'.$facebook_url.'" onclick="'.$str.'" target="_blank"><img src="'.$image_url.'"></a>'.PHP_EOL;
            break;
        case 'twitter':
			$twitter_url = $sns_send.'&amp;sns=twitter';
			$str = 'share_sns(\'twitter\', \''.$twitter_url.'\'); return false;';
			$str = '<a href="'.$twitter_url.'" onclick="'.$str.'" target="_blank"><img src="'.$image_url.'"></a>'.PHP_EOL;
			break;
        case 'naver':
			$naver_url = $sns_send.'&amp;sns=naver';
			$str = 'share_sns(\'naver\',\''.$naver_url.'\'); return false;';
			$str = '<a href="'.$naver_url.'" onclick="'.$str.'" target="_blank"><img src="'.$image_url.'" alt="Naver"></a>'.PHP_EOL;
            break;
		case 'googleplus':
			$gplus_url = $sns_send.'&amp;sns=googleplus';
            $str = 'share_sns(\'googleplus\',\''.$gplus_url.'\'); return false;';
			$str = '<a href="'.$gplus_url.'" onclick="'.$str.'" target="_blank"><img src="'.$image_url.'"></a>'.PHP_EOL;
            break;
		case 'kakaostory':
			$kakaostory_url = $sns_send . '&amp;sns=kakaostory';
            $str = 'share_sns(\'kakaostory\',\'' . $kakaostory_url . '\'); return false;';
			$str = '<a href="'.$kakaostory_url.'" onclick="'.$str.'" target="_blank"><img src="'.$image_url.'"></a>'.PHP_EOL;
            break;
		case 'naverband':
			$naverband_url = $sns_send . '&amp;sns=naverband';
            $str = 'share_sns(\'naverband\',\'' . $naverband_url . '\'); return false;';
			$str = '<a href="'.$naverband_url.'" onclick="'.$str.'" target="_blank"><img src="'.$image_url.'"></a>'.PHP_EOL;
            break;
		case 'pinterest':
			$pinterest_url = $sns_send . '&amp;sns=pinterest';
            $str = 'share_sns(\'pinterest\',\'' . $pinterest_url . '\'); return false;';
			$str = '<a href="'.$pinterest_url.'" onclick="'.$str.'" target="_blank"><img src="'.$image_url.'"></a>'.PHP_EOL;
            break;
		case 'tumblr':
			$tumblr_url = $sns_send.'&amp;sns=tumblr';
			$str = 'share_sns(\'tumblr\',\''.$tumblr_url.'\'); return false;';
			$str = '<a href="'.$tumblr_url.'" onclick="'.$str.'" target="_blank"><img src="'.$img.'"></a>'.PHP_EOL;
            break;
    }

    return $str;
}

// goo.gl 짧은주소 만들기
function google_short_url($longUrl)
{
    global $default;

    // Get API key from : http://code.google.com/apis/console/
    // URL Shortener API ON
    $apiKey = $default['de_googl_shorturl_apikey'];

	$postData = array('longUrl' => $longUrl, 'key' => $apiKey);
	$jsonData = json_encode($postData);

	$curlObj = curl_init();

	curl_setopt($curlObj, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url?key=' . $apiKey);
	curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($curlObj, CURLOPT_HEADER, 0);
	curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
	curl_setopt($curlObj, CURLOPT_POST, 1);
	curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);

	$response = curl_exec($curlObj);

	// Change the response json string to object
	$json = json_decode($response);

	curl_close($curlObj);

    return $json->id;
}


/*************************************************************************
**
**  쿠폰 관련 함수 모음
**
*************************************************************************/

// 쿠폰 : 카테고리 추출
function get_extract($gs_id)
{
	$str = "";
	$sql = "select * from shop_goods_cate where gs_id='$gs_id'";
	$result = sql_query($sql);
	for($i=0; $row = sql_fetch_array($result); $i++) {
		$str .= $comma . $row['gcate'];
		$comma = ',';
	}

	return $str;
}

// 쿠폰 : 문자열 검색
function get_substr_count($haystrack, $needle)
{
	$count = 0;
	if($haystrack && $needle) {
		$arr_needle = explode(",", $needle);
		for($i=0; $i<count($arr_needle); $i++) {
			if(substr_count($haystrack, trim($arr_needle[$i])) > 0 ) {
				$count++;
			}
		}
	}

	return (int)$count;
}

// 쿠폰 : 혜택
function get_cp_sale_amt($chk_engine)
{
	global $row;

	$sale_amt = array();
	$sale_amt[0] = 0;
	$sale_amt[1] = '';

	if($row['cp_sale_type'] == '0') {
		$sale_amt[0] = ($chk_engine / 100) * $row['cp_sale_percent'];
		$sale_amt[1] = $row['cp_sale_percent'].'%';

		if($row['cp_sale_amt_max'] > 0 && $sale_amt[0] > $row['cp_sale_amt_max']) {
			$sale_amt[0] = $row['cp_sale_amt_max'];
		}
	} else {
		$sale_amt[0] = $row['cp_sale_amt'];
		$sale_amt[1] = display_price($row['cp_sale_amt']);
	}

	return $sale_amt;
}

// 쿠폰 : 사용 가능한 쿠폰
function get_cp_precompose($mb_id)
{
	$query = array();

	// 쿠폰유효 기간 (날짜)
	$fr_date = "(cp_inv_sdate = '9999999999' or cp_inv_sdate <= curdate())";
	$to_date = "(cp_inv_edate = '9999999999' or cp_inv_edate >= curdate())";

	// 쿠폰유효 기간 (시간대)
	$fr_hour = "(cp_inv_shour1 = '99' or cp_inv_shour1 <= date_format(now(),'%H'))";
	$to_hour = "(cp_inv_shour2 = '99' or cp_inv_shour2 > date_format(now(),'%H'))";

	$query[0]  = " from shop_coupon_log ";
	$query[1]  = " where mb_id='$mb_id' and mb_use='0' ";
	$query[1] .= " and ((cp_inv_type='0' and ($fr_date and $to_date) and ($fr_hour and $to_hour)) ";
	$query[1] .= " or (cp_inv_type='1' and date_add(`cp_wdate`, interval `cp_inv_day` day) > now())) ";
	$query[2]  = " order by lo_id ";

	$sql = " select count(*) as cnt {$query[0]} {$query[1]} ";
	$row = sql_fetch($sql);
	$query[3] = (int)$row['cnt'];

	return $query;
}

// 쿠폰 체크
function is_used_coupon($type, $gs_id)
{
	global $config, $member;

	if(!$config['coupon_yes']) return '';

	$tmp_coupon = array();

	$sql = "select * from shop_coupon where cp_use='1' and cp_download='$type'";
	$result = sql_query($sql);
	for($i=0; $cp=sql_fetch_array($result); $i++) {

		$sql_fld = " where cp_id = '$cp[cp_id]' and mb_id = '$member[id]' ";
		if($cp['cp_overlap']) { // 중복발급 허용시
			$sql_fld .= " and mb_use = '0' ";
		}

		// 다운로드 쿠폰
		$sql = "select count(*) as cnt from shop_coupon_log {$sql_fld} ";
		$row = sql_fetch($sql);
		$dwd_count = (int)$row['cnt'];

		$is_coupon = false;

		// 다운로드 레벨제한 검사
		if($cp['cp_id'] && ($member['grade'] <= $cp['cp_dlevel'])) {
			// 다운로드 누적제한 검사 (무제한이거나 다운로드 횟수가 아직 남아있을때)
			if($cp['cp_dlimit'] == '99999999999' || ($dwd_count < $cp['cp_dlimit'])) {

				// 전체상품에 쿠폰사용 가능
				if($cp['cp_use_part'] == '0' && !$dwd_count) {
					$is_coupon = true;
				}
				// 일부 상품만 쿠폰사용 가능
				else if($cp['cp_use_part'] == '1') {

					if($cp['cp_use_goods']) {
						$sql = "select count(*) as cnt
								  from shop_coupon_log
									   {$sql_fld}
								   and find_in_set('$gs_id', cp_use_goods) >= 1 ";
						$row = sql_fetch($sql);
						$it_dw_count = (int)$row['cnt'];
						$cp_el_goods = explode(',', $cp['cp_use_goods']);
						if(!$it_dw_count && in_array($gs_id, $cp_el_goods)) {
							$is_coupon = true;
						}
					}
				}
				// 일부 카테고리만 쿠폰사용 가능
				else if($cp['cp_use_part'] == '2') {

					if($cp['cp_use_category']) {
						$ca_list = get_extract($gs_id);

						$cl = sql_fetch("select cp_use_category from shop_coupon_log {$sql_fld} ");
						$ca_dw_count = get_substr_count($ca_list, $cl['cp_use_category']);
						$ca_to_count = get_substr_count($ca_list, $cp['cp_use_category']);

						if(!$ca_dw_count && $ca_to_count) {
							$is_coupon = true;
						}
					}
				}
				// 일부 상품은 쿠폰사용 불가
				else if($cp['cp_use_part'] == '3') {
					if($cp['cp_use_goods']) {
						$sql = "select count(*) as cnt
								  from shop_coupon_log
									   {$sql_fld}
								   and find_in_set('$gs_id', cp_use_goods) < 1 ";
						$row = sql_fetch($sql);
						$it_dw_count = (int)$row['cnt'];
						$cp_el_goods = explode(',', $cp['cp_use_goods']);
						if(!$it_dw_count && !in_array($gs_id, $cp_el_goods)) {
							$is_coupon = true;
						}
					}
				}
				// 일부 카테고리는 쿠폰사용 불가
				else if($cp['cp_use_part'] == '4') {

					if($cp['cp_use_category']) {
						$ca_list = get_extract($gs_id);

						$cl = sql_fetch("select cp_use_category from shop_coupon_log {$sql_fld} ");
						$ca_dw_count = get_substr_count($ca_list, $cl['cp_use_category']);
						$ca_to_count = get_substr_count($ca_list, $cp['cp_use_category']);

						if(!$ca_dw_count && !$ca_to_count) {
							$is_coupon = true;
						}
					}
				}
			}
		}

		if($is_coupon) {
			switch($cp['cp_type']){
				case '0': // 발행 날짜 지정
					if(($cp['cp_pub_sdate'] <= TB_TIME_YMD || $cp['cp_pub_sdate'] == '9999999999') &&
					   ($cp['cp_pub_edate'] >= TB_TIME_YMD || $cp['cp_pub_edate'] == '9999999999')) {
						$tmp_coupon[] = $cp['cp_id'];
					}
					break;
				case '1': // 발행 시간/요일 지정
					if(($cp['cp_pub_sdate'] <= TB_TIME_YMD || $cp['cp_pub_sdate'] == '9999999999') &&
					   ($cp['cp_pub_edate'] >= TB_TIME_YMD || $cp['cp_pub_edate'] == '9999999999')) {

						$yoil = array("일"=>"0","월"=>"1","화"=>"2","수"=>"3","목"=>"4","금"=>"5", "토"=>"6");

						$cp_week_day = explode(",", $cp['cp_week_day']);

						$wr_week = array();
						for($j=0; $j<count($cp_week_day); $j++) {
							for($k = 1; checkdate(TB_TIME_MONTH, $k, TB_TIME_YEAR); $k ++) {
								$thismonth = TB_TIME_MONTH.'/'.substr(sprintf('%02d',$k), -2);
								$thistime = strtotime($thismonth);
								$thisweek = date("w", $thistime);
								if($thisweek == $yoil[$cp_week_day[$j]]) {
									$wr_week[] = substr(sprintf('%02d',$k), -2);
								}
							}
						}

						$wr_week = array_unique($wr_week, SORT_STRING);
						$is_week = implode(",", $wr_week);

						$cnt = substr_count($is_week, TB_TIME_DAY);
						if($cnt) {
							//쿠폰발행 시간
							$tmp_cpdown = array();
							for($j=1; $j<=3; $j++) {
								$cp_pub_use = $cp['cp_pub_'.$j.'_use'];
								$cp_pub_cnt = $cp['cp_pub_'.$j.'_cnt'];
								$cp_pub_down = $cp['cp_pub_'.$j.'_down'];

								$cp_pub_shour = sprintf('%02d', $cp['cp_pub_shour'.$j]);
								$cp_pub_ehour = sprintf('%02d', $cp['cp_pub_ehour'.$j]);

								if($cp_pub_use &&
								  ((date('H') >= $cp_pub_shour || $cp_pub_shour == '99') &&
								   (date('H') <= $cp_pub_ehour || $cp_pub_ehour == '99'))) {

									if($cp_pub_cnt && ($cp_pub_cnt > $cp_pub_down)) {
										$tmp_coupon[] = $cp['cp_id'];
										$tmp_cpdown[] = 'cp_pub_'.$j.'_down^'.$cp['cp_id'];
									}
								}
							}

							$tmp_cpdown = array_unique($tmp_cpdown, SORT_STRING);
							$ss_cpdown = implode(",", $tmp_cpdown);
						}
					}
					break;
				case '2': // 성별구분으로 발급
					$gender = strtoupper($member['gender']);
					if(($cp['cp_pub_sdate'] <= TB_TIME_YMD || $cp['cp_pub_sdate'] == '9999999999') &&
					   ($cp['cp_pub_edate'] >= TB_TIME_YMD || $cp['cp_pub_edate'] == '9999999999')) {
						if(!$cp['cp_use_sex'] || $cp['cp_use_sex'] == $gender) {
							$tmp_coupon[] = $cp['cp_id'];
						}
					}
					break;
				case '3': // 회원 생일자 발급
					$mb_birth_month = conv_number($member['birth_month']);
					$mb_birth_day = conv_number($member['birth_day']);
					$cp_pub_sday = conv_number($cp['cp_pub_sday']);
					$cp_pub_eday = conv_number($cp['cp_pub_eday']);

					$is_check_vars = false;
					if($mb_birth_month && $mb_birth_day) {
						$is_check_vars = true;
					}

					if($is_check_vars) {
						$year = date("Y");
						$month = sprintf('%02d', $mb_birth_month);
						$day = sprintf('%02d', $mb_birth_day);

						// 생일 전
						$fr_day = $day - (int)$cp_pub_sday;
						$fr_birthday = date("Y-m-d",mktime(0,0,1,$month,$fr_day,$year));

						// 생일 후
						$to_day = $day + (int)$cp_pub_eday;
						$to_birthday = date("Y-m-d",mktime(0,0,1,$month,$to_day,$year));

						if(TB_TIME_YMD >= $fr_birthday && TB_TIME_YMD <= $to_birthday) {
							$tmp_coupon[] = $cp['cp_id'];
						}
					}
					break;
				case '4': // 연령 구분으로 발급
					if(($cp['cp_pub_sdate'] <= TB_TIME_YMD || $cp['cp_pub_sdate'] == '9999999999') &&
					   ($cp['cp_pub_edate'] >= TB_TIME_YMD || $cp['cp_pub_edate'] == '9999999999')) {

						$mb_birth_year = conv_number($member['birth_year']);
						$cp_use_sage = conv_number($cp['cp_use_sage']);
						$cp_use_eage = conv_number($cp['cp_use_eage']);

						$is_check_vars = false;
						if(strlen($mb_birth_year) == 4) {
							if(strlen($cp_use_sage) == 4 && strlen($cp_use_eage) == 4) {
								$is_check_vars = true;
							}
						}

						if($is_check_vars) {
							if($mb_birth_year >= $cp_use_sage && $mb_birth_year <= $cp_use_eage) {
								$tmp_coupon[] = $cp['cp_id'];
							}
						}
					}
					break;
			}
		}
	}

	if($ss_cpdown)
		set_session('ss_pub_down', $ss_cpdown);
	else
		set_session('ss_pub_down', '');

	$tmp_coupon = array_unique($tmp_coupon, SORT_STRING);
	$tmp_list = implode(",", $tmp_coupon);

	return $tmp_list;
}

// 쿠폰 발급
function insert_used_coupon($mb_id, $mb_name, $cp)
{
	global $config;

	if($config['coupon_yes']) {
		unset($value);
		$value['mb_id']			= $mb_id;
		$value['mb_name']		= $mb_name;
		$value['cp_id']			= $cp['cp_id'];
		$value['cp_type']		= $cp['cp_type'];
		$value['cp_dlimit']		= $cp['cp_dlimit'];
		$value['cp_dlevel']		= $cp['cp_dlevel'];
		$value['cp_subject']	= $cp['cp_subject'];
		$value['cp_explan']		= $cp['cp_explan'];
		$value['cp_use']		= $cp['cp_use'];
		$value['cp_download']	= $cp['cp_download'];
		$value['cp_overlap']	= $cp['cp_overlap'];
		$value['cp_sale_type']	= $cp['cp_sale_type'];
		$value['cp_sale_percent'] = $cp['cp_sale_percent'];
		$value['cp_sale_amt_max'] = $cp['cp_sale_amt_max'];
		$value['cp_sale_amt']	= $cp['cp_sale_amt'];
		$value['cp_dups']		= $cp['cp_dups'];
		$value['cp_pub_sdate']	= $cp['cp_pub_sdate'];
		$value['cp_pub_edate']	= $cp['cp_pub_edate'];
		$value['cp_pub_sday']	= $cp['cp_pub_sday'];
		$value['cp_pub_eday']	= $cp['cp_pub_eday'];
		$value['cp_use_sex']	= $cp['cp_use_sex'];
		$value['cp_use_sage']	= $cp['cp_use_sage'];
		$value['cp_use_eage']	= $cp['cp_use_eage'];
		$value['cp_week_day']	= $cp['cp_week_day'];
		$value['cp_pub_1_use']	= $cp['cp_pub_1_use'];
		$value['cp_pub_shour1']	= $cp['cp_pub_shour1'];
		$value['cp_pub_ehour1']	= $cp['cp_pub_ehour1'];
		$value['cp_pub_1_cnt']	= $cp['cp_pub_1_cnt'];
		$value['cp_pub_1_down']	= $cp['cp_pub_1_down'];
		$value['cp_pub_2_use']	= $cp['cp_pub_2_use'];
		$value['cp_pub_shour2']	= $cp['cp_pub_shour2'];
		$value['cp_pub_ehour2']	= $cp['cp_pub_ehour2'];
		$value['cp_pub_2_cnt']	= $cp['cp_pub_2_cnt'];
		$value['cp_pub_2_down']	= $cp['cp_pub_2_down'];
		$value['cp_pub_3_use']	= $cp['cp_pub_3_use'];
		$value['cp_pub_shour3']	= $cp['cp_pub_shour3'];
		$value['cp_pub_ehour3']	= $cp['cp_pub_ehour3'];
		$value['cp_pub_3_cnt']	= $cp['cp_pub_3_cnt'];
		$value['cp_pub_3_down']	= $cp['cp_pub_3_down'];
		$value['cp_inv_type']	= $cp['cp_inv_type'];
		$value['cp_inv_sdate']	= $cp['cp_inv_sdate'];
		$value['cp_inv_edate']	= $cp['cp_inv_edate'];
		$value['cp_inv_shour1']	= $cp['cp_inv_shour1'];
		$value['cp_inv_shour2']	= $cp['cp_inv_shour2'];
		$value['cp_inv_day']	= $cp['cp_inv_day'];
		$value['cp_low_amt']	= $cp['cp_low_amt'];
		$value['cp_use_part']	= $cp['cp_use_part'];
		$value['cp_use_goods']	= $cp['cp_use_goods'];
		$value['cp_use_category'] = $cp['cp_use_category'];
		$value['cp_wdate']		= TB_TIME_YMDHIS;
		insert("shop_coupon_log", $value);

		$ss_pub_down = get_session('ss_pub_down');
		if($ss_pub_down) {
			unset($value);
			$arr_pub_down = explode(",", $ss_pub_down);
			for($i=0; $i<count($arr_pub_down); $i++) {
				$pub_down = explode("^", $arr_pub_down[$i]);

				$value[$pub_down[0]] = $cp[$pub_down[0]] + 1;
				update("shop_coupon",$value,"where cp_id='$pub_down[1]'");
			}

			set_session('ss_pub_down', '');
		}
	}

	return true;
}

// 쿠폰 : 구매 가능한 상품
function get_log_precompose($lo_id)
{
	global $member;

	// 쿠폰유효 기간 (시간대)
	$fr_hour = "(cp_inv_shour1 = '99' or cp_inv_shour1 <= date_format(now(),'%H'))";
	$to_hour = "(cp_inv_shour2 = '99' or cp_inv_shour2 > date_format(now(),'%H'))";

	$sql_common  = " from shop_coupon_log ";
	$sql_search  = " where mb_id='$member[id]' and mb_use='0' and lo_id='$lo_id' ";
	$sql_search .= " and ((cp_inv_type='0' and ($fr_hour and $to_hour)) ";
	$sql_search .= " or (cp_inv_type='1' and date_add(`cp_wdate`, interval `cp_inv_day` day) > now())) ";

	$sql = " select * $sql_common $sql_search ";
	$log = sql_fetch($sql);

	switch($log['cp_use_part']) {
		case '0': // 전체상품에 쿠폰사용 가능
			$sql_cost = "";
			break;
		case '1': // 일부 상품만 쿠폰사용 가능
			if($log['cp_use_goods']) {
				$sql_cost = " and a.index_no in($log[cp_use_goods]) ";
			}
			break;
		case '2': // 일부 카테고리만 쿠폰사용 가능
			if($log['cp_use_category']) {
				$sql_cost = " and b.gcate in($log[cp_use_category]) ";
			}
			break;
		case '3': // 일부 상품은 쿠폰사용 불가
			if($log['cp_use_goods']) {
				$sql_cost = " and a.index_no not in($log[cp_use_goods]) ";
			}
			break;
		case '4': // 일부 카테고리는 쿠폰사용 불가
			if($log['cp_use_category']) {
				$sql_cost = " and b.gcate not in($log[cp_use_category]) ";
			}
			break;
	}

	return $sql_cost;
}

/*************************************************************************
**
**  접속자집계 관련 함수 모음
**
*************************************************************************/

// get_browser() 함수는 이미 있음
function get_brow($agent)
{
    $agent = strtolower($agent);

    //echo $agent; echo "<br/>";

    if(preg_match("/msie ([1-9][0-9]\.[0-9]+)/", $agent, $m)) { $s = 'MSIE '.$m[1]; }
    else if(preg_match("/firefox/", $agent))            { $s = "FireFox"; }
    else if(preg_match("/chrome/", $agent))             { $s = "Chrome"; }
    else if(preg_match("/x11/", $agent))                { $s = "Netscape"; }
    else if(preg_match("/opera/", $agent))              { $s = "Opera"; }
    else if(preg_match("/gec/", $agent))                { $s = "Gecko"; }
    else if(preg_match("/bot|slurp/", $agent))          { $s = "Robot"; }
    else if(preg_match("/internet explorer/", $agent))  { $s = "IE"; }
    else if(preg_match("/mozilla/", $agent))            { $s = "Mozilla"; }
    else { $s = "기타"; }

    return $s;
}

function get_os($agent)
{
    $agent = strtolower($agent);

    //echo $agent; echo "<br/>";

    if(preg_match("/windows 98/", $agent))                 { $s = "98"; }
    else if(preg_match("/windows 95/", $agent))             { $s = "95"; }
    else if(preg_match("/windows nt 4\.[0-9]*/", $agent))   { $s = "NT"; }
    else if(preg_match("/windows nt 5\.0/", $agent))        { $s = "2000"; }
    else if(preg_match("/windows nt 5\.1/", $agent))        { $s = "XP"; }
    else if(preg_match("/windows nt 5\.2/", $agent))        { $s = "2003"; }
    else if(preg_match("/windows nt 6\.0/", $agent))        { $s = "Vista"; }
    else if(preg_match("/windows nt 6\.1/", $agent))        { $s = "Windows7"; }
    else if(preg_match("/windows nt 6\.2/", $agent))        { $s = "Windows8"; }
    else if(preg_match("/windows 9x/", $agent))             { $s = "ME"; }
    else if(preg_match("/windows ce/", $agent))             { $s = "CE"; }
    else if(preg_match("/mac/", $agent))                    { $s = "MAC"; }
    else if(preg_match("/linux/", $agent))                  { $s = "Linux"; }
    else if(preg_match("/sunos/", $agent))                  { $s = "sunOS"; }
    else if(preg_match("/irix/", $agent))                   { $s = "IRIX"; }
    else if(preg_match("/phone/", $agent))                  { $s = "Phone"; }
    else if(preg_match("/bot|slurp/", $agent))              { $s = "Robot"; }
    else if(preg_match("/internet explorer/", $agent))      { $s = "IE"; }
    else if(preg_match("/mozilla/", $agent))                { $s = "Mozilla"; }
    else { $s = "기타"; }

    return $s;
}

/*************************************************************************
**
**  SQL 관련 함수 모음
**
*************************************************************************/

// DB 연결
function sql_connect($host, $user, $pass, $db=TB_MYSQL_DB)
{
    if(function_exists('mysqli_connect') && TB_MYSQLI_USE) {
        $link = mysqli_connect($host, $user, $pass, $db);

        // 연결 오류 발생 시 스크립트 종료
        if(mysqli_connect_errno()) {
            die('Connect Error: '.mysqli_connect_error());
        }
    } else {
        $link = mysql_connect($host, $user, $pass);
    }

    return $link;
}

// DB 선택
function sql_select_db($db, $connect)
{
    if(function_exists('mysqli_select_db') && TB_MYSQLI_USE)
        return @mysqli_select_db($connect, $db);
    else
        return @mysql_select_db($db, $connect);
}

function sql_set_charset($charset, $link=null)
{
    global $tb;

    if(!$link)
        $link = $tb['connect_db'];

    if(function_exists('mysqli_set_charset') && TB_MYSQLI_USE)
        mysqli_set_charset($link, $charset);
    else
        mysql_query(" set names {$charset} ", $link);
}

// mysqli_query 와 mysqli_error 를 한꺼번에 처리
// mysql connect resource 지정 - 명랑폐인님 제안
function sql_query($sql, $error=TB_DISPLAY_SQL_ERROR, $link=null)
{
    global $tb;

    if(!$link)
        $link = $tb['connect_db'];

    // Blind SQL Injection 취약점 해결
    $sql = trim($sql);
    // union의 사용을 허락하지 않습니다.
    //$sql = preg_replace("#^select.*from.*union.*#i", "select 1", $sql);
    $sql = preg_replace("#^select.*from.*[\s\(]+union[\s\)]+.*#i ", "select 1", $sql);
    // `information_schema` DB로의 접근을 허락하지 않습니다.
    $sql = preg_replace("#^select.*from.*where.*`?information_schema`?.*#i", "select 1", $sql);

    if(function_exists('mysqli_query') && TB_MYSQLI_USE) {
        if($error) {
            $result = @mysqli_query($link, $sql) or die("<p>$sql<p>" . mysqli_errno($link) . " : " .  mysqli_error($link) . "<p>error file : {$_SERVER['SCRIPT_NAME']}");
        } else {
            $result = @mysqli_query($link, $sql);
        }
    } else {
        if($error) {
            $result = @mysql_query($sql, $link) or die("<p>$sql<p>" . mysql_errno() . " : " .  mysql_error() . "<p>error file : {$_SERVER['SCRIPT_NAME']}");
        } else {
            $result = @mysql_query($sql, $link);
        }
    }

    return $result;
}

// 쿼리를 실행한 후 결과값에서 한행을 얻는다.
function sql_fetch($sql, $error=TB_DISPLAY_SQL_ERROR, $link=null)
{
    global $tb;

    if(!$link)
        $link = $tb['connect_db'];

    $result = sql_query($sql, $error, $link);
    //$row = @sql_fetch_array($result) or die("<p>$sql<p>" . mysqli_errno() . " : " .  mysqli_error() . "<p>error file : $_SERVER['SCRIPT_NAME']");
    $row = sql_fetch_array($result);
    return $row;
}

// 결과값에서 한행 연관배열(이름으로)로 얻는다.
function sql_fetch_array($result)
{
    if(function_exists('mysqli_fetch_assoc') && TB_MYSQLI_USE)
        $row = @mysqli_fetch_assoc($result);
    else
        $row = @mysql_fetch_assoc($result);

    return $row;
}

// $result에 대한 메모리(memory)에 있는 내용을 모두 제거한다.
// sql_free_result()는 결과로부터 얻은 질의 값이 커서 많은 메모리를 사용할 염려가 있을 때 사용된다.
// 단, 결과 값은 스크립트(script) 실행부가 종료되면서 메모리에서 자동적으로 지워진다.
function sql_free_result($result)
{
    if(function_exists('mysqli_free_result') && TB_MYSQLI_USE)
        return mysqli_free_result($result);
    else
        return mysql_free_result($result);
}

function sql_password($value)
{
    // mysql 4.0x 이하 버전에서는 password() 함수의 결과가 16bytes
    // mysql 4.1x 이상 버전에서는 password() 함수의 결과가 41bytes
    $row = sql_fetch(" select password('$value') as pass ");

    return $row['pass'];
}

// 비밀번호 비교
function check_password($pass, $hash)
{
    $password = get_encrypt_string($pass);

    return ($password === $hash);
}

function sql_insert_id($link=null)
{
    global $tb;

    if(!$link)
        $link = $tb['connect_db'];

    if(function_exists('mysqli_insert_id') && TB_MYSQLI_USE)
        return mysqli_insert_id($link);
    else
        return mysql_insert_id($link);
}

function sql_num_rows($result)
{
    if(function_exists('mysqli_num_rows') && TB_MYSQLI_USE)
        return mysqli_num_rows($result);
    else
        return mysql_num_rows($result);
}

function sql_fetch_row($result)
{
    if(function_exists('mysqli_fetch_row') && TB_MYSQLI_USE)
        return mysqli_fetch_row($result);
    else
        return mysql_fetch_row($result);
}

function sql_field_names($table, $link=null)
{
    global $tb;

    if(!$link)
        $link = $tb['connect_db'];

    $columns = array();

    $sql = " select * from `$table` limit 1 ";
    $result = sql_query($sql, $link);

    if(function_exists('mysqli_fetch_field') && TB_MYSQLI_USE) {
        while($field = mysqli_fetch_field($result)) {
            $columns[] = $field->name;
        }
    } else {
        $i = 0;
        $cnt = mysql_num_fields($result);
        while($i < $cnt) {
            $field = mysql_fetch_field($result, $i);
            $columns[] = $field->name;
            $i++;
        }
    }

    return $columns;
}

function sql_error_info($link=null)
{
    global $tb;

    if(!$link)
        $link = $tb['connect_db'];

    if(function_exists('mysqli_error') && TB_MYSQLI_USE) {
        return mysqli_errno($link) . ' : ' . mysqli_error($link);
    } else {
        return mysql_errno($link) . ' : ' . mysql_error($link);
    }
}

// PHPMyAdmin 참고
function get_table_define($table, $crlf="\n")
{
    global $tb;

    // For MySQL < 3.23.20
    $schema_create .= 'CREATE TABLE ' . $table . ' (' . $crlf;

    $sql = 'SHOW FIELDS FROM ' . $table;
    $result = sql_query($sql);
    while($row = sql_fetch_array($result))
    {
        $schema_create .= '    ' . $row['Field'] . ' ' . $row['Type'];
        if(isset($row['Default']) && $row['Default'] != '')
        {
            $schema_create .= ' DEFAULT \'' . $row['Default'] . '\'';
        }
        if($row['Null'] != 'YES')
        {
            $schema_create .= ' NOT NULL';
        }
        if($row['Extra'] != '')
        {
            $schema_create .= ' ' . $row['Extra'];
        }
        $schema_create     .= ',' . $crlf;
    } // end while
    sql_free_result($result);

    $schema_create = preg_replace('/,' . $crlf . '$/', '', $schema_create);

    $sql = 'SHOW KEYS FROM ' . $table;
    $result = sql_query($sql);
    while($row = sql_fetch_array($result))
    {
        $kname    = $row['Key_name'];
        $comment  = (isset($row['Comment'])) ? $row['Comment'] : '';
        $sub_part = (isset($row['Sub_part'])) ? $row['Sub_part'] : '';

        if($kname != 'PRIMARY' && $row['Non_unique'] == 0) {
            $kname = "UNIQUE|$kname";
        }
        if($comment == 'FULLTEXT') {
            $kname = 'FULLTEXT|$kname';
        }
        if(!isset($index[$kname])) {
            $index[$kname] = array();
        }
        if($sub_part > 1) {
            $index[$kname][] = $row['Column_name'] . '(' . $sub_part . ')';
        } else {
            $index[$kname][] = $row['Column_name'];
        }
    } // end while
    sql_free_result($result);

    while(list($x, $columns) = @each($index)) {
        $schema_create     .= ',' . $crlf;
        if($x == 'PRIMARY') {
            $schema_create .= '    PRIMARY KEY (';
        } else if(substr($x, 0, 6) == 'UNIQUE') {
            $schema_create .= '    UNIQUE ' . substr($x, 7) . ' (';
        } else if(substr($x, 0, 8) == 'FULLTEXT') {
            $schema_create .= '    FULLTEXT ' . substr($x, 9) . ' (';
        } else {
            $schema_create .= '    KEY ' . $x . ' (';
        }
        $schema_create     .= implode($columns, ', ') . ')';
    } // end while

    $schema_create .= $crlf . ') ENGINE=MyISAM DEFAULT CHARSET=utf8';

    return $schema_create;
} // end of the 'PMA_getTableDef()' function


// 테이블 존재여부 검사
function table_exists($tablename, $database=false)
{
    if(!$database) {
        $res = sql_query("SELECT DATABASE()");
        $row = sql_fetch_row($res);
		$database = $row[0];
    }

    $row = sql_fetch("
        SELECT COUNT(*) AS cnt
          FROM information_schema.tables
         WHERE table_schema = '$database'
           AND table_name = '$tablename'
    ");

    return (int)$row['cnt'];
}

// mysql_query("insert into..  형태를 구현
// table은 쿼리를 실행할 테이블 명
// $values 는 연관배열 형태. 즉 array('name'=>'kk', 'id'=>'');
function insert($table,$values)
{
	$count=count($values);
	if(!$count) return false;

	$i=1;
	while(list($index,$key)=each($values)){
		if($i==$count){
			$field=$field.$index;
			if($index=='passwd')
			{	$value=$value."password('".trim($key)."')";	}
			else
			{	$value=$value."'".trim($key)."'";	}
		}
		else{
			$field=$field.$index.",";
			if($index=='passwd')
			{	$value=$value."password('".trim($key)."'),";	}
			else
			{	$value=$value."'".trim($key)."',";	}
		}
		$i++;
	}

	$sql = "insert into $table ($field) VALUES ($value)";	// 실제 쿼리 생성
	return sql_query($sql);
}

// mysql_query("insert into..  형태를 구현
// table은 쿼리를 실행할 테이블 명
// $values 는 연관배열 형태. 즉 array('name'=>'kk', 'id'=>'');
function insert_search($table,$values)
{
	$count=count($values);
	if(!$count) return false;

	$i=1;
	while(list($index,$key)=each($values)){
		if($i==$count){
			$field=$field.$index;
			if($index=='passwd')
			{	$value=$value."password('".trim($key)."')";	}
			else
			{	$value=$value."'".trim($key)."'";	}
		}
		else{
			$field=$field.$index.",";
			if($index=='passwd')
			{	$value=$value."password('".trim($key)."'),";	}
			else
			{	$value=$value."'".trim($key)."',";	}
		}
		$i++;
	}
	if(strpos($_SERVER['HTTP_REFERER'], TB_URL) !== false) {
		$sql = "insert into $table ($field) VALUES ($value)";	// 실제 쿼리 생성
		return sql_query($sql);
	}
}

// mysql_query("update $table set ...") 함수를 구현
// $table는 적용할 table명
// $values는 값을 배열 array('name'=>'','id'=>'')
function update($table,$values,$where="")
{
	$count=count($values);
	if(!$count)return false;

	$i=1;

	while(  list($index,$key)=each($values) ){

		if($i==$count)
		{
			if($index=='passwd')
			{	$value=$value.$index."=password('".trim($key)."') ";	}
			else
			{	$value=$value.$index."='".trim($key)."' ";	}
		}
		else
		{
			if($index=='passwd')
			{	$value=$value.$index."=password('".trim($key)."'), ";	}
			else
			{	$value=$value.$index."='".trim($key)."', ";	}
		}

		$i++;
	}

	$sql = "update $table SET $value ".$where;	// 실제 쿼리 생성
	return sql_query($sql);
}

/*************************************************************************
**
**  SMS 관련 함수 모음
**
*************************************************************************/

// str_replace
function rpc($str, $kind=",", $conv="")
{
	return str_replace($kind, $conv, $str);
}

// 문자열중 숫자만 추출
function conv_number($str)
{
	return preg_replace("/[^0-9]*/s", "", $str);
}

// 발신번호 유효성 체크
function check_vaild_callback($callback){
   $_callback = preg_replace('/[^0-9]/','', $callback);

   /**
   * 1588 로시작하면 총8자리인데 7자리라 차단
   * 02 로시작하면 총9자리 또는 10자리인데 11자리라차단
   * 1366은 그자체가 원번호이기에 다른게 붙으면 차단
   * 030으로 시작하면 총10자리 또는 11자리인데 9자리라차단
   */

   if( substr($_callback,0,4) == '1588') if( strlen($_callback) != 8) return false;
   if( substr($_callback,0,2) == '02')   if( strlen($_callback) != 9  && strlen($_callback) != 10 ) return false;
   if( substr($_callback,0,3) == '030')  if( strlen($_callback) != 10 && strlen($_callback) != 11 ) return false;

   if( !preg_match("/^(02|0[3-6]\d|01(0|1|3|5|6|7|8|9)|070|080|007)\-?\d{3,4}\-?\d{4,5}$/",$_callback) &&
       !preg_match("/^(15|16|18)\d{2}\-?\d{4,5}$/",$_callback) ){
             return false;
   } else if( preg_match("/^(02|0[3-6]\d|01(0|1|3|5|6|7|8|9)|070|080)\-?0{3,4}\-?\d{4}$/",$_callback )) {
             return false;
   } else {
             return true;
   }
}

// get_sock 함수 대체
if(!function_exists("get_sock")) {
    function get_sock($url)
    {
        // host 와 uri 를 분리
        //if(ereg("http://([a-zA-Z0-9_\-\.]+)([^<]*)", $url, $res))
        if(preg_match("/http:\/\/([a-zA-Z0-9_\-\.]+)([^<]*)/", $url, $res))
        {
            $host = $res[1];
            $get  = $res[2];
        }

        // 80번 포트로 소캣접속 시도
        $fp = fsockopen ($host, 80, $errno, $errstr, 30);
        if(!$fp)
        {
            die("$errstr ($errno)\n");
        }
        else
        {
            fputs($fp, "GET $get HTTP/1.0\r\n");
            fputs($fp, "Host: $host\r\n");
            fputs($fp, "\r\n");

            // header 와 content 를 분리한다.
            while(trim($buffer = fgets($fp,1024)) != "")
            {
                $header .= $buffer;
            }
            while(!feof($fp))
            {
                $buffer .= fgets($fp,1024);
            }
        }
        fclose($fp);

        // content 만 return 한다.
        return $buffer;
    }
}

// 인증, 결제 모듈 실행 체크
function module_exec_check($exe, $type)
{
    $error = '';
    $is_linux = false;
    if(strtoupper(substr(PHP_OS, 0, 3)) !== 'WIN')
        $is_linux = true;

    // 모듈 파일 존재하는지 체크
    if(!is_file($exe)) {
        $error = $exe.' 파일이 존재하지 않습니다.';
    } else {
        // 실행권한 체크
        if(!is_executable($exe)) {
            if($is_linux)
                $error = $exe.'\n파일의 실행권한이 없습니다.\n\nchmod 755 '.basename($exe).' 과 같이 실행권한을 부여해 주십시오.';
            else
                $error = $exe.'\n파일의 실행권한이 없습니다.\n\n'.basename($exe).' 파일에 실행권한을 부여해 주십시오.';
        } else {
            // 바이너리 파일인지
            if($is_linux) {
                $search = false;
                $isbinary = true;
                $executable = true;

                switch($type) {
                    case 'ct_cli':
                        exec($exe.' -h 2>&1', $out, $return_var);

                        if($return_var == 139) {
                            $isbinary = false;
                            break;
                        }

                        for($i=0; $i<count($out); $i++) {
                            if(strpos($out[$i], 'KCP ENC') !== false) {
                                $search = true;
                                break;
                            }
                        }
                        break;
                    case 'pp_cli':
                        exec($exe.' -h 2>&1', $out, $return_var);

                        if($return_var == 139) {
                            $isbinary = false;
                            break;
                        }

                        for($i=0; $i<count($out); $i++) {
                            if(strpos($out[$i], 'CLIENT') !== false) {
                                $search = true;
                                break;
                            }
                        }
                        break;
                    case 'okname':
                        exec($exe.' D 2>&1', $out, $return_var);

                        if($return_var == 139) {
                            $isbinary = false;
                            break;
                        }

                        for($i=0; $i<count($out); $i++) {
                            if(strpos(strtolower($out[$i]), 'ret code') !== false) {
                                $search = true;
                                break;
                            }
                        }
                        break;
                }

                if(!$isbinary || !$search) {
                    $error = $exe.'\n파일을 바이너리 타입으로 다시 업로드하여 주십시오.';
                }
            }
        }
    }

    if($error) {
        $error = '<script>alert("'.$error.'");</script>';
    }

    return $error;
}

// 아이코드 사용자정보
function get_icode_userinfo($id, $pass)
{
    $res = get_sock('http://www.icodekorea.com/res/userinfo.php?userid='.$id.'&userpw='.$pass);
    $res = explode(';', $res);
    $userinfo = array(
        'code'      => $res[0], // 결과코드
        'coin'      => $res[1], // 고객 잔액 (충전제만 해당)
        'gpay'      => $res[2], // 고객의 건수 별 차감액 표시 (충전제만 해당)
        'payment'   => $res[3]  // 요금제 표시, A:충전제, C:정액제
    );

    return $userinfo;
}

// 문자전송 (회원가입)
function icode_sms_send($mb_id, $fld)
{
	global $config, $super;

	$sm = sql_fetch("select * from shop_sms");
	if(!$sm['cf_sms_use'])
		return;

	$mb = get_member($mb_id);
	$pt = get_member($mb['pt_id']);

	$mb_hp = $mb['cellphone'];
	$admin_hp = $super['cellphone'];
	$partner_hp = ($mb['pt_id'] != 'admin') ? $pt['cellphone'] : '';

	// SMS BEGIN --------------------------------------------------------
	if($sm["cf_mb_use{$fld}"] || $sm["cf_ad_use{$fld}"] || $sm["cf_re_use{$fld}"])
	{
		$is_sms_send = false;

		// 충전식일 경우 잔액이 있는지 체크
		if($sm['cf_icode_id'] && $sm['cf_icode_pw']) {
			$userinfo = get_icode_userinfo($sm['cf_icode_id'], $sm['cf_icode_pw']);

			if($userinfo['code'] == 0) {
				if($userinfo['payment'] == 'C') { // 정액제
					$is_sms_send = true;
				} else {
					$minimum_coin = 100;
					if(defined('TB_ICODE_COIN'))
						$minimum_coin = intval(TB_ICODE_COIN);

					if((int)$userinfo['coin'] >= $minimum_coin)
						$is_sms_send = true;
				}
			}
		}

		if($is_sms_send)
		{
			$sms_send_use = array($sm["cf_mb_use{$fld}"], $sm["cf_ad_use{$fld}"], $sm["cf_re_use{$fld}"]);
			$recv_numbers = array($mb_hp, $admin_hp, $partner_hp);
			$send_number = conv_number($sm['cf_sms_recall']);

			$sms_count = 0;
			$sms_messages = array();
			for($s=0; $s<count($sms_send_use); $s++) {
				$sms_content = $sm["cf_cont{$fld}"];
				$recv_number = conv_number($recv_numbers[$s]);

				$sms_content = rpc($sms_content, "{이름}", $mb['name']);
				$sms_content = rpc($sms_content, "{아이디}", $mb['id']);

				if($sms_send_use[$s] && $recv_number) {
					$sms_messages[] = array('recv' => $recv_number, 'send' => $send_number, 'cont' => $sms_content);
					$sms_count++;
				}
			}

			// SMS 전송
			if($sms_count > 0) {

				if($sm['cf_sms_type'] == 'LMS') {
					include_once(TB_LIB_PATH.'/icode.lms.lib.php');

					$port_setting = get_icode_port_type($sm['cf_icode_id'], $sm['cf_icode_pw']);

					// SMS 모듈 클래스 생성
					if($port_setting !== false) {
						$SMS = new LMS;
						$SMS->SMS_con($sm['cf_icode_server_ip'], $sm['cf_icode_id'], $sm['cf_icode_pw'], $port_setting);

						for($s=0; $s<count($sms_messages); $s++) {
							$strDest     = array();
							$strDest[]   = $sms_messages[$s]['recv'];
							$strCallBack = $sms_messages[$s]['send'];
							$strCaller   = iconv_euckr(trim($config['company_name']));
							$strSubject  = '';
							$strURL      = '';
							$strData     = iconv_euckr($sms_messages[$s]['cont']);
							$strDate     = '';
							$nCount      = count($strDest);

							$res = $SMS->Add($strDest, $strCallBack, $strCaller, $strSubject, $strURL, $strData, $strDate, $nCount);

							$SMS->Send();
							$SMS->Init(); // 보관하고 있던 결과값을 지웁니다.
						}
					}
				} else {
					include_once(TB_LIB_PATH.'/icode.sms.lib.php');

					$SMS = new SMS; // SMS 연결
					$SMS->SMS_con($sm['cf_icode_server_ip'], $sm['cf_icode_id'], $sm['cf_icode_pw'], $sm['cf_icode_server_port']);

					for($s=0; $s<count($sms_messages); $s++) {
						$recv_number = $sms_messages[$s]['recv'];
						$send_number = $sms_messages[$s]['send'];
						$sms_content = iconv_euckr($sms_messages[$s]['cont']);

						$SMS->Add($recv_number, $send_number, $sm['cf_icode_id'], $sms_content, "");
					}

					$SMS->Send();
					$SMS->Init(); // 보관하고 있던 결과값을 지웁니다.
				}
			}
		}
	}
	// SMS END   --------------------------------------------------------
}

// 문자전송 (주문관련)
function icode_order_sms_send($od_hp, $fld, $od_id)
{
	global $config, $super;

	$sm = sql_fetch("select * from shop_sms");
	if(!$sm['cf_sms_use'])
		return;

	$od = get_order($od_id); // 주문정보
	$pt = get_member($od['pt_id'], 'cellphone');
	$sr = get_seller_cd($od['seller_id'], 'info_tel');

	$admin_hp	= $super['cellphone'];
	$seller_hp	= $sr['info_tel'];
	$partner_hp = ($od['pt_id'] != 'admin') ? $pt['cellphone'] : '';
	$dlcomp		= explode("|", $od['delivery']);

	// SMS BEGIN --------------------------------------------------------
	if($sm["cf_mb_use{$fld}"] || $sm["cf_ad_use{$fld}"] || $sm["cf_re_use{$fld}"] || $sm["cf_sr_use{$fld}"])
	{
		$is_sms_send = false;

		// 충전식일 경우 잔액이 있는지 체크
		if($sm['cf_icode_id'] && $sm['cf_icode_pw']) {
			$userinfo = get_icode_userinfo($sm['cf_icode_id'], $sm['cf_icode_pw']);

			if($userinfo['code'] == 0) {
				if($userinfo['payment'] == 'C') { // 정액제
					$is_sms_send = true;
				} else {
					$minimum_coin = 100;
					if(defined('TB_ICODE_COIN'))
						$minimum_coin = intval(TB_ICODE_COIN);

					if((int)$userinfo['coin'] >= $minimum_coin)
						$is_sms_send = true;
				}
			}
		}

		if($is_sms_send)
		{
			$sms_send_use = array(
				$sm["cf_mb_use{$fld}"],
				$sm["cf_ad_use{$fld}"],
				$sm["cf_re_use{$fld}"],
				$sm["cf_sr_use{$fld}"]
			);

			$recv_numbers = array($od_hp, $admin_hp, $partner_hp, $seller_hp);
			$send_number = conv_number($sm['cf_sms_recall']);

			$sms_count = 0;
			$sms_messages = array();
			for($s=0; $s<count($sms_send_use); $s++) {
				$sms_content = $sm["cf_cont{$fld}"];
				$recv_number = conv_number($recv_numbers[$s]);

				$sms_content = rpc($sms_content, "{이름}", $od['name']);
				$sms_content = rpc($sms_content, "{주문번호}", $od_id);
				$sms_content = rpc($sms_content, "{업체}", $dlcomp[0]);
				$sms_content = rpc($sms_content, "{송장번호}", $od['delivery_no']);

				if($sms_send_use[$s] && $recv_number) {
					$sms_messages[] = array('recv' => $recv_number, 'send' => $send_number, 'cont' => $sms_content);
					$sms_count++;
				}
			}

			// SMS 전송
			if($sms_count > 0) {
				if($sm['cf_sms_type'] == 'LMS') {
					include_once(TB_LIB_PATH.'/icode.lms.lib.php');

					$port_setting = get_icode_port_type($sm['cf_icode_id'], $sm['cf_icode_pw']);

					// SMS 모듈 클래스 생성
					if($port_setting !== false) {
						$SMS = new LMS;
						$SMS->SMS_con($sm['cf_icode_server_ip'], $sm['cf_icode_id'], $sm['cf_icode_pw'], $port_setting);

						for($s=0; $s<count($sms_messages); $s++) {
							$strDest     = array();
							$strDest[]   = $sms_messages[$s]['recv'];
							$strCallBack = $sms_messages[$s]['send'];
							$strCaller   = iconv_euckr(trim($config['company_name']));
							$strSubject  = '';
							$strURL      = '';
							$strData     = iconv_euckr($sms_messages[$s]['cont']);
							$strDate     = '';
							$nCount      = count($strDest);

							$res = $SMS->Add($strDest, $strCallBack, $strCaller, $strSubject, $strURL, $strData, $strDate, $nCount);

							$SMS->Send();
							$SMS->Init(); // 보관하고 있던 결과값을 지웁니다.
						}
					}
				} else {
					include_once(TB_LIB_PATH.'/icode.sms.lib.php');

					$SMS = new SMS; // SMS 연결
					$SMS->SMS_con($sm['cf_icode_server_ip'], $sm['cf_icode_id'], $sm['cf_icode_pw'], $sm['cf_icode_server_port']);

					for($s=0; $s<count($sms_messages); $s++) {
						$recv_number = $sms_messages[$s]['recv'];
						$send_number = $sms_messages[$s]['send'];
						$sms_content = iconv_euckr($sms_messages[$s]['cont']);

						$SMS->Add($recv_number, $send_number, $sm['cf_icode_id'], $sms_content, "");
					}

					$SMS->Send();
					$SMS->Init(); // 보관하고 있던 결과값을 지웁니다.
				}
			}
		}
	}
	// SMS END   --------------------------------------------------------
}

// 문자전송 (개별전송)
function icode_member_send($recv_number, $sms_content)
{
	global $config;

	// SMS BEGIN --------------------------------------------------------
	$sm = sql_fetch("select * from shop_sms");
	if($sm['cf_sms_use'] && $recv_number) {
		$is_sms_send = false;

		// 충전식일 경우 잔액이 있는지 체크
		if($sm['cf_icode_id'] && $sm['cf_icode_pw']) {
			$userinfo = get_icode_userinfo($sm['cf_icode_id'], $sm['cf_icode_pw']);

			if($userinfo['code'] == 0) {
				if($userinfo['payment'] == 'C') { // 정액제
					$is_sms_send = true;
				} else {
					$minimum_coin = 100;
					if(defined('TB_ICODE_COIN'))
						$minimum_coin = intval(TB_ICODE_COIN);

					if((int)$userinfo['coin'] >= $minimum_coin)
						$is_sms_send = true;
				}
			}
		}

		if($is_sms_send)
		{
			$send_number = conv_number($sm['cf_sms_recall']);
			$recv_number = conv_number($recv_number);
			$sms_content = iconv_euckr($sms_content);

			if($sm['cf_sms_type'] == 'LMS') {
				include_once(TB_LIB_PATH.'/icode.lms.lib.php');

				$port_setting = get_icode_port_type($sm['cf_icode_id'], $sm['cf_icode_pw']);

				// SMS 모듈 클래스 생성
				if($port_setting !== false) {
					$SMS = new LMS;
					$SMS->SMS_con($sm['cf_icode_server_ip'], $sm['cf_icode_id'], $sm['cf_icode_pw'], $port_setting);

					$strDest     = array();
					$strDest[]   = $recv_number;
					$strCallBack = $send_number;
					$strCaller   = iconv_euckr(trim($config['company_name']));
					$strSubject  = '';
					$strURL      = '';
					$strData     = $sms_content;
					$strDate     = '';
					$nCount      = count($strDest);

					$res = $SMS->Add($strDest, $strCallBack, $strCaller, $strSubject, $strURL, $strData, $strDate, $nCount);

					$SMS->Send();
					$SMS->Init(); // 보관하고 있던 결과값을 지웁니다.
				}
			} else {
				// SMS 전송
				include_once(TB_LIB_PATH.'/icode.sms.lib.php');

				$SMS = new SMS; // SMS 연결
				$SMS->SMS_con($sm['cf_icode_server_ip'], $sm['cf_icode_id'], $sm['cf_icode_pw'], $sm['cf_icode_server_port']);

				$SMS->Add($recv_number, $send_number, $sm['cf_icode_id'], $sms_content, "");

				$SMS->Send();
				$SMS->Init(); // 보관하고 있던 결과값을 지웁니다.
			}
		}
	}
	// SMS END   --------------------------------------------------------
}


/*************************************************************************
**
**  기타 함수 모음
**
*************************************************************************/

// 회원 삭제
function member_delete($mb_id)
{
	$mb = get_member($mb_id);

	$banner_dir = TB_DATA_PATH."/banner";
	$goods_dir  = TB_DATA_PATH."/goods";

	// 회원 탈퇴시 레그구성 변경 즉! a > b > c : b가 탈퇴를 하면 c는 a아래로 붙음
	$sql = "select id from shop_member where pt_id='$mb_id'";
	$res = sql_query($sql);
	while($row = sql_fetch_array($res)) {
		$sql = "update shop_member set pt_id='$mb[pt_id]' where id='$row[id]'";
		sql_query($sql);

		$memo = $mb_id." 회원이 탈퇴하여 추천인 {$mb['pt_id']} 으로 변경 되었습니다.";
		$sql = "insert into shop_leave_log ( new_id, old_id, mb_id, reg_time, memo )
				values ( '$mb[pt_id]','$mb_id','$row[id]','".TB_TIME_YMDHIS."','$memo' )";
		sql_query($sql);
	}

	// 카테고리 테이블 DROP
	$target_table = 'shop_cate_'.$mb_id;
	sql_query(" DROP TABLE {$target_table} ", FALSE);

	// 카테고리 폴더 전체 삭제
	if($mb_id) {
		rm_rf(TB_DATA_PATH.'/category/'.$mb_id);
	}

	sql_query("delete from shop_partner where mb_id='$mb_id'"); // 가맹점정보
	sql_query("delete from shop_partner_pay where mb_id='$mb_id'"); // 가맹점 수수료
	sql_query("delete from shop_partner_payrun where mb_id='$mb_id'"); // 가맹점 출금요청
	sql_query("delete from shop_partner_term where mb_id='$mb_id'"); // 가맹점 기간연장
	sql_query("delete from shop_leave_log where mb_id='$mb_id'"); // 가맹점 추천인변경로그

	// 로고
	$lg = sql_fetch("select * from shop_logo where mb_id='$mb_id'");
	if($lg['basic_logo']) @unlink($banner_dir.'/'.$lg['basic_logo']);
	if($lg['mobile_logo']) @unlink($banner_dir.'/'.$lg['mobile_logo']);
	if($lg['sns_logo']) @unlink($banner_dir.'/'.$lg['sns_logo']);
	if($lg['favicon_ico']) @unlink($banner_dir.'/'.$lg['favicon_ico']);
	sql_query("delete from shop_logo where mb_id='$mb_id'");

	// 배너
	$sql = "select * from shop_banner where mb_id='$mb_id' ";
	$res = sql_query($sql);
	for($i=0; $row=sql_fetch_array($res); $i++) {
		if($row['bn_file']) @unlink($banner_dir.'/'.$row['bn_file']);
	}
	sql_query("delete from shop_banner where mb_id='$mb_id'");

	// 상품정보
	$sr = sql_fetch("select seller_code from shop_seller where mb_id='$mb_id'");
	$sql = "select * from shop_goods where mb_id='$sr[seller_code]' or mb_id='$mb_id' ";
	$res = sql_query($sql);
	for($i=0; $row=sql_fetch_array($res); $i++) {
		$dir_list = $goods_dir.'/'.$row['gcode'];

		for($g=1; $g<=6; $g++) {
			if($row['simg'.$g]) {
				@unlink($goods_dir.'/'.$row['simg'.$g]);
				delete_item_thumbnail($dir_list, $row['simg'.$g]);
			}
		}

		// 에디터 이미지 삭제
		delete_editor_image($row['memo']);
	}

	sql_query("delete from shop_popup where mb_id='$mb_id'"); // 팝업
	sql_query("delete from shop_point where mb_id='$mb_id'"); // 회원 포인트
	sql_query("delete from shop_goods where mb_id='$sr[seller_code]'"); // 공급사 상품
	sql_query("delete from shop_goods where mb_id='$mb_id'"); // 가맹점 상품
	sql_query("delete from shop_goods_type where mb_id='$mb_id'"); // 상품진열관리
	sql_query("delete from shop_goods_qa where mb_id='$mb_id'"); // 상품문의
	sql_query("delete from shop_brand where mb_id='$sr[seller_code]'"); // 브랜드정보
	sql_query("delete from shop_brand where mb_id='$mb_id'"); // 브랜드정보
	sql_query("delete from shop_seller where mb_id='$mb_id'"); // 공급사 신청정보
	sql_query("delete from shop_seller_cal where mb_id='$mb_id'"); // 공급사 정산내역
	sql_query("delete from shop_visit where mb_id='$mb_id'"); // 접속자집계
	sql_query("delete from shop_visit_sum where mb_id='$mb_id'"); // 접속자집계
	sql_query("delete from shop_keyword where pt_id='$mb_id'"); // 키워드
	sql_query("delete from shop_member where id='$mb_id'"); // 회원정보
}

// 회원의 정보를 추출 ($mb_no는 회원의 주키값)
function get_member_no($mb_no, $fileds='*')
{
	return sql_fetch("select $fileds from shop_member where index_no='$mb_no' ");
}

// 회원의 정보를 리턴
function get_member($mb_id, $fileds='*')
{
	return sql_fetch("select $fileds from shop_member where id = TRIM('$mb_id')");
}

// 회원레벨 인덱스번호 체크
function get_grade($gb_no)
{
	$row = sql_fetch("select * from shop_member_grade where gb_no = '$gb_no'");
	$gb_name = $row['gb_name'];

	return $gb_name;
}

// 게시판 스킨경로를 얻는다
function get_skin_dir($skin='')
{
	$result_array = array();

	$dirname = TB_BBS_PATH."/skin/";
	$handle = opendir($dirname);
	while($file = readdir($handle))
	{
		if($file == "."||$file == "..") continue;

		if(is_dir($dirname.$file)) $result_array[] = $file;
	}
	closedir($handle);
	sort($result_array);

	return $result_array;
}

// 테마 path
function get_theme_path($skin)
{
	$skin_path = TB_PATH.'/theme/'.$skin;

    return $skin_path;
}

// 테마 url
function get_theme_url($skin)
{
	$skin_url = TB_URL.'/theme/'.$skin;

    return $skin_url;
}

// pc 테마 스킨경로를 얻는다
function get_theme_dir()
{
    $result_array = array();

    $dirname = TB_PATH.'/theme/';
    if(!is_dir($dirname))
        return;

    $handle = opendir($dirname);
    while($file = readdir($handle)) {
        if($file == '.'||$file == '..') continue;

        if(is_dir($dirname.$file)) $result_array[] = $file;
    }
    closedir($handle);
    sort($result_array);

    return $result_array;
}

// pc 테마 스킨디렉토리를 SELECT 형식으로 얻음
function get_theme_select($name, $selected='')
{
    $skins = array();
    $skins = array_merge($skins, get_theme_dir());

    $str = "<select id=\"$name\" name=\"$name\">\n";
    for($i=0; $i<count($skins); $i++) {
        if($i == 0) $str .= "<option value=\"\">선택</option>\n";
		$text = $skins[$i];
        $str .= option_selected($skins[$i], $selected, $text);
    }
    $str .= "</select>";

    return $str;
}

// mobile 테마 path
function get_mobile_theme_path($skin)
{
	$skin_path = TB_PATH.'/m/theme/'.$skin;

    return $skin_path;
}

// mobile 테마 url
function get_mobile_theme_url($skin)
{
	$skin_url = TB_URL.'/m/theme/'.$skin;

    return $skin_url;
}

// mobile 테마 스킨경로를 얻는다
function get_mobile_theme_dir()
{
    $result_array = array();

    $dirname = TB_PATH.'/m/theme/';
    if(!is_dir($dirname))
        return;

    $handle = opendir($dirname);
    while($file = readdir($handle)) {
        if($file == '.'||$file == '..') continue;

        if(is_dir($dirname.$file)) $result_array[] = $file;
    }
    closedir($handle);
    sort($result_array);

    return $result_array;
}

// mobile 테마 스킨디렉토리를 SELECT 형식으로 얻음
function get_mobile_theme_select($name, $selected='')
{
    $skins = array();
    $skins = array_merge($skins, get_mobile_theme_dir());

    $str = "<select id=\"$name\" name=\"$name\">\n";
    for($i=0; $i<count($skins); $i++) {
        if($i == 0) $str .= "<option value=\"\">선택</option>\n";
		$text = $skins[$i];
        $str .= option_selected($skins[$i], $selected, $text);
    }
    $str .= "</select>";
    return $str;
}

// 포인트 부여
function insert_point($mb_id, $point, $content='', $rel_table='', $rel_id='', $rel_action='')
{
    // 포인트가 없다면 업데이트 할 필요 없음
    if($point == 0) { return 0; }

    // 회원아이디가 없다면 업데이트 할 필요 없음
    if($mb_id == '') { return 0; }
    $mb = sql_fetch(" select id from shop_member where id = '$mb_id' ");
    if(!$mb['id']) { return 0; }

    // 회원포인트
    $mb_point = get_point_sum($mb_id);

    // 이미 등록된 내역이라면 건너뜀
    if($rel_table || $rel_id || $rel_action)
    {
        $sql = " select count(*) as cnt
				   from shop_point
                  where mb_id = '$mb_id'
                    and po_rel_table = '$rel_table'
                    and po_rel_id = '$rel_id'
                    and po_rel_action = '$rel_action' ";
        $row = sql_fetch($sql);
        if($row['cnt'])
            return -1;
    }

    $po_mb_point = $mb_point + $point;

    $sql = " insert into shop_point
                set mb_id = '$mb_id',
                    po_datetime = '".TB_TIME_YMDHIS."',
                    po_content = '".addslashes($content)."',
                    po_point = '$point',
                    po_use_point = '0',
                    po_mb_point = '$po_mb_point',
                    po_rel_table = '$rel_table',
                    po_rel_id = '$rel_id',
                    po_rel_action = '$rel_action' ";
    sql_query($sql);

    // 포인트를 사용한 경우 포인트 내역에 사용금액 기록
    if($point < 0) {
        insert_use_point($mb_id, $point);
    }

    // 포인트 UPDATE
    $sql = " update shop_member set point = '$po_mb_point' where id = '$mb_id' ";
    sql_query($sql);

    return 1;
}

// 사용포인트 입력
function insert_use_point($mb_id, $point, $po_id='')
{
    $point1 = abs($point);
    $sql = " select po_id, po_point, po_use_point
                from shop_point
                where mb_id = '$mb_id'
                  and po_id <> '$po_id'
                  and po_point > po_use_point
				order by po_id asc ";
    $result = sql_query($sql);
    for($i=0; $row=sql_fetch_array($result); $i++) {
        $point2 = $row['po_point'];
        $point3 = $row['po_use_point'];

        if(($point2 - $point3) > $point1) {
            $sql = " update shop_point
                        set po_use_point = po_use_point + '$point1'
					  where po_id = '{$row['po_id']}' ";
            sql_query($sql);
            break;
        } else {
            $point4 = $point2 - $point3;
            $sql = " update shop_point
                        set po_use_point = po_use_point + '$point4'
					  where po_id = '{$row['po_id']}' ";
            sql_query($sql);
            $point1 -= $point4;
        }
    }
}

// 사용포인트 삭제
function delete_use_point($mb_id, $point)
{
	$point1 = abs($point);
    $sql = " select po_id, po_use_point
                from shop_point
                where mb_id = '$mb_id'
                  and po_use_point > 0
                order by po_id desc ";
    $result = sql_query($sql);
    for($i=0; $row=sql_fetch_array($result); $i++) {
        $point2 = $row['po_use_point'];

        if($point2 > $point1) {
            $sql = " update shop_point
                        set po_use_point = po_use_point - '$point1'
					  where po_id = '{$row['po_id']}' ";
            sql_query($sql);
            break;
        } else {
            $sql = " update shop_point
                        set po_use_point = '0'
                      where po_id = '{$row['po_id']}' ";
            sql_query($sql);

            $point1 -= $point2;
        }
    }
}

// 포인트 내역 합계
function get_point_sum($mb_id)
{
    // 포인트합
    $sql = " select sum(po_point) as sum_po_point from shop_point where mb_id = '$mb_id' ";
    $row = sql_fetch($sql);

    return $row['sum_po_point'];
}

// 포인트 삭제
function delete_point($mb_id, $rel_table, $rel_id, $rel_action)
{
    $result = false;
    if($rel_table || $rel_id || $rel_action)
    {
        // 포인트 내역정보
        $sql = " select * from shop_point
                    where mb_id = '$mb_id'
                      and po_rel_table = '$rel_table'
                      and po_rel_id = '$rel_id'
                      and po_rel_action = '$rel_action' ";
        $row = sql_fetch($sql);

        if($row['po_point'] < 0) {
            $mb_id = $row['mb_id'];
            $po_point = abs($row['po_point']);

            delete_use_point($mb_id, $po_point);
        } else {
            if($row['po_use_point'] > 0) {
                insert_use_point($row['mb_id'], $row['po_use_point'], $row['po_id']);
            }
        }

        $result = sql_query(" delete from shop_point
                     where mb_id = '$mb_id'
                       and po_rel_table = '$rel_table'
                       and po_rel_id = '$rel_id'
                       and po_rel_action = '$rel_action' ", false);

        // po_mb_point에 반영
        $sql = " update shop_point
                    set po_mb_point = po_mb_point - '{$row['po_point']}'
                    where mb_id = '$mb_id'
                      and po_id > '{$row['po_id']}' ";
        sql_query($sql);

        // 포인트 내역의 합을 구하고
        $sum_point = get_point_sum($mb_id);

        // 포인트 UPDATE
        $sql = " update shop_member set point = '$sum_point' where id = '$mb_id' ";
        $result = sql_query($sql);
    }

    return $result;
}

// 검색어 인서트
function get_sql_search($word, $pt_id)
{
	if(!file_exists(TB_PATH."/DEMO")) {
		$word = trim(strip_tags($word));
		$pp_date = date('W');

		if(!$word) alert('검색키워드가 값이 넘어오지 않았습니다.');

		if(substr_count($word, "&#") > 50) {
			alert("내용에 올바르지 않은 코드가 다수 포함되어 있습니다.");
		}

		unset($value);
		$value['keyword'] = $word;
		$value['pp_date'] = $pp_date;
		$value['pt_id']   = $pt_id;

		// 키워드값이 없으면 새로 인서트한다.
		$row = sql_fetch("select * from shop_keyword where keyword='$word'");
		if(!$row['index_no']) {
			$value['scount'] = 1;
			insert_search("shop_keyword",$value);
		} else {
			if($row['pp_date'] < $pp_date) {
				// 이번주보다 이전 검색된 키워드면 지난주 검색 카운터를 업데이트하고 새로운 카운터 1을 증가
				$value['old_scount'] = $row['scount'];
				$value['scount'] = 1;
			} else if($row['pp_date'] == $pp_date) {
				// 이번주와 동일할때 검색 카운터만 1씩 증가
				$value['scount'] = $row['scount'] + 1;
			}

			update("shop_keyword",$value," where index_no='$row[index_no]'");
		}
	}
}

// 상품 이미지를 얻는다
function get_it_image($gs_id, $it_img, $wpx, $hpx=0, $img_id='')
{
    if(!$gs_id || !$wpx)
		return '';

	$gs = get_goods($gs_id, 'gcode');

	if(preg_match("/^(http[s]?:\/\/)/", $it_img) == false)
	{
		$file = TB_DATA_PATH."/goods/".$it_img;
		if(is_file($file) && $it_img)
		{
			$size = @getimagesize($file);
			$img_wpx  = $size[0];
			$img_hpx  = $size[1];
			$filepath = dirname($file);
			$filename = basename($file);

			if($img_wpx != $wpx && $img_hpx != $hpx) {
				if($img_wpx && !$hpx)
					$hpx = round(($wpx * $img_hpx) / $img_wpx);

				if($filename) {
					$savepath = TB_DATA_PATH."/goods/".$gs['gcode'];
					$size = @getimagesize($file);
					// Animated GIF는 썸네일 생성하지 않음
					if($size[2] == 1) {
						if(is_animated_gif($file))
							$savepath = TB_DATA_PATH."/goods";
					}
					$thumb = @thumbnail($filename, $filepath, $savepath, $wpx, $hpx, false, true, 'center', false, $um_value='80/0.5/3', false);
				}

				$file_url = rpc($savepath, TB_PATH, TB_URL);
			} else {
				$file_url = rpc($filepath, TB_PATH, TB_URL);
			}

			if($thumb) $img = '<img src="'.$file_url.'/'.$thumb.'" width="'.$wpx.'" height="'.$hpx.'"';
			else $img = '<img src="'.$file_url.'/'.$filename.'" width="'.$wpx.'" height="'.$hpx.'"';
		}
		else {
			$img = '<img src="'.TB_IMG_URL.'/noimage.gif" width="'.$wpx.'" height="'.$hpx.'"';
		}
	}
	else {

	    // 보안 서버 이슈 대응
        // - 예) http://rndmarket.co.kr/_image/allforlab/product/190712/data/S19-0390-1-1.gif => /_image/allforlab/product/190712/data/S19-0390-1-1.gif
        $http_host = preg_replace('/^www./','', $_SERVER['HTTP_HOST']);
        if( strstr($it_img, $http_host) ) {
            $it_img = "/".implode("/", array_slice(explode("/", $it_img), 3));;
        }

		$img = '<img src="'.$it_img.'" width="'.$wpx.'" height="'.$hpx.'"';
	}

	if($img_id) {
		$img .= ' '.$img_id;
	}
	$img .= '>';

	return $img;
}

// 상품 이미지 URL을 얻는다
function get_it_image_url($gs_id, $it_img, $wpx, $hpx=0)
{
    if(!$gs_id || !$wpx)
		return '';

	$gs = get_goods($gs_id, 'gcode');

	if(preg_match("/^(http[s]?:\/\/)/", $it_img) == false)
	{
		$file = TB_DATA_PATH."/goods/".$it_img;
		if(is_file($file) && $it_img)
		{
			$size = @getimagesize($file);
			$img_wpx  = $size[0];
			$img_hpx  = $size[1];
			$filepath = dirname($file);
			$filename = basename($file);

			if($img_wpx != $wpx && $img_hpx != $hpx) {
				if($img_wpx && !$hpx)
					$hpx = round(($wpx * $img_hpx) / $img_wpx);

				if($filename) {
					$savepath = TB_DATA_PATH."/goods/".$gs['gcode'];
					$size = @getimagesize($file);
					// Animated GIF는 썸네일 생성하지 않음
					if($size[2] == 1) {
						if(is_animated_gif($file))
							$savepath = TB_DATA_PATH."/goods";
					}
					$thumb = @thumbnail($filename, $filepath, $savepath, $wpx, $hpx, false, true, 'center', false, $um_value='80/0.5/3', false);
				}

				$file_url = rpc($savepath, TB_PATH, TB_URL);
			} else {
				$file_url = rpc($filepath, TB_PATH, TB_URL);
			}

			if($thumb) $img = $file_url.'/'.$thumb;
			else $img = $file_url.'/'.$filename;
		}
		else {
			$img = TB_IMG_URL.'/noimage.gif';
		}
	}
	else {
		$img = $it_img;
	}

	return $img;
}

// 주문상품 이미지를 얻는다
function get_od_image($od_id, $it_img, $wpx, $hpx=0)
{
    if(!$od_id || !$wpx)
		return '';

	if(preg_match("/^(http[s]?:\/\/)/", $it_img) == false)
	{
		$file = TB_DATA_PATH."/order/".substr($od_id,0,4)."/".$od_id."/".$it_img;
		if(is_file($file) && $it_img)
		{
            $size = @getimagesize($file);
			$img_wpx  = $size[0];
			$img_hpx  = $size[1];
			$filepath = dirname($file);
			$filename = basename($file);

			if($img_wpx != $wpx && $img_hpx != $hpx) {
				if($img_wpx && !$hpx)
					$hpx = round(($wpx * $img_hpx) / $img_wpx);

				if($filename) {
					$thumb = @thumbnail($filename, $filepath, $filepath, $wpx, $hpx, false, true, 'center', false, $um_value='80/0.5/3', false);
				}
			}

			$file_url = rpc($filepath, TB_PATH, TB_URL);
			if($thumb) $img = '<img src="'.$file_url.'/'.$thumb.'" width="'.$wpx.'" height="'.$hpx.'"';
			else $img = '<img src="'.$file_url.'/'.$filename.'" width="'.$wpx.'" height="'.$hpx.'"';
		}
		else {
			$img = '<img src="'.TB_IMG_URL.'/noimage.gif" width="'.$wpx.'" height="'.$hpx.'"';
		}
	}
	else {
		$img = '<img src="'.$it_img.'" width="'.$wpx.'" height="'.$hpx.'"';
	}

	$img .= '>';

	return $img;
}

// 원격지에 파일이 존재하는지 확인
// $filepath = "http://원격지 URL/파일명.png";
function remoteFileExist($filepath)
{
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL,$filepath);
	curl_setopt($ch, CURLOPT_NOBODY, 1);
	curl_setopt($ch, CURLOPT_FAILONERROR, 1);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	if(curl_exec($ch)!==false) {
		return true;
    } else {
        return false;
    }
}

function radio_checked($field, $checked, $value, $text='')
{
    if(!$text) $text = $value;

	$str = '<label><input type="radio" name="'.$field.'" value="'.$value.'"';
	if($value == $checked) $str.= ' checked="checked"';
    $str.= '> '.$text.'</label>'.PHP_EOL;

	return $str;
}

function check_checked($field, $checked, $value, $text='')
{
    if(!$text) $text = $value;

	$str = '<label><input type="checkbox" name="'.$field.'" value="'.$value.'"';
	if($value == $checked) $str.= ' checked="checked"';
    $str.= '> '.$text.'</label>'.PHP_EOL;

	return $str;
}

// 회원 레벨
function get_search_level($field, $value, $start_id=2, $end_id=9)
{
	$str = radio_checked($field, $value,  '', '전체');

	$sql = " select *
			   from shop_member_grade
			  where gb_no between {$start_id} and {$end_id}
			    and gb_name <> ''
			  order by gb_no desc ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$str .= radio_checked($field, $value, $row['gb_no'], $row['gb_name']);
	}

	return $str;
}

// 상품 진열,1
function display_itemtype($mb_id, $type, $rows='')
{
	$sql = " select a.*
			   from shop_goods a left join shop_goods_type b on (a.index_no=b.gs_id)
			  where b.mb_id = '$mb_id'
				and a.shop_state = '0'
				and a.isopen < '3'
				and find_in_set('$mb_id', a.use_hide) = '0'
				and b.it_type{$type} = '1'
			  order by a.index_no desc ";
	if($rows) $sql .= " limit $rows ";
	$result = sql_query($sql);

	return $result;
}

// 상품 진열,2
function query_itemtype($mb_id, $type, $sql_search, $sql_order)
{
	$sql = " select a.*
			   from shop_goods a left join shop_goods_type b on (a.index_no=b.gs_id)
			  where b.mb_id = '$mb_id'
				and a.shop_state = '0'
			    and a.isopen < '3'
			    and find_in_set('$mb_id', a.use_hide) = '0'
			    and b.it_type{$type} = '1'
				{$sql_search}
				{$sql_order} ";
	$result = sql_query($sql);

	return $result;
}

// 불법접근을 막도록 토큰을 생성하면서 토큰값을 리턴
function get_admin_token()
{
    $token = md5(uniqid(rand(), true));
    set_session('ss_admin_token', $token);

    return $token;
}

// POST로 넘어온 토큰과 세션에 저장된 토큰 비교
function check_admin_token()
{
    $token = get_session('ss_admin_token');
    set_session('ss_admin_token', '');

    if(!$token || !$_REQUEST['token'] || $token != $_REQUEST['token'])
        alert('올바른 방법으로 이용해 주십시오.', TB_URL);

    return true;
}

// POST로 넘어온 토큰과 세션에 저장된 토큰 비교
function ajax_admin_token()
{
    $token = get_session('ss_admin_token');
    set_session('ss_admin_token', '');

    if(!$token || !$_REQUEST['token'] || $token != $_REQUEST['token'])
		die("{\"error\":\"올바른 방법으로 이용해 주십시오.\"}");

    return true;
}

// 관리자 페이지 referer 체크
function admin_referer_check($return=false)
{
    $referer = trim($_SERVER['HTTP_REFERER']);
    if(!$referer) {
        $msg = '정보가 올바르지 않습니다.';

        if($return)
            return $msg;
        else
            alert($msg, TB_URL);
    }

    $p = @parse_url($referer);
    $host = preg_replace('/:[0-9]+$/', '', $_SERVER['HTTP_HOST']);

    if($host != $p['host']) {
        $msg = '올바른 방법으로 이용해 주십시오.';

        if($return)
            return $msg;
        else
            alert($msg, TB_URL);
    }
}

// 외부이미지 서버에 저장(방법,1)
function get_remote_image($url, $dir)
{
    $filename = '';

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_NOBODY, true);
    curl_exec ($ch);

    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if($http_code == 200)
	{
        $filename = basename($url);
        if(preg_match("/\.(gif|jpg|jpeg|png)$/i", $filename))
		{
			$pattern = "/[#\&\+\-%@=\/\\:;,'\"\^`~\|\!\?\*\$#<>\(\)\[\]\{\}]/";

			$filename = preg_replace("/\s+/", "", $filename);
			$filename = preg_replace($pattern, "", $filename);

			$filename = preg_replace_callback(
								  "/[가-힣]+/",
								  create_function('$matches', 'return base64_encode($matches[0]);'),
								  $filename);

			$filename = preg_replace($pattern, "", $filename);

            // 파일 다운로드
            $path = $dir.'/'.$filename;
            $fp = fopen ($path, 'w+');

            $ch = curl_init();
            curl_setopt( $ch, CURLOPT_URL, $url );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, false );
            curl_setopt( $ch, CURLOPT_BINARYTRANSFER, true );
            curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, 10 );
            curl_setopt( $ch, CURLOPT_FILE, $fp );
            curl_exec( $ch );
            curl_close( $ch );

            fclose( $fp );

            // 다운로드 파일이 이미지인지 체크
            if(is_file($path)) {
                $size = @getimagesize($path);
                if($size[2] < 1 || $size[2] > 3) {
                    @unlink($path);
                    $filename = '';
                } else {
                    @rename($path, $dir.'/'.$filename);
                    @chmod($dir.'/'.$filename, TB_FILE_PERMISSION);
                }
            }
        }
    }

    return $filename;
}

// 외부이미지 서버에 저장(방법,2)
function get_remote_image2($url, $dir)
{
	if(strpos($url,"http://") !== false)
	{
		$name_exchane = array('?','%');

		$newname = str_replace($name_exchane,'_',basename($url));

		$path = pathinfo($newname); //파일에 대한 정보를 얻음
		$ext = strtolower($path['extension']); //확장자를 연관배열에서 가져

		if(!is_dir($dir))
		{
			$oldmask = umask(0);
			@mkdir($dir, 0777);
			umask($oldmask);
		}

		$img_file = file_get_contents($url);
		$file_handler = fopen($dir."/".$newname,'wb');
		if(fwrite($file_handler,$img_file)==false){
			echo 'error';
		}
		fclose($file_handler);

		return $newname;
	}
}

// 출력멘트 리턴
function get_head_title($fild, $mb_id)
{
	global $config;

	$row = sql_fetch("select $fild from shop_partner where mb_id='$mb_id' ");
	if(!$row[$fild])
		$row[$fild] = $config[$fild];

	$str = $row[$fild];

	return $str;
}

// 이메일 주소 추출
function get_email_address($email)
{
    preg_match("/[0-9a-z._-]+@[a-z0-9._-]{4,}/i", $email, $matches);

    return $matches[0];
}

// 주소출력
function print_address($addr1, $addr2, $addr3, $addr4)
{
    $address = get_text(trim($addr1));
    $addr2   = get_text(trim($addr2));
    $addr3   = get_text(trim($addr3));

    if($addr4 == 'N') {
        if($addr2)
            $address .= ' '.$addr2;
    } else {
        if($addr2)
            $address .= ', '.$addr2;
    }

    if($addr3)
        $address .= ' '.$addr3;

    return $address;
}

// 관리자 체크.
function is_admin($grade='')
{
	global $member;

	$grade = $grade ? $grade : $member['grade'];

	switch($grade)
	{
		case '1' :
			return true;
			break;
		default :
			return false;
	}
}

// 공급사인가?
function is_seller($mb_id)
{
	global $config;

    if(!$mb_id) return '';

	$sr = sql_fetch("select state from shop_seller where mb_id = '$mb_id'");

    if($config['shop_state'] == 0 && $sr['state']) {
		return true;
	} else {
		return false;
	}
}

// 공급사 정보를 리턴
function get_seller($mb_id, $fileds='*')
{
	return sql_fetch("select $fileds from shop_seller where mb_id = TRIM('$mb_id')");
}

// 공급사 정보를 리턴
function get_seller_cd($code, $fileds='*')
{
	return sql_fetch("select $fileds from shop_seller where seller_code = TRIM('$code')");
}

// 공급업체 유일키검사
function code_uniqid()
{
	for($i=100001; $i<=999999; $i++) {
		$code = 'AP-'.sprintf("%06d", $i);

		// 주문서에 탈퇴한 공급사코드가 있는지 검사
		$sql = " select count(*) as cnt from shop_order where seller_id = '$code' ";
		$row = sql_fetch($sql);
		if($row['cnt']) // 있다면 건너뛴다.
			continue;

		$sql = " select count(*) as cnt from shop_seller where seller_code = '$code' ";
		$row = sql_fetch($sql);
		if(!$row['cnt']) { // 없다면 빠진다.
			return $code;
			break;
		}

        // count 하지 못했으면 일정시간 쉰다음 다시 유일키를 검사한다.
        usleep(10000); // 100분의 1초를 쉰다
	}

	return '';
}

// 장바구니에 담긴 상품수
function get_cart_count()
{
    $where_add = get_cart_mode('select','','where');

    $sql = " select * from shop_cart where ct_select='0' {$where_add} group by gs_id ";

	$result = sql_query($sql);
	$cart_count = sql_num_rows($result);

    return (int)$cart_count;
}

// 장바구니에 담긴 상품 index_no 조회
function get_cart_info($fields)
{
    $where_add = get_cart_mode('select','','where');

    $sql = " select {$fields} from shop_cart where ct_select='0' {$where_add} group by gs_id ";

    $result = sql_query($sql);
    while ($item = mysqli_fetch_assoc($result))
    {
        $r[] = $item[$fields];
    }

    return implode(",",$r);
}

// 장바구니 계정 & 쿠키 사용 여부
function get_cart_mode($mode,$gs_id='',$get_sql='')
{
    global $set_cart_id,$member;

    if($mode=='select')
    {
        if($member['id']!='')
        {
            #계정 확인
            $where_add = " AND mb_id='{$member['id']}' ";
        }else{
            #쿠키 확인
            $where_add = " AND ct_direct='$set_cart_id' AND mb_id = '' ";
        }

        if($get_sql=='where')
        {
            $r = $where_add;
        }else{
            $r = " select * from shop_cart where ct_select='0' {$where_add} ";
        }

    }

    if($mode=='delete')
    {
        if($member['id']!='')
        {
            #장바구니 계정확인 삭제
            $where_add = " AND mb_id='{$member['id']}' ";
        } else {
            #장바구니 쿠키확인 삭제
            $where_add = " AND ct_direct='$set_cart_id' AND mb_id = '' ";
        }

        $r = " delete from shop_cart where ct_select='0' {$where_add} ";
    }

    if($gs_id)
    {
        $r .= " AND {$gs_id} ";
    }

    return $r;
}

// 로그인 후 이동할 URL
function login_url($url='')
{
    if(!$url) $url = TB_URL;

    return urlencode(clean_xss_tags(urldecode($url)));
}

// 금액 표시
function display_price($price)
{
	return number_format($price, 0).'원';
}

// 금액 표시
function display_price2($price)
{
	return '<p class="spr">'.number_format($price).'<span>원</span></p>';
}

// 포인트 표시
function display_point($price)
{
	return number_format($price, 0).'P';
}

// 수량 표시
function display_qty($price)
{
	return number_format($price, 0).'개';
}

// 품절상품인지 체크
function is_soldout($gs_id)
{
	// 상품정보
	$sql = " select isopen, stock_qty, stock_mod from shop_goods where index_no = '$gs_id' ";
	$gs = sql_fetch($sql);

	if(($gs['stock_mod'] && $gs['stock_qty']==0) || $gs['isopen'] > 1)
		return true;

	$count = 0;
	$soldout = false;

	// 상품에 선택옵션 있으면..
	$sql = " select count(*) as cnt from shop_goods_option where gs_id = '$gs_id' and io_type = '0' ";
	$row = sql_fetch($sql);

	if($row['cnt']) {
		$sql = " select io_id, io_type, io_stock_qty
					from shop_goods_option
					where gs_id = '$gs_id'
					  and io_type = '0'
					  and io_use = '1' ";
		$result = sql_query($sql);

		for($i=0; $row=sql_fetch_array($result); $i++) {
			// 주문대기수량
			$sql = " select SUM(ct_qty) as qty
					   from shop_cart
					  where gs_id = '$gs_id'
						and io_id = '$io_id'
						and io_type = '$type'
						and ct_select = '0' ";
			$sum = sql_fetch($sql);

			// 옵션 재고수량
			$stock_qty = get_option_stock_qty($gs_id, $row['io_id'], $row['io_type']);

			if($stock_qty - $sum['qty'] <= 0)
				$count++;
		}

		// 모든 선택옵션 품절이면 상품 품절
		if($i == $count)
			$soldout = true;
	} else {
		// 주문대기수량
		$sql = " select SUM(ct_qty) as qty
				   from shop_cart
				  where gs_id = '$gs_id'
					and io_id = '$io_id'
					and io_type = '$type'
					and ct_select = '0' ";
		$sum = sql_fetch($sql);

		// 상품 재고수량
		$stock_qty = get_it_stock_qty($gs_id);

		if($stock_qty - $sum['qty'] <= 0)
			$soldout = true;
	}

	return $soldout;
}

// 상품의 재고 (창고재고수량)
function get_it_stock_qty($gs_id)
{
	$sql = " select stock_qty,stock_mod from shop_goods where index_no = '$gs_id' ";
	$row = sql_fetch($sql);
	$jaego = (int)$row['stock_qty'];

	if(!$row['stock_mod']) {
		$jaego = 999999999;
	}

	return $jaego;
}

// 옵션의 재고 (창고재고수량)
function get_option_stock_qty($gs_id, $io_id, $type)
{
	$sql = " select io_stock_qty
			   from shop_goods_option
			  where gs_id = '$gs_id'
				and io_id = '$io_id'
				and io_type = '$type'
				and io_use = '1' ";
	$row = sql_fetch($sql);
	$jaego = (int)$row['io_stock_qty'];

	return $jaego;
}

/*************************************************************************
**
**  주문서관련 함수 모음
**
*************************************************************************/

// 주문서별 실입금액 합계
function get_order_ipgum($od_id)
{
	$sql = " select SUM(use_price) as useprice from shop_order where od_id = '$od_id' ";
	$row = sql_fetch($sql);

	return $row;
}

// 주문서별 총합계
function get_order_spay($od_id, $sql_search='')
{
	$sql = " select SUM(goods_price) as price,
					SUM(baesong_price) as baesong,
					SUM(goods_price + baesong_price) as buyprice,
					SUM(supply_price) as supply,
					SUM(cancel_price) as cancel,
					SUM(refund_price) as refund,
					SUM(coupon_price) as coupon,
					SUM(use_point) as usepoint,
					SUM(use_price) as useprice,
					SUM(sum_qty) as qty,
					SUM(sum_point) as point
			   from shop_order
			  where od_id = '$od_id'
				{$sql_search} ";
	$row = sql_fetch($sql);

	return $row;
}

// 주문정보 주문번호
function get_order($order_id, $fileds='*')
{
	if(strlen($order_id) < 14)
		$sql_where = " where od_no = '$order_id'"; // 주문일련번호
	else
		$sql_where = " where od_id = '$order_id'"; // 주문번호

	return sql_fetch(" select $fileds from shop_order {$sql_where} ");
}

// 복합과세
function comm_tax_flag($od_id)
{
	$comm_tax_mny	= 0; // 과세금액
	$comm_vat_mny	= 0; // 부가세
	$comm_free_mny	= 0; // 면세금액
	$tot_tax_mny	= 0;
	$tot_baesong	= 0;

	$sql = " select * from shop_order where od_id = '$od_id' order by index_no ";
	$result = sql_query($sql);
	while($row = sql_fetch_array($result)) {
		if($row['gs_notax']) // 과세
			$tot_tax_mny += ($row['use_price'] - $row['baesong_price']);
		else // 면세
			$comm_free_mny += ($row['use_price'] - $row['baesong_price']);

		$tot_baesong += $row['baesong_price'];
	}

	$comm_tax_mny = round(($tot_tax_mny + $tot_baesong) / 1.1);
	$comm_vat_mny = ($tot_tax_mny + $tot_baesong) - $comm_tax_mny;

	$info = array();
	$info['comm_tax_mny']  = $comm_tax_mny;
	$info['comm_vat_mny']  = $comm_vat_mny;
	$info['comm_free_mny'] = $comm_free_mny;

	return $info;
}

// 주문서 삭제
function order_delete($od_no, $od_id)
{
	$sql = " select * from shop_order where od_no = '$od_no' and od_id = '$od_id' ";
	$od = sql_fetch($sql);
	if(!$od) return;

	// 신규가입 쿠폰일경우 다시 사용할 수 있도록 돌려준다.
	subtract_coupon_log($od_no);

	// 상품옵션별재고 또는 상품재고에 더하기
	add_io_stock($od_no, $od_id);

	// 주문취소 회원의 포인트를 되돌려 줌
	if($od['mb_id'] && $od['use_point']) {
		insert_point($od['mb_id'], $od['use_point'], "주문번호 {$od_id} ({$od_no}) 주문취소");
	}

	// 삭제
	sql_query(" delete from shop_cart where od_no = '$od_no' and od_id = '$od_id' ");
	sql_query(" delete from shop_order where od_no = '$od_no' and od_id = '$od_id' ");
}

// 신규가입 쿠폰일경우 다시 사용할 수 있도록 돌려준다.
function subtract_coupon_log($od_no)
{
	$sql = " select lo_id from shop_coupon_log where od_no = '$od_no' and cp_type = '5' ";
	$row = sql_fetch($sql);
	if($row['lo_id']) {
		$sql = " update shop_coupon_log
					set mb_use = '0'
					  , od_no = ''
					  , cp_udate = ''
				  where lo_id = '{$row['lo_id']}' ";
		sql_query($sql, FALSE);
	}
}

// 상품옵션별재고 또는 상품재고에 더하기
function add_io_stock($od_no, $od_id)
{
	$sql = " select * from shop_cart where od_no = '$od_no' and od_id = '$od_id' ";
	$res = sql_query($sql);
	while($ct=sql_fetch_array($res)) {
		if($ct['io_id']) { // 옵션 : 재고수량 증가
			$sql = " update shop_goods_option
						set io_stock_qty = io_stock_qty + '{$ct['ct_qty']}'
					  where io_id = '{$ct['io_id']}'
						and gs_id = '{$ct['gs_id']}'
						and io_type = '{$ct['io_type']}'
						and io_stock_qty <> '999999999' ";
			sql_query($sql, FALSE);
		} else { // 상품 : 재고수량 증가
			$sql = " update shop_goods
						set stock_qty = stock_qty + '{$ct['ct_qty']}'
					  where index_no = '{$ct['gs_id']}'
						and stock_mod = '1' ";
			sql_query($sql, FALSE);
		}
	}
}

// 상품 판매수량 반영
function add_sum_qty($gs_id)
{
	// 배송완료 된 상품만 수량을 더한다.
	$sql = " select sum(sum_qty) as it_sum_qty from shop_order where gs_id = '$gs_id' and dan IN(5,8) ";
	$row = sql_fetch($sql);

	$sql = " update shop_goods set sum_qty = '{$row['it_sum_qty']}' where index_no = '$gs_id' ";
	sql_query($sql);
}

// '배송완료' > '구매확정' 상태로 변경
function change_status_final($od_no)
{
	$sql = " update shop_order
				set user_ok = '1'
				  , user_date = '".TB_TIME_YMDHIS."'
			  where od_no = '$od_no'
				and user_ok = '0'
				and dan = '5' ";
	sql_query($sql);
}

// '구매확정' > '구매확정취소' 상태로 변경
function change_status_final_cancel($od_no)
{
	$sql = " update shop_order
				set user_ok = '0'
				  , user_date = '0000-00-00 00:00:00'
			  where od_no = '$od_no'
				and user_ok = '1'
				and dan = '5' ";
	sql_query($sql);
}

// '입금대기' 상태로 변경
function change_order_status_1($od_no)
{
	$sql = " update shop_order
				set dan = '1'
				  , receipt_time = '0000-00-00 00:00:00'
			  where od_no = '$od_no'
			    and dan = '2' ";
	sql_query($sql);
}

// '입금완료' 상태로 변경 (일련번호)
function change_order_status_2($od_no)
{
	$sql = " update shop_order
				set dan = '2'
				  , receipt_time = '".TB_TIME_YMDHIS."'
			  where od_no = '$od_no'
			    and dan = '1' ";
	sql_query($sql);
}

// '입금완료' 상태로 변경 (주문번호)
function change_order_status_ipgum($od_id)
{
	$sql = " update shop_order
				set dan = '2'
				  , receipt_time = '".TB_TIME_YMDHIS."'
			  where od_id = '$od_id'
			    and dan = '1' ";
	sql_query($sql);
}

// '배송준비' 상태로 변경
function change_order_status_3($od_no, $delivery='', $delivery_no='')
{
	$sql = " update shop_order
				set dan = '3'
				  , delivery = '$delivery'
				  , delivery_no = '$delivery_no'
			  where od_no = '$od_no' ";
	sql_query($sql);
}

// '배송중' 상태로 변경
function change_order_status_4($od_no, $delivery='', $delivery_no='')
{
	$sql = " update shop_order
				set dan = '4'
				  , delivery_date = '".TB_TIME_YMDHIS."'
				  , delivery = '$delivery'
				  , delivery_no = '$delivery_no'
			  where od_no = '$od_no' ";
	sql_query($sql);
}

// '배송완료' 상태로 변경
function change_order_status_5($od_no)
{
	global $config;

	$od = get_order($od_no);

	$sql = " update shop_order
				set dan = '5'
				  , invoice_date = '".TB_TIME_YMDHIS."' ";
	if(is_null_time($od['delivery_date'])) { // 배송일이 비었나?
		$sql .= " , delivery_date = '".TB_TIME_YMDHIS."' ";
	}
	$sql .= " where od_no = '$od_no' ";
	sql_query($sql);

	// 상품 판매수량 반영
	add_sum_qty($od['gs_id']);

	// 상품정보
	$gs = unserialize($od['od_goods']);

	// 주문완료 후 배송완료시에 쿠폰발행
	if($config['coupon_yes'] && !$gs['use_aff'] && $od['mb_id']) {
		$member = get_member($od['mb_id']);
		$cp_used = is_used_coupon('2', $od['gs_id']);
		if($cp_used) {
			$cp_id = explode(",", $cp_used);
			for($g=0; $g<count($cp_id); $g++) {
				if($cp_id[$g]) {
					$cp = sql_fetch("select * from shop_coupon where cp_id='{$cp_id[$g]}'");
					insert_used_coupon($od['mb_id'], $od['name'], $cp);
				}
			}
		}
	}

	// 포인트 적립
	if($od['mb_id'] && $od['sum_point'] > 0) {
		insert_point($od['mb_id'], $od['sum_point'], "주문번호 {$od['od_id']} ({$od_no}) 배송완료", "@delivery", $od['mb_id'], "{$od['od_id']},{$od_no}");
	}

	// 가맹점 판매수수료 적립
	insert_sale_pay($od['pt_id'], $od, $gs);
}

// '주문취소' 상태로 변경
function change_order_status_6($od_no)
{
	$od = get_order($od_no);

	$sql = " update shop_order
				set dan = '6'
				  , cancel_price = (goods_price + baesong_price)
				  , cancel_date = '".TB_TIME_YMDHIS."'
			  where od_no = '$od_no' ";
	sql_query($sql);

	// 신규가입 쿠폰일경우 다시 사용할 수 있도록 돌려준다.
	subtract_coupon_log($od_no);

	// 상품옵션별재고 또는 상품재고에 더하기
	add_io_stock($od_no, $od['od_id']);

	// 상품 판매수량 반영
	add_sum_qty($od['gs_id']);

	// 사용한 회원의 포인트를 반환
	if($od['mb_id'] && $od['use_point']) {
		insert_point($od['mb_id'], $od['use_point'], "주문번호 {$od['od_id']} ({$od_no}) 주문취소");
	}
}

// '배송후 반품' 상태로 변경
function change_order_status_7($od_no)
{
	$od = get_order($od_no);

	$sql = " update shop_order
				set dan = '7'
				  , return_date = '".TB_TIME_YMDHIS."'
			  where od_no = '$od_no' ";
	sql_query($sql);

	// 판매수수료 환수처리
	$sql = " select *
			   from shop_partner_pay
			  where pp_rel_id = '{$od_no}'
				and pp_rel_action = '{$od['od_id']}' ";
	$res = sql_query($sql);
	while($row=sql_fetch_array($res)) {
		delete_pay($row['mb_id'], "sale", $row['pp_rel_id'], $row['pp_rel_action']);
	}

	// 상품 판매수량 반영
	add_sum_qty($od['gs_id']);

	// 신규가입 쿠폰일경우 다시 사용할 수 있도록 돌려준다.
	subtract_coupon_log($od_no);

	// 상품옵션별재고 또는 상품재고에 더하기
	add_io_stock($od_no, $od['od_id']);

	// 적립된 회원의 포인트를 뺀다
	if($od['mb_id'] && $od['sum_point']) {
		delete_point($od['mb_id'], "@delivery", $od['mb_id'], "{$od['od_id']},{$od_no}");
	}

	// 사용한 회원의 포인트를 되돌려 줌
	if($od['mb_id'] && $od['use_point']) {
		insert_point($od['mb_id'], $od['use_point'], "주문번호 {$od['od_id']} ({$od_no}) 반품");
	}
}

// '배송후 교환' 상태로 변경
function change_order_status_8($od_no)
{
	$sql = " update shop_order
				set dan = '8'
				  , change_date = '".TB_TIME_YMDHIS."'
			  where od_no = '$od_no' ";
	sql_query($sql);
}

// '배송전 환불' 상태로 변경
function change_order_status_9($od_no)
{
	$od = get_order($od_no);

	$sql = " update shop_order
				set dan = '9'
				  , refund_date = '".TB_TIME_YMDHIS."'
			  where od_no = '$od_no' ";
	sql_query($sql);

	// 신규가입 쿠폰일경우 다시 사용할 수 있도록 돌려준다.
	subtract_coupon_log($od_no);

	// 상품옵션별재고 또는 상품재고에 더하기
	add_io_stock($od_no, $od['od_id']);

	// 상품 판매수량 반영
	add_sum_qty($od['gs_id']);

	// 사용한 회원의 포인트를 반환
	if($od['mb_id'] && $od['use_point']) {
		insert_point($od['mb_id'], $od['use_point'], "주문번호 {$od['od_id']} ({$od_no}) 환불");
	}
}

// 배송조회버튼 생성
function get_delivery_inquiry($company, $invoice, $class='')
{
	if(!$company || !$invoice)
		return '';

	// 배송정보 (예:배송회사|배송추적URL)
	list($com, $url) = explode('|', $company);

	$str = '';
	if($com && $url) {
		$str .= '<a href="'.$url.$invoice.'" target="_blank"';
		if($class) $str .= ' class="'.$class.'"';
		$str .='>배송조회</a>';
	}

	return $str;
}

// 게시판설정
function get_board($boardid)
{
	return sql_fetch("select * from shop_board_conf where index_no='$boardid'");
}

// 카테고리
function get_cate($catecode)
{
	return sql_fetch("select * from shop_cate where catecode='$catecode'");
}

// 카테고리 이름
function get_catename($code)
{
	global $tb;

	$row = sql_fetch("select catename from {$tb['category_table']} where catecode='$code'");
	return $row['catename'];
}

// 무료배송 검사
function is_free_baesong($row)
{
	global $config;

	$is_free = false;

	if($row['sc_type'] == 0) {
		if($row['mb_id'] == 'admin') { // 본사
			$delivery_method = $config['delivery_method'];
		} else { // 가맹점 및 공급사
			if($row['use_aff'])
				$sr = get_partner($row['mb_id'], 'delivery_method');
			else
				$sr = get_seller_cd($row['mb_id'], 'delivery_method');

			$delivery_method = $sr['delivery_method'];
		}

		if($delivery_method == '1')
			$is_free = true;
	} else if($row['sc_type'] == 1) {
        $is_free = true;
    }

	return $is_free;
}

// 배송비 구함
function get_item_sendcost($sell_price)
{
	global $row, $gs, $config, $sr;

	$info = array();

	// 공통설정
	if($gs['sc_type']=='0') {
		if($gs['mb_id'] == 'admin') { // 본사
			$delivery_method  = $config['delivery_method'];
			$delivery_price	  = $config['delivery_price'];
			$delivery_price2  = $config['delivery_price2'];
			$delivery_minimum = $config['delivery_minimum'];
		} else { // 가맹점 및 공급사
			$delivery_method  = $sr['delivery_method'];
			$delivery_price	  = $sr['delivery_price'];
			$delivery_price2  = $sr['delivery_price2'];
			$delivery_minimum = $sr['delivery_minimum'];
		}

		switch($delivery_method) { // 배송정책
			case '1': // 무료배송
				$info['price'] = 0;
				$info['content'] = '무료';
				break;
			case '2': // 착불배송
				$info['price'] = 0;
				$info['content'] = '착불';
				break;
			case '3': // 유료배송
				$info['price'] = $delivery_price;
				$info['content'] = display_price($delivery_price);
				break;
			case '4': // 조건부무료배송
				if($sell_price >= $delivery_minimum) {
					$info['price'] = 0;
					$info['content'] = '무료';
				} else {
					$info['price'] = $delivery_price2;
					$info['content'] = display_price($delivery_price2);
				}
				break;
		}

		// 조건부무료배송 과 유료배송일때
		if(in_array($delivery_method, array('3','4'))) {
			if($gs['sc_method'] == 1) { // 착불
				$info['price'] = 0;
				$info['content'] = '착불';
			} else if($gs['sc_method'] == 2) { // 사용자선택
				if($row['ct_send_cost'] == 1)  {// 착불
					$info['price'] = 0;
					$info['content'] = '착불';
				}
			}
		}
	}
	else { // 개별설정
		switch($gs['sc_type']) {
			case '1': // 무료배송
				$info['price'] = 0;
				$info['content'] = '무료';
				break;
			case '2': // 조건부무료배송
				if($sell_price >= $gs['sc_minimum']) {
					$info['price'] = 0;
					$info['content'] = '무료';
				} else {
					$info['price'] = $gs['sc_amt'];
					$info['content'] = display_price($gs['sc_amt']);
				}
				break;
			case '3': // 유료배송
				$info['price'] = $gs['sc_amt'];
				$info['content'] = display_price($gs['sc_amt']);
				break;
		}

		// 조건부무료배송 과 유료배송일때
		if(in_array($gs['sc_type'], array('2','3'))) {
			if($gs['sc_method'] == 1) { // 착불
				$info['price'] = 0;
				$info['content'] = '착불';
			} else if($gs['sc_method'] == 2) { // 사용자선택
				if($row['ct_send_cost'] == 1) { // 착불
					$info['price'] = 0;
					$info['content'] = '착불';
				}
			}
		}
	}

	$arr = array();
	$arr[] = $gs['mb_id'];
	$arr[] = $gs['sc_each_use']?'개별':'묶음';
	$arr[] = $info['price'];
	$info['pattern'] = implode('|', $arr);

	return $info;
}

// 배송비를 구분자로 나눔 (주문폼으로 넘기기위한 작업)
function get_tune_sendcost($com_array, $val_array)
{
	global $item_sendcost;

	if(!$item_sendcost)
		return;

	$com = array();
	$val = array();
	for($i=0; $i<count($com_array); $i++) {
		if(is_array($com_array[$i])) {
			for($j=0; $j<count($com_array[$i]); $j++) {
				$com[] = $com_array[$i][$j];
				$val[] = $val_array[$i][$j];
			}
		} else {
			$com[] = $com_array[$i];
			$val[] = $val_array[$i];
		}
	}

	// 배열 재정렬
	$dlcomb = array_combine($com,$val);

	// 빈 배열을 채움.
	$dltune = array();
	for($i=0; $i<count($item_sendcost); $i++) {
		if($dlcomb[$i]) {
			$dltune[$i] = $dlcomb[$i];
		} else {
			$dltune[$i] = 0;
		}
	}

	return implode('|', $dltune);
}

// 장바구니의 정보를 리턴.
// $idnex는 장바구니 주키 값
function get_cart_id($cart_id)
{
	return sql_fetch("select * from shop_cart where index_no='$cart_id'");
}

// 장바구니의 정보를 리턴.
// $od_no는 장바구니 주문번호
function get_shop_cart($od_no)
{
	return sql_fetch("select * from shop_cart where od_no='$od_no'");
}

// 상품 정보의 배열을 리턴
function get_goods($gs_id)
{
	return sql_fetch(" select * from shop_goods where index_no='$gs_id'" );
}

// 상품명과 건수를 반환
function get_full_name($cart_id)
{
    // 상품명만들기
    $row = sql_fetch(" select a.gs_id, b.gname from shop_cart a, shop_goods b where a.gs_id = b.index_no and a.od_id = '$cart_id' order by a.index_no limit 1 ");
    // 상품명에 "(쌍따옴표)가 들어가면 오류 발생함
    $goods['gs_id'] = $row['gs_id'];
    $goods['full_name']= $goods['name'] = addslashes($row['gname']);
    // 특수문자제거
    $goods['full_name'] = preg_replace ("/[ #\&\+\-%@=\/\\\:;,\.'\"\^`~\_|\!\?\*$#<>()\[\]\{\}]/i", "",  $goods['full_name']);

    // 상품건수
    $row = sql_fetch(" select count(*) as cnt from shop_cart where od_id = '$cart_id' ");
    $cnt = $row['cnt'] - 1;
    if($cnt)
        $goods['full_name'] .= ' 외 '.$cnt.'건';
    $goods['count'] = $row['cnt'];

    return $goods;
}

// 별
function get_star($score)
{
    $star = round($score);
    if($star > 5) $star = 5;
    else if($star < 0) $star = 0;

    return $star;
}

// 별 이미지
function get_star_image($gs_id)
{
	global $default, $pt_id;

    $sql = "select (SUM(score) / COUNT(*)) as avg
	          from shop_goods_review
			 where gs_id = '$gs_id' ";
	if($default['de_review_wr_use']) {
		$sql .= " and pt_id = '$pt_id' ";
	}
    $row = sql_fetch($sql);

    return (int)get_star($row['avg']);
}

// 상품 브랜드주키 정보의 배열을 리턴
function get_brand($br_id)
{
	$br = sql_fetch("select br_id,br_name from shop_brand where br_id='$br_id'" );
	if($br['br_id'])
		$br_name = $br['br_name'];

	return $br_name;
}

// 모든쿼리 공용
function get_sql_precompose($sql_option='')
{
	global $tb, $pt_id, $config, $auth_good;

	$sql_search = " AND c.p_hide = '0' ";

	// 본사카테고리 고정일때
	if($config['pf_auth_cgy'] == 1)
		$sql_search .= " AND c.p_oper = 'y' ";

	// 가맹점 판매권한 체크
	if($auth_good) {
		$sql_partner = " OR a.mb_id = '$pt_id'";
	}

	$sql  = " FROM shop_goods a
				   LEFT JOIN shop_goods_cate b ON ( a.index_no = b.gs_id )
				   LEFT JOIN {$tb['category_table']} c ON ( b.gcate = c.catecode )
			 WHERE a.shop_state = '0'
			   AND a.isopen < '3'
			   AND c.u_hide = '0'
			   AND (a.use_aff = '0'{$sql_partner})
			   AND find_in_set('$pt_id', a.use_hide) = '0'
			   {$sql_search}
			   {$sql_option} ";

	return $sql;
}

// 상품 가격정보의 배열을 리턴
function get_sale_price($gs_id)
{
	global $member;

	$gb = sql_fetch("select * from shop_member_grade where gb_no = '$member[grade]'");
	$gs = sql_fetch("select goods_price,use_aff from shop_goods where index_no = '$gs_id'");

	$price = $gs['goods_price'];

	if($gb['gb_sale'] > 0 && $member['id'] && !$gs['use_aff']) {
		if($gb['gb_sale_rate'] == 1) // 금액으로 할인
			$price = $gs['goods_price'] - $gb['gb_sale'];
		else // 퍼센트로 할인
			$price = $gs['goods_price'] - (($gs['goods_price'] / 100) * $gb['gb_sale']);

		if(strlen($price) > 2 && $gb['gb_sale_unit'])
			$price = floor((int)$price/(int)$gb['gb_sale_unit']) * (int)$gb['gb_sale_unit'];
	}

	return $price;
}

// 시중가등 가격을 보이기위한 검사
function is_uncase($gs_id)
{
	global $member, $is_member;

	$gs = sql_fetch("select index_no,price_msg,buy_level,buy_only from shop_goods where index_no = '$gs_id'");

	if(is_soldout($gs['index_no'])) {
		// 재고가 한정상태이고 재고가 없을때, 품절상태일때..
		return true;
	} else {
		if($gs['price_msg']) {
			// 가격대체 문구
			return true;
		} else if($gs['buy_only'] == 1 && $member['grade'] > $gs['buy_level']) {
			// 특정 레벨이상 가격공개이고 레벨이 해당되지 않을때 가격을 감춤
			return true;
		} else if($gs['buy_only'] == 0 && $member['grade'] > $gs['buy_level']) {
			// 가격은 모두 공개이지만 레벨이 해당되지 않을때
			if(!$is_member)
				return true;
			else
				return false;
		} else {
			return false;
		}
	}
}

// 로고 url
function display_logo_url($fld='basic_logo')
{
	global $pt_id;

	$row = sql_fetch("select $fld from shop_logo where mb_id='$pt_id'");
	if(!$row[$fld] && $pt_id != 'admin') {
		$row = sql_fetch("select $fld from shop_logo where mb_id='admin'");
	}

	$file = TB_DATA_PATH.'/banner/'.$row[$fld];
	if(is_file($file) && $row[$fld]) {
		return rpc($file, TB_PATH, TB_URL);
	}

	return '';
}

// 게시판의 다음글 번호를 얻는다.
function get_next_num($table)
{
	// 가장 큰 번호를 얻어
	$sql = " select max(index_no) as max_num from $table ";
	$row = sql_fetch($sql);
	// 가장 큰 번호에 1을 더해서 넘겨줌
	return (int)($row['max_num'] + 1);
}

// 다음글 번호를 얻는다.
function get_next_wr_num($table, $val, $option='')
{
	// 가장 큰 번호를 얻어
	$sql = " select max($val) as max_num from $table $option ";
	$row = sql_fetch($sql);
	// 가장 큰 번호에 1을 더해서 넘겨줌
	return (int)($row['max_num'] + 1);
}

// 임시주문 데이터로 주문 필드 생성
function make_order_field($data, $exclude)
{
    $field = '';

    foreach($data as $key=>$value) {
        if(!empty($exclude) && in_array($key, $exclude))
            continue;

        if(is_array($value)) {
            foreach($value as $k=>$v) {
                $field .= '<input type="hidden" name="'.$key.'['.$k.']" value="'.$v.'">'.PHP_EOL;
            }
        } else {
            $field .= '<input type="hidden" name="'.$key.'" value="'.$value.'">'.PHP_EOL;
        }
    }

    return $field;
}

// 은행정보 : select 형태로 얻음
function get_bank_select($name, $opt='')
{
	$str = "<select name=\"{$name}\" id=\"{$name}\"";
    if($opt) $str .= " $opt";
    $str .= ">\n";
	$str.= "<option value=''>선택</option>\n";
	$str.= "<option value='경남은행'>경남은행</option>\n";
	$str.= "<option value='광주은행'>광주은행</option>\n";
	$str.= "<option value='국민은행'>국민은행</option>\n";
	$str.= "<option value='기업은행'>기업은행</option>\n";
	$str.= "<option value='농협'>농협</option>\n";
	$str.= "<option value='대구은행'>대구은행</option>\n";
	$str.= "<option value='도이치뱅크'>도이치뱅크</option>\n";
	$str.= "<option value='부산은행'>부산은행</option>\n";
	$str.= "<option value='산업은행'>산업은행</option>\n";
	$str.= "<option value='상호저축은행'>상호저축은행</option>\n";
	$str.= "<option value='새마을금고'>새마을금고</option>\n";
	$str.= "<option value='수협중앙회'>수협중앙회</option>\n";
	$str.= "<option value='신용협동조합'>신용협동조합	</option>\n";
	$str.= "<option value='신한은행'>신한은행</option>\n";
	$str.= "<option value='외환은행'>외환은행</option>\n";
	$str.= "<option value='우리은행'>우리은행</option>\n";
	$str.= "<option value='우체국'>우체국</option>\n";
	$str.= "<option value='전북은행'>전북은행</option>\n";
	$str.= "<option value='제주은행'>제주은행</option>\n";
	$str.= "<option value='하나은행'>하나은행</option>\n";
	$str.= "<option value='한국시티은행'>한국시티은행</option>\n";
	$str.= "<option value='HSBC'>HSBC</option>\n";
	$str.= "<option value='SC제일은행'>SC제일은행</option>\n";
	$str.= "</select>";

	return $str;
}

// 취소/반품/교환 : select 형태로 얻음
function get_cancel_select($name, $opt='')
{
	$str = "<select name=\"{$name}\"";
    if($opt) $str .= " $opt";
    $str .= ">\n";
	$str.= "<option value=''>선택</option>\n";
	$str.= "<option value='고객변심(스타일)'>고객변심(스타일)</option>\n";
	$str.= "<option value='출하전 취소(주문서변경)'>출하전 취소(주문서변경)</option>\n";
	$str.= "<option value='화면과 다름(퀄리티)'>화면과 다름(퀄리티)</option>\n";
	$str.= "<option value='퀄리티 불만'>퀄리티 불만</option>\n";
	$str.= "<option value='중복주문'>중복주문</option>\n";
	$str.= "<option value='A/S관련'>A/S관련</option>\n";
	$str.= "<option value='재결제'>재결제</option>\n";
	$str.= "<option value='품절'>품절</option>\n";
	$str.= "<option value='상품불량'>상품불량</option>\n";
	$str.= "<option value='결제 오류'>결제 오류</option>\n";
	$str.= "<option value='시스템오류'>시스템오류</option>\n";
	$str.= "<option value='오배송'>오배송</option>\n";
	$str.= "<option value='출하전 취소(재주문)'>출하전 취소(재주문)</option>\n";
	$str.= "<option value='출하전 취소(변심환불)'>출하전 취소(변심환불)</option>\n";
	$str.= "<option value='배송중분실'>배송중분실</option>\n";
	$str.= "<option value='기타'>기타</option>\n";
	$str.= "<option value='고객센터 불만족'>고객센터 불만족</option>\n";
	$str.= "<option value='업무 처리 지연'>업무 처리 지연</option>\n";
	$str.= "<option value='교환제품 품절'>교환제품 품절</option>\n";
	$str.= "<option value='사이즈 맞지 않음(단순)'>사이즈 맞지 않음(단순)</option>\n";
	$str.= "<option value='화면과 다름(색상)'>화면과 다름(색상)</option>\n";
	$str.= "<option value='화면과 다름(디자인)'>화면과 다름(디자인)</option>\n";
	$str.= "<option value='화면과 다름(재질)'>화면과 다름(재질)</option>\n";
	$str.= "<option value='상세 실측 오류'>상세 실측 오류</option>\n";
	$str.= "<option value='고객오류'>고객오류</option>\n";
	$str.= "<option value='배송지연'>배송지연</option>\n";
	$str.= "</select>";

	return $str;
}

// 주문완료 옵션호출 (이메일)
function print_complete_options2($gs_id, $od_id)
{
	$sql = " select ct_option, ct_qty, io_type, io_price
				from shop_cart where od_id = '$od_id' and gs_id = '$gs_id' order by io_type asc, index_no asc ";
	$result = sql_query($sql);

	$str = '';
	$ul_st = ' style="margin:0;padding:0"';
	$ny_st = ' style="list-style:none;font-size:11px;color:#888888"';
	$ty_st = ' style="list-style:none;font-size:11px;color:#7d62c3"';
	for($i=0; $row=sql_fetch_array($result); $i++) {
		if($i == 0)
			$str .= '<ul'.$ul_st.'>'.PHP_EOL;

		$price_plus = '';
        if($row['io_price'] >= 0)
            $price_plus = '+';

		if($row['io_type'])
			$str .= "<li".$ny_st.">[추가상품]&nbsp;".$row['ct_option']." ".display_qty($row['ct_qty'])." (".$price_plus.display_price($row['io_price']).")</li>".PHP_EOL;
		else
			$str .= "<li".$ty_st.">".$row['ct_option']." ".display_qty($row['ct_qty'])." (".$price_plus.display_price($row['io_price']).")</li>".PHP_EOL;
	}

	if($i > 0)
		$str .= '</ul>';

	return $str;
}

// 특정필드는 제외
function get_columns($tablename)
{
	$columns = array();
    $res = sql_query("SHOW COLUMNS FROM {$tablename}");
    while($row=sql_fetch_array($res)) {
        $columns[] = $row["Field"];
    }

    return $columns;
}

// 고객이 주문/배송조회를 위해 보관해 둔다.
function save_goods_data($gs_id, $odrno, $od_id)
{
	if(!$gs_id || !$odrno || !$od_id)
		return;

	// 특정필드는 제외한다.
	$columns = get_columns("shop_goods");
	$columns = array_diff($columns, array("info_value", "info_gubun", "memo", "admin_memo", "info_color"));

	$sql = " select ".implode(",",$columns)." from shop_goods where index_no = '$gs_id' ";
	$cp = sql_fetch($sql);

	$data = serialize($cp);

	// 상품정보를 주문서에 업데이트한다.
	$sql = " update shop_order set od_goods = '".addslashes($data)."' where index_no = '$odrno' ";
	sql_query($sql);

	$ymd_dir = TB_DATA_PATH.'/order/'.date('ym', time());
	$upl_dir = $ymd_dir.'/'.$od_id; // 저장될 위치

	// 년도별로 따로 저장
	if(!is_dir($ymd_dir)) {
		@mkdir($ymd_dir, TB_DIR_PERMISSION);
		@chmod($ymd_dir, TB_DIR_PERMISSION);
	}

	// 주문번호별로 따로 저장
	if(!is_dir($upl_dir)) {
		@mkdir($upl_dir, TB_DIR_PERMISSION);
		@chmod($upl_dir, TB_DIR_PERMISSION);
	}

	if(preg_match("/^(http[s]?:\/\/)/", $cp['simg1']) == false)
	{
		$file = TB_DATA_PATH.'/goods/'.$cp['simg1'];
		if(is_file($file) && $cp['simg1']) {
			$file_url = $upl_dir.'/'.$cp['simg1'];
			@copy($file, $file_url);
			@chmod($file_url, TB_FILE_PERMISSION);
		}
	}
}

// 이미지를 추출하여 사이즈를 가로 재조정
function get_image_resize($html)
{
	$imgs = get_editor_image($html);

	$img = preg_replace("/<([a-z][a-z0-9]*)(?:[^>]*(\ssrc=['\"][^'\"]*['\"]))?[^>]*?(\/?)>/i",'<$1$2$3 class="img_fix">', $imgs[0]);

	$html = str_replace($imgs[0], $img, $html);

	return $html;
}

// 상세 페이지출력 (배송/교환/반품)
function get_policy_content($gs_id)
{
	global $config, $sr;

	$gs = get_goods($gs_id);

	if($gs['mb_id']=='admin')	{
		if(TB_IS_MOBILE)
			return get_image_resize($config['baesong_cont2']);
		else
			return $config['baesong_cont1'];
	} else {
		if(TB_IS_MOBILE)
			return get_image_resize($sr['baesong_cont2']);
		else
			return $sr['baesong_cont1'];
	}
}

// 분류 옵션을 얻음
function get_category_option($usecate)
{
	$arr = explode("|", $usecate); // 구분자가 | 로 되어 있음
	$str = "";
	for($i=0; $i<count($arr); $i++)
		if(trim($arr[$i]))
			$str .= "<option value='$arr[$i]'>$arr[$i]</option>\n";

	return $str;
}

// 핸드폰번호 : select 형태로 얻음
function get_hp_select($name, $opt='', $selected='')
{
	$str = "<select id=\"{$name}\" name=\"{$name}\"";
    if($opt) $str .= " $opt";
    $str .= ">\n";
	$str.= option_selected('', $selected, '선택');
	$str.= option_selected('010', $selected, '010');
	$str.= option_selected('011', $selected, '011');
	$str.= option_selected('016', $selected, '016');
	$str.= option_selected('017', $selected, '017');
	$str.= option_selected('018', $selected, '018');
	$str.= option_selected('019', $selected, '019');
	$str.= option_selected('0130', $selected, '0130');
	$str.= "</select>\n";

	return $str;
}

// 전화번호 : select 형태로 얻음
function get_tel_select($name, $opt='', $selected='')
{
	$str = "<select id=\"{$name}\" name=\"{$name}\"";
    if($opt) $str .= " $opt";
    $str .= ">\n";
	$str.= option_selected('', $selected, '선택');
	$str.= option_selected('02', $selected, '02');
	$str.= option_selected('031', $selected, '031');
	$str.= option_selected('032', $selected, '032');
	$str.= option_selected('033', $selected, '033');
	$str.= option_selected('041', $selected, '041');
	$str.= option_selected('042', $selected, '042');
	$str.= option_selected('043', $selected, '043');
	$str.= option_selected('044', $selected, '044');
	$str.= option_selected('051', $selected, '051');
	$str.= option_selected('052', $selected, '052');
	$str.= option_selected('053', $selected, '053');
	$str.= option_selected('054', $selected, '054');
	$str.= option_selected('055', $selected, '055');
	$str.= option_selected('061', $selected, '061');
	$str.= option_selected('062', $selected, '062');
	$str.= option_selected('063', $selected, '063');
	$str.= option_selected('064', $selected, '064');
	$str.= option_selected('070', $selected, '070');
	$str.= option_selected('080', $selected, '080');
	$str.= option_selected('0507', $selected, '0507');
	$str.= option_selected('0506', $selected, '0506');
	$str.= option_selected('0505', $selected, '0505');
	$str.= option_selected('0504', $selected, '0504');
	$str.= option_selected('0503', $selected, '0503');
	$str.= option_selected('0502', $selected, '0502');
	$str.= option_selected('0303', $selected, '0303');
	$str.= option_selected('010', $selected, '010');
	$str.= option_selected('011', $selected, '011');
	$str.= option_selected('016', $selected, '016');
	$str.= option_selected('017', $selected, '017');
	$str.= option_selected('018', $selected, '018');
	$str.= option_selected('019', $selected, '019');
	$str.= "</select>\n";

	return $str;
}

// 가맹점 카테고리복사
function sql_member_category($mb_id, $proc_hide = false)
{
    if(!$mb_id)
        return;

	$target_table = 'shop_cate_'.$mb_id;
	$member_path = TB_DATA_PATH.'/category/'.$mb_id;
	$admin_path = TB_DATA_PATH.'/category/admin';

	if(!is_dir($member_path)) {

		// 카테고리 폴더 생성
		@mkdir($member_path, TB_DIR_PERMISSION);
		@chmod($member_path, TB_DIR_PERMISSION);

		// 디렉토리에 있는 파일의 목록을 보이지 않게 한다.
		/*
		$file = $member_path . '/index.php';
		$f = @fopen($file, 'w');
		@fwrite($f, '');
		@fclose($f);
		@chmod($file, TB_FILE_PERMISSION);
		*/

		$copy_file = 0;
		$d = dir($admin_path);
		while($entry = $d->read()) {
			if($entry == '.' || $entry == '..') continue;

			if(is_dir($admin_path.'/'.$entry)){
				$dd = dir($admin_path.'/'.$entry);
				@mkdir($member_path.'/'.$entry, TB_DIR_PERMISSION);
				@chmod($member_path.'/'.$entry, TB_DIR_PERMISSION);
				while($entry2 = $dd->read()) {
					if($entry2 == '.' || $entry2 == '..') continue;
					@copy($admin_path.'/'.$entry.'/'.$entry2, $member_path.'/'.$entry.'/'.$entry2);
					@chmod($member_path.'/'.$entry.'/'.$entry2, TB_DIR_PERMISSION);
					$copy_file++;
				}
				$dd->close();
			}
			else {
				@copy($admin_path.'/'.$entry, $member_path.'/'.$entry);
				@chmod($member_path.'/'.$entry, TB_DIR_PERMISSION);
				$copy_file++;
			}
		}
		$d->close();
	}

	// 테이블이 없을 경우만 새로생성
	if(!table_exists($target_table)) {
		include TB_ADMIN_PATH."/category/category_sql.php";
		sql_query($category_table, FALSE);

		$sql = "select * from shop_cate order by index_no asc ";
		$rec = sql_query($sql);
		for($i=0; $cp = sql_fetch_array($rec); $i++) {

			// 상품테이블의 필드가 추가되어도 수정하지 않도록 필드명을 추출하여 insert 퀴리를 생성한다. (상품코드만 새로운것으로 대체)
			$sql_common = "";
			$fields = sql_field_names("shop_cate");
			foreach($fields as $fld) {
				if($fld == 'index_no' || $fld == 'p_catecode' || $fld == 'p_upcate')
					continue;

				if($proc_hide && $fld == 'p_hide'){ // 감춤처리
                    $cp[$fld] = 1;
                }

				$sql_common .= " , $fld = '".addslashes($cp[$fld])."' ";
			}

			$sql2 = " insert $target_table
						 set p_catecode = '{$cp['catecode']}',
							 p_upcate   = '{$cp['upcate']}'
							 $sql_common ";
			sql_query($sql2, FALSE);
		}
	}
}

// 찜하기
function zzimCheck($gs_id)
{
	global $member;

	$sql = "select count(*) as cnt from shop_wish where mb_id='{$member['id']}' and gs_id='{$gs_id}' ";
	$row =  sql_fetch($sql);

	return ($row['cnt']) ? "zzim on" : "zzim";
}

// 배열을 comma 로 구분하여 연결
function gnd_implode($str, $comma=",")
{
	$arr = is_array($str) ? $str : array($str);

	return implode($comma, $arr);
}

function get_color_boder($color, $b_use='1')
{
	$str = "";

	$sql = " select * from shop_goods_color where gd_color=TRIM('{$color}') ";
	$row = sql_fetch($sql);
	if($row['gd_b_use'])
		$str = "<span style='background-color:{$row['gd_color']};border:1px solid #d8d9db;'></span>";
	else
		$str = "<span style='background-color:{$row['gd_color']};'></span>";

	if(!$b_use)
		$str = "<span style='background-color:{$row['gd_color']};'></span>";

	return $str;
}

// 분류 쿼리
function sql_query_cgy($upcate, $type='', $rows='')
{
	global $tb, $config;

	if($upcate == 'all')
		$sql_search = " LENGTH(catecode) = '3' ";
	else
		$sql_search = " upcate = '$upcate' AND upcate <> '' ";

	$sql_search .= " AND u_hide = '0' AND p_hide = '0' ";

	// 본사카테고리 고정일때
	if($config['pf_auth_cgy'] == 1)
		$sql_search .= " AND p_oper = 'y' ";

	if($type == 'COUNT') {
		$sql = "SELECT COUNT(*) AS cnt
				  FROM {$tb['category_table']}
				 WHERE {$sql_search} ";
		return sql_fetch($sql);
	} else if($type == 'LIMIT') {
		$sql = "SELECT *
				  FROM {$tb['category_table']}
				 WHERE {$sql_search}
				 ORDER BY list_view ASC limit {$rows}";
		return sql_query($sql);
	} else {
		$sql = "SELECT *
				  FROM {$tb['category_table']}
				 WHERE {$sql_search}
				 ORDER BY list_view ASC ";
		return sql_query($sql);
	}
}

// 본인아래 모든 하위회원 (1대,2대....등)
/*
사용법
$list = array();
$list = mb_sublist($member['id']);
$mid = mb_comma($list);
*/
function mb_sublist($mb_recommend)
{
	global $list;

	$list[] = $mb_recommend;
	$sql = "select id from shop_member where pt_id='$mb_recommend' order by index_no asc ";
	$rst = sql_query($sql);
	for($i=0; $row=sql_fetch_array($rst); $i++) {
		if($mb_recommend == $row['id']) {
			break;
		} else {
			mb_sublist($row['id']);
		}
	}

	return $list;
}

// 쿼리에 맞게 콤마로 구분
function mb_comma($list)
{
	$mid = $comma = '';
	foreach($list as $id) {
		$id = trim($id);
		$mid .= $comma."'{$id}'";
		$comma = ',';
	}

	return $mid;
}

// 스킨 style sheet 파일 얻기
function get_skin_stylesheet($skin_path, $dir='')
{
    if(!$skin_path)
        return "";

    $str = "";
    $files = array();

    if($dir)
        $skin_path .= '/'.$dir;

    $skin_url = TB_URL.str_replace("\\", "/", str_replace(TB_PATH, "", $skin_path));

    if(is_dir($skin_path)) {
        if($dh = opendir($skin_path)) {
            while(($file = readdir($dh)) !== false) {
                if($file == "." || $file == "..")
                    continue;

                if(is_dir($skin_path.'/'.$file))
                    continue;

                if(preg_match("/\.(css)$/i", $file))
                    $files[] = $file;
            }
            closedir($dh);
        }
    }

    if(!empty($files)) {
        sort($files);

        foreach($files as $file) {
            $str .= '<link rel="stylesheet" href="'.$skin_url.'/'.$file.'?='.date("md").'">'."\n";
        }
    }

    return $str;

    /*
    // glob 를 이용한 코드
    if(!$skin_path) return '';
    $skin_path .= $dir ? '/'.$dir : '';

    $str = '';
    $skin_url = TB_URL.str_replace('\\', '/', str_replace(TB_PATH, '', $skin_path));

    foreach (glob($skin_path.'/*.css') as $filepath) {
        $file = str_replace($skin_path, '', $filepath);
        $str .= '<link rel="stylesheet" href="'.$skin_url.'/'.$file.'?='.date('md').'">'."\n";
    }
    return $str;
    */
}

// 스킨 javascript 파일 얻기
function get_skin_javascript($skin_path, $dir='')
{
    if(!$skin_path)
        return "";

    $str = "";
    $files = array();

    if($dir)
        $skin_path .= '/'.$dir;

    $skin_url = TB_URL.str_replace("\\", "/", str_replace(TB_PATH, "", $skin_path));

    if(is_dir($skin_path)) {
        if($dh = opendir($skin_path)) {
            while(($file = readdir($dh)) !== false) {
                if($file == "." || $file == "..")
                    continue;

                if(is_dir($skin_path.'/'.$file))
                    continue;

                if(preg_match("/\.(js)$/i", $file))
                    $files[] = $file;
            }
            closedir($dh);
        }
    }

    if(!empty($files)) {
        sort($files);

        foreach($files as $file) {
            $str .= '<script src="'.$skin_url.'/'.$file.'"></script>'."\n";
        }
    }

    return $str;
}

// file_put_contents 는 PHP5 전용 함수이므로 PHP4 하위버전에서 사용하기 위함
// http://www.phpied.com/file_get_contents-for-php4/
if(!function_exists('file_put_contents')) {
    function file_put_contents($filename, $data) {
        $f = @fopen($filename, 'w');
        if(!$f) {
            return false;
        } else {
            $bytes = fwrite($f, $data);
            fclose($f);
            return $bytes;
        }
    }
}

// HTML 마지막 처리
function html_end()
{
    global $html_process;

    return $html_process->run();
}

function add_stylesheet($stylesheet, $order=0)
{
    global $html_process;

    if(trim($stylesheet))
        $html_process->merge_stylesheet($stylesheet, $order);
}

function add_javascript($javascript, $order=0)
{
    global $html_process;

    if(trim($javascript))
        $html_process->merge_javascript($javascript, $order);
}

class html_process {
    protected $css = array();
    protected $js  = array();

    function merge_stylesheet($stylesheet, $order)
    {
        $links = $this->css;
        $is_merge = true;

        foreach($links as $link) {
            if($link[1] == $stylesheet) {
                $is_merge = false;
                break;
            }
        }

        if($is_merge)
            $this->css[] = array($order, $stylesheet);
    }

    function merge_javascript($javascript, $order)
    {
        $scripts = $this->js;
        $is_merge = true;

        foreach($scripts as $script) {
            if($script[1] == $javascript) {
                $is_merge = false;
                break;
            }
        }

        if($is_merge)
            $this->js[] = array($order, $javascript);
    }

    function run()
    {
        global $tb, $member;

        // 현재접속자 처리
        $tmp_sql = " select count(*) as cnt from shop_login where lo_ip = '{$_SERVER['REMOTE_ADDR']}' ";
        $tmp_row = sql_fetch($tmp_sql);

        if($tmp_row['cnt']) {
            $tmp_sql = " update shop_login set mb_id = '{$member['id']}', lo_datetime = '".TB_TIME_YMDHIS."', lo_location = '{$tb['lo_location']}', lo_url = '{$tb['lo_url']}' where lo_ip = '{$_SERVER['REMOTE_ADDR']}' ";
            sql_query($tmp_sql, FALSE);
        } else {
            $tmp_sql = " insert into shop_login ( lo_ip, mb_id, lo_datetime, lo_location, lo_url ) values ( '{$_SERVER['REMOTE_ADDR']}', '{$member['id']}', '".TB_TIME_YMDHIS."', '{$tb['lo_location']}',  '{$tb['lo_url']}' ) ";
            sql_query($tmp_sql, FALSE);

            // 시간이 지난 접속은 삭제한다
            sql_query(" delete from shop_login where lo_datetime < '".date("Y-m-d H:i:s", TB_SERVER_TIME - (60 * 10))."' ");

            // 부담(overhead)이 있다면 테이블 최적화
            //$row = sql_fetch(" SHOW TABLE STATUS FROM `$mysql_db` LIKE '$tb['login_table']' ");
            //if($row['Data_free'] > 0) sql_query(" OPTIMIZE TABLE $tb['login_table'] ");
        }

        $buffer = ob_get_contents();
        ob_end_clean();

        $stylesheet = '';
        $links = $this->css;

        if(!empty($links)) {
            foreach ($links as $key => $row) {
                $order[$key] = $row[0];
                $index[$key] = $key;
                $style[$key] = $row[1];
            }

            array_multisort($order, SORT_ASC, $index, SORT_ASC, $links);

            foreach($links as $link) {
                if(!trim($link[1]))
                    continue;

                $link[1] = preg_replace('#\.css([\'\"]?>)$#i', '.css?ver='.TB_CSS_VER.'$1', $link[1]);

                $stylesheet .= PHP_EOL.$link[1];
            }
        }

        $javascript = '';
        $scripts = $this->js;
        $php_eol = '';

        unset($order);
        unset($index);

        if(!empty($scripts)) {
            foreach ($scripts as $key => $row) {
                $order[$key] = $row[0];
                $index[$key] = $key;
                $script[$key] = $row[1];
            }

            array_multisort($order, SORT_ASC, $index, SORT_ASC, $scripts);

            foreach($scripts as $js) {
                if(!trim($js[1]))
                    continue;

                $js[1] = preg_replace('#\.js([\'\"]?>)$#i', '.js?ver='.TB_JS_VER.'$1', $js[1]);

                $javascript .= $php_eol.$js[1];
                $php_eol = PHP_EOL;
            }
        }

        /*
        </title>
        <link rel="stylesheet" href="default.css">
        밑으로 스킨의 스타일시트가 위치하도록 하게 한다.
        */
        $buffer = preg_replace('#(</title>[^<]*<link[^>]+>)#', "$1$stylesheet", $buffer);

        /*
        </head>
        <body>
        전에 스킨의 자바스크립트가 위치하도록 하게 한다.
        */
        $nl = '';
        if($javascript)
            $nl = "\n";
        $buffer = preg_replace('#(</head>[^<]*<body[^>]*>)#', "$javascript{$nl}$1", $buffer);

        return $buffer;
    }
}


/*************************************************************************
**
**  쇼핑몰 배너관련 함수 모음
**
*************************************************************************/

// 연속배너 sql
function sql_banner_rows($code, $mb_id)
{
	global $config, $mk;

	if(TB_IS_MOBILE) // 모바일접속인가?
		$sql_where = " where bn_device = 'mobile' and bn_theme = '{$mk['mobile_theme']}' ";
	else
		$sql_where = " where bn_device = 'pc' and bn_theme = '{$mk['theme']}' ";

	$sql_where .= " and bn_code = '$code' and bn_use = '1' ";
	$sql_order  = " order by bn_order asc ";

	$sql = " select * from shop_banner {$sql_where} and mb_id = '$mb_id' {$sql_order} ";
	/*$row = sql_fetch($sql);
	if(!$row['bn_id'] && $mb_id != 'admin') {
		$sql = " select * from shop_banner {$sql_where} and mb_id = 'admin' {$sql_order} ";
	}*/

    return $sql;
}

// 랜덤배너 sql
function sql_banner($code, $mb_id)
{
	global $config, $mk;

	if(TB_IS_MOBILE) // 모바일접속인가?
		$sql_where = " where bn_device = 'mobile' and bn_theme = '{$mk['mobile_theme']}' ";
	else
		$sql_where = " where bn_device = 'pc' and bn_theme = '{$mk['theme']}' ";

	$sql_where .= " and bn_code = '$code' and bn_use = '1' ";
	$sql_order  = " order by rand() limit 1 ";

	$sql = " select * from shop_banner {$sql_where} and mb_id = '$mb_id' {$sql_order} ";
	/*$row = sql_fetch($sql);
	if(!$row['bn_id'] && $mb_id != 'admin') {
		$sql = " select * from shop_banner {$sql_where} and mb_id = 'admin' {$sql_order} ";
	}*/

    return $sql;
}

// 배너 URL
function display_banner_url($code, $mb_id)
{
	$str = "";

	$sql = sql_banner($code, $mb_id);
	$row = sql_fetch($sql);

	$file = TB_DATA_PATH.'/banner/'.$row['bn_file'];
	if(is_file($file) && $row['bn_file']) {
		$str = rpc($file, TB_PATH, TB_URL);
	}

	return $str;
}

// 개별배너 설정값이없으면 본사 설정을 그대로 복사.
function check_banner_copy($mb_id, $device)
{
	if(!$mb_id || !$device) return;

	$banner_dir = TB_DATA_PATH."/banner";

	// 배너
	$sql = "select * from shop_banner where mb_id = '$mb_id' and bn_device = '$device' ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		@unlink($banner_dir.'/'.$row['bn_file']);
	}

	// 삭제
	$sql = "delete from shop_banner where mb_id = '$mb_id' and bn_device = '$device'";
	sql_query($sql);

	$sql = " select *
			   from shop_banner
			  where mb_id = 'admin'
			    and bn_device = '$device'
			  order by bn_id asc ";
	$result = sql_query($sql);
	for($i=0; $cp=sql_fetch_array($result); $i++)
	{
		$sql_common = "";
		$fields = sql_field_names("shop_banner");
		foreach($fields as $fld) {
			if(in_array($fld, array('bn_id', 'mb_id', 'bn_file'))) continue;

			$sql_common .= " , $fld = '".addslashes($cp[$fld])."' ";
		}

		$sql = " insert into shop_banner set mb_id = '$mb_id' $sql_common ";
		sql_query($sql);
		$new_bn_id = sql_insert_id();

		$file = $banner_dir.'/'.$cp['bn_file'];
		if(is_file($file) && $cp['bn_file']) {
			$dstfile = $banner_dir.'/'.$new_bn_id.'_'.$cp['bn_file'];
			$new_bn_file = basename($dstfile);

			@copy($file, $dstfile);
			@chmod($dstfile, TB_FILE_PERMISSION);

			$sql = " update shop_banner set bn_file = '$new_bn_file' where bn_id = '$new_bn_id' ";
			sql_query($sql);
		}
	}
}

function chkFreeDelivery($tot_price,$seller)
{
    if($seller['delivery_totmethod']=='1' && $tot_price >= $seller['delivery_totminimum'])
    {
        $result = true;
    }else{
        $result = false;
    }

    return $result;
}

function nonTradeCanceled($od_no)
{
    sql_fetch(" UPDATE shop_order SET shop_memo = '{$od_no} / 결제수단 변경' WHERE od_no='{$od_no}' ");
    sql_fetch(" UPDATE shop_order SET od_no = '' WHERE od_no='{$od_no}' ");
}

// 에러 로그 기록
function uf_write_log_mbg( $msg, $filename='') {

    // 파일명($path) 생성----------
    if( ! $filename) {
        $filename = $_SERVER["PHP_SELF"];
    }

    $max_len = 30;
    $t = str_replace('/', '_', $filename);
    $filename = substr($t, -$max_len);

    $path = $_SERVER['DOCUMENT_ROOT']."/../logs/". date("Ym");
    if( ! is_dir($path) ) {
        mkdir($path, 0707, true);
    }

    $path .=  '/' . $filename . "_" . date("Ymd").".log";
    // 파일명($path) 생성(e)----------

    $loginfo ='';
    $date = date("Y-m-d H:i:s");

    $logs = array();

    $logs[] = "S - {$date}";
    $logs[] = "WHO\t:{$_SESSION['ss_mb_id']}";
    $logs[] = "IP\t:{$_SERVER["REMOTE_ADDR"]}";
    $logs[] = "WHERE\t:{$_SERVER["REQUEST_URI"]}";
    if(is_array($msg)){
        $msg = print_r($msg, true);
    }
    $logs[] = "MSG\t:{$msg}";
    $logs[] = "E - {$date}";

    $nl = "\r\n";
    $logs[] = $nl;
    @error_log( implode($nl, $logs),3, $path);
}