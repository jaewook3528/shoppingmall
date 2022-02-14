<?php

// 키움페이
class UfKiwoomPay {

    public $log_path = '';

    public $kiwoom_data = array();

    public function __construct()
    {
        $this->log_path = TB_SHOP_PATH."/kiwoom/log/".date("Ym");
    }

    // 일반결제 폼
    function getCardOrderForm( $type ) {
        global $default;
        global $kw_shop_id, $od_id, $tot_price, $od, $member, $product_cd, $product_nm;  // type : 2;

        $receive_url = TB_SHOP_URL."/kiwoom/receive_check.php";

        if( $type == '1' ) {
            $t = '';
            if ($default['de_card_test']) {
                $t = 'test';
            }
            $pg_url = "https://ssl{$t}.kiwoompay.co.kr";
        } else {
            // 할부개월
            $cpquota_html = $this->getCpquota_html($default['de_card_installment']);
        }

        include dirname(__FILE__). '/card/req_form.' . $type . '.php';
    }

    // 할부개월수 input 태그 리턴
    function getCpquota_html($cpquota_max=null){
        $cpquota_html = '';

        // input html
        if( $cpquota_max ) {
            // 할부에 1개월이라는 값은 없음
            $r = range(2, $cpquota_max);
            array_unshift($r, 0);
            $t = implode(':', $r);
            $cpquota_html = '할부개월수 : <input type="text" name="CPQUOTA" size="20" value="' . $t. '" style="IME-MODE:disabled">(형식 : 2:3:4:5....:12)<br>';

        }

        return $cpquota_html;
    }

    // 라이브러리 및 설정 파일 인클루드
    function includeLibrary(){
        global $default;

        /* ========================================================================== */
        /* =   데이터 셋업 					                    					= */
        /* ========================================================================== */
        require dirname(__FILE__)."/dcardsugi/library/Card_library.php";
        require dirname(__FILE__)."/dcardsugi/config/common_config.cfg";

        $server_ip = "27.102.213.207"; // 실서버 서버
        if( $default['de_card_test'] == '1' ) {
            $server_ip = "123.140.121.205"; // 테스트 서버
        }
        define( "SERVER_IP"	, $server_ip );

        define( "ENCKEY"	, $default['de_kiwoom_crosskey'] );// 암호화 키값

        return true;
    }

    // 키움페이 일반 결제 응답(통지) 처리
    function procReceive($args=array()) {

        // 주문번호가 없다면
        if( ! $args['ORDERNO'] ) {
            return false;
        }

        // 문자 인코딩 변환
        $args = $this->iconv_pg($args);

        $this->setReceive($args);

        return true;
    }

    function getResult($args)
    {
        $row = $this->getReceive($args);

        //var_dump($row);exit;
        $tno = $row['DAOUTRX']; // 다우거래번호
        $card_name  = $row['CARDCODE']."(".$row['CARDNAME'].")"; // 카드사코드(카드사명)
        $app_no     = $row['AUTHNO']; // 카드승인번호
        $od_card_nm = $card_name;
        $amount = $row['AMOUNT']; // 결제금액
        //$od_sell_mm = $SELL_MM; // 할부개월 - 키움은 할부 개월수를 통보해주지 않음.
        $app_time = $row['SETTDATE']; // 결제일자

        return compact('tno', 'card_name', 'app_no', 'od_card_nm', 'amount', 'app_time');
    }

    function getReceive($args)
    {
        $rtn = false;

        $path = $this->log_path.'/'.$args['ORDERNO'].'.log';
        if( file_exists($path) ) {
            $data = file_get_contents($path);
            $rtn = unserialize($data);
        }

        return $rtn;
    }

    function setReceive($args=array()) {
        //var_dump(serialize($args));
        if( $args ) {
            if( ! is_dir($this->log_path) ) {
                mkdir($this->log_path, 0707, true);
            }

            file_put_contents($this->log_path.'/'.$args['ORDERNO'].'.log', serialize($args));
        }

    }

    function iconv_r($in_charset , $out_charset , $str) {
        if( is_array($str) ) {
            foreach($str as &$v){
                $v = $this->iconv_r($in_charset, $out_charset, $v);
            }
            unset($v);
        } else {
            return iconv($in_charset, $out_charset.'//IGNORE', $str);
        }

        return $str;
    }

    function iconv_pg($str, $to='') {
        $charset = array('euckr','utf8');
        if( $to == 'pg') { // PG사로 보내는 데이터
            $charset = array_reverse($charset);
        }

        return $this->iconv_r( $charset[0], $charset[1], $str);
    }

    // 일반결제 통지 부분 - 정상요청인지 체크
    function checkReceive() {
        $t = $_SERVER['REMOTE_ADDR'];

        if ( ! (
            $t == "123.140.121.205" // 키움페이지 테스트 서버도 아니고,
            || ("27.102.213.200" <= $t && $t <= '27.102.213.209') // 실서버 ip 대역도 아니라면
            || $t == "218.145.245.126" // 우리 사무실 ip (통지 테스트 용도)
        ) ) {
            return false;
        }

        return true;
    }

