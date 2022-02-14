<?php
define('_PURENESS_', true);
include_once("./_common.php");

$gs_id = $_POST['gs_id'];
$opt_id = $_POST['opt_id'];
$idx = $_POST['idx'];
$sel_count = $_POST['sel_count'];

$amt = get_sale_price($gs_id);

$sql = " select * 
		   from shop_goods_option
          where io_type = '0'
            and gs_id = '$gs_id'
            and io_use = '1'
            and io_id like '$opt_id%'
          order by io_no asc ";
$result = sql_query($sql);

$str = '<option value="">(필수) 선택하세요</option>';
$opt = array();

for($i=0; $row=sql_fetch_array($result); $i++) {
    $val = explode(chr(30), $row['io_id']);
    $key = $idx + 1;

    if(!$val[$key])
        continue;

    if(in_array($val[$key], $opt))
        continue;

    $opt[] = $val[$key];

    if($key + 1 < $sel_count) {
        $str .= PHP_EOL.'<option value="'.$val[$key].'">'.$val[$key].'</option>';
    } else {
        if($row['io_price'] >= 0)
            $price = '&nbsp;&nbsp;+ '.display_price($row['io_price']);
        else
            $price = '&nbsp;&nbsp; '.display_price($row['io_price']);

        $io_stock_qty = get_option_stock_qty($gs_id, $row['io_id'], $row['io_type']);

        if($io_stock_qty < 1)
            $soldout = '&nbsp;&nbsp;[품절]';
        else
            $soldout = '';

		$str .= PHP_EOL.'<option value="'.$val[$key].','.$row['io_price'].','.$io_stock_qty.','.$amt.'">'.$val[$key].$price.$soldout.'</otpion>';
    }
}

echo $str;
?>