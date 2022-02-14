<?php
if(!defined('_TUBEWEB_')) exit;

/* * ***********************************************************************
 * *
 * *  가맹점 관련 함수 모음
 * *
 * *********************************************************************** */

// 가맹점인가?
function is_partner($mb_id)
{
    if(!$mb_id) return '';

	$mb = get_member($mb_id, 'grade');
	$pt = sql_fetch("select state from shop_partner where mb_id = '$mb_id'");

    if(in_array($mb['grade'], array(2,3,4,5,6)) && $pt['state']) {
		return true;
	} else {
		return false;
	}
}

// 가맹점 정보를 리턴
function get_partner($mb_id, $fileds='*')
{
	return sql_fetch("select $fileds from shop_partner where mb_id = TRIM('$mb_id')");
}

// 가맹점 정산요청 정보를 리턴
function get_partner_payrun($index_no, $fileds='*')
{
	return sql_fetch("select $fileds from shop_partner_payrun where index_no = TRIM('$index_no')");
}

// 가맹점 관리비연장 정보를 리턴
function get_partner_term($index_no, $fileds='*')
{
	return sql_fetch("select $fileds from shop_partner_term where index_no = TRIM('$index_no')");
}

// 가맹점 계좌출력
function print_partner_bank($mb_id)
{
	$row = get_partner($mb_id, 'bank_name, bank_account, bank_holder');

	$info = array();
	$info[] = get_text($row['bank_name']); // 은행명
	$info[] = get_text($row['bank_account']); // 계좌번호
	$info[] = get_text($row['bank_holder']); // 예금주명

	if($info[0] && $info[1] && $info[2])
		$bank_str = implode(' ', $info);
	else
		$bank_str = '미등록';

	return $bank_str;
}

// 가맹점 계좌출력
function print_partner_bank2($bank_name, $bank_account, $bank_holder)
{
	$info = array();
	$info[] = get_text($bank_name); // 은행명
	$info[] = get_text($bank_account); // 계좌번호
	$info[] = get_text($bank_holder); // 예금주명

	if($info[0] && $info[1] && $info[2])
		$bank_str = implode(' ', $info);
	else
		$bank_str = '미등록';

	return $bank_str;
}

// 가맹점 등급별 설정값
function get_partner_basic($gb_no, $fields='*')
{
	$sql = " select $fields from shop_member_grade where gb_no = '$gb_no' ";
	return sql_fetch($sql);
}

// 만료일 기간연장
function get_term_date($term='1')
{
	global $config;

	// 관리비를 사용중인가?
	if($config['pf_expire_use']) {
		$term_date = date("Y-m-d", strtotime("+{$term} month", time()));
	} else {
		$term_date = '9999-12-31';
	}

	return $term_date;
}

// 실제 도메인만 추출
function get_basedomain($url)
{
	global $config;

	// 모든 공백을 제거
	$basedomain = preg_replace("/\s+/", "", $config['pf_basedomain']);

	$value = strtolower(trim($url));
	if(preg_match('/^(?:(?:[0-9a-z_]+):\/\/)?((?:[0-9a-z_\d\-]{2,}\.)+[0-9a-z_]{2,})(?::\d{1,5})?(?:\/[^\?]*)?(?:\?.+)?$/i', $value)) {
		preg_match('/([0-9a-z_\d\-]+(?:\.(?:'.$basedomain.')){1,2})(?::\d{1,5})?(?:\/[^\?]*)?(?:\?.+)?$/i', $value, $matches);
		$host = (!$matches[1]) ? $value : $matches[1];
	}

	return $host;
}

