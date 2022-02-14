<?php
include_once("./_common.php");

check_demo();

if(!$od_id)
	alert("주문번호가 넘어오지 않았습니다.");

$sql = " select *
		   from shop_order
		  where od_id IN ({$od_id})
		    and seller_id = '{$seller['seller_code']}'
		  order by od_time desc, index_no asc ";
$result = sql_query($sql);
$cnt = @sql_num_rows($result);
if(!$cnt)
	alert("출력할 자료가 없습니다.");

define("_ORDERPHPExcel_", true);

// 주문서 PHPExcel 공통
include_once('./seller_odr_excel.sub.php');
?>