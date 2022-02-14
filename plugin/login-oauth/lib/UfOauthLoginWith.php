<?php

/**
 * 소셜네트워크 로그인 처리 클래스
 */
class UfOauthLoginWith
{
    public $is_dev = false; // 개발서버이면 true, 운영서버면 false
    public $client = null;
    public $mb_gubun = null;

    function __construct(oauth_client_class $client)
    {

        $this->client = $client;
        $this->client->debug = true;
        $this->client->debug_http = true;

        $server_url = $redirect_suffix = null;

        $c_server = $this->client->server;
        if( $c_server == 'Psys' ) { // Psys
            $server_url = $this->is_dev ? 'www.srms.kr' : 'www.psys.co.kr';
            $redirect_suffix = 'psys';
            $this->mb_gubun = 'ps_';
        } else if( $c_server == 'Pay2pay' ) { // Pay2pay
            $server_url = $this->is_dev ? 'www.cube24.co.kr' : 'www.pay2pay.co.kr';
            $redirect_suffix = 'pay2pay';
            $this->mb_gubun = 'p2p_';
        }

        if( $server_url) {

            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";

            $this->client->server_url = 'https://' . $server_url . '/_out/oauth';
            $this->client->redirect_uri = $protocol.'://'.$_SERVER['HTTP_HOST'].
                dirname(strtok($_SERVER['REQUEST_URI'],'?')).'/login_with_'.$redirect_suffix.'.php';
        }
    }

    function process() {

        $application_line = __LINE__;

        if(strlen($this->client->client_id) == 0)
            alert_close('피시스 연동키값을 확인해 주세요.');

        if( $_GET['login']=='Y') {
            unset($_SESSION['OAUTH_STATE']);
            $this->client->ResetAccessToken();
        }

        /* API permissions
         */
        if(($success = $this->client->Initialize()))
        {
            if(($success = $this->client->Process()))
            {
                if(strlen($this->client->access_token))
                {
                    $success = $this->client->CallAPI(
                        $this->client->server_url.'/resource.php',
                        'POST', array('mode'=>'userinfo'), array('FailOnAccessError'=>true), $user);
                }
            }
            $success = $this->client->Finalize($success);
        }
        if($this->client->exit)
            exit;

        if($success)
        {
            if( $user->result == 'success' ) {
                $this->client->GetAccessToken($AccessToken);
                $user->data = $this->check_data($user->data);

                $mb_gubun = $this->mb_gubun;
                $mb_id = $user->data->user_id; //동일인 식별정보
                $mb_name = $user->data->user_name; //사용자 이름
                $mb_email = $user->data->email; //사용자 메일주소
                $mb_cellphone = $user->data->hp; //사용자 연락처
                $mb_zip = $user->data->post; //사용자 우편번호
                $mb_addr1 = $user->data->addr; //사용자 주소
                $mb_addr2 = $user->data->addr_desc; //사용자 상세주소
                $mb_gender = $user->data->gender; //사용자 상세주소
                $birth = $user->data->birth;
                $mb_birth_year = substr($birth,0,4); //사용자 상세주소
                $mb_birth_month = substr($birth,4,2); //사용자 상세주소
                $mb_birth_day = substr($birth,6,2); //사용자 상세주소
                /*			$mb_nick = $xml->response->nickname; //사용자 별명
                            $mb_gender = $xml->response->gender; //F:여성, M:남성, U:확인불가
                            $mb_age = $xml->response->age; //사용자 연령대
                            $mb_birthday = $xml->response->birthday; //사용자 생일(MM-DD 형식)
                            $mb_profile_image = $xml->response->profile_image; //사용자 프로필 사진 URL*/
                $token_value = $AccessToken['value'];
                $token_refresh = $AccessToken['refresh'];
                $token_secret = '';

                //$this->client->ResetAccessToken();

                include_once(dirname(__FILE__).'/../oauth_check.php');
            } else {
                $error = HtmlSpecialChars($user->result);
                alert_close($error);
            }
        } else {
            unset($_SESSION['OAUTH_STATE']);
            $this->client->ResetAccessToken();
            $error = addslashes($this->client->error);
            $error = str_replace("\n", "\\n", $error);

            alert_close($error);
        }
    }

    function check_data($data)
    {
        if( ! $data->user_id ){
            $r = json_decode($data->data);
        }else{
            $r = $data;
        }

        return $r;
    }
}