<?php
include_once("./_common.php");

if(TB_IS_MOBILE) {
	goto_url(TB_MSHOP_URL.'/brand.php');
}

$tb['title'] = '브랜드샵';
include_once("./_head.php");

$where = array();
$sql_where = "";

$qstx = utf8_strcut(get_search_string(trim($_GET['qstx'])), 30, "");
$qword = isset($_GET['qword']) ? trim($_GET['qword']) : '';

if(isset($_GET['qsort'])) {
    $qsort = trim($_GET['qsort']);
    $qsort = preg_replace("/[\<\>\'\"\\\'\\\"\%\=\(\)\s]/", "", $qsort);
} else {
    $qsort = '';
}

if(isset($_GET['qorder'])) {
    $qorder = preg_match("/^(asc|desc)$/i", $qorder) ? $qorder : '';
} else {
    $qorder = '';
}

$qsort  = strtolower($qsort);
$qorder = strtolower($qorder);

if(!$qsort) {
    $qsort = "br_name";
    $qorder = "asc";
}

$where[] = " ( br_user_yes = 0 or (br_user_yes = 1 and mb_id = '$pt_id') ) and {$qsort} <> '' ";

if($qstx) {
	$where[] = " concat(br_name,' ',br_name_eng) like '%$qstx%' ";
}

if(check_string($qword, TB_HANGUL) && $qword == 'ㄱ')
	$where[] = " br_name between '가' and '나' ";
else if(check_string($qword, TB_HANGUL) && $qword == 'ㄴ')
	$where[] = " br_name between '나' and '다' ";
else if(check_string($qword, TB_HANGUL) && $qword == 'ㄷ')
	$where[] = " br_name between '다' and '라' ";
else if(check_string($qword, TB_HANGUL) && $qword == 'ㄹ')
	$where[] = " br_name between '라' and '마' ";
else if(check_string($qword, TB_HANGUL) && $qword == 'ㅁ')
	$where[] = " br_name between '마' and '바' ";
else if(check_string($qword, TB_HANGUL) && $qword == 'ㅂ')
	$where[] = " br_name between '바' and '사' ";
else if(check_string($qword, TB_HANGUL) && $qword == 'ㅅ')
	$where[] = " br_name between '사' and '아' ";
else if(check_string($qword, TB_HANGUL) && $qword == 'ㅇ')
	$where[] = " br_name between '아' and '자' ";
else if(check_string($qword, TB_HANGUL) && $qword == 'ㅈ')
	$where[] = " br_name between '자' and '차' ";
else if(check_string($qword, TB_HANGUL) && $qword == 'ㅊ')
	$where[] = " br_name between '차' and '카' ";
else if(check_string($qword, TB_HANGUL) && $qword == 'ㅋ')
	$where[] = " br_name between '카' and '타' ";
else if(check_string($qword, TB_HANGUL) && $qword == 'ㅌ')
	$where[] = " br_name between '타' and '파' ";
else if(check_string($qword, TB_HANGUL) && $qword == 'ㅍ')
	$where[] = " br_name between '파' and '하' ";
else if(check_string($qword, TB_HANGUL) && $qword == 'ㅎ')
	$where[] = " br_name >= '하' ";
else if(check_string($qword, TB_ALPHAUPPER) && $qword)
	$where[] = " SUBSTRING(br_name_eng,1,1) = '$qword' ";
else if(check_string($qword, TB_ALPHAUPPER) && $qword)
	$where[] = " {$qsort} REGEXP '^[0-9]' ";

if($where) {
	$sql_where .= " where " . implode(" and ", $where);
}

$sql_common = " from shop_brand {$sql_where} ";

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt {$sql_common} ";
$row = sql_fetch($sql);
$total_count = (int)$row['cnt'];

$sql = " select * {$sql_common} order by {$qsort} {$qorder} ";
$result = sql_query($sql);
for($i=0; $row=sql_fetch_array($result); $i++) {
	$list[$i] = $row;
	$list[$i]['br_name'] = ($qsort == 'br_name') ? $row['br_name'] : $row['br_name_eng'];
	$list[$i]['br_href'] = TB_SHOP_URL.'/brandlist.php?br_id='.$row['br_id'];

	$bimg = TB_DATA_PATH.'/brand/'.$row['br_logo'];
	if(is_file($bimg) && $row['br_logo'])
		$list[$i]['br_logo'] = rpc($bimg, TB_PATH, TB_URL);
	else
		$list[$i]['br_logo'] = TB_IMG_URL.'/brlogo_sam.jpg';
}

if($qsort == 'br_name')
	$charAt = explode('|', 'ㄱ|ㄴ|ㄷ|ㄹ|ㅁ|ㅂ|ㅅ|ㅇ|ㅈ|ㅊ|ㅋ|ㅌ|ㅍ|ㅎ');
else
	$charAt = explode('|', 'A|B|C|D|E|F|G|H|I|J|K|L|M|N|O|P|Q|R|S|T|U|V|W|X|Y|Z');

include_once(TB_THEME_PATH.'/brand.skin.php');

include_once("./_tail.php");
?>