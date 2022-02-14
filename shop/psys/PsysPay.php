<?php

// Psys 외부 결제 연동 클래스 - 연구비카드, 장기무이자
class PsysPay
{

    const PG_JS_URL = '/outvendnew/js/PsyspayRe.js';
    const PG_CANCEL_URL = '/outvendnew/vendor/cancel.php';
    const PG_RECEIPT_URL = '/outvendnew/vendor/receipt.php';
    const PG_IDEN = 'psys';
    const PG_RECEIVE_URL = "/shop/psys/psys_receive.php";
    public static $vailidResultcodeR = array('0000','0505'); // 0000: 취소 성공, 0505 : 이미취소된 거래

    public static $card_use = array('r' => '연구비카드', 'l' => '신용카드(장기무이자)');
    public static $card_use_add = array('r' => '(신한,BC,삼성)', 'l' => ''); // 안내문구

    public static $service_r = array(
        'psys' => array( 'name' => 'PSYS', 'domain' => 'https://www.psys.co.kr' )
        , 'pay2pay' => array( 'name' => 'Pay2Pay', 'domain' => 'https://www.pay2pay.co.kr' )
    );

    // 클래스 메서드 -----------------------

    public static function getPgDomain($paymethod) {
        $r = self::getPgInfo($paymethod);
        return self::$service_r[ $r['service'] ]['domain'];
    }

    public static function getCombineConst($key, $paymethod='') {
        $domain = self::getPgDomain( $paymethod );

        $r = array(
            'PG_JS_URL' => $domain.self::PG_JS_URL
            , 'PG_CANCEL_URL' => $domain.self::PG_CANCEL_URL
            , 'PG_RECEIVE_URL' => self::getDomainUrl().self::PG_RECEIVE_URL
        );

        return $r[ $key ];
    }

    public static function checkLongUse($gs) {
        return $gs['psys_long_use'];
    }

    public static function getLongUseStr($gs) {
        return ( self::checkLongUse($gs) ? ' [장기무이자할부]' : '' );
    }

    public static function isEnablePg($type, $gs=array()) {
        global $default, $mk;

        $use_pg_rnd = $mk['use_pg_rnd'];
        $use_pg_long = $mk['use_pg_long'];

        $psys_long_use = 1;
        if( $gs ){ // 상품정보가 있을 경우 - 상품 상세
            $psys_long_use = $gs['psys_long_use'];
        }


        if( $mk['id'] == 'admin' ) { // 관리자
            $use_pg_rnd = 1;
            $use_pg_long = 1;
        }

        $r = array();
        $r['rnd'] = $use_pg_rnd && $default['de_psys_rnd_use']; // 연구비카드
        $r['long'] = $use_pg_long && $default['de_psys_long_use'] && $psys_long_use; // 장기무이자

        return $r[$type];
    }

    public static function printBtn($gs) {

        $html = '';
        $only_long_js = '';

        // 연구비 카드의 경우는 상품 상세에 버튼이 표시되지 않는 것으로.. 이유는 연구비카드가 없는 고객들이 대부분이기에 마케팅적으로 저하되기에..
        if( false && self::isEnablePg('rnd') ) {
            $t = self::$card_use['r'];
            $html .= <<<END
                <span><a href="javascript: uf_fbuyform_submit('{$t}');" class="btn_large blue">연구비카드 결제(PSYS)</a></span>
END;
        }

        if( self::isEnablePg('long', $gs) ) {

            $only_long_js = "
            $('.vi_txt_bx .vi_btn .wset').closest('span').remove(); // 피씨
            $('.sp_vbox .wset').closest('p').remove(); // 모바일
          ";
            $t = self::$card_use['l'];
            $html .= <<<END
                <span><a href="javascript: uf_fbuyform_submit('{$t}');" class="btn_large DarkOrange">장기무이자할부 결제(18,24,36개월 선택)</a></span>
END;
        }

        if( ! $html ) {
            return;
        }

        echo '<div class="psys_btn">';
        echo $html;
        echo '</div>';

        echo self::printJsCommon();
        echo <<<END
            <script>
            
                $(function() {
                    {$only_long_js}
                });
                
                function uf_fbuyform_submit(paymethod) {                        
                    set_cookie(uf_paymethod, paymethod);                        
                    fbuyform_submit('buy');
                } 
            </script>
END;

    }

