<?php
if(!defined('_TUBEWEB_')) exit;
?>

<div id="ft">
	<p>Copyright &copy; <?php echo $config['company_name']; ?>. All rights reserved.</p>
</div>

<div id="ajax-loading"><img src="<?php echo TB_IMG_URL; ?>/ajax-loader.gif"></div>
<div id="anc_header"><a href="#anc_hd"><span></span>TOP</a></div>

<?php if(!$boardid) { // 게시판일경우 admin.js 파일을 실행하면 안됨 ?>
<script src="<?php echo TB_ADMIN_URL; ?>/js/admin.js?ver=<?php echo TB_JS_VER;?>"></script>
<?php } ?>

<script src="<?php echo TB_JS_URL; ?>/wrest.js"></script>
</body>
</html>
<?php echo html_end(); // HTML 마지막 처리 함수 : 반드시 넣어주시기 바랍니다. ?>