// 가맹점 PG결제 정보를 default 변수에 담는다.
function set_partner_value($mb_id)
{
	global $default;

	if(!is_partner($mb_id))
		return $default;

	$pt = get_partner($mb_id);

	$default['de_bank_use'] = $pt['de_bank_use']; // 무통장입금
    $default['de_card_use'] = $pt['de_card_use']; // 신용카드
    $default['de_card_direct_use'] = $pt['de_card_direct_use']; // 신용카드(카드번호)
	$default['de_iche_use'] = $pt['de_iche_use']; // 계좌이체
	$default['de_vbank_use'] = $pt['de_vbank_use']; // 가상계좌
	$default['de_hp_use'] = $pt['de_hp_use']; // 휴대폰
	$default['de_card_test'] = $pt['de_card_test']; // 결제 테스트
	$default['de_pg_service'] = $pt['de_pg_service']; // 결제대행사
	$default['de_tax_flag_use'] = $pt['de_tax_flag_use']; // 복합과세 결제
	$default['de_taxsave_use'] = $pt['de_taxsave_use']; // 현금영수증 발급사용
	$default['de_card_noint_use'] = $pt['de_card_noint_use']; // 신용카드 무이자할부사용
	$default['de_easy_pay_use'] = $pt['de_easy_pay_use']; // PG사 간편결제 버튼사용
	$default['de_escrow_use'] = $pt['de_escrow_use']; // Escrow 사용여부
	$default['de_kcp_mid'] = $pt['de_kcp_mid']; // NHN KCP SITE CODE
	$default['de_kcp_site_key'] = $pt['de_kcp_site_key']; // NHN KCP SITE KEY
	$default['de_lg_mid'] = $pt['de_lg_mid']; // LG유플러스 상점아이디
	$default['de_lg_mert_key'] = $pt['de_lg_mert_key']; // LG유플러스 MertKey
	$default['de_inicis_mid'] = $pt['de_inicis_mid']; // KG이니시스 상점아이디
	$default['de_inicis_admin_key']	= $pt['de_inicis_admin_key']; // KG이니시스 키패스워드
	$default['de_inicis_sign_key'] = $pt['de_inicis_sign_key']; // KG이니시스 웹결제 사인키
	$default['de_samsung_pay_use'] = $pt['de_samsung_pay_use']; // KG이니시스 삼성페이 버튼
	$default['de_bank_account'] = $pt['de_bank_account']; // 무통장입금계좌
	$default['de_kakaopay_mid'] = $pt['de_kakaopay_mid']; // 카카오페이 상점MID
	$default['de_kakaopay_key'] = $pt['de_kakaopay_key']; // 카카오페이 상점키
	$default['de_kakaopay_enckey'] = $pt['de_kakaopay_enckey']; // 카카오페이 EncKey
	$default['de_kakaopay_hashkey'] = $pt['de_kakaopay_hashkey']; // 카카오페이 HashKey
	$default['de_kakaopay_cancelpwd'] = $pt['de_kakaopay_cancelpwd']; // 카카오페이 결제취소 P/W
	$default['de_naverpay_mid'] = $pt['de_naverpay_mid']; // 네이버페이 가맹점 아이디
	$default['de_naverpay_cert_key'] = $pt['de_naverpay_cert_key']; // 네이버페이 가맹점 인증키
	$default['de_naverpay_button_key'] = $pt['de_naverpay_button_key']; // 네이버페이 버튼 인증키
	$default['de_naverpay_test'] = $pt['de_naverpay_test']; // 네이버페이 결제테스트 아이디
	$default['de_naverpay_mb_id'] = $pt['de_naverpay_mb_id']; // 네이버페이 결제테스트 아이디
	$default['de_naverpay_sendcost'] = $pt['de_naverpay_sendcost']; // 네이버페이 추가배송비 안내
	$default['de_allat_mid'] = $pt['de_allat_mid']; // KG올앳 상점아이디
	$default['de_allat_crosskey'] = $pt['de_allat_crosskey']; // KG올앳 크로스키
    $default['de_kiwoom_mid'] = $pt['de_kiwoom_mid']; // 키움페이 상점아이디
    $default['de_kiwoom_crosskey'] = $pt['de_kiwoom_crosskey']; // 키움페이 크로스키
    $default['de_card_installment'] = $pt['de_card_installment']; // 최대 할부가능 개월

    // 연구비카드
    $default['de_psys_rnd_service'] = $pt['de_psys_rnd_service']; // 서비스
    $default['de_psys_rnd_mid'] = $pt['de_psys_rnd_mid']; // 상점 아이디
	$default['de_psys_rnd_crosskey'] = $pt['de_psys_rnd_crosskey']; // 상점 크로스키

	// 장기무이자
    $default['de_psys_long_service'] = $pt['de_psys_long_service']; // 서비스
	$default['de_psys_long_mid'] = $pt['de_psys_long_mid']; // 상점 아이디
	$default['de_psys_long_crosskey'] = $pt['de_psys_long_crosskey']; // 상점 크로스키

    $default['de_psys_rnd_use']		= $pt['de_psys_rnd_use']; // 연구비카드
    $default['de_psys_long_use']		= $pt['de_psys_long_use']; // 장기무이자

	return $default;
}