    public static function printJsCommon() {
        echo <<<END
        <script>
            var uf_buyform_f = document.buyform;
            var uf_paymethod = 'uf_paymethod';
            
            function ufAddHiddenFormEle(f, name, value) {
                var ele = document.createElement('input');
                ele.setAttribute('type', 'hidden');
                ele.setAttribute('name', name);
                ele.setAttribute('value', value);
                f.appendChild(ele);
            }
        </script>
END;

    }

    public static function printPaymethodForm($type, $goods_psys_long_no_use_cnt=0) {
        if($type == 'js') { // js
            $t = self::$card_use['r'];
            $t2 = self::$card_use['l'];
            echo <<<END
            if(getRadioVal(f.paymethod) == '{$t}') {
                if(tot_price < 1000) {
                    alert("{$t}는 1000원 이상 결제가 가능합니다.");
                    return false;
                }
            }
        
            if(getRadioVal(f.paymethod) == '{$t}') {
                if(tot_price < 1000) {
                    alert("{$t}는 1000원 이상 결제가 가능합니다.");
                    return false;
                }
            }
END;

        } else { // html
            $only_long_js = '';
            if( self::isEnablePg('rnd') ) {

                echo '<input type="radio" name="paymethod" id="paymethod_psys_card_use" value="'.self::$card_use['r'].'" onclick="calculate_paymethod(this.value);"> <label for="paymethod_psys_card_use">'.self::$card_use['r'].self::$card_use_add['r'].'</label>'.PHP_EOL;
            }
            if( self::isEnablePg('long') && $goods_psys_long_no_use_cnt == 0 ) {
                
                $id = 'paymethod_only_long';
                
                echo '<span id="'.$id.'"><input type="radio" name="paymethod" id="paymethod_psys_card_use2" value="'.self::$card_use['l'].'" onclick="calculate_paymethod(this.value);"> <label for="paymethod_psys_card_use2">'.self::$card_use['l'].self::$card_use_add['l'].'</label></span>'.PHP_EOL;
                
                // 장기무이자 이외의 결제 수단 삭제
                $only_long_js = <<<END
                $('#{$id}').parent().find('> [id!="{$id}"]').remove();
END;
            }

            echo self::printJsCommon();

            echo <<<END
            <script>
                $(function(){
                    var v = get_cookie(uf_paymethod);                    
                    $(uf_buyform_f.paymethod).filter("[value='" + v + "']").prop('checked', true);
                    {$only_long_js}
                })
            </script>
            
END;


        }

    }

    public static function printPaymethodFormMo($type, $goods_psys_long_no_use_cnt=0) {

        $rtn = '';

        if($type == 'js') { // js
            $t = self::$card_use['r'];
            $t2 = self::$card_use['l'];
            echo <<<END
            if(getRadioVal(f.paymethod) == '{$t}') {
                if(tot_price < 1000) {
                    alert("{$t}는 1000원 이상 결제가 가능합니다.");
                    return false;
                }
            }
        
            if(getRadioVal(f.paymethod) == '{$t}') {
                if(tot_price < 1000) {
                    alert("{$t}는 1000원 이상 결제가 가능합니다.");
                    return false;
                }
            }
END;

        } else { // html

            $only_long_js = '';

            if( self::isEnablePg('rnd') ) {

                $rtn .= "<option value='".self::$card_use['r']."'>".self::$card_use['r'].self::$card_use_add['r']."</option>\n";
            }
            if( self::isEnablePg('long') && $goods_psys_long_no_use_cnt == 0 ) {

                $rtn .= "<option value='".self::$card_use['l']."'>".self::$card_use['l'].self::$card_use_add['l']."</option>\n";

                // 장기무이자 이외의 결제 수단 삭제
                $t = self::$card_use['l'];
                $only_long_js = <<<END
                $(uf_buyform_f.paymethod).find('option[value!="{$t}"]').remove();
END;
            }

            echo self::printJsCommon();

            echo <<<END
            <script>
                $(function(){
                    var v = get_cookie(uf_paymethod);                    
                    $(uf_buyform_f.paymethod).val(v);
                    {$only_long_js}
                })
            </script>
            
END;

        }

        return $rtn;

    }


