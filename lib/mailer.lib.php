<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TB_PHPMAILER_PATH.'/PHPMailerAutoload.php');

// 메일 보내기 (파일 여러개 첨부 가능)
// type : text=0, html=1, text+html=2
function mailer($fname, $fmail, $to, $subject, $content, $type=0, $file="", $cc="", $bcc="")
{
    if($type != 1)
        $content = nl2br($content);

    $mail = new PHPMailer(); // defaults to using php "mail()"
    if(defined('TB_SMTP') && TB_SMTP) {
        $mail->IsSMTP(); // telling the class to use SMTP
        $mail->Host = TB_SMTP; // SMTP server
        if(defined('TB_SMTP_PORT') && TB_SMTP_PORT)
            $mail->Port = TB_SMTP_PORT;
    }
    $mail->CharSet = 'UTF-8';
    $mail->From = $fmail;
    $mail->FromName = $fname;
    $mail->Subject = $subject;
    $mail->AltBody = ""; // optional, comment out and test
    $mail->msgHTML($content);
    $mail->addAddress($to);
    if($cc)
        $mail->addCC($cc);
    if($bcc)
        $mail->addBCC($bcc);
    //print_r2($file); exit;
    if($file != "") {
        foreach ($file as $f) {
            $mail->addAttachment($f['path'], $f['name']);
        }
    }
    return $mail->send();
}

// 파일을 첨부함
function attach_file($filename, $tmp_name)
{
    // 서버에 업로드 되는 파일은 확장자를 주지 않는다. (보안 취약점)
    $dest_file = TB_DATA_PATH.'/tmp/'.str_replace('/', '_', $tmp_name);
    move_uploaded_file($tmp_name, $dest_file);
    $tmpfile = array("name" => $filename, "path" => $dest_file);
    return $tmpfile;
}

function order_mailer($od,$subject1,$subject2,$content)
{
    # $od['type'] => "passbook" : 무통장 입금 시 관리자가 직업 입금완료 작업 구분
    global $mk, $config, $super;

    $seller = get_seller_cd($od['seller_id']);

    if(!$seller)
    {
        #가맹점일때,
        // 주문자에게 메일발송
        if($od['type']!='passbook')
        {
            mailer($config['company_name'], $mk['email'], $od['email'], $subject1, $content, 1);
        }

        // 관리자에게 메일발송
        if($mk['email'] != $od['email']) {
            mailer($od['name'], $od['email'], $mk['email'], $subject2, $content, 1);
        }

    }else{

        if($od['seller_id']=='admin')
        {
            #아이템 등록 주체가 본사일때,
            // 주문자에게 메일발송
            if($od['type']!='passbook')
            {
                mailer($config['company_name'], $super['email'], $od['email'], $subject1, $content, 1);
            }

            // 관리자에게 메일발송
            if($super['email'] != $od['email']) {
                mailer($od['name'], $od['email'], $super['email'], $subject2, $content, 1);
            }
        }else{

            if($od['type']=='passbook')
            {
                #아이템 등록 주체가 입점사일때,
                if($seller['info_email']=='')
                {
                    $subject3 = " 입점사 이메일 주소 미설정";
                    // 관리자에게 메일발송
                    if($super['email'] != $od['email']) {
                        mailer($od['name'], $super['email'], $super['email'], $subject3, $content, 1);
                    }
                }else{
                    // 입점사에게 메일 발송
                    mailer($config['company_name'], $super['email'], $seller['info_email'], $subject2, $content, 1);
                }

            }else{

                // 관리자에게 메일발송
                if($super['email'] != $od['email']) {
                    mailer($od['name'], $od['email'], $super['email'], $subject2, $content, 1);
                }

                // 주문자에게 메일발송
                mailer($config['company_name'], $super['email'], $od['email'], $subject1, $content, 1);

                if($od['paymethod']!='무통장')
                {
                    #아이템 등록 주체가 입점사일때,
                    if($seller['info_email']=='')
                    {
                        $subject3 = " 입점사 이메일 주소 미설정";
                        // 관리자에게 메일발송
                        if($super['email'] != $od['email']) {
                            mailer($od['name'], $super['email'], $super['email'], $subject3, $content, 1);
                        }
                    }else{
                        // 입점사에게 메일 발송
                        mailer($config['company_name'], $super['email'], $seller['info_email'], $subject2, $content, 1);
                    }
                }
            }
        }
    }
}

function ipgum_send_mail($od){
    global $config;
    // 메일발송
    if($od['email']) {
        $subject1 = get_text($od['name'])."님 주문이 정상적으로 처리되었습니다.";
        $subject2 = get_text($od['name'])." 고객님께서 신규주문을 신청하셨습니다.";

        ob_start();
        include(TB_SHOP_PATH.'/orderformupdate_mail.php');
        $content = ob_get_contents();
        ob_end_clean();

        order_mailer($od,$subject1,$subject2,$content);
    }
}

?>