/* * ***********************************************************************
 * *
 * *  가맹점 수수료관련 함수 모음
 * *
 * *********************************************************************** */

// 수수료 부여
function insert_pay($mb_id, $pay, $content='', $rel_table='', $rel_id='', $rel_action='', $referer='', $agent='')
{
	// 수수료가 없거나 승인된 가맹점이 아니라면 업데이트 할 필요 없음
	if($pay == 0 || !is_partner($mb_id)) { return 0; }

	// 이미 등록된 내역이라면 건너뜀
	if($rel_table || $rel_id || $rel_action)
	{
		$sql = " select count(*) as cnt
				   from shop_partner_pay
				  where mb_id = '$mb_id'
					and pp_rel_table = '$rel_table'
					and pp_rel_id = '$rel_id'
					and pp_rel_action = '$rel_action' ";
		$row = sql_fetch($sql);
		if($row['cnt'])
			return -1;
	}

	$pt_pay = get_pay_sum($mb_id); // 회원수수료
	$pp_balance = $pt_pay + $pay; // 잔액

	$sql = " insert into shop_partner_pay
				set mb_id = '$mb_id'
				  , pp_datetime = '".TB_TIME_YMDHIS."'
				  , pp_content = '".addslashes($content)."'
				  , pp_pay = '$pay'
				  , pp_use_pay = '0'
				  , pp_balance = '$pp_balance'
				  , pp_rel_table = '$rel_table'
				  , pp_rel_id = '$rel_id'
				  , pp_rel_action = '$rel_action'
				  , pp_referer = '$referer'
				  , pp_agent = '$agent' ";
	sql_query($sql);

	// 수수료를 사용한 경우 수수료 내역에 사용금액 기록
	if($pay < 0) {
		insert_use_pay($mb_id, $pay);
	}

	// 수수료 UPDATE
	$sql = " update shop_member set pay = '$pp_balance' where id = '$mb_id' ";
	sql_query($sql);

	// 누적수수료에 따른 자동 레벨업
	check_promotion($mb_id);

	return 1;
}

// 사용수수료 입력
function insert_use_pay($mb_id, $pay, $pp_id='')
{
	$pay1 = abs($pay);
	$sql = " select pp_id, pp_pay, pp_use_pay
			   from shop_partner_pay
			  where mb_id = '$mb_id'
				and pp_id <> '$pp_id'
				and pp_pay > pp_use_pay
			  order by pp_id asc ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$pay2 = $row['pp_pay'];
		$pay3 = $row['pp_use_pay'];

		if(($pay2 - $pay3) > $pay1) {
			$sql = " update shop_partner_pay
						set pp_use_pay = pp_use_pay + '$pay1'
					  where pp_id = '{$row['pp_id']}' ";
			sql_query($sql);
			break;
		} else {
			$pay4 = $pay2 - $pay3;
			$sql = " update shop_partner_pay
						set pp_use_pay = pp_use_pay + '$pay4'
					  where pp_id = '{$row['pp_id']}' ";
			sql_query($sql);
			$pay1 -= $pay4;
		}
	}
}

// 사용수수료 삭제
function delete_use_pay($mb_id, $pay)
{
	$pay1 = abs($pay);
	$sql = " select pp_id, pp_use_pay
			   from shop_partner_pay
			  where mb_id = '$mb_id'
				and pp_use_pay > 0
			  order by pp_id desc ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$pay2 = $row['pp_use_pay'];

		if($pay2 > $pay1) {
			$sql = " update shop_partner_pay
						set pp_use_pay = pp_use_pay - '$pay1'
					  where pp_id = '{$row['pp_id']}' ";
			sql_query($sql);
			break;
		} else {
			$sql = " update shop_partner_pay
						set pp_use_pay = '0'
					  where pp_id = '{$row['pp_id']}' ";
			sql_query($sql);

			$pay1 -= $pay2;
		}
	}
}

