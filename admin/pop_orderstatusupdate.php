<?php
include_once("./_common.php");
include_once(TB_LIB_PATH.'/mailer.lib.php');

uf_write_log_mbg($_POST);

check_demo();

check_admin_token();

$chk_count = count($_POST['chk']);
if(!$chk_count)
    alert('처리할 자료를 하나 이상 선택해 주십시오.');


if( $_POST['Psys_enc_data'] ) {
    include_once(TB_SHOP_PATH.'/psys/PsysPay.php');
    $t = PsysPay::pgDataDecrypt( $_POST['Psys_pay_type'], $_POST['Psys_enc_data'] ) ;
    if( ! PsysPay::cancelReqIsValid($t) ) {
        alert("정상적인 취소 요청이 아닙니다.");
    }
    //var_dump($_POST, $t, $Psys_resultmsg); exit;
}

$od_sms_ipgum_check		= 0;
$od_sms_baesong_check	= 0;
$od_sms_delivered_check = 0;
$od_sms_cancel_check	= 0;
$od_cancel_change		= 0;

//==============================================================================
// 주문상태변경
//------------------------------------------------------------------------------
$send_mail_no = array();
for($i=0; $i<$chk_count; $i++)
{
	// 실제 번호를 넘김
	$k				= $_POST['chk'][$i];
	$od_no			= $_POST['od_no'][$k];
	$change_status  = $_POST['change_status'][$k];
	$current_status = $_POST['current_status'][$k];
	$delivery		= trim($_POST['delivery'][$k]);
	$delivery_no	= trim($_POST['delivery_no'][$k]);
    $od = get_order($od_no);

	if($_POST['act_button'] == '입금대기') $change_status = 1;
	if($_POST['act_button'] == '입금완료') $change_status = 2;
	if($_POST['act_button'] == '주문취소') $change_status = 6;
	if($_POST['act_button'] == '전체반품') $change_status = 7;
	if($_POST['act_button'] == '전체환불') $change_status = 9;

	switch($change_status) {
		case '1': // 입금대기
			if($current_status != 2) continue;
			change_order_status_1($od_no);
			break;
		case '2': // 입금완료
			if($current_status != 1) continue;
			change_order_status_2($od_no);
			$od_sms_ipgum_check++;
			$send_mail_no[$od['od_id']] = $od_no;
			break;
		case '3': // 배송준비
			if($current_status != 2) continue;
			change_order_status_3($od_no, $delivery, $delivery_no);
			break;
		case '4': // 배송중
			if($current_status != 3) continue;
			change_order_status_4($od_no, $delivery, $delivery_no);
			$od_sms_baesong_check++;
			break;
		case '5': // 배송완료
			if(!in_array($current_status, array(3,4))) continue;
			change_order_status_5($od_no);
			$od_sms_delivered_check++;
			break;
		case '6': // 취소
			if($current_status != 1) continue;
			change_order_status_6($od_no);
			$od_sms_cancel_check++;
			$od_cancel_change++;
			break;
		case '7': // 반품
			if($current_status != 5) continue;
			change_order_status_7($od_no);
			$od_cancel_change++;
			break;
		case '8': // 교환
			if($current_status != 5) continue;
			change_order_status_8($od_no);
			break;
		case '9': // 환불
			if(!in_array($current_status, array(2,3))) continue;
			change_order_status_9($od_no);
			$od_sms_cancel_check++;
			$od_cancel_change++;
			break;
	}
}
//------------------------------------------------------------------------------
//==============================================================================
// 입금완료 메일 전송
//------------------------------------------------------------------------------
foreach ($send_mail_no as $k => $v)
{
    $od = get_order($k);
    $od['type']='passbook';
    ipgum_send_mail($od);
}
//------------------------------------------------------------------------------
//==============================================================================
// 문자전송
//------------------------------------------------------------------------------
if($od_sms_ipgum_check)
	icode_order_sms_send($od_hp, 3, $od_id); // 입금완료 문자

if($od_sms_baesong_check)
	icode_order_sms_send($od_hp, 4, $od_id); // 배송중 문자

if($od_sms_delivered_check)
	icode_order_sms_send($od_hp, 6, $od_id); // 배송완료 문자

if($od_sms_cancel_check)
	icode_order_sms_send($od_hp, 5, $od_id); // 주문취소 문자
//------------------------------------------------------------------------------

// 상품 모두 취소일 경우 주문상태 변경
$mod_history = '';
$pg_res_cd = '';
$pg_res_msg = '';
$pg_cancel_log = '';

