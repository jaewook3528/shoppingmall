<?php

/**
 * 유파운드 소켓 클래스
 */
class UfSocket {

    public $host = null;
    public $port = 443;
    public $url = null;

    function __construct($host, $path)
    {
        $this->host = $host;
        $this->url = "POST {$path} HTTP/1.0\r\n";
    }

    function request($data=array()) {
        $req_addr = "ssl://".$this->host;

        $send_data = '';
        foreach($data as $k => $v) {
            $send_data .= $k."=".$v."&";
        }

        $at_sock = fsockopen($req_addr, $this->port, $errno, $errstr);
        if($at_sock) {
            fwrite($at_sock, $this->url );
            fwrite($at_sock, "Host: ".$this->host.":".$this->port."\r\n" );
            fwrite($at_sock, "Content-type: application/x-www-form-urlencoded\r\n");
            fwrite($at_sock, "Content-length: ".strlen($send_data)."\r\n");
            fwrite($at_sock, "Accept: */*\r\n");
            fwrite($at_sock, "\r\n");
            fwrite($at_sock, $send_data."\r\n");
            fwrite($at_sock, "\r\n");
            $resp_txt = $this->get_response($at_sock);
            fclose($at_sock);
        } else {
            $resp_txt = "Socket Connect Error:".$errstr."\n";
        }

        return $resp_txt;
    }

    function get_response($csock){
        $headers = '';
        $bodys = '';
        while(!feof($csock)){
            $headers .= $t = fgets($csock,4096);
            if( $t=="\r\n"){
                break;
            }
        }
        while(!feof($csock)){
            $bodys.=fgets($csock,4096);
        }

        return compact('headers', 'bodys');
    }

    function arr2str($data, $reverse = false) {
        $rtn = null;

        $deli = array('set' => "\n", 'kv' => "=");

        if( $reverse ) {
            $rtn = array();
            $kv = explode( $deli['set'], trim($data) );
            for( $i=0; $i < sizeof($kv); $i++){
                $t = explode( $deli['kv'], trim($kv[$i]) );
                $rtn[ $t[0] ] = $t[1];
            }
        } else {
            $rtn = '';
            foreach($data as $k => $v) {
                $rtn .= $k . $deli['kv'] . $v . $deli['set'];
            }
        }

        return $rtn;
    }

}
