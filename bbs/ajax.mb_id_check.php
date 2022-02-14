<?php
define('_PURENESS_', true);
include_once("./_common.php");

if(preg_match("/[^0-9a-z_]+/i", $mb_id)) {
    echo '<strong class="fc_red">영문자, 숫자, _ 만 입력하세요.</strong>';
} else if(strlen($mb_id) < 3) {
    echo '<strong class="fc_red">최소 3자이상 입력하세요.</strong>';
} else {
	$mb = get_member($mb_id);
    if($mb['id']) {
        echo '<strong class="fc_red">이미 사용중인 아이디 입니다.</strong>';
    } else {
        if(preg_match("/[\,]?{$mb_id}/i", $config['prohibit_id']))
			 echo '<strong class="fc_red">예약어로 금지된 회원아이디 입니다.</strong>';
        else
             echo '<strong class="fc_197">사용하셔도 좋은 아이디 입니다.</strong>';
    }
}
?>