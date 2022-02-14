<meta charset="utf-8"><?php

include 'common.php';

$_POST['cross_key'] = 'y0Q1dFWVbMV43cBU'; // 계정 CrossKey

var_dump($_POST);

$ufSocket = new UfSocket( "cube24.co.kr", '/_out/url_pay/decrypt.php');
$resp = $ufSocket->request($_POST);
$resp_r = $ufSocket->arr2str( $resp['bodys'], true );

var_dump($resp_r);

var_dump(json_decode($resp_r['detail_datas']));

$fp = fopen('data.txt', 'a+');
fwrite($fp, date("Y-m-d H:i:s").print_r($resp_r, true));
fclose($fp);

//uf_socket_send('/_out/url_pay/decrypt.php', $_POST);
