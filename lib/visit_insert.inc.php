<?php
if(!defined('_TUBEWEB_')) exit; // 개별 페이지 접근 불가

// 컴퓨터의 아이피와 쿠키에 저장된 아이피가 다르다면 테이블에 반영함
if(get_cookie('ck_visit_ip') != $_SERVER['REMOTE_ADDR'])
{
    set_cookie('ck_visit_ip', $_SERVER['REMOTE_ADDR'], 86400); // 하루동안 저장

    $tmp_row = sql_fetch(" select max(vi_id) as max_vi_id from shop_visit ");
    $vi_id = $tmp_row['max_vi_id'] + 1;	

    // $_SERVER 배열변수 값의 변조를 이용한 SQL Injection 공격을 막는 코드입니다. 110810
    $remote_addr = escape_trim($_SERVER['REMOTE_ADDR']);
    $referer = "";
    if(isset($_SERVER['HTTP_REFERER']))
        $referer = escape_trim(clean_xss_tags($_SERVER['HTTP_REFERER']));
    $user_agent  = escape_trim(clean_xss_tags($_SERVER['HTTP_USER_AGENT']));
    $sql = " insert shop_visit ( vi_id, mb_id, vi_ip, vi_date, vi_time, vi_referer, vi_agent ) values ( '{$vi_id}', '{$pt_id}', '{$remote_addr}', '".TB_TIME_YMD."', '".TB_TIME_HIS."', '{$referer}', '{$user_agent}' ) ";
    $result = sql_query($sql, FALSE);

    // 정상으로 INSERT 되었다면 방문자 합계에 반영
    if($result) {
		$sql = " select vs_count as cnt 
				   from shop_visit_sum 
				   where vs_date = '".TB_TIME_YMD."' 
				     and mb_id = '{$pt_id}' ";
        $row = sql_fetch($sql);
		$tmp_cnt = (int)$row['cnt'];
        if($tmp_cnt) {
			$sql = " update shop_visit_sum 
						set vs_count = vs_count + 1 
						where vs_date = '".TB_TIME_YMD."' 
						  and mb_id = '{$pt_id}' ";
            sql_query($sql);
		} else {
			$sql = " insert shop_visit_sum (vs_count, vs_date, mb_id) values (1, '".TB_TIME_YMD."', '{$pt_id}') ";
			sql_query($sql);
		}

        // INSERT, UPDATE 된건이 있다면 기본환경설정 테이블에 저장
        // 방문객 접속시마다 따로 쿼리를 하지 않기 위함 (엄청난 쿼리를 줄임 ^^)
		
		// 접속수수료 지급
		insert_visit_pay($pt_id, $remote_addr, $referer, $user_agent);

        // 오늘
        $sql = " select vs_count as cnt 
				   from shop_visit_sum 
				   where vs_date = '".TB_TIME_YMD."' 
					 and mb_id = '{$pt_id}' ";
        $row = sql_fetch($sql);
        $vi_today = (int)$row['cnt'];

        // 어제
        $sql = " select vs_count as cnt 
				   from shop_visit_sum
				   where vs_date = DATE_SUB('".TB_TIME_YMD."', INTERVAL 1 DAY)
				     and mb_id = '{$pt_id}' ";
        $row = sql_fetch($sql);
        $vi_yesterday = (int)$row['cnt'];

        // 최대
        $sql = " select max(vs_count) as cnt 
				   from shop_visit_sum 
				  where mb_id = '{$pt_id}' ";
        $row = sql_fetch($sql);
        $vi_max = (int)$row['cnt'];

        // 전체
        $sql = " select sum(vs_count) as total from shop_visit_sum where mb_id = '{$pt_id}' ";
        $row = sql_fetch($sql);
        $vi_sum = (int)$row['total'];

        $visit = '오늘:'.$vi_today.', 어제:'.$vi_yesterday.', 최대:'.$vi_max.', 전체:'.$vi_sum;		

        // 기본설정 테이블에 방문자수를 기록한 후
        // 방문자수 테이블을 읽지 않고 출력한다.
        // 쿼리의 수를 상당부분 줄임
        $sql = " update shop_member 
					set vi_today = '{$vi_today}',
						vi_yesterday = '{$vi_yesterday}',
						vi_max = '{$vi_max}',
						vi_sum = '{$vi_sum}',
						vi_history = '{$visit}'
				  where id = '{$pt_id}' ";
		sql_query($sql);
    }
}
?>