if($od_cancel_change) {
	$sql = " select COUNT(*) as od_count1,
                    SUM(IF(dan = '6' OR dan = '7' OR dan = '9', 1, 0)) as od_count2,
					SUM(refund_price) as od_refund_price,
					SUM(use_price) as od_receipt_price
			   from shop_order
			  where od_id = '$od_id' ";
    $row = sql_fetch($sql);

	if($row['od_count1'] == $row['od_count2']) {
		// PG 신용카드 결제 취소일 때
		$od_receipt_price = $row['od_receipt_price'];
		if($od_receipt_price > 0 && $row['od_refund_price'] == 0) {
			$sql = " select * from shop_order where od_id = '$od_id' ";
			$od = sql_fetch($sql);

			if($od['od_tno'] && ($od['paymethod'] == '신용카드' || $od['paymethod'] == '간편결제' || $od['paymethod'] == 'KAKAOPAY') || ($od['od_pg'] == 'inicis' && $od['paymethod'] == '삼성페이')) {
				// 가맹점 PG결제 정보
				$default = set_partner_value($od['od_settle_pid']);

				switch($od['od_pg']) {
					case 'lg':
						include_once(TB_SHOP_PATH.'/settle_lg.inc.php');

						$LGD_TID = $od['od_tno'];

						$xpay = new XPay($configPath, $CST_PLATFORM);

						// Mert Key 설정
						$xpay->set_config_value('t'.$LGD_MID, $default['de_lg_mert_key']);
						$xpay->set_config_value($LGD_MID, $default['de_lg_mert_key']);

						$xpay->Init_TX($LGD_MID);

						$xpay->Set('LGD_TXNAME', 'Cancel');
						$xpay->Set('LGD_TID', $LGD_TID);

						if($xpay->TX()) {
							$res_cd = $xpay->Response_Code();
							if($res_cd != '0000' && $res_cd != 'AV11') {
								$pg_res_cd = $res_cd;
								$pg_res_msg = $xpay->Response_Msg();
							}
						} else {
							$pg_res_cd = $xpay->Response_Code();
							$pg_res_msg = $xpay->Response_Msg();
						}
						break;
					case 'inicis':
						include_once(TB_SHOP_PATH.'/settle_inicis.inc.php');
						$cancel_msg = iconv_euckr('쇼핑몰 운영자 승인 취소');

						/*********************
						 * 3. 취소 정보 설정 *
						 *********************/
						$inipay->SetField("type",      "cancel");                        // 고정 (절대 수정 불가)
						$inipay->SetField("mid",       $default['de_inicis_mid']);       // 상점아이디
						/**************************************************************************************************
						 * admin 은 키패스워드 변수명입니다. 수정하시면 안됩니다. 1111의 부분만 수정해서 사용하시기 바랍니다.
						 * 키패스워드는 상점관리자 페이지(https://iniweb.inicis.com)의 비밀번호가 아닙니다. 주의해 주시기 바랍니다.
						 * 키패스워드는 숫자 4자리로만 구성됩니다. 이 값은 키파일 발급시 결정됩니다.
						 * 키패스워드 값을 확인하시려면 상점측에 발급된 키파일 안의 readme.txt 파일을 참조해 주십시오.
						 **************************************************************************************************/
						$inipay->SetField("admin",     $default['de_inicis_admin_key']); // 비대칭 사용키 키패스워드
						$inipay->SetField("tid",       $od['od_tno']);                   // 취소할 거래의 거래아이디
						$inipay->SetField("cancelmsg", $cancel_msg);                     // 취소사유

						/****************
						 * 4. 취소 요청 *
						 ****************/
						$inipay->startAction();

						/****************************************************************
						 * 5. 취소 결과                                           	*
						 *                                                        	*
						 * 결과코드 : $inipay->getResult('ResultCode') ("00"이면 취소 성공)  	*
						 * 결과내용 : $inipay->getResult('ResultMsg') (취소결과에 대한 설명) 	*
						 * 취소날짜 : $inipay->getResult('CancelDate') (YYYYMMDD)          	*
						 * 취소시각 : $inipay->getResult('CancelTime') (HHMMSS)            	*
						 * 현금영수증 취소 승인번호 : $inipay->getResult('CSHR_CancelNum')    *
						 * (현금영수증 발급 취소시에만 리턴됨)                          *
						 ****************************************************************/

						$res_cd  = $inipay->getResult('ResultCode');
						$res_msg = $inipay->getResult('ResultMsg');

						if($res_cd != '00') {
							$pg_res_cd = $res_cd;
							$pg_res_msg = iconv_utf8($res_msg);
						}
						break;
					case 'KAKAOPAY':
						include_once(TB_SHOP_PATH.'/settle_kakaopay.inc.php');
						$_REQUEST['TID']               = $od['od_tno'];
						$_REQUEST['Amt']               = $od_receipt_price;
						$_REQUEST['CancelMsg']         = '쇼핑몰 운영자 승인 취소';
						$_REQUEST['PartialCancelCode'] = 0;
						include TB_SHOP_PATH.'/kakaopay/kakaopay_cancel.php';
						break;
					case 'kcp':
						include_once(TB_SHOP_PATH.'/settle_kcp.inc.php');
						require_once(TB_SHOP_PATH.'/kcp/pp_ax_hub_lib.php');

						// locale ko_KR.euc-kr 로 설정
						setlocale(LC_CTYPE, 'ko_KR.euc-kr');

						$c_PayPlus = new C_PP_CLI_T;

						$c_PayPlus->mf_clear();

						$tno = $od['od_tno'];
						$tran_cd = '00200000';
						$cancel_msg = iconv_euckr('쇼핑몰 운영자 승인 취소');
						$cust_ip = $_SERVER['REMOTE_ADDR'];
						$bSucc_mod_type = "STSC";

						$c_PayPlus->mf_set_modx_data( "tno",      $tno );  // KCP 원거래 거래번호
						$c_PayPlus->mf_set_modx_data( "mod_type", $bSucc_mod_type );  // 원거래 변경 요청 종류
						$c_PayPlus->mf_set_modx_data( "mod_ip",   $cust_ip );  // 변경 요청자 IP
						$c_PayPlus->mf_set_modx_data( "mod_desc", $cancel_msg );  // 변경 사유

						$c_PayPlus->mf_do_tx( $tno,  $g_conf_home_dir, $g_conf_site_cd,
											  $g_conf_site_key,  $tran_cd,    "",
											  $g_conf_gw_url,  $g_conf_gw_port,  "payplus_cli_slib",
											  $ordr_idxx, $cust_ip, "3" ,
											  0, 0, $g_conf_key_dir, $g_conf_log_dir);

						$res_cd  = $c_PayPlus->m_res_cd;
						$res_msg = $c_PayPlus->m_res_msg;

						if($res_cd != '0000') {
							$pg_res_cd = $res_cd;
							$pg_res_msg = iconv_utf8($res_msg);
						}

						// locale 설정 초기화
						setlocale(LC_CTYPE, '');
						break;
					case 'allat':
						include TB_SHOP_PATH.'/allat/allat_cancel.php';

						if($res_cd != '0000') {
							$pg_res_cd = $res_cd;
							$pg_res_msg = iconv_utf8($res_msg);
						}
						break;
                    case 'kiwoom':
                        include_once(TB_PATH.'/plugin/kiwoompay/UfKiwoomPay.php');
                        $ufKiwoomPay = new UfKiwoomPay();
                        $r = $ufKiwoomPay->procCardCancel($od);

                        if( ! in_array($r['res_resultcode'], array("0000", "9112") ) ){
                            $pg_res_cd = $r['res_resultcode'];
                            $pg_res_msg = $r['res_errormessage'];
                        }

                        break;
				}

				// PG 취소요청 성공했으면
				if($pg_res_cd == '') {
					$pg_cancel_log = ' PG 신용카드 승인취소 처리';
				}
			}

			// 전체취소
			$sql = " select index_no from shop_order where od_id = '$od_id' order by index_no asc ";
			$res = sql_query($sql);
			while($row=sql_fetch_array($res)) {
				$sql = " update shop_order
							set refund_price = use_price
						  where index_no = '{$row['index_no']}' ";
				sql_query($sql);
			}
		}

		// 관리자 주문취소 로그
		$mod_history = TB_TIME_YMDHIS.' '.$member['id'].' 주문취소 처리'.$pg_cancel_log."\n";
    }
}

if($mod_history) { // 주문변경 히스토리 기록
	$sql = " update shop_order
				set od_mod_history = CONCAT(od_mod_history,'$mod_history')
			  where od_id = '$od_id' ";
	sql_query($sql);
}

$url = TB_ADMIN_URL."/pop_orderform.php?od_id=$od_id";

// 신용카드 취소 때 오류가 있으면 알림
if($pg_res_cd && $pg_res_msg) {
    alert('오류코드 : '.$pg_res_cd.' 오류내용 : '.$pg_res_msg, $url);
}

goto_url($url);
?>