    // 카드 결제 취소 처리
    function procCardCancel( $data=array() ) {
        $stotal = get_order_spay($data['od_id']); // 총계
        $args = array();
        $args[ "DAOUTRX"	] = $data['od_tno'];
        $args[ "AMOUNT"	] = $stotal['useprice'];
        $args[ "CANCELMEMO"	] = '취소 - '.$_POST['cancel_memo'].' - '.$_SERVER["HTTP_HOST"];

        return $this->procCardCancelByPrepData($args);
    }

    function procCardCancelByPrepData( $args=array() ) {

        global $DAOUTRX, $AMOUNT, $IPADDRESS, $CANCELMEMO;
        global $res_resultcode, $res_errormessage, $res_daoutrx, $res_amount, $res_canceldate;
        global $default;

        $args = $this->iconv_pg($args,'pg');

        /* ========================================================================== */
        /* =   프로그램 명	:	CardCancel.php                                 = */
        /* =   프로그램 설명	:	Card 취소요청 샘플  파일                     = */
        /* =   작성일		:	2012-09      		                    = */
        /* =   저작권		:	(주)다우기술                                = */
        /* ========================================================================== */


        /* ========================================================================== */
        /* =   요청 정보 설정                                   		    = */
        /* ========================================================================== */
        /* = 	 NOT NULL	                                                    = */
        /* ========================================================================== */

        $this->includeLibrary();

        $CPID				= $default['de_kiwoom_mid'];
        $DAOUTRX			= $args[ "DAOUTRX"	];
        $AMOUNT				= $args[ "AMOUNT"	];
        $IPADDRESS			= $_SERVER["SERVER_ADDR"];
        $CANCELMEMO			= $args[ "CANCELMEMO"	];



        /* ========================================================================== */
        /* = 	 취소 요청				     	                    = */
        /* ========================================================================== */
        //var_dump(  SERVER_IP, CARD_PORT, $CPID, ENCKEY, TIMEOUT ); exit;
        CardCancel(  SERVER_IP, CARD_PORT, $CPID, ENCKEY, TIMEOUT );

        //var_dump( $this->iconv_pg( array($res_resultcode, $res_errormessage, $res_daoutrx, $res_amount, $res_canceldate) ));


        /* ========================================================================== */
        /* =  결과코드 0000 : 성공	그외 : 실패				    = */
        /* ========================================================================== */
        $r = compact(        'res_resultcode', 'res_errormessage', 'res_daoutrx', 'res_amount', 'res_canceldate');

        return $this->iconv_pg($r);
    }


    // 클래스메서드 ---------------------------------------------------------------------


    // 20181129143023 => 2018-11-29 14:30:23
    static function getDateStrConvert($date) {
        if( ! $date ) {
            return false;
        }

        $t = $date;
        return substr($t,0,4) . '-' . substr($t,4,2) . '-' . substr($t,6,2)
            . ' ' . substr($t,8,2) . ':' . substr($t,10,2) . ':' . substr($t,12,2);
    }


    static function getReceiptR($args) {
        $ret = array();

        $rr = self::getReceiptR_R();

        $STATUS = $rr['STATUS'][false]; // 승인된 건
        if( $args['canceled'] ) {
            $STATUS = $rr['STATUS'][true]; // 취소된 건
        }

        $VAT = $rr['VAT'][false]; // VAT 표시
        if( $args['no_vat'] ) {
            $VAT = $rr['VAT'][true]; // VAT 미표시
        }

        $ret['STATUS'] = $STATUS;
        $ret['VAT'] = $VAT;

        return $ret;
    }

    static function getReceiptR_R() {
        $ret = array();
        $ret['DAOUTRX'] = ''; //거래번호 (차후 채워야함)

        $r = array();
        $r[false] = '11'; // 승인된 건
        $r[true] = '12'; // 취소된 건

        $ret['STATUS'] = $r;

        $r = array();
        $r[false] = 'Y'; // VAT 표시
        $r[true] = 'N'; // VAT 미표시

        $ret['VAT'] = $r;

        return $ret;
    }

    static function getConfigGuideHtml(){
        echo <<<END
<div style="width: 260px; border: 1px solid #ccc; padding: 3px; margin: 3px;">
<strong>&lt;키움페이 카드사 심사용 설정 방법&gt;</strong><br/>
- 결제테스트 : 테스트결제<br/>
- 결제대행사  키움페이<br/>
- 키움페이 상점아이디 : CTS15232<br/>
- 키움페이 암화화키 : Ufound01<br/>
- 최대 할부가능 개월 : 개월수 입력
</div>
END;

    }

    // 매출전표
    static function getReceiptJs($od=array()) {
        global $default;

        $t = '';
        if( $default['de_card_test'] == '1' ) { // 개발
            $t = 'test';
        }

        $args = array();
        if( in_array($od['dan'], array(6,7,9)) ) { // 6 : 취소, 7 : 반품, 9 : 환불
            $args['canceled'] = true;
        }
        $r = self::getReceiptR($args);

        $r['DAOUTRX'] = $od['od_tno'];

        $url = "https://agent{$t}.kiwoompay.co.kr/util/selectCmmnTradePrintCard.do?".http_build_query($r);
        return "window.open('{$url}','app','width=430,height=650,scrollbars=0');";
    }
}