<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$upl_dir = TB_DATA_PATH."/board/boardimg";
$upl = new upload_files($upl_dir);

if(!$_POST['boardname']) { alert("게시판 제목을 입력하세요."); }

if($file = $_POST['topfile']) {
    if(!preg_match("/\.(php|htm[l]?)$/i", $file)) {
        alert("상단 파일 경로가 php, html 파일이 아닙니다.");
    }
}
if($file = $_POST['downfile']) {
    if(!preg_match("/\.(php|htm[l]?)$/i", $file)) {
        alert("하단 파일 경로가 php, html 파일이 아닙니다.");
    }
}

if($image_head_del) {
	$upl->del($image_head_del);
	$value['fileurl1'] = '';
}
if($image_tail_del) {
	$upl->del($image_tail_del);
	$value['fileurl2'] = '';
}

if($_FILES['image_head']['name']) {
	$value['fileurl1'] = $upl->upload($_FILES['image_head']); 
}
if($_FILES['image_tail']['name']) {
	$value['fileurl2'] = $upl->upload($_FILES['image_tail']);  
}

if($w == "") {
	$value['gr_id']				= $_POST['gr_id'];
	$value['boardname']			= $_POST['boardname'];
	$value['skin']				= $_POST['skin'];
	$value['list_skin']			= $_POST['skin'];
	$value['list_priv']			= $_POST['list_priv'];
	$value['read_priv']			= $_POST['read_priv'];
	$value['write_priv']		= $_POST['write_priv'];
	$value['reply_priv']		= $_POST['reply_priv'];
	$value['tail_priv']			= $_POST['tail_priv'];
	$value['topfile']			= $_POST['topfile'];
	$value['downfile']			= $_POST['downfile'];
	$value['use_secret']		= $_POST['use_secret'];
	$value['use_category']		= $_POST['use_category'];
	$value['usecate']			= $_POST['usecate'];
	$value['usefile']			= $_POST['usefile'];
	$value['usereply']			= $_POST['usereply'];
	$value['usetail']			= $_POST['usetail'];
	$value['width']				= $_POST['width'];
	$value['page_num']			= $_POST['page_num'];
	$value['read_list']			= $_POST['read_list'];
	$value['list_cut']			= $_POST['list_cut'];
	$value['content_head']		= $_POST['content_head'];
	$value['content_tail']		= $_POST['content_tail'];
	$value['insert_content']	= $_POST['insert_content'];
	insert("shop_board_conf",$value);
	$bo_table = sql_insert_id();

	include_once(TB_ADMIN_PATH."/config/board_sql.php");
	@sql_query($board_table);
	@sql_query($board_tail_table);
	$path = TB_DATA_PATH."/board/".$bo_table;
	exec("mkdir  $path");
 	exec("chmod 707 $path");

	goto_url(TB_ADMIN_URL."/config.php?code=board_form&w=u&bo_table=$bo_table");

} else if($w == "u") {
	$value['gr_id']				= $_POST['gr_id'];
	$value['boardname']			= $_POST['boardname'];
	$value['skin']				= $_POST['skin'];
	$value['list_skin']			= $_POST['skin'];
	$value['list_priv']			= $_POST['list_priv'];
	$value['read_priv']			= $_POST['read_priv'];
	$value['write_priv']		= $_POST['write_priv'];
	$value['reply_priv']		= $_POST['reply_priv'];
	$value['tail_priv']			= $_POST['tail_priv'];
	$value['topfile']			= $_POST['topfile'];
	$value['downfile']			= $_POST['downfile'];
	$value['use_secret']		= $_POST['use_secret'];
	$value['use_category']		= $_POST['use_category'];
	$value['usecate']			= $_POST['usecate'];
	$value['usefile']			= $_POST['usefile'];
	$value['usereply']			= $_POST['usereply'];
	$value['usetail']			= $_POST['usetail'];
	$value['width']				= $_POST['width'];
	$value['page_num']			= $_POST['page_num'];
	$value['read_list']			= $_POST['read_list'];
	$value['list_cut']			= $_POST['list_cut'];
	$value['content_head']		= $_POST['content_head'];
	$value['content_tail']		= $_POST['content_tail'];
	$value['insert_content']	= $_POST['insert_content'];
	update("shop_board_conf",$value," where index_no='$bo_table'");
	
	goto_url(TB_ADMIN_URL."/config.php?code=board_form&w=u&bo_table=$bo_table$qstr&page=$page");
}
?>