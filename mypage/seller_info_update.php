<?php
include_once("./_common.php");

check_demo();

check_admin_token();

unset($value);
$value['seller_item']			= $_POST['seller_item']; //제공상품
$value['company_name']			= $_POST['company_name']; //업체(법인)명
$value['company_owner']			= $_POST['company_owner']; //대표자명
$value['company_saupja_no']		= $_POST['company_saupja_no'];//사업자등록번호
$value['company_item']			= $_POST['company_item']; //업태
$value['company_service']		= $_POST['company_service']; //종목
$value['company_tel']			= $_POST['company_tel']; //전화번호
$value['company_fax']			= $_POST['company_fax']; //팩스번호
$value['company_zip']			= $_POST['company_zip']; //우편번호
$value['company_addr1']			= $_POST['company_addr1']; //기본주소
$value['company_addr2']			= $_POST['company_addr2']; //상세주소
$value['company_addr3']			= $_POST['company_addr3']; //참고항목
$value['company_addr_jibeon']	= $_POST['company_addr_jibeon']; //지번
$value['company_hompage']		= $_POST['company_hompage']; //홈페이지
$value['bank_name']				= $_POST['bank_name']; //은행명
$value['bank_account']			= $_POST['bank_account']; //계좌번호
$value['bank_holder']			= $_POST['bank_holder']; //예금주명
$value['info_name']				= $_POST['info_name']; //담당자명
$value['info_tel']				= $_POST['info_tel']; //담당자 핸드폰
$value['info_email']			= $_POST['info_email']; //담당자 이메일
$value['update_time']			= TB_TIME_YMDHIS;
update("shop_seller",$value,"where mb_id='$member[id]'");

goto_url(TB_MYPAGE_URL.'/page.php?code=seller_info');
?>