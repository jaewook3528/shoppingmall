<?php
include_once("./_common.php");

check_demo();

check_admin_token();

if(!preg_match("/^([A-Za-z0-9_]{1,10})$/", $gr_id))
    alert("그룹 ID는 공백없이 영문자, 숫자, _ 만 사용 가능합니다. (10자 이내)");

if(!$gr_subject) alert("그룹 제목을 입력하세요.");

if($w == "") 
{
    $sql = " select count(*) as cnt from shop_board_group where gr_id = '$_POST[gr_id]' ";
    $row = sql_fetch($sql);
    if($row['cnt']) 
        alert("이미 존재하는 그룹 ID 입니다.");

    $sql = " insert into shop_board_group
                set gr_id = '$_POST[gr_id]',
                    gr_subject = '$_POST[gr_subject]' ";
    sql_query($sql);
} 
else if($w == "u") 
{
    $sql = " update shop_board_group
                set gr_subject = '$_POST[gr_subject]'
              where gr_id = '$_POST[gr_id]' ";
    sql_query($sql);
} 
else
    alert("제대로 된 값이 넘어오지 않았습니다.");

goto_url(TB_ADMIN_URL."/config.php?code=board_group_form&w=u&gr_id=$gr_id&page=$page");
?>