// 수수료 삭제
function delete_pay($mb_id, $rel_table, $rel_id, $rel_action)
{
	$result = false;
	if($rel_table || $rel_id || $rel_action)
	{
		// 수수료 내역정보
		$sql = " select *
				   from shop_partner_pay
				  where mb_id = '$mb_id'
					and pp_rel_table = '$rel_table'
					and pp_rel_id = '$rel_id'
					and pp_rel_action = '$rel_action' ";
		$row = sql_fetch($sql);

		if($row['pp_pay'] < 0) {
			$mb_id = $row['mb_id'];
			$pp_pay = abs($row['pp_pay']);

			delete_use_pay($mb_id, $pp_pay);
		} else {
			if($row['pp_use_pay'] > 0) {
				insert_use_pay($row['mb_id'], $row['pp_use_pay'], $row['pp_id']);
			}
		}

		$sql = " delete from shop_partner_pay
				  where mb_id = '$mb_id'
					and pp_rel_table = '$rel_table'
					and pp_rel_id = '$rel_id'
					and pp_rel_action = '$rel_action' ";
		$result = sql_query($sql, false);

		// pp_balance에 반영
		$sql = " update shop_partner_pay
					set pp_balance = pp_balance - '{$row['pp_pay']}'
				  where mb_id = '$mb_id'
					and pp_id > '{$row['pp_id']}' ";
		sql_query($sql);

		// 수수료 내역의 합을 구하고
		$sum_pay = get_pay_sum($mb_id);

		// 수수료 UPDATE
		$sql = " update shop_member set pay = '$sum_pay' where id = '$mb_id' ";
		$result = sql_query($sql);
	}

	return $result;
}

// 수수료합
function get_pay_sum($mb_id)
{
	$sql = " select sum(pp_pay) as sum_pay
			   from shop_partner_pay
			  where mb_id = '$mb_id' ";
	$row = sql_fetch($sql);

	return (int)$row['sum_pay'];
}

// 유형별 수수료합
function get_pay_status($mb_id, $rel_table, $select_add='')
{
	$sql = " select count(*) as cnt,
					sum(pp_pay) as pay
			   from shop_partner_pay
			  where mb_id = '$mb_id'
				and pp_rel_table = '$rel_table'
				$select_add ";
	$row = sql_fetch($sql);

	$info = array();
	$info['cnt'] = (int)$row['cnt'];
	$info['pay'] = (int)$row['pay'];

	return $info;
}

// 수수료합 (총적립액, 총지급액)
function get_pay_sheet($mb_id)
{
	$sql_where = " where mb_id = '$mb_id' ";

	$sql1 = " select sum(pp_pay) as pay from shop_partner_pay {$sql_where} and pp_pay > 0 ";
	$row1 = sql_fetch($sql1);

	$sql2 = " select sum(pp_pay) as pay from shop_partner_pay {$sql_where} and pp_pay < 0 ";
	$row2 = sql_fetch($sql2);

	$info = array();
	$info['pay'] = (int)$row1['pay'];
	$info['usepay'] = (int)$row2['pay'];

	return $info;
}

// 누적수수료에 따른 자동 레벨업
function check_promotion($mb_id)
{
	if(!is_partner($mb_id))
		return;

	// 수수료 총적립액
	$info = get_pay_sheet($mb_id);
	$sum_pay = $info['pay'];
	if($sum_pay <= 0)
		return;

	$mb = get_member($mb_id, 'grade');

	// 최상위 레벨이면 리턴
	if($mb['grade'] == 2)
		return;

	$sql = " select gb_no, gb_promotion
			   from shop_member_grade
			  where gb_no between '2' and '6'
			  order by gb_no asc ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		if($mb['grade'] == $row['gb_no'])
			break;

		if(!$row['gb_promotion'])
			continue;

		if($sum_pay >= $row['gb_promotion']) {
			$sql = " update shop_member set grade = '{$row['gb_no']}' where id = '$mb_id' ";
			sql_query($sql);
			break;
		}
	}
}

