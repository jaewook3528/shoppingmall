<?
// shop_cate_rndmarket 테이블 list_view 값 넣는 코드
// 디비로 카테고리를 대량으로 인서트 시 list_view 값은 자동화하기가 까다로워 파일을 만듬.

$db_table = 'shop_cate_rndmarket';

define('_TUBEWEB_', 1);
include dirname(__FILE__). '/../data/dbconfig.php';

echo '<meta charset="utf-8">';

mysql_connect(TB_MYSQL_HOST, TB_MYSQL_USER, TB_MYSQL_PASSWORD) or
die("Could not connect: " . mysql_error());
mysql_select_db(TB_MYSQL_DB);

$result = mysql_query("
SELECT * 
FROM {$db_table}
WHERE upcate
  IN (
    SELECT catecode
    FROM {$db_table}
    WHERE LENGTH( catecode ) = '6' -- '3'
    AND catecode >= '011000' -- '011'
)
ORDER BY catecode
");

$prev_upcate = '';
while ($row = mysql_fetch_array($result)) {
    if($prev_upcate != $row['upcate']) {
        $prev_upcate = $row['upcate'];
        $list_view = 0;
    }

    $list_view++;

    $query = "
    UPDATE {$db_table} SET
      list_view = '{$list_view}'
    WHERE index_no = '{$row['index_no']}'
    ";
    var_dump($query, '<br>');
    //mysql_query($query);
}

mysql_free_result($result);