    public static function getOrderUrl($paymethod, $od_id, $domain=TB_SHOP_URL) {
        $rtn = false;

        if( self::checkPaymethod($paymethod) ) {
            $rtn = $domain.'/orderpsys.php?od_id='.$od_id;
        }

        return $rtn;
    }

    public static function getRequirePath($type) {
        $rtn = false;
        if( $type == 'orderform.1' ) { // 결제대행사별 코드 include (스크립트 등)
            $rtn = TB_SHOP_PATH.'/psys/orderform.1.php';
        } else if ( $type == 'orderform.2' ) {
            $rtn = TB_SHOP_PATH.'/psys/orderform.2.php';
        }

        return $rtn;
    }

    public static function checkPaymethod($paymethod) {
        $rtn = false;
        foreach(self::$card_use as $k => $v) {
            if( $paymethod == $v ) {
                $rtn = $k;
                break;
            }

        }
        return $rtn;
    }

    public static function getPgInfo($paymethod) {
        global $default;
        $rtn = false;

        if( $k = self::checkPaymethod($paymethod) ) {

            $r = array();
            $r['r'] = $default['de_psys_rnd_mid']; // 연구비카드
            $r['l'] = $default['de_psys_long_mid']; // 장기무이자

            $rtn = array();
            $rtn['shop_id'] =  $r[ $k ];

            $r = array();
            $r['r'] = $default['de_psys_rnd_crosskey']; // 연구비카드
            $r['l'] = $default['de_psys_long_crosskey']; // 장기무이자

            $rtn['shop_crosskey'] =  $r[ $k ];

            $r = array();
            $r['r'] = $default['de_psys_rnd_service']; // 연구비카드
            $r['l'] = $default['de_psys_long_service']; // 장기무이자

            $rtn['service'] =  $r[ $k ];

            $rtn['pg_iden'] =  self::PG_IDEN;

        }

        return $rtn;
    }

    public static function getCancelFormHtml($od, $stotal) {

        $card_type = self::getCardType($od['paymethod']);
        $receive_url = self::getCombineConst('PG_RECEIVE_URL');

        $r = array(
            "Psys_shopingmall_order_no" => $od['od_tno']
            , "Psys_totalamt" => $stotal['useprice']
            , "Psys_card_type" => $card_type
            , "ReturnURL" => $receive_url
        );

        $c = self::getPsysEncrypt($od['paymethod']);
        $enc_data = $c->encrypt($r);

        $rtn = <<<END
            <input type="hidden" name="Psys_shopid" value="{$od['od_settle_pid']}">            
            <input type="hidden" name="Psys_enc_data" value="{$enc_data}">
            <input type="hidden" name="Psys_pay_type" value="{$od['paymethod']}">
            <input type="hidden" name="Psys_cancel_memo" value="" >
END;
;

        return $rtn;
    }

    public static function getCancelFormJs($od, $type='') {

        $cancel_memo = "";
        if( $type == 'admin' ) { // 관리자에서
            $cancel_memo = "관리자 취소";
        }

        $action = self::getCombineConst('PG_CANCEL_URL', $od['paymethod']);

        // cancel_status 값은 관리자에만..
        $rtn = <<<END
            if( typeof cancel_status !== "undefined" && ! cancel_status ) {
                f.Psys_enc_data.value = '';
                return true;
            } else {
                var t = '{$cancel_memo}';
                f.Psys_cancel_memo.value = t ? t : f.cancel_memo.value;
                
                var p_action = f.action;
                var p_target = f.target;
                
                var pop_name = "PSYS_PAY_POPUP";
                if( ! window.open("",pop_name,"width=10, height=10") ) {
                    alert('팝업을 허용해주십시오.');
                }
                f.target = pop_name; 
                f.action = "{$action}";
                
                // 아이폰 대응 (새창이 뜬후 일정시간이 지난후 요청해야 받는다)
                setTimeout( function() {
                    f.submit();
                    
                    f.action = p_action;
                    f.target = p_target;
                }, 100);

                      
                return false;
            }
END;

        return $rtn;
    }

