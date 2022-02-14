<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$_POST = array_map('trim', $_POST);

$po_point   = $_POST['po_point'];
$po_content = $_POST['po_content'];

$mb	= get_member($_POST['mb_id'], 'id, point');

if(!$mb['id'])
    alert("존재하는 회원아이디가 아닙니다.");

if(($po_point < 0) && ($po_point * (-1) > $mb['point']))
    alert("포인트를 차감하는 경우 현재 포인트보다 작으면 안됩니다.");

insert_point($mb['id'], $po_point, $po_content, '@passive', $mb['id'], $member['id'].'-'.uniqid(''));

alert('정상적으로 처리 되었습니다.','replace');
?>