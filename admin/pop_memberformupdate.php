<?php
include_once("./_common.php");

check_demo();

check_admin_token();

if($mb_id == 'admin') {
	alert('관리자는 수정하실 수 없습니다.');
}

unset($mfrm);
$mfrm['name']			= $_POST['name']; // 회원명
$mfrm['pt_id']			= $_POST['pt_id']; // 추천인
$mfrm['gender']			= strtoupper($_POST['gender']); // 성별
$mfrm['birth_type']		= strtoupper($_POST['birth_type']); // 음력/양력
$mfrm['birth_year']		= $_POST['birth_year']; // 년
$mfrm['birth_month']	= sprintf('%02d',$_POST['birth_month']); // 월
$mfrm['birth_day']		= sprintf('%02d',$_POST['birth_day']); // 일
$mfrm['age']			= substr(date("Y")-$_POST['birth_year'],0,1).'0'; // 연령대
$mfrm['email']			= $_POST['email']; // 이메일
$mfrm['grade']			= $mb_grade; // 레벨
$mfrm['cellphone']		= replace_tel($_POST['cellphone']); //핸드폰
$mfrm['telephone']		= replace_tel($_POST['telephone']); //전화번호
$mfrm['zip']			= $_POST['zip']; // 우편번호
$mfrm['addr1']			= $_POST['addr1']; // 주소
$mfrm['addr2']			= $_POST['addr2']; // 상세주소
$mfrm['addr3']			= $_POST['addr3']; // 참고항목
$mfrm['addr_jibeon']	= $_POST['addr_jibeon']; // 지번주소
$mfrm['use_good']		= $_POST['use_good']; // 개별상품판매
$mfrm['use_pg']			= $_POST['use_pg']; // 개인결제

$mfrm['use_pg_rnd']			= $_POST['use_pg_rnd']; // 연구비카드
$mfrm['use_pg_long']			= $_POST['use_pg_long']; // 장기무이자

$mfrm['payment']		= $_POST['payment']; // 추가 판매수수료
$mfrm['payflag']		= $_POST['payflag']; // 추가 판매수수료 유형
$mfrm['homepage']		= $_POST['homepage']; // 도메인
$mfrm['theme']			= $_POST['theme']; //테마스킨
$mfrm['mobile_theme']	= $_POST['mobile_theme']; //모바일테마스킨
$mfrm['memo']			= $_POST['memo']; // 메모
$mfrm['intercept_date'] = $_POST['intercept_date']; // 접근차단일자
$mfrm['auth_1']			= $_POST['auth_1'];
$mfrm['auth_2']			= $_POST['auth_2'];
$mfrm['auth_3']			= $_POST['auth_3'];
$mfrm['auth_4']			= $_POST['auth_4'];
$mfrm['auth_5']			= $_POST['auth_5'];
$mfrm['auth_6']			= $_POST['auth_6'];
$mfrm['auth_7']			= $_POST['auth_7'];
$mfrm['auth_8']			= $_POST['auth_8'];
$mfrm['auth_9']			= $_POST['auth_9'];
$mfrm['auth_10']		= $_POST['auth_10'];

if($_POST['passwd']) {
	$mfrm['passwd'] = $_POST['passwd']; // 패스워드
}
update("shop_member", $mfrm," where id='$mb_id'");

$mb = get_member($mb_id);
$pt = get_partner($mb_id);

if($pt['mb_id']) {
	$sql = " update shop_partner
				set bank_name    = '{$_POST['bank_name']}',
					bank_account = '{$_POST['bank_account']}',
					bank_holder  = '{$_POST['bank_holder']}'
			  where mb_id = '$mb_id'";
	sql_query($sql, FALSE);
}

if(in_array($mb_grade, array(9,8,7))) {
	$sql = " update shop_member
				set term_date = '0000-00-00',
					anew_date = '0000-00-00'
			  where mb_id = '$mb_id'";
	sql_query($sql, FALSE);

	sql_query("delete from shop_partner where mb_id='$mb_id'");

	// 카테고리 테이블 DROP
	$target_table = 'shop_cate_'.$mb_id;
	sql_query(" drop table {$target_table} ", FALSE);

	// 카테고리 폴더 전체 삭제
	rm_rf(TB_DATA_PATH.'/category/'.$mb_id);

} else if(in_array($mb_grade, array(6,5,4,3,2))) {
	if(is_null_time($mb['term_date'])) { // 만료일
		$expire_date = get_term_date($config['pf_expire_term']);
		$sql = "update shop_member set term_date = '$expire_date' where mb_id = '$mb_id'";
		sql_query($sql, FALSE);
	}
	if(is_null_time($mb['anew_date'])) { // 등업일
		$sql = "update shop_member set anew_date = '".TB_TIME_YMD."' where mb_id = '$mb_id'";
		sql_query($sql, FALSE);
	}

	// 카테고리 생성
	sql_member_category($mb_id);

	// 회원 아이디가 존재하지 않을 경우만 실행
	if(!$pt['mb_id']) {
		$pb = get_partner_basic($mb_grade);

		unset($pfrm);
		$pfrm['mb_id']			 = $mb_id; //회원명
		$pfrm['bank_name']		 = $_POST['bank_name']; //은행명
		$pfrm['bank_account']	 = $_POST['bank_account']; //계좌번호
		$pfrm['bank_holder']	 = $_POST['bank_holder']; //예금주
		$pfrm['anew_grade']		 = $mb_grade; //레벨 인덱스번호
		$pfrm['receipt_price']	 = $pb['gb_anew_price']; //분양개설비
		$pfrm['deposit_name']	 = $mb['name']; //입금자명
		$pfrm['pay_settle_case'] = 1; //결제방식 1은 무통장, 2는 신용카드결제
		$pfrm['memo']			 = '관리자에 의해 승인처리 되었습니다.'; //메모
		$pfrm['state']			 = 1; //처리결과 1은 완료, 0은 대기
		$pfrm['reg_ip']			 = $_SERVER['REMOTE_ADDR'];
		$pfrm['reg_time']		 = TB_TIME_YMDHIS;
		$pfrm['update_time']	 = TB_TIME_YMDHIS;
		insert("shop_partner", $pfrm);

		insert_anew_pay($mb_id); // 추천수수료

	} else {
		// 신청내역이 이미 있으나 승인처리가 되지 않았을경우
		if(!$pt['state']) {
			$sql = " update shop_partner
						set state = '1'
						  , anew_grade = '$mb_grade'
					  where mb_id = '$mb_id' ";
			sql_query($sql);

			insert_anew_pay($mb_id); // 추천수수료
		}
	}
}

goto_url(TB_ADMIN_URL.'/pop_memberform.php?mb_id='.$mb_id);
?>