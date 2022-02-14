<?php
$file = $_GET['file']; // 화일이 실제로 있는 위치를.. 
$filename = $_GET['url'];

if( strstr($_SERVER['HTTP_USER_AGENT'],"MSIE 5.5")) { 
	header("Content-Type: doesn/matterrn"); 
	header("Content-Disposition: filename=".$file.""); 
	header("Content-Transfer-Encoding: binaryrn"); 
	header("Pragma: no-cache"); 
	header("Expires: 0"); 
} else if(strstr($_SERVER['HTTP_USER_AGENT'], "MSIE 6.0")) {
    Header("Content-type: application/x-msdownload"); 
    Header("Content-Length: ".(string)(filesize("$filename"))); 
    Header("Content-Disposition: attachment; filename=$file");   
    Header("Content-Transfer-Encoding: binary");   
    Header("Pragma: no-cache");   
    Header("Expires: 0");   
} else { 
	Header("Content-type: file/unknown"); 
	Header("Content-Length: ".(string)(filesize("$filename"))); 
	Header("Content-Disposition: attachment; filename=".$file.""); 
	Header("Content-Description: PHP3 Generated Data"); 
	header("Pragma: no-cache"); 
	header("Expires: 0"); 
} 

if(is_file("$filename")){ 
	$fp = fopen("$filename","rb"); 

if(!fpassthru($fp))
	fclose($fp); 
} 
?>