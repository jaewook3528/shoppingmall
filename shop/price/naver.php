<?php
include_once('./_common.php');

// clean the output buffer
ob_end_clean();

/*
EP 버전 3.0

네이버지식쇼핑상품EP (Engine Page) 제작및연동가이드 (제휴사제공용)
http://join.shopping.naver.com/misc/download/ep_guide.nhn

Field                   Status  Notes
id                      필수    판매하는 상품의 유니크한 상품ID
title                   필수    실제 서비스에 반영될 상품명(Title)
price_pc                필수    상품가격
link                    필수    상품URL
image_link              필수    해당 상품의 이미지URL
category_name1          필수    카테고리명(대분류)
category_name2          권장    카테고리명(중분류)
category_name3          권장    카테고리명(소분류)
category_name4          권장    카테고리명(세분류)
model_number            권장    모델명
brand                   권장    브랜드
maker                   권장    제조사
origin                  권장    원산지
event_words             권장    이벤트
coupon                  권장    쿠폰
interest_free_event     권장    무이자
point                   권장    포인트
shipping                필수    배송료
seller_id               권장    셀러 ID (오픈마켓에 한함)
class                   필수(요약)  I (신규상품) / U (업데이트 상품) / D (품절상품)
update_time             필수(요약)  상품정보 생성 시각
*/

$tab = "\t";

ob_start();

echo "id{$tab}title{$tab}price_pc{$tab}link{$tab}image_link{$tab}category_name1{$tab}category_name2{$tab}category_name3{$tab}model_number{$tab}brand{$tab}maker{$tab}origin{$tab}point{$tab}shipping";

$sql_cost = " AND a.stock_qty > 0 ";
$sql_common = get_sql_precompose($sql_cost);
$sql_order = " group by a.index_no order by a.index_no desc LIMIT 10000"; // 네이버 쇼핑 - 등록 가능한 상품 수 (10,000)

$sql = " select a.* $sql_common $sql_order ";

$result = sql_query($sql);
for($i=0; $row=sql_fetch_array($result); $i++)
{
    $cate1 = $cate2 = $cate3 = '';
    $caid1 = $caid2 = $caid3 = '';

	// 대표 카테고리
	$sql2 = "select * from shop_goods_cate where gs_id='$row[index_no]' order by index_no asc limit 1 ";
	$cgy = sql_fetch($sql2);

    if(strlen($cgy['gcate']) >= 9) {
        $caid3 = substr($cgy['gcate'],0,9);
        $row2 = sql_fetch(" select catename from shop_cate where catecode = '$caid3' ");
        $cate3 = $row2['catename'];
    }
    if(strlen($cgy['gcate']) >= 6) {
        $caid2 = substr($cgy['gcate'],0,6);
        $row2 = sql_fetch(" select catename from shop_cate where catecode = '$caid2' ");
        $cate2 = $row2['catename'];
    }
    if(strlen($cgy['gcate']) >= 3) {
        $caid1 = substr($cgy['gcate'],0,3);
        $row2 = sql_fetch(" select catename from shop_cate where catecode = '$caid1' ");
        $cate1 = $row2['catename'];
    }

    // 배송비계산
    $delivery = get_sendcost_amt2($row['index_no'], $row['goods_price']);

    // 상품이미지
    $img_url = get_it_image_url($row['index_no'], $row['simg2'], 400, 400);

	$item_link = TB_SHOP_URL.'/view.php?index_no='.$row['index_no'];

	echo "\n{$row['index_no']}{$tab}{$row['gname']}{$tab}{$row['goods_price']}{$tab}{$item_link}{$tab}{$img_url}{$tab}{$cate1}{$tab}{$cate2}{$tab}{$cate3}{$tab}{$row['model']}{$tab}{$row['brand_nm']}{$tab}{$row['maker']}{$tab}{$row['origin']}{$tab}{$row['gpoint']}{$tab}{$delivery}";
}

$content = ob_get_contents();
ob_end_clean();

echo $content;
?>