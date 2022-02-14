<?php
include_once("./_common.php");

check_demo();

$_POST = array_map('trim', $_POST);

if($_POST["token"] && get_session("ss_token") == $_POST["token"]) {
	// 맞으면 세션을 지워 다시 입력폼을 통해서 들어오도록 한다.
	set_session("ss_token", "");
} else {
	alert("잘못된 접근 입니다.");
	exit;
}

if(substr_count($_POST['memo'], "&#") > 50) {
    alert("내용에 올바르지 않은 코드가 다수 포함되어 있습니다.");
}

$upl_dir = TB_DATA_PATH."/board/".$boardid;
$upl = new upload_files($upl_dir);

if(!$_POST['subject']) { alert("게시판 제목을 입력하세요."); }
if(!$_POST['writer_s']) { alert("작성자명이 없습니다."); }

$upload_max_filesize = ini_get('upload_max_filesize');
if(empty($_POST))
    alert("파일 또는 글내용의 크기가 서버에서 설정한 값을 넘어 오류가 발생하였습니다.\\n\\npost_max_size=".ini_get('post_max_size')." , upload_max_filesize=$upload_max_filesize\\n\\n게시판관리자 또는 서버관리자에게 문의 바랍니다.");

$writer = 0;

if($_POST['havehtml']!='Y')	$_POST['havehtml'] = "N";
if($_POST['btype']!='1') $_POST['btype'] = '2';
if($_POST['issecret']!='Y') $_POST['issecret'] = "N";
if($is_member) $writer = $member['index_no'];

$sql = "select fid,thread,passwd from shop_board_{$boardid} where index_no='$index_no'";
$res = sql_query($sql);
$row = sql_fetch_row($res);
$fid = $row[0];
$thread = $row[1];
$passwds = $row[2];

$sql = "select thread,right(thread,1) 
          from shop_board_{$boardid} 
		 where fid=$fid 
		   and length(thread) = length('$thread')+1 
		   and locate('$thread',thread) = 1 
		 order by thread desc limit 1";
$result = sql_query($sql);
$rows = sql_num_rows($result);
if($rows) {
	$row = sql_fetch_row($result);
	$thread_head = substr($row[0],0,-1);
	$thread_foot = ++$row[1];
	$new_thread = $thread_head . $thread_foot;
} else {	
	$new_thread = $thread . "A";	
}

if($_POST['mode'] == "w") {
	$sql_commend = " , btype	= '$_POST[btype]'                
					 , ca_name	= '$_POST[ca_name]'
					 , issecret	= '$_POST[issecret]'
					 , havehtml	= '$_POST[havehtml]'
					 , writer	= '$writer'
					 , writer_s	= '$_POST[writer_s]'
					 , subject	= '$_POST[subject]'
					 , memo		= '$_POST[memo]'									
					 , passwd	= '$_POST[passwd]'
					 , average	= '$_POST[average]'
					 , product	= '$_POST[product]' 
					 , pt_id	= '$pt_id' ";

	if($_POST['del_file1']) {
		$upl->del($_POST['del_file1']);
		delete_board_thumbnail($boardid, $_POST['del_file1']);
		$sql_commend .= " , fileurl1 = '' ";
	}
	if($_POST['del_file2']) {
		$upl->del($_POST['del_file2']);
		delete_board_thumbnail($boardid, $_POST['del_file2']);
		$sql_commend .= " , fileurl2 = '' ";
	}
	if($_FILES['file1']['name']) {
		$new_file1 = $upl->upload($_FILES['file1']); 
		$sql_commend .= " , fileurl1 = '$new_file1' ";
	}
	if($_FILES['file2']['name']) {
		$new_file2 = $upl->upload($_FILES['file2']); 
		$sql_commend .= " , fileurl2 = '$new_file2' ";
	}
	   
	$sql = " insert into shop_board_{$boardid}
				set fid		= '$fid'
				  , wdate	= '".TB_SERVER_TIME."'
				  , wip		= '{$_SERVER['REMOTE_ADDR']}'
				  , thread	= '$new_thread' 
				{$sql_commend} ";
	sql_query($sql);

	goto_url(TB_BBS_URL."/list.php?boardid=$boardid");
} else {
	alert("정상적인 접근이 아닌것 같습니다.");
}
?>