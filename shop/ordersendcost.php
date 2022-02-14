<?php
define('_PURENESS_', true);
include_once("./_common.php");

$code = preg_replace('#[^0-9]#', '', $_POST['zipcode']);

if(!$code)
    die('0');

$sql = " select is_id, is_price
           from shop_island
          where is_zip1 <= $code
            and is_zip2 >= $code ";
$row = sql_fetch($sql);

if(!$row['is_id'])
    die('0');

die($row['is_price']);
?>