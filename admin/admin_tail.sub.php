<?php
if(!defined('_TUBEWEB_')) exit;
?>

<div id="ajax-loading"><img src="<?php echo TB_IMG_URL; ?>/ajax-loader.gif"></div>
<?php if(!defined('_NEWWIN_')) { // �˾�â�� �������� �ʴ´� ?>
<div id="anc_header"><a href="#anc_hd"><span></span>TOP</a></div>
<?php } ?>

<script src="<?php echo TB_ADMIN_URL; ?>/js/admin.js?ver=<?php echo TB_JS_VER; ?>"></script>

<script src="<?php echo TB_JS_URL; ?>/wrest.js"></script>
</body>
</html>
<?php echo html_end(); // HTML ������ ó�� �Լ� : �ݵ�� �־��ֽñ� �ٶ��ϴ�. ?>