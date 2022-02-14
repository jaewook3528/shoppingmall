<?php
include_once("./_common.php");

check_demo();

check_admin_token();

if($mb_id == '') {
	alert('적용할 아이디가 넘어오지 않았습니다.');
}

$mb = get_member($mb_id, 'id');
if(!$mb['id']) {
	alert('회원아이디가 존재하지 않습니다.');
}

// 카테고리 테이블 DROP
$target_table = 'shop_cate_'.$mb_id;
sql_query(" drop table {$target_table} ", FALSE);

// 카테고리 폴더 전체 삭제
rm_rf(TB_DATA_PATH.'/category/'.$mb_id);

// 카테고리 생성
sql_member_category($mb_id);

goto_url(TB_ADMIN_URL.'/partner/pt_category.php?mb_id='.$mb_id);
?>