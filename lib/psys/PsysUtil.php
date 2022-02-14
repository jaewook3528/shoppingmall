<?php

// 피시스 유틸 클래스
class PsysUtil
{
    public static function getLogoTypeHtml($lg, $type_name, $text_name) {
        echo '<div style="padding-bottom: .5em;"> * 타입 : &nbsp; ';
        $r = array('i' => '이미지', 't' => '텍스트' );

        foreach($r as $k => $v){
            echo radio_checked($type_name, $lg[ $type_name ], $k, $v);
        }

        echo "<input type='text' name='{$text_name}' value='{$lg[$text_name]}' class='frm_input' size='30' placeholder='로고 텍스트'>";
        echo '</div>';
    }

    public static function getLogoHtml($filed) {

        global $pt_id;

        $row = sql_fetch("select * from shop_logo where mb_id='$pt_id'");

        $exist = $row[ $filed.'_type' ] == 'i' ? $row[ $filed ] : $row[ $filed.'_text' ];

        if(!$exist && $pt_id != 'admin') {
            $row = sql_fetch("select * from shop_logo where mb_id='admin'");
        }

        $rtn = '';

        // 피씨로고
        $img_attr = '';
        $a_url = TB_URL;
        if( $filed == 'mobile_logo' ) { // 모바일로고
            $img_attr = ' class="lg_wh" ';
            $a_url = TB_MURL;
        }

        if( $row[ $filed.'_type' ] == 't' ) {
            $rtn = "<span class='logo_text'>{$row[ $filed.'_text' ]}</span>";
        } else {
            $file = TB_DATA_PATH.'/banner/'.$row[$filed];
            if(is_file($file) && $row[$filed]) {
                $file = rpc($file, TB_PATH, TB_URL);
                $rtn = '<img src="'.$file.'" '.$img_attr.'>';
            } else {
                $rtn = '';
            }
        }
        
        return $rtn ? '<a href="'.$a_url.'">'.$rtn.'</a>' : '';
    }
}