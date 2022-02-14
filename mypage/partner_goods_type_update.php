<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$mb_id = $member['id'];

for($i=0; $i<count($_POST['gs_id']); $i++)
{
    $sql = " select count(*) as cnt 
				from shop_goods_type
			   where mb_id = '$mb_id' 
			     and gs_id = '{$_POST['gs_id'][$i]}' ";
    $row = sql_fetch($sql);
	if($row['cnt']) {
		$sql = "update shop_goods_type
				   set it_type1 = '{$_POST['it_type1'][$i]}',
					   it_type2 = '{$_POST['it_type2'][$i]}',
					   it_type3 = '{$_POST['it_type3'][$i]}',
					   it_type4 = '{$_POST['it_type4'][$i]}',
					   it_type5 = '{$_POST['it_type5'][$i]}'
				 where mb_id = '$mb_id'
				   and gs_id = '{$_POST['gs_id'][$i]}' ";
		sql_query($sql);
	} else {
		$sql = "insert into shop_goods_type
				   set mb_id = '$mb_id',
					   gs_id = '{$_POST['gs_id'][$i]}',
					   it_type1 = '{$_POST['it_type1'][$i]}',
					   it_type2 = '{$_POST['it_type2'][$i]}',
					   it_type3 = '{$_POST['it_type3'][$i]}',
					   it_type4 = '{$_POST['it_type4'][$i]}',
					   it_type5 = '{$_POST['it_type5'][$i]}' ";
		sql_query($sql);
	}
}

goto_url(TB_MYPAGE_URL."/page.php?{$q1}&page={$page}");
?>