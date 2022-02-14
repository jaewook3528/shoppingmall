<?php
/*
 * login_with_naver.php
 *
 * @(#) $Id: login_with_facebook.php,v 1.3 2013/07/31 11:48:04 mlemos Exp $
 * @(#) $Id: login_with_naver,v 1.0 2014/12/30 dosa
 *
 */

/*
 *  Get the http.php file from http://www.phpclasses.org/httpclient
 */

include_once('./_common.php');
require('http.php');
require('oauth_client.php');

require('lib/UfOauthLoginWith.php');

$client = new oauth_client_class;
$client->client_id = $pay2pay_ClientID;
$client->client_secret = $pay2pay_ClientSecret;
$client->server = 'Pay2pay';

$uf_oauth = new UfOauthLoginWith($client);
$uf_oauth->process();
