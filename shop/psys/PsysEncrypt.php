<?php

// PSYS 외부 결제 통신 암호화 클래스
// 차후에는 암호화와 복호화를 클라이언트에서 하는 것이 아닌 우리 서버에 쇼켓통신으로 요청하면 해주는 것으로 하여야함.
class PsysEncrypt
{
    public $encrypt_key = '';

    function __construct($encrypt_key)
    {
        $this->encrypt_key = $encrypt_key;
    }

    public function encrypt( $arr=array() ) {

        $plaintext = json_encode($arr);
        $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($plaintext, $cipher, $this->encrypt_key, $options=OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $ciphertext_raw, $this->encrypt_key, $as_binary=true);
        $ciphertext = base64_encode( $iv.$hmac.$ciphertext_raw );

        return $ciphertext;
    }

    public function decrypt($ciphertext) {

        $c = base64_decode($ciphertext);
        $ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, $sha2len=32);
        $ciphertext_raw = substr($c, $ivlen+$sha2len);
        $original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $this->encrypt_key, $options=OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac('sha256', $ciphertext_raw, $this->encrypt_key, $as_binary=true);

        $rtn = false;
        if ( $this->hash_equals($hmac, $calcmac) )//PHP 5.6+ timing attack safe comparison
        {
            $rtn = json_decode($original_plaintext, true);
        }


        return $rtn;
    }

    function hash_equals($str1, $str2)
    {
        if(strlen($str1) != strlen($str2))
        {
            return false;
        }
        else
        {
            $res = $str1 ^ $str2;
            $ret = 0;
            for($i = strlen($res) - 1; $i >= 0; $i--)
            {
                $ret |= ord($res[$i]);
            }
            return !$ret;
        }
    }
}