<?php

include_once dirname(__FILE__)."/../../lib/common/UfSocket.php";

/**
 * Pay2Pay 페이
 */
class Pay2Pay_Pay
{

    const SERV_KEY = 'real';
    public static $service_r = array(
        'real' => 'pay2pay.co.kr',
        'dev' => 'cube24.co.kr'
    );

    const PG_DIRECT_URL = '/_out/pay/direct.php';
    const PG_CANCEL_URL = '/_out/pay/cancel.php';
    const PG_RECEIPT_URL = '/_out/pay/receipt.php';
    const PG_APPROVAL_URL = '/_out/pay/direct/approval.php';
    const PG_SMS_VERIFY_URL = '/_out/sms/send_verify_code.php';

    public $pg_info = array();

    function __construct($default=array())
    {
        $this->pg_info = array(
            "vendor_id" => $default['de_pay2pay_pay_mid'],
            "cross_key" => $default['de_pay2pay_pay_crosskey'],
        );
    }

    public function socketRequest($req_r, $url) {
        $ufSocket = new UfSocket( self::$service_r[ self::SERV_KEY ], $url);

        $resp = $ufSocket->request($req_r);
        //var_dump($resp);

        $resp_r = $ufSocket->arr2str( $resp['bodys'], true );
        //var_dump($resp_r);

        return $resp_r;
    }

    // 인증 결제 처리
    public function certProc($data=array()) {

        $req_r = array(
            "enc_data" => $data["enc_data"], // 암호화된 주문정보
        );

        return $this->payProc($req_r, self::PG_APPROVAL_URL);
    }

    // 수기 결제 처리
    public function sugiProc($data=array()) {

        $req_r = array(
            "pay_type" => "1", // 연구비카드(신한,BC,삼성)여부
            "card_no" => $data["card_no"], // 카드번호
            "cardvalid_ym" => $data["cardvalid_ym"], // 유효기간 - 년월
            "sell_mm" => $data["sell_mm"], // 할부개월값
            "amt" => $data['tot_price'], // 결제금액(승인금액)
            "order_no" => $data['od_id'], // 주문번호
            "buyer_nm" => $data["od_name"], // 결제자 성명
            "email_addr" => $data["od_email"], // 결제자 E-mail
            "verify_key" => $data["verify_key"], // 인증키
            "verify_code" => $data["verify_code"], // 인증번호
            "product_cd" => $data["product_cd"], // 상품코드
            "product_nm" => $data["product_nm"], // 상품명
            "pmember_id" => $data["pmember_id"], // 쇼핑몰 회원 ID
            "etc1" => "", // 추가필드1
            "etc2" => "", // 추가필드2
            "etc3" => "", // 추가필드3
        );

        return $this->payProc($req_r, self::PG_DIRECT_URL);
    }

    public function payProc($req_r=array(), $target_url=null) {

        $req_r = array_merge($req_r, $this->pg_info);

        $resp_r = $this->socketRequest( $req_r, $target_url);

        // 결제 실패시
        if ($resp_r['result_code'] != '0000')
        {
            alert("{$resp_r['result_code']} : {$resp_r['error_msg']}");
            exit;
        }

        $r = array();
        $r['tno'] = $resp_r['tr_no'];
        $r['amount'] = $resp_r['amt'];
        $r['app_no'] = $resp_r['accept_no'];
        $r['app_time'] = $resp_r['accept_date'];
        $r['od_card_nm'] = $r['card_name'] = $resp_r['card_name'];

        //var_dump($r); exit;

        return $r;
    }

    // 카드 결제 취소 처리
    function procCardCancel( $data=array(), $post=array() ) {
        $req_r = array();

        $stotal = get_order_spay($data['od_id']); // 총계

        // 필수 : 거래번호(od_tno), 상점아이디, 취소금액
        // 선택 : 취소 사유
        $req_r['tr_no'] = $data['od_tno'];
        $req_r['amt'] = $stotal['useprice'];
        $req_r['cancel_memo'] = $post['cancel_memo'];

        $req_r = array_merge($req_r, $this->pg_info);
        //var_dump($req_r);
        $resp_r = $this->socketRequest($req_r, self::PG_CANCEL_URL);
        //var_dump($resp_r);

        return $resp_r;
    }

    public function getCardOrderForm($type) {
        global $kw_shop_id, $od_id, $tot_price, $od, $member, $product_cd, $product_nm;

        $rtn = false;
        if ( $type == 'sugi.req_form' ) {
            $rtn = 'sugi/req_form.php';
        } else if ( $type == 'cert.req_form' ) {
            $rtn = 'cert/req_form.php';
        }

        if( $rtn ) include dirname(__FILE__).'/'.$rtn;

    }

    public function sendVerifyCode($data=array()) {

        $req_r = array(
            "phone_number" => $data['phone_number'],
        );

        $req_r = array_merge($req_r, $this->pg_info);
        $resp_r = $this->socketRequest( $req_r, self::PG_SMS_VERIFY_URL);
        
        echo json_encode($resp_r);
        
    }

    // 클래스 메서드 ---------------------------------------------------------------

    // 매출전표
    static function getReceiptJs($od=array()) {
        $r = array('tr_no' => $od['od_tno']);
        $url = "https://" . self::$service_r[ self::SERV_KEY ] . self::PG_RECEIPT_URL . "?".http_build_query($r);
        return "window.open('{$url}','app','width=410,height=650,scrollbars=0');";
    }
}