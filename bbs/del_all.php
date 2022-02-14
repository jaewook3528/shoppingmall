<?php
include_once("./_common.php");

for($i=0;$i<sizeof($OrderNum);$i++) { 
	$ordernum_del .= $OrderNum[$i]."/"; 
}

$order_all_delete_Division = explode("/",$ordernum_del);
$order_all_delete_Total = count($order_all_delete_Division);
$order_all_delete_Total = $order_all_delete_Total-1 ;

$board_dir = TB_DATA_PATH."/board/".$boardid;

for($i=0; $i<$order_all_delete_Total; $i++) 
{	
	$row = sql_fetch(" select * from shop_board_{$boardid} where index_no='$order_all_delete_Division[$i]'" );

	if($row['fileurl1']) @unlink($board_dir."/".$row['fileurl1']);
	if($row['fileurl1']) delete_board_thumbnail($boardid, $row['fileurl1']);
	if($row['fileurl2']) @unlink($board_dir."/".$row['fileurl2']);
	if($row['fileurl2']) delete_board_thumbnail($boardid, $row['fileurl2']);

	delete_editor_image($row['memo']);

	$sql = "delete from shop_board_{$boardid} where index_no=".$order_all_delete_Division[$i];
	sql_query($sql); 
}

goto_url(TB_BBS_URL."/list.php?boardid=$boardid$qstr&page=$page");
?>