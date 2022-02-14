<?php
include_once("./_common.php");

$od_pwd = get_encrypt_string($od_pwd);

// 회원인 경우
if($is_member)
    $sql_common = " from shop_order where mb_id = '{$member['id']}' and dan <> '0' ";
else if($od_id && $od_pwd) // 비회원인 경우 주문서번호와 비밀번호가 넘어왔다면
    $sql_common = " from shop_order where od_id = '$od_id' and od_pwd = '$od_pwd' and dan <> '0' ";
else // 그렇지 않다면 로그인으로 가기
    goto_url(TB_MBBS_URL.'/login.php?url='.$urlencode);

// 테이블의 전체 레코드수만 얻음
$sql = " select count(DISTINCT od_id) as cnt " . $sql_common;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

// 비회원 주문확인시 비회원의 모든 주문이 다 출력되는 오류 수정
// 조건에 맞는 주문서가 없다면
if($total_count == 0)
{
    if($is_member) // 회원일 경우는 메인으로 이동
        alert('주문이 존재하지 않습니다.', TB_MURL);
    else // 비회원일 경우는 이전 페이지로 이동
        alert('주문이 존재하지 않습니다.');
}

$rows = 10;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

// 비회원 주문확인의 경우 바로 주문서 상세조회로 이동
if(!$is_member) {
    $sql = " select od_id, od_time, od_ip from shop_order where od_id = '$od_id' and od_pwd = '$od_pwd' ";
    $row = sql_fetch($sql);
    if($row['od_id']) {
        $uid = md5($row['od_id'].$row['od_time'].$row['od_ip']);
        set_session('ss_orderview_uid', $uid);
        goto_url(TB_MSHOP_URL.'/orderinquiryview.php?od_id='.$row['od_id'].'&uid='.$uid);
    }
}

$sql_order = " group by od_id order by index_no desc ";

$sql = " select * $sql_common $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

$tb['title'] = '주문/배송조회';
include_once("./_head.php");
include_once(TB_MTHEME_PATH."/orderinquiry.skin.php");
include_once("./_tail.php");
?>