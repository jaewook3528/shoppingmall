<?php
include_once("./_common.php");

check_demo();

check_admin_token();

unset($value);
$value['de_sns_login_use']			= $_POST['de_sns_login_use'];
$value['de_naver_appid']			= $_POST['de_naver_appid'];
$value['de_naver_secret']			= $_POST['de_naver_secret'];
$value['de_facebook_appid']			= $_POST['de_facebook_appid'];
$value['de_facebook_secret']		= $_POST['de_facebook_secret'];
$value['de_kakao_rest_apikey']		= $_POST['de_kakao_rest_apikey'];
$value['de_psys_appid']		= $_POST['de_psys_appid'];
$value['de_psys_secret']		= $_POST['de_psys_secret'];
$value['de_pay2pay_appid']		= $_POST['de_pay2pay_appid'];
$value['de_pay2pay_secret']		= $_POST['de_pay2pay_secret'];

$value['de_googl_shorturl_apikey']	= $_POST['de_googl_shorturl_apikey'];
$value['de_insta_url']				= $_POST['de_insta_url'];
$value['de_insta_client_id']		= $_POST['de_insta_client_id'];
$value['de_insta_redirect_uri']		= $_POST['de_insta_redirect_uri'];
$value['de_insta_access_token']		= $_POST['de_insta_access_token'];
$value['de_sns_facebook']			= $_POST['de_sns_facebook'];
$value['de_sns_twitter']			= $_POST['de_sns_twitter'];
$value['de_sns_instagram']			= $_POST['de_sns_instagram'];
$value['de_sns_pinterest']			= $_POST['de_sns_pinterest'];
$value['de_sns_naverblog']			= $_POST['de_sns_naverblog'];
$value['de_sns_naverband']			= $_POST['de_sns_naverband'];
$value['de_sns_kakaotalk']			= $_POST['de_sns_kakaotalk'];
$value['de_sns_kakaostory']			= $_POST['de_sns_kakaostory'];
update("shop_partner",$value,"where mb_id='$member[id]'");

goto_url(TB_MYPAGE_URL.'/page.php?code=partner_sns');
?>