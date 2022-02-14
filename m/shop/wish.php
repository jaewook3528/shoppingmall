<?php
include_once("./_common.php");

if(!$is_member)
	goto_url(TB_MBBS_URL.'/login.php?url='.$urlencode);

$tb['title'] = "찜한상품";
include_once("./_head.php");

$sql  = " select a.wi_id, a.wi_time, a.gs_id, b.* 
            from shop_wish a left join shop_goods b ON ( a.gs_id = b.index_no )
		   where a.mb_id = '{$member['id']}' 
		   order by a.wi_id desc ";
$result = sql_query($sql);
$wish_count = sql_num_rows($result);

include_once(TB_MTHEME_PATH.'/wish.skin.php');

include_once("./_tail.php");
?>