// 추천수수료 지급
function insert_anew_pay($mb_id)
{
	global $config;

	// 추천수수료를 사용을 하지 않는다면 리턴
	if(!$config['pf_anew_use']) return;

	// 추천수수료를 적용할 단계가 없다면 리턴
	if(!$config['pf_anew_benefit_dan']) return;

	// 신청자가 가맹점이 아니면 리턴
	if(!is_partner($mb_id)) return;

	// 신청자 정보
	$pt = get_partner($mb_id, 'mb_id, anew_grade, receipt_price');

	// 가맹점개설비가 없다면 리턴
	$reg_price = (int)$pt['receipt_price'];
	if($reg_price == 0) return;

	// 신청자의 추천인을 담고
	$mb = get_member($mb_id, 'pt_id');
	$pt_id = $mb['pt_id'];

	// 추천인이 가맹점이 아니면 리턴
	if(!is_partner($pt_id)) return;

	// 추천인은 본인이 될 수 없음
	if($mb_id == $pt_id) return;

	// 신청레벨에 따른 인센티브를 배열로 담는다
	$anew_benefit = explode(chr(30), $config['pf_anew_benefit_'.$pt['anew_grade']]);

	for($i=0; $i<$config['pf_anew_benefit_dan']; $i++)
	{
		// 추천인이 없거나 최고관리자라면 중지
		if(!$pt_id || $pt_id == 'admin')
			break;

		// 적용할 인센티브가 없다면 건너뜀
		$benefit = (int)trim($anew_benefit[$i]);
		if($benefit <= 0) continue;

		if($config['pf_anew_benefit_type'])
			$pt_pay = $benefit; // 설정금액(원)
		else
			$pt_pay = floor($reg_price * $benefit / 100); // 설정비율(%)

		insert_pay($pt_id, $pt_pay, $mb_id.'님 가맹점가입 축하', 'anew', $mb_id, '추천수수료');

		// 단계별 상위 추천인을 담고 다시 배열로 돌린다
		$mb = get_member($pt_id, 'pt_id');
		$pt_id = $mb['pt_id'];
	}
}

// 접속수수료 지급
function insert_visit_pay($mb_id, $remote_addr, $referer, $user_agent)
{
	global $config;

	// 접속수수료를 사용을 하지 않는다면 리턴
	if(!$config['pf_visit_use']) return;

	// 가맹점이 아니면 리턴
	if(!is_partner($mb_id)) return;

	// 가맹점 정보
	$mb = get_member($mb_id, 'grade');

	// 레벨에 따른 접속수수료 설정값
	$pb = get_partner_basic($mb['grade']);

	// 접속수수료가 없다면 리턴
	$pay = (int)$pb['gb_visit_pay'];

	if($pay == 0) return;

	$ip = preg_replace("/([0-9]+).([0-9]+).([0-9]+).([0-9]+)/", TB_IP_DISPLAY, $remote_addr);

	insert_pay($mb_id, $pay, "접속자 ({$ip})", "visit", $remote_addr, TB_TIME_YMD, $referer, $user_agent);
}

