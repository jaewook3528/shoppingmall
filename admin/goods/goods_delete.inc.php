<?php
if(!defined('_TUBEWEB_')) exit;
if(!defined('_GOODS_DELETE_')) exit; // 개별 페이지 접근 불가

if(!function_exists("goods_delete")) {

    // 상품삭제
    function goods_delete($gs_id)
    {
        // 이미지는 삭제 하지 않는 것으로 (이미지 url로 대량으로 삭제하고 대량으로 올릴경우 이미지가 없는 문제 발생)
        if( false ) {
            $gs = get_goods($gs_id);

            $dir_list = TB_DATA_PATH.'/goods/'.$gs['gcode'];

            for($g=1; $g<=6; $g++) {
                if($gs['simg'.$g]) {
                    @unlink(TB_DATA_PATH.'/goods/'.$gs['simg'.$g]);
                    delete_item_thumbnail($dir_list, $gs['simg'.$g]);
                }
            }

            // 에디터 이미지 삭제
            delete_editor_image($gs['memo']);
        }


		// 삭제
		sql_query("delete from shop_goods where index_no='$gs_id'"); // 상품테이블
		sql_query("delete from shop_goods_type where gs_id='$gs_id'"); //진열관리
		sql_query("delete from shop_goods_cate where gs_id='$gs_id'"); // 카테고리
		sql_query("delete from shop_goods_review where gs_id='$gs_id'"); // 상품평
		sql_query("delete from shop_goods_option where gs_id='$gs_id'"); // 옵션
		sql_query("delete from shop_cart where gs_id='$gs_id' and ct_select='0'"); // 장바구니
		sql_query("delete from shop_wish where gs_id='$gs_id'"); // 찜목록
		sql_query("delete from shop_goods_qa where gs_id='$gs_id'"); // 상품문의
		sql_query("delete from shop_goods_relation where gs_id = '$gs_id'");// 관련상품
		sql_query("delete from shop_goods_relation where gs_id2 = '$gs_id'");// 관련상품
	}
}

goods_delete($gs_id);
?>