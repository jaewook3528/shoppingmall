<?php
if(!defined('_TUBEWEB_')) exit;

include_once(TB_THEME_PATH.'/tail.skin.php'); // 하단

// BODY 내부 메시지
if($config['tail_script']) {
	echo $config['tail_script'].PHP_EOL;
}

include_once(TB_PATH."/tail.sub.php");
?>