    public static function getCardType($paymethod) {
        $rtn = false;

        if( $k = self::checkPaymethod($paymethod) ) {

            $r = array();
            $r['r'] = '1'; // 연구비카드
            $r['l'] = '7'; // 장기무이자

            $rtn = $r[ $k ];

        }

        return $rtn;
    }

    public static function getDomainUrl() {

        $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];

        return $actual_link;
    }
    
    public static function getCancelReceiveJs($form, $json) {
        $r = json_encode(self::$vailidResultcodeR);
        echo <<<END
        var r = {$r}; // 0000: 취소 성공, 0505 : 이미취소된 거래
        if( r.indexOf({$json}.Psys_resultcode) != -1 ) {
            {$form}.Psys_enc_data.value = json.Psys_enc_data;
            {$form}.submit();
        } else {
            alert( {$json}.Psys_resultcode + " - " + {$json}.Psys_resultmsg );
        }
END;

    }

    public static function uf_iconv($in_charset , $out_charset , $str) {
        if( is_array($str) ) {
            foreach($str as &$v){
                $v = uf_iconv($in_charset, $out_charset, $v);
            }
            unset($v);
        } else {
            return iconv($in_charset, $out_charset.'//IGNORE', $str);
        }

        return $str;
    }

    public static function receive() {

        // 허용된 아이피에 대한 응답만 처리

        /*
        if( getenv('LANG') == "ko_KR.eucKR" ) {
            $_POST = PsysPay::uf_iconv("EUC-KR","UTF-8", $_POST);
        }*/

        $json_data = json_encode($_POST);

        if( $_POST['req_type'] == 'cancel' ) { // 취소 요청 응답
            $js_action_func = 'fcancel_receive';
        } else { // 결제 요청 응답
            $js_action_func = 'result_submit';
        }

        // 결과값 Return
        echo <<<END
            <script>
            
                var target = window.opener;
                var is_close = true;
                
                if( ! target ) {
                    target = window.parent;
                    is_close = false;
                }
                
                target.{$js_action_func}($json_data);
                
                if( is_close ) {
                    self.close();
                }
                
            </script> 
END;

    }

    public static function pgDataDecrypt($paymethod, $enc_data) {

        $c = self::getPsysEncrypt($paymethod);
        return $c->decrypt($enc_data);

    }

    public static function getPsysEncrypt($paymethod) {

        $pgInfo = self::getPgInfo($paymethod);
        //var_dump($pgInfo);

        include dirname(__FILE__) . "/PsysEncrypt.php";

        return new PsysEncrypt( $pgInfo['shop_crosskey'] );
    }

    // 취소가 정상적으로 되었나?
    public static function cancelReqIsValid($result) {
        $r = self::$vailidResultcodeR;

        return in_array($result['Psys_resultcode'], $r);
    }

    public static function getSelServiceHtml($name, $default) {

        foreach( self::$service_r as $k => $v ) {
            $checked = get_checked($default[$name], $k);
            echo "
                <input type='radio' name='{$name}' value='{$k}' id='{$name}_{$k}' {$checked} >
                <label for='{$name}_{$k}'>{$v['name']}</label>
            ";
        }

    }

    static function getReceiptJs($od=array()) {
        $r = array('tr_no' => $od['od_tno']);
        $url = "https://psys.co.kr" . self::PG_RECEIPT_URL . "?".http_build_query($r);
        return "window.open('{$url}','app','width=410,height=650,scrollbars=0');";
    }
}