<?php
set_time_limit(0);

// 파일이 존재한다면 설치할 수 없다.
if (file_exists("../data/dbconfig.php")) {
    die("설치하실 수 없습니다.");
}

$gmnow = gmdate("D, d M Y H:i:s") . " GMT";
header("Expires: 0"); // rfc2616 - Section 14.21
header("Last-Modified: " . $gmnow);
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: pre-check=0, post-check=0, max-age=0"); // HTTP/1.1
header("Pragma: no-cache"); // HTTP/1.0

$mysql_host  = $_POST['mysql_host'];
$mysql_user  = $_POST['mysql_user'];
$mysql_pass  = $_POST['mysql_pass'];
$mysql_db    = $_POST['mysql_db'];
$admin_id    = $_POST['admin_id'];
$admin_pass  = $_POST['admin_pass'];
$admin_name  = $_POST['admin_name'];
$admin_email = $_POST['admin_email'];

@mysql_query("set names utf8");
$dblink = @mysql_connect($mysql_host, $mysql_user, $mysql_pass);
if (!$dblink) {
	die("MySQL Host, User, Password 를 확인해 주십시오.");
}

@mysql_query("set names utf8");
$select_db = @mysql_select_db($mysql_db, $dblink);
if (!$select_db) {
	die("MySQL DB 를 확인해 주십시오.");
}
?>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>솔루션 설치 (3/3) - DB</title>
<style type="text/css">
.body {
    font-family: 굴림;
	font-size: 12px;
}
.box {
	background-color: #FCFCFC;
    color:#B19265;
    font-family:굴림;
	font-size: 12px;
}
.nobox {
	background-color: #FCFCFC;
    border-style:none;
    font-family:굴림;
    font-size: 12px;
}
.mw{position:fixed;_position:absolute;top:0;left:0;width:100%;height:100%;z-index:9000;text-align:center;}
.mw .bg{position:absolute;top:0;left:0;width:100%;height:100%;}
.mw .fg{position:absolute;top:50%;left:50%;width:587px;height:365px;margin:-183px 0 0 -294px;background:#fff}
</style>
</head>

<body background="img/all_bg.gif" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<div class="mw">
	<div class="bg"></div>
	<div class="fg">
		<table width="587" border="0" cellspacing="0" cellpadding="0">
		<form name=frminstall2>
		<tr>
			<td colspan="3">
				<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="587" height="22">
				<param name="movie" value="../install/img/top.swf">
				<param name="quality" value="high">
				<embed src="../install/img/top.swf" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="587" height="22"></embed>
				</object>
			</td>
		</tr>
		<tr>
			<td width="3"><img src="../install/img/box_left.gif" width="3" height="340"></td>
			<td width="581" valign="top" bgcolor="#FCFCFC">
				<br>
				<table width="541" border="0" align="center" cellpadding="0" cellspacing="0" class="body">
				<tr>
					<td>설치를 시작합니다. <font color="#CC0000">설치중 작업을 중단하지 마십시오. </font></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><div align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input name="status_bar" type="text" class="box" size="76" readonly></div></td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td>
						<table width="350" border="0" align="center" cellpadding="5" cellspacing="0" class="body">
						<tr>
							<td width="50"> </td>
							<td width="300"><input type=text name=job1 class=nobox size=80 readonly></td>
						</tr>
						<tr>
							<td width="50"> </td>
							<td width="300"><input type=text name=job2 class=nobox size=80 readonly></td>
						</tr>
						<tr>
							<td width="50"> </td>
							<td width="300"><input type=text name=job3 class=nobox size=80 readonly></td>
						</tr>
						<tr>
							<td width="50"><div align="center"></div></td>
							<td width="300"><input type=text name=job4 class=nobox size=80 readonly></td>
						</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<td><input type=text name=job5 class=nobox size=90 readonly></td>
				</tr>
				</table>

				<table width="562" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td height=20><img src="../install/img/box_line.gif" width="562" height="2"></td>
				</tr>
				</table>

				<table width="551" border="0" align="center" cellpadding="0" cellspacing="0">
				<tr>
					<td align="right">
					<input type="button" name="btn_next" disabled value="메인화면" onclick="location.href='../';">
					</td>
				</tr>
				</table>
			</td>
			<td width="3"><img src="../install/img/box_right.gif" width="3" height="340"></td>
		</tr>
		<tr>
			<td colspan="3"><img src="../install/img/box_bottom.gif" width="587" height="3"></td>
		</tr>
		</form>
		</table>
	</div>
</div>

<?php
flush(); usleep(50000);

// 테이블 생성 ------------------------------------
$file = implode("", file("./sql_db.sql"));
//eval("\$file = \"$file\";");

$f = explode(";", $file);
for ($i=0; $i<count($f); $i++) {
    if (trim($f[$i]) == "") continue;
    mysql_query($f[$i]) or die(mysql_error());
}
// 테이블 생성 ------------------------------------

echo "<script>document.frminstall2.job1.value='전체 테이블 생성중';</script>";
flush(); usleep(50000);

for ($i=0; $i<45; $i++)
{
    echo "<script language='JavaScript'>document.frminstall2.status_bar.value += '■';</script>\n";
    flush();
    usleep(500);
}

echo "<script>document.frminstall2.job1.value='전체 테이블 생성 완료';</script>";
flush(); usleep(50000);

//-------------------------------------------------------------------------------------------------
// 운영자 레벨명 UPDATE
$sql = " update shop_member_grade
            set gb_name = '$admin_name'
		  where gb_no = '1' ";
mysql_query($sql);

// 운영자 UPDATE
$sql = " update shop_member
            set name = '$admin_name'
			  , passwd = password('$admin_pass')
			  , email = '$admin_email'
			  , grade = '1'
			  , reg_time = '".date("Y-m-d H:i:s", time())."'
			  , today_login = '".date("Y-m-d H:i:s", time())."'
			  , login_ip = '{$_SERVER['REMOTE_ADDR']}'
		  where index_no = '1' ";
mysql_query($sql);

echo "<script>document.frminstall2.job2.value='DB설정 완료';</script>";
flush(); usleep(50000);
//-------------------------------------------------------------------------------------------------

// DB 설정 파일 생성
$file = "../data/dbconfig.php";
$f = @fopen($file, "w");

fwrite($f, "<?php\n");
fwrite($f, "if(!defined('_TUBEWEB_')) exit;\n");
fwrite($f, "define('TB_MYSQL_HOST', '{$mysql_host}');\n");
fwrite($f, "define('TB_MYSQL_USER', '{$mysql_user}');\n");
fwrite($f, "define('TB_MYSQL_PASSWORD', '{$mysql_pass}');\n");
fwrite($f, "define('TB_MYSQL_DB', '{$mysql_db}');\n");
fwrite($f, "define('TB_MYSQL_SET_MODE', false);\n");
fwrite($f, "?>");

fclose($f);
@chmod($file, 0606);
echo "<script>document.frminstall2.job3.value='DB설정 파일 생성 완료';</script>";
flush(); usleep(50000);

// data 디렉토리 및 하위 디렉토리에서는 .htaccess .htpasswd .php .phtml .html .htm .inc .cgi .pl 파일을 실행할수 없게함.
$f = fopen('../data/.htaccess', 'w');
$str = <<<EOD
<FilesMatch "\.(htaccess|htpasswd|[Pp][Hh][Pp]|[Pp][Hh][Tt]|[Pp]?[Hh][Tt][Mm][Ll]?|[Ii][Nn][Cc]|[Cc][Gg][Ii]|[Pp][Ll])">
Order allow,deny
Deny from all
</FilesMatch>
EOD;
fwrite($f, $str);
fclose($f);

@rename("../install", "../install.bak");
//-------------------------------------------------------------------------------------------------

echo "<script language='JavaScript'>document.frminstall2.status_bar.value += '■';</script>\n";
flush();
sleep(1);

echo "<script>document.frminstall2.job4.value='필요한 Table, File, 디렉토리 생성을 모두 완료 하였습니다.';</script>";
echo "<script>document.frminstall2.job5.value='* 메인화면에서 운영자 로그인을 한 후 운영자 화면으로 이동하여 환경설정을 변경해 주십시오.';</script>";
flush(); usleep(50000);
?>
<script>document.frminstall2.btn_next.disabled = false;</script>
<script>document.frminstall2.btn_next.focus();</script>
</body>
</html>