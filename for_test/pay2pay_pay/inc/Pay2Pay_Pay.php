<?php

include_once dirname(__FILE__)."/UfSocket.php";

/**
 * Pay2Pay 페이
 */
class Pay2Pay_Pay
{

    public static $service_r = 'pay2pay.co.kr';

    const PG_DIRECT_URL = '/_out/pay/direct.php';
    const PG_CANCEL_URL = '/_out/pay/cancel.php';
    const PG_RECEIPT_URL = '/_out/pay/receipt.php';
    const PG_APPROVAL_URL = '/_out/pay/direct/approval.php';
    const PG_SMS_VERIFY_URL = '/_out/sms/send_verify_code.php';

    public $pg_info = array();

    function __construct($args=array())
    {
        $this->pg_info = array(
            "vendor_id" => $args['vendor_id'],
            "cross_key" => $args['cross_key'],
        );
    }

    public function socketRequest($req_r, $url) {
        $ufSocket = new UfSocket( self::$service_r, $url);

        $resp = $ufSocket->request($req_r);

        $resp_r = $ufSocket->arr2str( $resp['bodys'], true );

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
        return $this->payProc($data, self::PG_DIRECT_URL);
    }

    public function payProc($req_r=array(), $target_url=null) {

        $req_r = array_merge($req_r, $this->pg_info);

        $resp_r = $this->socketRequest( $req_r, $target_url);

        return $resp_r;
    }

    // 카드 결제 취소 처리
    function procCardCancel( $post=array() ) {

        $req_r = array_merge($post, $this->pg_info);
        $resp_r = $this->socketRequest($req_r, self::PG_CANCEL_URL);

        return $resp_r;
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
    static function getReceiptJs($args=array()) {
        $r = array('tr_no' => $args['tr_no']);
        $url = "https://" . self::$service_r . self::PG_RECEIPT_URL . "?".http_build_query($r);
        return "window.open('{$url}','app','width=410,height=650,scrollbars=0');";
    }

    static function getDomainUrl() {
        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://";
        $host = $_SERVER['HTTP_HOST'];
        $actual_link .= $host;

        return $actual_link;
    }

    static function getDatetimeWithMicroseconds() {
        list($usec, $sec) = explode(" ", microtime());
        $usec = str_replace('0.', '', $usec); // 0.22532500 -> 22532500
        return date("YmdHis").$usec;
    }

    static function printData($data=array()) {
        echo "<table border='1'>";
        foreach($data as $k => $v){
            echo "<tr><th>{$k}</th><td>{$v}</td></tr>";
        }
        echo "</table>";
    }
}