// 판매수수료 지급
function insert_sale_pay($pt_id, $od, $gs)
{
	global $config;

	// 판매수수료를 사용을 하지 않는다면 리턴
	if(!$config['pf_sale_use']) return;

	// 가맹점상품이면 리턴
	if($gs['use_aff']) return;

	// 가맹점이 아니면 리턴
	if(!is_partner($pt_id)) return;

	// 가맹점 정보
	$mb = get_member($pt_id, 'grade');

	$amount = 0;

	// 원가 계산
	if($config['pf_sale_flag']) {
		if($od['supply_price'] > 0) // 공급가
			$amount = $od['goods_price'] - $od['supply_price'];

		if($config['pf_sale_flag'] == 1)
			$amount = $amount - ($od['coupon_price'] + $od['use_point']); // 할인쿠폰 + 포인트결제
	} else {
		$amount = $od['use_price'] - $od['baesong_price']; // 순수결제액 - 배송비
	}

	// 적용할 금액이 없다면 리턴
	if($amount < 1) return;

	if($gs['ppay_type']) { // 개별설정
		$sale_benefit_dan  = $gs['ppay_dan'];
		$sale_benefit_type = $gs['ppay_rate'];
		$sale_benefit	   = explode(chr(30), $gs['ppay_fee']);
	} else { // 공통설정
		$sale_benefit_dan  = $config['pf_sale_benefit_dan'];
		$sale_benefit_type = $config['pf_sale_benefit_type'];
		$sale_benefit	   = explode(chr(30), $config['pf_sale_benefit_'.$mb['grade']]);
	}

	// 판매수수료를 적용할 단계가 없다면 리턴
	if($sale_benefit_dan < 1) return;

	for($i=0; $i<$sale_benefit_dan; $i++)
	{
		// 추천인이 없거나 최고관리자라면 중지
		if(!$pt_id || $pt_id == 'admin')
			break;

		// 적용할 인센티브가 없다면 건너뜀
		$benefit = (int)trim($sale_benefit[$i]);
		if($benefit <= 0) continue;

		$pt_pay = 0;

		if($sale_benefit_type)
			$pt_pay = (int)($benefit * $od['sum_qty']); // 설정금액(원)
		else
			$pt_pay = (int)($amount * $benefit / 100); // 설정비율(%)

		// 추천인 정보
		$mb = get_member($pt_id, 'pt_id, payment, payflag');

		// 개별 추가 판매수수료
		if($mb['payment']) {
			if($mb['payflag'])
				$pt_pay += (int)($mb['payment'] * $od['sum_qty']); // 설정금액(원)
			else
				$pt_pay += (int)($amount * $mb['payment'] / 100); // 설정비율(%)
		}

		// 적용할 수수료가 없다면 건너뜀
		if($pt_pay <= 0) continue;

		insert_pay($pt_id, $pt_pay, "주문번호 {$od['od_id']} ({$od['od_no']}) 배송완료", 'sale', $od['od_no'], $od['od_id']);

		// 상위 추천인을 담고 다시 배열로 돌린다
		$pt_id = $mb['pt_id'];

	} // for
}

// 판매수수료 예상가
function get_payment($gs_id)
{
	global $config, $member;

	// 판매수수료 노출여부 사용중이아니면 리턴
	if(!$config['pf_payment_yes']) return 0;

	// 가맹점이 아니면 리턴
	if(!is_partner($member['id'])) return 0;

	$gs = get_goods($gs_id);

	// 가맹점상품이면 리턴
	if($gs['use_aff']) return 0;

	// 원가 계산
	if($config['pf_sale_flag'])
		$amount = $gs['goods_price'] - $gs['supply_price']; // 판매가 - 공급가
	else
		$amount = $gs['goods_price']; // 판매가

	// 적용할 금액이 없다면 리턴
	if($amount < 1) return 0;

	if($gs['ppay_type']) { // 개별설정
		$sale_benefit_type = $gs['ppay_rate'];
		$sale_benefit	   = explode(chr(30), $gs['ppay_fee']);
	} else { // 공통설정
		$sale_benefit_type = $config['pf_sale_benefit_type'];
		$sale_benefit      = explode(chr(30), $config['pf_sale_benefit_'.$member['grade']]);
	}

	$benefit = (int)trim($sale_benefit[0]);

	if($sale_benefit_type)
		$pt_pay = $benefit; // 설정금액(원)
	else
		$pt_pay = (int)($amount * $benefit / 100); // 설정비율(%)

	// 개별 추가 판매수수료
	if($member['payment']) {
		if($member['payflag'])
			$pt_pay += $member['payment']; // 설정금액(원)
		else
			$pt_pay += (int)($amount * $member['payment'] / 100); // 설정비율(%)
	}

	if($pt_pay < 0)
		$pt_pay = 0;

	return $pt_pay;
}
?>