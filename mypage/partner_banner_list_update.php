<?php
include_once("./_common.php");

check_demo();

check_admin_token();

if($_REQUEST['mod_type'] == 'R') {
	check_banner_copy($member['id'], 'pc');

	goto_url(TB_MYPAGE_URL."/page.php?code=partner_banner_list");

} else {

	$count = count($_POST['chk']);
	if(!$count) {
		alert($_POST['act_button']." 하실 항목을 하나 이상 체크하세요.");
	}

	if($_POST['act_button'] == "선택수정")
	{
		for($i=0; $i<$count; $i++)
		{
			// 실제 번호를 넘김
			$k = $_POST['chk'][$i];

			$sql = " update shop_banner  
						set bn_use = '{$_POST['bn_use'][$k]}'
						  , bn_width = '{$_POST['bn_width'][$k]}'
						  , bn_height = '{$_POST['bn_height'][$k]}'
						  , bn_target = '{$_POST['bn_target'][$k]}'
						  , bn_link = '{$_POST['bn_link'][$k]}'
						  , bn_order = '{$_POST['bn_order'][$k]}'
					  where bn_id = '{$_POST['bn_id'][$k]}' ";
			sql_query($sql);
		}
	} 
	else if($_POST['act_button'] == "선택삭제") 
	{
		$upl_dir = TB_DATA_PATH."/banner";
		$upl = new upload_files($upl_dir);

		for($i=0; $i<$count; $i++)
		{
			// 실제 번호를 넘김
			$k = $_POST['chk'][$i];

			$bn_id = trim($_POST['bn_id'][$k]);
			$row = sql_fetch("select * from shop_banner where bn_id='$bn_id'");

			$upl->del($row['bn_file']);

			sql_query(" delete from shop_banner where bn_id = '$bn_id' ");
		}
	}

	goto_url(TB_MYPAGE_URL."/page.php?$q1&page=$page");
}
?>