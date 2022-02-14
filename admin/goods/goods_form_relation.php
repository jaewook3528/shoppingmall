<?php
include_once("./_common.php");

$gcate = trim($gcate);
$gname = trim(strip_tags($gname));

if(!$gcate && !$gname)
    die('<p>카테고리를 선택하시거나 상품명을 입력하신 후 검색하여 주십시오.</p>');

$sql_common = " from shop_goods a ";
$sql_search = " where a.use_aff = 0 and a.shop_state = 0 and a.index_no <> '$gs_id' ";

if($gcate) {
	$len = strlen($gcate);
    $sql_common .= " left join shop_goods_cate b ON (a.index_no = b.gs_id) ";
    $sql_search .= " and (left(b.gcate,$len) = '$gcate') ";
}

if($gname)
    $sql_search .= " and (a.gname like '%$gname%') ";

$sql_order = " group by a.index_no order by a.index_no desc ";

$list = '';

$sql = " select a.index_no, a.gname, a.simg1 $sql_common $sql_search $sql_order ";
$result = sql_query($sql);
for($i=0;$row=sql_fetch_array($result);$i++) {
    $sql2 = " select count(*) as cnt 
				from shop_goods_relation
			   where gs_id = '$gs_id' 
			     and gs_id2 = '{$row['index_no']}' ";
    $row2 = sql_fetch($sql2);
    if($row2['cnt'])
        continue;

    $gname = get_it_image($row['index_no'], $row['simg1'], 50, 50).' '.$row['gname'];

    $list .= '<li class="list_res">';
    $list .= '<input type="hidden" name="re_gs_id[]" value="'.$row['index_no'].'">';
    $list .= '<div class="list_item">'.$gname.'</div>';
    $list .= '<div class="list_item_btn"><button type="button" class="add_item btn_small">추가</button></div>';
    $list .= '</li>'.PHP_EOL;
}

if($list)
    $list = '<ul>'.$list.'</ul>';
else
    $list = '<p>등록된 상품이 없습니다.';

echo $list;
?>
